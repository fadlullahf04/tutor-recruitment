<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\TutorProfile;
use App\Models\Faculty;
use App\Models\StudyProgram;
use App\Models\Course;
use App\Models\RecruitmentPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\TutorRegisteredMail;
use App\Mail\TutorApprovedMail;
use App\Mail\TutorRejectedMail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    /**
     * Show admin dashboard statistics.
     */
    public function dashboard()
    {
        $stats = [
            'total_applicants' => TutorProfile::count(),
            'pending_review' => TutorProfile::where('status', 'Pending')->count(),
            'approved_tutors' => TutorProfile::where('status', 'Approved')->count(),
            'rejected_tutors' => TutorProfile::where('status', 'Rejected')->count(),
            'drafts' => TutorProfile::where('status', 'Draft')->count(),
            'faculties' => Faculty::count(),
            'programs' => StudyProgram::count(),
            'courses' => Course::count(),
        ];

        $recentTutors = TutorProfile::with('user')
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentTutors'));
    }

    /**
     * View list of candidate tutors.
     */
    public function tutorIndex(Request $request)
    {
        $status = $request->get('status');
        $search = $request->get('search');
        $periodId = $request->get('period_id');

        $query = TutorProfile::with(['user', 'period']);

        if (!Auth::user()->isSuperAdmin()) {
            $query->where('status', '!=', 'Draft');
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('full_name_with_titles', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('registration_number', 'like', "%{$search}%");
            });
        }

        if ($periodId) {
            $query->where('recruitment_period_id', $periodId);
        }

        $tutors = $query->orderBy('created_at', 'desc')->paginate(10);
        $periods = RecruitmentPeriod::all();

        return view('admin.tutors.index', compact('tutors', 'periods', 'status', 'search', 'periodId'));
    }

    /**
     * Export all candidate tutors to an Excel-compatible XLSX spreadsheet.
     */
    public function tutorExport()
    {
        $tutors = TutorProfile::with(['user', 'period', 'courses.studyProgram.faculty'])
            ->orderBy('created_at', 'desc')
            ->get();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Calon Tutor');

        // Headers
        $headers = [
            'No. Registrasi',
            'NIK',
            'NIP',
            'NIDN',
            'NUPTK',
            'NPWP',
            'Nama Lengkap & Gelar',
            'Email',
            'Jenis Kelamin',
            'No. HP',
            'Tanggal Lahir',
            'Alamat',
            'Pendidikan Terakhir',
            'Universitas',
            'Program Studi Asal',
            'Tahun Lulus',
            'Bidang Keahlian',
            'Instansi Asal',
            'Status Pekerjaan',
            'Masa Kerja',
            'Bidang Pekerjaan',
            'Golongan',
            'Pilihan Fakultas',
            'Pilihan Program Studi',
            'Pilihan Mata Kuliah',
            'Bank',
            'No. Rekening',
            'Nama Pemilik Rekening',
            'Status Verifikasi',
            'Alasan Ditolak / Catatan Revisi',
            'Tanggal Daftar'
        ];

        // 1. Populate headers in row 1
        $col = 'A';
        foreach ($headers as $headerText) {
            $sheet->setCellValue($col . '1', $headerText);
            $col++;
        }

        // 2. Populate data rows
        $row = 2;
        foreach ($tutors as $tutor) {
            $course = $tutor->courses->first();
            $studyProgram = $course ? $course->studyProgram : null;
            $faculty = $studyProgram ? $studyProgram->faculty : null;

            $data = [
                $tutor->registration_number ?: '-',
                $tutor->nik ?: '-',
                $tutor->nip ?: '-',
                $tutor->nidn ?: '-',
                $tutor->nuptk ?: '-',
                $tutor->npwp ?: '-',
                $tutor->full_name_with_titles ?: '-',
                $tutor->user ? $tutor->user->email : '-',
                $tutor->gender ?: '-',
                $tutor->phone ?: '-',
                $tutor->date_of_birth ? \Carbon\Carbon::parse($tutor->date_of_birth)->format('Y-m-d') : '-',
                $tutor->address ?: '-',
                $tutor->last_education ?: '-',
                $tutor->university_name ?: '-',
                $tutor->field_of_study ?: '-',
                $tutor->graduation_year ?: '-',
                $tutor->field_of_expertise ?: '-',
                $tutor->institution_name ?: '-',
                $tutor->employment_status ?: '-',
                $tutor->work_duration ?: '-',
                $tutor->work_field ?: '-',
                $tutor->rank_group ?: '-',
                $faculty ? $faculty->name : '-',
                $studyProgram ? $studyProgram->name : '-',
                $course ? $course->code . ' - ' . $course->name : '-',
                $tutor->bank_name ?: '-',
                $tutor->bank_account_number ?: '-',
                $tutor->bank_account_name ?: '-',
                $tutor->status ?: '-',
                $tutor->rejection_reason ?: '-',
                $tutor->created_at ? $tutor->created_at->format('Y-m-d H:i:s') : '-'
            ];

            $col = 'A';
            foreach ($data as $index => $value) {
                // Explicitly bind long numeric identity strings (NIK, NIP, NIDN, NUPTK, NPWP, phone, and account numbers) as TYPE_STRING
                if (in_array($index, [1, 2, 3, 4, 5, 9, 26])) {
                    $sheet->setCellValueExplicit($col . $row, $value, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                } else {
                    $sheet->setCellValue($col . $row, $value);
                }
                $col++;
            }
            $row++;
        }

        // 3. Apply cell styling
        $highestColumn = $sheet->getHighestColumn();
        $highestRow = $sheet->getHighestRow();

        $headerRange = 'A1:' . $highestColumn . '1';
        $fullRange = 'A1:' . $highestColumn . $highestRow;

        // Custom green color matching application (emerald green)
        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11,
                'name' => 'Inter'
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '059669']
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ]
        ]);

        // Border styling
        $sheet->getStyle($fullRange)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => 'CBD5E1'] // slate-200
                ]
            ],
            'font' => [
                'size' => 10,
                'name' => 'Inter'
            ]
        ]);

        // Adjust dimensions
        $sheet->getRowDimension(1)->setRowHeight(35);
        for ($r = 2; $r <= $highestRow; $r++) {
            $sheet->getRowDimension($r)->setRowHeight(25);
            $sheet->getStyle('A' . $r . ':' . $highestColumn . $r)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        }

        // Enable auto-filter
        $sheet->setAutoFilter($headerRange);

        // Auto column sizing
        $colIterator = $sheet->getColumnIterator();
        foreach ($colIterator as $column) {
            $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
        }

        // Output file stream
        $fileName = 'Data_Calon_Tutor_' . date('Ymd_His') . '.xlsx';
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        return response()->stream(
            function () use ($writer) {
                $writer->save('php://output');
            },
            200,
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
                'Cache-Control' => 'max-age=0',
                'Pragma' => 'no-cache'
            ]
        );
    }

    /**
     * View specific tutor details for review.
     */
    public function tutorShow($id)
    {
        $tutor = TutorProfile::with(['user', 'period'])->findOrFail($id);
        return view('admin.tutors.show', compact('tutor'));
    }

    /**
     * Approve candidate registration.
     */
    public function tutorApprove($id)
    {
        $tutor = TutorProfile::findOrFail($id);
        $tutor->update([
            'status' => 'Approved',
            'rejection_reason' => null
        ]);

        // Eager load courses with study program and faculty for the email view
        $tutor->load('courses.studyProgram.faculty');

        try {
            Log::info("Mengirim email persetujuan ke {$tutor->user->email}...");
            Mail::to($tutor->user->email)->send(new TutorApprovedMail($tutor));
            Log::info("Email persetujuan berhasil dikirim ke {$tutor->user->email}");
        } catch (\Throwable $e) {
            Log::error("Gagal mengirim email persetujuan ke {$tutor->user->email}: " . $e->getMessage() . "\n" . $e->getTraceAsString());
        }

        return redirect()->route('admin.tutors.show', $tutor->id)
            ->with('success', "Calon tutor {$tutor->full_name_with_titles} berhasil disetujui.");
    }

    /**
     * Reject candidate registration with feedback reason.
     */
    public function tutorReject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000'
        ], [
            'rejection_reason.required' => 'Alasan penolakan wajib diisi.'
        ]);

        $tutor = TutorProfile::findOrFail($id);
        $tutor->update([
            'status' => 'Rejected',
            'rejection_reason' => $request->rejection_reason
        ]);

        try {
            Log::info("Mengirim email penolakan ke {$tutor->user->email}...");
            Mail::to($tutor->user->email)->send(new TutorRejectedMail($tutor));
            Log::info("Email penolakan berhasil dikirim ke {$tutor->user->email}");
        } catch (\Throwable $e) {
            Log::error("Gagal mengirim email penolakan ke {$tutor->user->email}: " . $e->getMessage() . "\n" . $e->getTraceAsString());
        }

        return redirect()->route('admin.tutors.show', $tutor->id)
            ->with('success', "Calon tutor {$tutor->full_name_with_titles} ditolak dengan alasan yang ditentukan.");
    }

    /**
     * List all system user accounts (Super Admin only).
     */
    public function index(Request $request)
    {
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Akses ditolak. Fitur ini hanya untuk Super Admin.');
        }

        $role = $request->get('role');
        $search = $request->get('search');

        $query = User::query();

        if ($role) {
            $query->where('role', $role);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('role')->orderBy('name')->paginate(10);

        return view('admin.users.index', compact('users', 'role', 'search'));
    }

    /**
     * Store a new user account (Super Admin only).
     */
    public function store(Request $request)
    {
        if (!Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:super_admin,admin,tutor'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // If creating a tutor, also create their profile
        if ($user->role === 'tutor') {
            $activePeriod = RecruitmentPeriod::where('is_active', true)->first();
            
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

            TutorProfile::create([
                'user_id' => $user->id,
                'recruitment_period_id' => $activePeriod ? $activePeriod->id : null,
                'status' => 'Draft',
                'full_name_with_titles' => $user->name,
                'registration_number' => $regNumber,
                'completed_step' => 0
            ]);
        }

        return redirect()->route('admin.users.index')->with('success', 'Akun berhasil ditambahkan.');
    }

    /**
     * Update an existing user account (Super Admin only).
     */
    public function update(Request $request, $id)
    {
        if (!Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => "required|email|unique:users,email,{$user->id}",
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:super_admin,admin,tutor'
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Akun berhasil diperbarui.');
    }

    /**
     * Delete user account (Super Admin only).
     */
    public function destroy($id)
    {
        if (!Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $user = User::findOrFail($id);
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Akun berhasil dihapus.');
    }

    /**
     * Show form to create new tutor.
     */
    public function tutorCreate()
    {
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Akses ditolak. Fitur ini hanya untuk Super Admin.');
        }

        $periods = RecruitmentPeriod::all();
        return view('admin.tutors.create', compact('periods'));
    }

    /**
     * Store newly created tutor.
     */
    public function tutorStore(Request $request)
    {
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Akses ditolak. Fitur ini hanya untuk Super Admin.');
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
            'email' => 'required|email|max:255|unique:users,email',
            'recruitment_period_id' => 'required|exists:recruitment_periods,id',
        ], [
            'nik.required' => 'NIK wajib diisi.',
            'nik.digits' => 'NIK harus tepat 16 digit.',
            'nik.unique' => 'NIK ini sudah terdaftar.',
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
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
            'recruitment_period_id' => $request->recruitment_period_id,
            'status' => 'Draft',
            'nik' => $request->nik,
            'full_name_with_titles' => $request->name,
            'registration_number' => $regNumber,
            'completed_step' => 0,
        ]);

        // Send email with credentials
        try {
            Log::info("Mengirim email registrasi ke {$user->email}...");
            Mail::to($user->email)->send(new TutorRegisteredMail($user, $rawPassword, $request->nik));
            Log::info("Email registrasi berhasil dikirim ke {$user->email}");
        } catch (\Throwable $e) {
            Log::error("Gagal mengirim email registrasi ke {$user->email}: " . $e->getMessage() . "\n" . $e->getTraceAsString());
        }

        return redirect()->route('admin.tutors.index')
            ->with('success', "Calon tutor {$user->name} berhasil ditambahkan dengan Password Sementara: {$rawPassword}");
    }

    /**
     * Show form to edit tutor details and status.
     */
    public function tutorEdit($id)
    {
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Akses ditolak. Fitur ini hanya untuk Super Admin.');
        }

        $tutor = TutorProfile::with('user')->findOrFail($id);
        $periods = RecruitmentPeriod::all();

        return view('admin.tutors.edit', compact('tutor', 'periods'));
    }

    /**
     * Update tutor details and status.
     */
    public function tutorUpdate(Request $request, $id)
    {
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Akses ditolak. Fitur ini hanya untuk Super Admin.');
        }

        $tutor = TutorProfile::with('user')->findOrFail($id);
        $user = $tutor->user;

        // Clean NIK from spaces, dashes, or dots
        if ($request->has('nik')) {
            $request->merge([
                'nik' => preg_replace('/[^0-9]/', '', $request->nik)
            ]);
        }

        $request->validate([
            'nik' => 'required|numeric|digits:16|unique:tutor_profiles,nik,' . $tutor->id,
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'recruitment_period_id' => 'required|exists:recruitment_periods,id',
        ], [
            'nik.required' => 'NIK wajib diisi.',
            'nik.digits' => 'NIK harus tepat 16 digit.',
            'nik.unique' => 'NIK ini sudah terdaftar.',
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah terdaftar.',
        ]);

        // Update User
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Update Profile
        $tutor->update([
            'nik' => $request->nik,
            'full_name_with_titles' => $request->name,
            'recruitment_period_id' => $request->recruitment_period_id,
        ]);

        return redirect()->route('admin.tutors.show', $tutor->id)
            ->with('success', "Data calon tutor {$tutor->full_name_with_titles} berhasil diperbarui.");
    }

    /**
     * Delete tutor candidate.
     */
    public function tutorDestroy($id)
    {
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Akses ditolak. Fitur ini hanya untuk Super Admin.');
        }

        $tutor = TutorProfile::with('user')->findOrFail($id);
        $user = $tutor->user;
        $name = $tutor->full_name_with_titles;

        // Delete profile and user (courses pivot cascade handles pivot deletions)
        $tutor->delete();
        if ($user) {
            $user->delete();
        }

        return redirect()->route('admin.tutors.index')
            ->with('success', "Calon tutor {$name} berhasil dihapus permanen dari sistem.");
    }
}
