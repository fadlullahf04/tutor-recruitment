<?php

namespace App\Http\Controllers;

use App\Models\TutorProfile;
use App\Models\RecruitmentPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TutorWizardController extends Controller
{
    /**
     * Show candidate tutor dashboard.
     */
    public function dashboard()
    {
        $user = Auth::user();
        if (!$user->isTutor()) {
            return redirect()->route('admin.dashboard');
        }

        $profile = $user->profile;

        // Create draft profile if it doesn't exist yet
        if (!$profile) {
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

            $profile = TutorProfile::create([
                'user_id' => $user->id,
                'recruitment_period_id' => $activePeriod ? $activePeriod->id : null,
                'status' => 'Draft',
                'full_name_with_titles' => $user->name,
                'registration_number' => $regNumber,
                'completed_step' => 0
            ]);
        }

        // Fetch offered courses grouped by study programs
        $studyPrograms = \App\Models\StudyProgram::with(['courses', 'faculty'])
            ->has('courses')
            ->orderBy('name')
            ->get();

        return view('tutor.dashboard', compact('profile', 'studyPrograms'));
    }

    /**
     * Show wizard form step.
     */
    public function showWizard(Request $request)
    {
        $user = Auth::user();
        if (!$user->isTutor()) {
            return redirect()->route('admin.dashboard');
        }

        $profile = $user->profile;
        if (!$profile) {
            return redirect()->route('tutor.dashboard');
        }

        // Cannot access wizard if already pending or approved
        if (in_array($profile->status, ['Pending', 'Approved'])) {
            return redirect()->route('tutor.dashboard');
        }

        $step = (int) $request->get('step', 1);

        // Enforce sequence logic
        if ($step < 1 || $step > 5) {
            $step = 1;
        }

        if ($step > 1 && $profile->completed_step < 1) {
            return redirect()->route('tutor.wizard', ['step' => 1])->with('error', 'Silakan lengkapi biodata diri terlebih dahulu.');
        }

        if ($step > 2 && $profile->completed_step < 2) {
            return redirect()->route('tutor.wizard', ['step' => 2])->with('error', 'Silakan lengkapi data instansi terlebih dahulu.');
        }

        if ($step > 3 && $profile->completed_step < 3) {
            return redirect()->route('tutor.wizard', ['step' => 3])->with('error', 'Silakan lengkapi data riwayat pendidikan terlebih dahulu.');
        }

        if ($step > 4 && $profile->completed_step < 4) {
            return redirect()->route('tutor.wizard', ['step' => 4])->with('error', 'Silakan pilih mata kuliah terlebih dahulu.');
        }

        $faculties = [];
        if ($step === 4) {
            $faculties = \App\Models\Faculty::with(['studyPrograms' => function($q) {
                $q->has('courses')->with('courses');
            }])->has('studyPrograms.courses')->orderBy('name')->get();
        }

        return view('tutor.wizard', compact('profile', 'step', 'faculties'));
    }

    /**
     * Save Step 1 (Biodata).
     */
    public function saveStep1(Request $request)
    {
        $user = Auth::user();
        $profile = $user->profile;

        // Clean NIK from spaces, dashes, or dots
        if ($request->has('nik')) {
            $request->merge([
                'nik' => preg_replace('/[^0-9]/', '', $request->nik)
            ]);
        }

        // Normalize additional identity numbers to a single '0' if they consist only of zeros
        foreach (['nip', 'nidn', 'nuptk', 'npwp'] as $field) {
            if ($request->has($field)) {
                $val = $request->input($field);
                if ($val !== null && preg_match('/^0+$/', $val)) {
                    $request->merge([$field => '0']);
                }
            }
        }

        $request->validate([
            'nik' => 'required|numeric|digits:16|unique:tutor_profiles,nik,' . $profile->id,
            'full_name_with_titles' => 'required|string|max:255',
            'gender' => 'required|in:Laki-laki,Perempuan',
            'phone' => 'required|numeric|digits_between:10,15',
            'bank_account_number' => 'required|numeric|digits_between:5,20',
            'bank_name' => 'required|in:BRI,BSI',
            'bank_account_name' => 'required|string|max:255',
            'nip' => ['required', 'string', 'regex:/^(0|[0-9]{9,20})$/'],
            'npwp' => 'required|string|max:30',
            'date_of_birth' => 'required|date',
            'address' => 'required|string|max:1000',
            'nidn' => ['required', 'string', 'regex:/^(0|[0-9]{9,20})$/'],
            'nuptk' => ['required', 'string', 'regex:/^(0|[0-9]{9,20})$/'],
        ], [
            'nik.required' => 'NIK wajib diisi.',
            'nik.digits' => 'NIK harus berupa 16 digit angka.',
            'nik.unique' => 'NIK ini sudah terdaftar.',
            'full_name_with_titles.required' => 'Nama lengkap beserta gelar wajib diisi.',
            'gender.required' => 'Jenis kelamin wajib dipilih.',
            'phone.required' => 'Nomor HP wajib diisi.',
            'bank_account_number.required' => 'Nomor rekening wajib diisi.',
            'bank_name.required' => 'Nama bank wajib diisi.',
            'bank_name.in' => 'Nama bank harus BRI atau BSI.',
            'bank_account_name.required' => 'Nama pemilik rekening wajib diisi.',
            'date_of_birth.required' => 'Tanggal lahir wajib diisi.',
            'address.required' => 'Alamat wajib diisi.',
            'nip.required' => 'NIP wajib diisi.',
            'nip.regex' => 'NIP harus berupa angka antara 9 sampai 20 digit, atau diisi 0 jika tidak memiliki.',
            'npwp.required' => 'NPWP wajib diisi.',
            'nidn.required' => 'NIDN wajib diisi.',
            'nidn.regex' => 'NIDN harus berupa angka antara 9 sampai 20 digit, atau diisi 0 jika tidak memiliki.',
            'nuptk.required' => 'NUPTK wajib diisi.',
            'nuptk.regex' => 'NUPTK harus berupa angka antara 9 sampai 20 digit, atau diisi 0 jika tidak memiliki.',
        ]);

        $profile->update([
            'nik' => $request->nik,
            'full_name_with_titles' => $request->full_name_with_titles,
            'gender' => $request->gender,
            'phone' => $request->phone,
            'bank_account_number' => $request->bank_account_number,
            'bank_name' => $request->bank_name,
            'bank_account_name' => $request->bank_account_name,
            'nip' => $request->nip,
            'npwp' => $request->npwp,
            'date_of_birth' => $request->date_of_birth,
            'address' => $request->address,
            'nidn' => $request->nidn,
            'nuptk' => $request->nuptk,
            'completed_step' => max($profile->completed_step, 1),
        ]);

        // Update name in users table for sync
        $user->update(['name' => $request->full_name_with_titles]);

        return redirect()->route('tutor.wizard', ['step' => 2])->with('success', 'Biodata diri berhasil disimpan.');
    }

    /**
     * Save Step 2 (Instansi).
     */
    public function saveStep2(Request $request)
    {
        $profile = Auth::user()->profile;

        $request->validate([
            'institution_name' => 'required|string|max:255',
            'employment_status' => 'required|in:ASN,Non ASN',
            'work_duration' => 'required|string|max:100',
            'work_field' => 'required|string|max:255',
            'rank_group' => 'required|in:III,IV,PPPK Gol X,PPPK Gol XI,PPPK Gol XIII,PPPK Gol XVI,Non ASN',
        ], [
            'institution_name.required' => 'Nama instansi wajib diisi.',
            'employment_status.required' => 'Status pekerjaan wajib diisi.',
            'employment_status.in' => 'Status pekerjaan harus ASN atau Non ASN.',
            'work_duration.required' => 'Masa kerja wajib diisi.',
            'work_field.required' => 'Bidang pekerjaan wajib diisi.',
            'rank_group.required' => 'Pangkat/Golongan wajib diisi.',
            'rank_group.in' => 'Pangkat/Golongan harus III, IV, PPPK Gol X, PPPK Gol XI, PPPK Gol XIII, PPPK Gol XVI, atau Non ASN.',
        ]);

        $profile->update([
            'institution_name' => $request->institution_name,
            'employment_status' => $request->employment_status,
            'work_duration' => $request->work_duration,
            'work_field' => $request->work_field,
            'rank_group' => $request->rank_group,
            'completed_step' => max($profile->completed_step, 2),
        ]);

        return redirect()->route('tutor.wizard', ['step' => 3])->with('success', 'Data instansi berhasil disimpan.');
    }

    /**
     * Save Step 3 (Pendidikan).
     */
    public function saveStep3(Request $request)
    {
        $profile = Auth::user()->profile;

        $request->validate([
            'last_education' => 'required|in:S2,S3',
            'university_name' => 'required|string|max:255',
            'field_of_study' => 'required|string|max:255',
            'graduation_year' => 'required|numeric|digits:4|min:1950|max:' . (date('Y') + 1),
            'field_of_expertise' => 'required|string|max:255',
            'title_front' => 'nullable|string|max:50',
            'title_back' => 'nullable|string|max:50',
        ], [
            'last_education.required' => 'Pendidikan terakhir wajib diisi.',
            'last_education.in' => 'Pendidikan terakhir harus S2 atau S3.',
            'university_name.required' => 'Nama universitas wajib diisi.',
            'field_of_study.required' => 'Bidang studi wajib diisi.',
            'graduation_year.required' => 'Tahun lulus wajib diisi.',
            'graduation_year.digits' => 'Tahun lulus harus berupa 4 digit angka.',
            'field_of_expertise.required' => 'Bidang keahlian wajib diisi.',
        ]);

        $profile->update([
            'last_education' => $request->last_education,
            'university_name' => $request->university_name,
            'field_of_study' => $request->field_of_study,
            'graduation_year' => $request->graduation_year,
            'field_of_expertise' => $request->field_of_expertise,
            'title_front' => $request->title_front,
            'title_back' => $request->title_back,
            'completed_step' => max($profile->completed_step, 3),
        ]);

        return redirect()->route('tutor.wizard', ['step' => 4])->with('success', 'Data pendidikan berhasil disimpan.');
    }

    /**
     * Save Step 4 (Pilih Mata Kuliah).
     */
    public function saveStep4(Request $request)
    {
        $profile = Auth::user()->profile;

        if (in_array($profile->status, ['Pending', 'Approved'])) {
            return redirect()->route('tutor.dashboard')->with('error', 'Pendaftaran Anda sudah dikirim dan tidak dapat diubah.');
        }

        $request->validate([
            'course_id' => 'required|exists:courses,idmk'
        ], [
            'course_id.required' => 'Anda wajib memilih salah satu mata kuliah yang ditawarkan.'
        ]);

        $profile->courses()->sync([$request->course_id]);
        $profile->update([
            'completed_step' => max($profile->completed_step, 4)
        ]);

        return redirect()->route('tutor.wizard', ['step' => 5])->with('success', 'Mata kuliah pilihan berhasil disimpan.');
    }

    /**
     * Save Step 5 (Dokumen) & Submit.
     */
    public function saveStep5(Request $request)
    {
        $profile = Auth::user()->profile;

        // If file already exists, it is not strictly required to upload it again
        $rules = [
            'file_ktp' => ($profile->file_ktp ? 'nullable' : 'required') . '|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_npwp' => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_buku_tabungan' => ($profile->file_buku_tabungan ? 'nullable' : 'required') . '|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_ijazah' => ($profile->file_ijazah ? 'nullable' : 'required') . '|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_cv' => ($profile->file_cv ? 'nullable' : 'required') . '|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_surat_kesediaan' => ($profile->file_surat_kesediaan ? 'nullable' : 'required') . '|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_transkrip' => ($profile->file_transkrip ? 'nullable' : 'required') . '|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_sertifikat_pjj' => ($profile->file_sertifikat_pjj ? 'nullable' : 'required') . '|mimes:pdf,jpg,jpeg,png|max:2048',
        ];

        $request->validate($rules, [
            'file_ktp.required' => 'Dokumen KTP wajib diunggah.',
            'file_buku_tabungan.required' => 'Dokumen Buku Tabungan wajib diunggah.',
            'file_ijazah.required' => 'Dokumen Ijazah Terakhir wajib diunggah.',
            'file_cv.required' => 'Dokumen CV wajib diunggah.',
            'file_surat_kesediaan.required' => 'Surat Kesediaan wajib diunggah.',
            'file_transkrip.required' => 'Dokumen Transkrip Nilai wajib diunggah.',
            'file_sertifikat_pjj.required' => 'Dokumen Sertifikat Mengelola PJJ wajib diunggah.',
            'file_ktp.mimes' => 'Format file KTP harus berupa PDF, JPG, JPEG, atau PNG.',
            'file_npwp.mimes' => 'Format file NPWP harus berupa PDF, JPG, JPEG, atau PNG.',
            'file_buku_tabungan.mimes' => 'Format file Buku Tabungan harus berupa PDF, JPG, JPEG, atau PNG.',
            'file_ijazah.mimes' => 'Format file Ijazah harus berupa PDF, JPG, JPEG, atau PNG.',
            'file_cv.mimes' => 'Format file CV harus berupa PDF, JPG, JPEG, atau PNG.',
            'file_surat_kesediaan.mimes' => 'Format file Surat Kesediaan harus berupa PDF, JPG, JPEG, atau PNG.',
            'file_transkrip.mimes' => 'Format file Transkrip Nilai harus berupa PDF, JPG, JPEG, atau PNG.',
            'file_sertifikat_pjj.mimes' => 'Format file Sertifikat Mengelola PJJ harus berupa PDF, JPG, JPEG, atau PNG.',
            '*.max' => 'Ukuran file maksimal adalah 2MB.',
        ]);

        $updates = [];

        // Upload documents if uploaded
        $documentTypes = ['file_ktp', 'file_npwp', 'file_buku_tabungan', 'file_ijazah', 'file_cv', 'file_surat_kesediaan', 'file_transkrip', 'file_sertifikat_pjj'];
        foreach ($documentTypes as $type) {
            if ($request->hasFile($type)) {
                // Delete old file if exists
                if ($profile->$type) {
                    Storage::disk('public')->delete($profile->$type);
                }
                $path = $request->file($type)->store('tutors/documents', 'public');
                $updates[$type] = $path;
            }
        }

        // Check if tutor has selected a course
        $hasSelectedCourse = $profile->courses()->count() > 0;

        if ($hasSelectedCourse) {
            // Generate registration number if not yet generated
            if (!$profile->registration_number) {
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
                $updates['registration_number'] = "PJJ-TUTOR-{$year}-{$sequence}";
            }

            // Set status to Pending and update steps
            $updates['status'] = 'Pending';
            $updates['completed_step'] = 5;
            $updates['rejection_reason'] = null; // Clear previous rejection reason if re-submitting

            $profile->update($updates);

            return redirect()->route('tutor.dashboard')->with('success', 'Pendaftaran Anda berhasil dikirim dan saat ini sedang ditinjau oleh Admin.');
        } else {
            // Save step 5 progress, but do NOT submit yet (keep status as Draft/Rejected)
            $updates['completed_step'] = 5;
            $profile->update($updates);

            return redirect()->route('tutor.wizard', ['step' => 4])->with('error', 'Dokumen pendaftaran berhasil disimpan. Silakan pilih mata kuliah terlebih dahulu sebelum mengirim pendaftaran.');
        }
    }

    /**
     * Final submission of the registration from the dashboard.
     */
    public function submit(Request $request)
    {
        $profile = Auth::user()->profile;

        if (!$profile) {
            return redirect()->route('tutor.dashboard');
        }

        if (in_array($profile->status, ['Pending', 'Approved'])) {
            return redirect()->route('tutor.dashboard')->with('error', 'Pendaftaran Anda sudah dikirim.');
        }

        if ($profile->completed_step < 5) {
            return redirect()->route('tutor.wizard')->with('error', 'Silakan lengkapi seluruh berkas pendaftaran terlebih dahulu.');
        }

        if ($profile->courses()->count() === 0) {
            return redirect()->route('tutor.dashboard')->with('error', 'Silakan pilih mata kuliah terlebih dahulu sebelum mengirim pendaftaran.');
        }

        // Generate registration number if not yet generated
        $updates = [];
        if (!$profile->registration_number) {
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
            $updates['registration_number'] = "PJJ-TUTOR-{$year}-{$sequence}";
        }

        $updates['status'] = 'Pending';
        $updates['rejection_reason'] = null; // Clear previous rejection reason if re-submitting

        $profile->update($updates);

        return redirect()->route('tutor.dashboard')->with('success', 'Pendaftaran Anda berhasil dikirim dan saat ini sedang ditinjau oleh Admin.');
    }

    /**
     * Show tutor course selection page (redirects to wizard).
     */
    public function showCourses()
    {
        $user = Auth::user();
        if (!$user->isTutor()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('tutor.wizard', ['step' => 4]);
    }

    /**
     * Save selected courses for the tutor (redirects to wizard step 5).
     */
    public function saveCourses(Request $request)
    {
        $profile = Auth::user()->profile;

        if (in_array($profile->status, ['Pending', 'Approved'])) {
            return redirect()->route('tutor.dashboard')->with('error', 'Pendaftaran Anda sudah dikirim dan tidak dapat diubah.');
        }

        $request->validate([
            'course_id' => 'required|exists:courses,idmk'
        ], [
            'course_id.required' => 'Anda wajib memilih salah satu mata kuliah yang ditawarkan.'
        ]);

        $profile->courses()->sync([$request->course_id]);
        $profile->update([
            'completed_step' => max($profile->completed_step, 4)
        ]);

        return redirect()->route('tutor.wizard', ['step' => 5])->with('success', 'Mata kuliah pilihan berhasil disimpan.');
    }

    /**
     * Show tutor password change page.
     */
    public function showPassword()
    {
        $user = Auth::user();
        if (!$user->isTutor()) {
            return redirect()->route('admin.dashboard');
        }

        return view('tutor.password');
    }
}
