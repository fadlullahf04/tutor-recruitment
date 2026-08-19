<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\TutorProfile;
use App\Models\RecruitmentPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\TutorRegisteredMail;
use App\Mail\TutorResetPasswordMail;

class AuthController extends Controller
{
    /**
     * Show login form.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        return view('auth.login');
    }

    /**
     * Handle login request.
     */
    public function login(Request $request)
    {
        $request->validate([
            'login_identifier' => 'required|string',
            'password' => 'required',
        ]);

        $loginIdentifier = $request->login_identifier;
        $password = $request->password;

        // Check if identifier is email or NIK
        $email = null;
        if (filter_var($loginIdentifier, FILTER_VALIDATE_EMAIL)) {
            $email = $loginIdentifier;
        } else {
            // Clean NIK from spaces, dashes, or dots
            $cleanNik = preg_replace('/[^0-9]/', '', $loginIdentifier);
            // Find by NIK in tutor profiles
            $profile = TutorProfile::where('nik', $cleanNik)->first();
            if ($profile && $profile->user) {
                $email = $profile->user->email;
            }
        }

        if ($email && Auth::attempt(['email' => $email, 'password' => $password], $request->boolean('remember'))) {
            $request->session()->regenerate();
            return $this->redirectBasedOnRole(Auth::user());
        }

        return back()->withErrors([
            'login_identifier' => 'Email/NIK atau password yang Anda masukkan salah.',
        ])->onlyInput('login_identifier');
    }

    /**
     * Show registration form.
     */
    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        $activePeriod = RecruitmentPeriod::where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();

        return view('auth.register', compact('activePeriod'));
    }

    /**
     * Handle registration request.
     */
    public function register(Request $request)
    {
        $activePeriod = RecruitmentPeriod::where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();

        if (!$activePeriod) {
            return back()->with('error', 'Pendaftaran tutor online sedang ditutup saat ini.');
        }

        // Clean NIK from spaces, dashes, or dots
        if ($request->has('nik')) {
            $request->merge([
                'nik' => preg_replace('/[^0-9]/', '', $request->nik)
            ]);
        }

        $request->validate([
            'nik' => 'required|numeric|digits:16|unique:tutor_profiles,nik',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
        ], [
            'nik.required' => 'NIK wajib diisi.',
            'nik.numeric' => 'NIK harus berupa angka.',
            'nik.digits' => 'NIK harus tepat 16 digit.',
            'nik.unique' => 'NIK ini sudah terdaftar.',
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
        ]);

        // Use default password
        $rawPassword = 'PJJ-12345';

        // Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($rawPassword),
            'role' => 'tutor',
        ]);

        // Generate registration number
        $year = date('Y');
        $lastProfile = TutorProfile::whereYear('created_at', $year)
            ->whereNotNull('registration_number')
            ->orderBy('registration_number', 'desc')
            ->first();

        $nextSequence = 1;
        if ($lastProfile) {
            $parts = explode('-', $lastProfile->registration_number);
            $lastSequence = intval(end($parts));
            $nextSequence = $lastSequence + 1;
        }
        $sequence = str_pad($nextSequence, 4, '0', STR_PAD_LEFT);
        $regNumber = "PJJ-TUTOR-{$year}-{$sequence}";

        // Create tutor profile
        TutorProfile::create([
            'user_id' => $user->id,
            'recruitment_period_id' => $activePeriod->id,
            'status' => 'Draft',
            'nik' => $request->nik,
            'full_name_with_titles' => $request->name,
            'registration_number' => $regNumber,
            'completed_step' => 0,
        ]);

        // Send email with credentials (wrapped in try-catch to prevent 500 error if SMTP is not fully configured)
        try {
            Mail::to($user->email)->send(new TutorRegisteredMail($user, $rawPassword, $request->nik));
        } catch (\Exception $e) {
            Log::error("Gagal mengirim email registrasi ke {$user->email}: " . $e->getMessage());
        }

        // Flash temporary credentials for verification
        return redirect()->route('register.success')->with([
            'reg_name' => $user->name,
            'reg_email' => $user->email,
            'reg_password' => $rawPassword,
            'reg_nik' => $request->nik,
        ]);
    }

    /**
     * Show registration success screen.
     */
    public function registerSuccess()
    {
        if (!session('reg_email')) {
            return redirect()->route('login');
        }
        return view('auth.register-success');
    }

    /**
     * Show forgot password form.
     */
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle forgot password request.
     */
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan di sistem kami.']);
        }

        // Generate new password
        $rawPassword = 'PJJ-RESET-' . strtoupper(Str::random(6));
        $user->password = Hash::make($rawPassword);
        $user->save();

        // Send reset password email (wrapped in try-catch to prevent 500 error if SMTP is not fully configured)
        try {
            Mail::to($user->email)->send(new TutorResetPasswordMail($user, $rawPassword));
        } catch (\Exception $e) {
            Log::error("Gagal mengirim email reset password ke {$user->email}: " . $e->getMessage());
        }

        return redirect()->route('login')->with('success_reset', "Password telah direset. Silakan cek email Anda (Simulasi Password Baru: {$rawPassword})");
    }

    /**
     * Log the user out.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    /**
     * Change the authenticated user's password.
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:6|confirmed',
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'new_password.required' => 'Password baru wajib diisi.',
            'new_password.min' => 'Password baru minimal 6 karakter.',
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.'
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini salah.']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Password Anda berhasil diubah.');
    }

    /**
     * Helper to redirect users based on role.
     */
    private function redirectBasedOnRole($user)
    {
        if ($user->isSuperAdmin() || $user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('tutor.dashboard');
    }
}
