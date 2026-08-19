<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\TutorProfile;
use App\Models\RecruitmentPeriod;
use App\Models\Faculty;
use App\Models\StudyProgram;
use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\TutorRegisteredMail;
use App\Mail\TutorApprovedMail;
use App\Mail\TutorRejectedMail;
use Tests\TestCase;

class TutorRegistrationFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_tutor_registration_and_course_validation_flow()
    {
        Storage::fake('public');

        // 1. Seed necessary structures
        $period = RecruitmentPeriod::create([
            'name' => 'Test Period',
            'start_date' => now()->subDay(),
            'end_date' => now()->addMonth(),
            'is_active' => true,
        ]);

        $faculty = Faculty::create(['name' => 'FTIK']);
        $program = StudyProgram::create(['faculty_id' => $faculty->id, 'name' => 'PAI']);
        $course = Course::create([
            'study_program_id' => $program->id,
            'code' => 'PAI-101',
            'name' => 'Test Course',
            'credits' => 3
        ]);

        // 2. Register user
        $user = User::create([
            'name' => 'Faqih Tutor',
            'email' => 'faqih@tutor.com',
            'password' => bcrypt('password123'),
            'role' => 'tutor'
        ]);

        $profile = TutorProfile::create([
            'user_id' => $user->id,
            'recruitment_period_id' => $period->id,
            'status' => 'Draft',
            'full_name_with_titles' => 'Faqih Tutor',
            'completed_step' => 0
        ]);

        $this->actingAs($user);

        // 3. Save Step 1 (Biodata)
        $response = $this->post(route('tutor.wizard.step1'), [
            'nik' => '1234567890123456',
            'full_name_with_titles' => 'Faqih Tutor, M.Pd.',
            'gender' => 'Laki-laki',
            'phone' => '08123456789',
            'bank_name' => 'BSI',
            'bank_account_number' => '1234567890',
            'bank_account_name' => 'Faqih Tutor',
            'date_of_birth' => '1990-01-01',
            'address' => 'Test Address',
            'nip' => '199001012015041001',
            'nidn' => '0401019001',
            'nuptk' => '1234567890123456',
            'npwp' => '123456789012345',
        ]);
        $response->assertRedirect(route('tutor.wizard', ['step' => 2]));
        $this->assertEquals(1, $profile->fresh()->completed_step);

        // 4. Save Step 2 (Data Instansi)
        $response = $this->post(route('tutor.wizard.step2'), [
            'institution_name' => 'UIN Siber Cirebon',
            'employment_status' => 'Non ASN',
            'work_duration' => '3 Tahun',
            'work_field' => 'Dosen',
            'rank_group' => 'Non ASN',
        ]);
        $response->assertRedirect(route('tutor.wizard', ['step' => 3]));
        $this->assertEquals(2, $profile->fresh()->completed_step);

        // 5. Save Step 3 (Pendidikan)
        $response = $this->post(route('tutor.wizard.step3'), [
            'last_education' => 'S2',
            'university_name' => 'UIN Siber Cirebon',
            'field_of_study' => 'PAI',
            'graduation_year' => '2020',
            'field_of_expertise' => 'Pendidikan Islam',
        ]);
        $response->assertRedirect(route('tutor.wizard', ['step' => 4]));
        $this->assertEquals(3, $profile->fresh()->completed_step);

        // 6. Save Step 4 (Pilih Mata Kuliah)
        $response = $this->post(route('tutor.wizard.step4'), [
            'course_id' => $course->id
        ]);
        $response->assertRedirect(route('tutor.wizard', ['step' => 5]));
        $this->assertEquals(4, $profile->fresh()->completed_step);

        // 7. Save Step 5 (Unggah Dokumen)
        $dummyFile = UploadedFile::fake()->create('document.pdf', 500);

        $response = $this->post(route('tutor.wizard.step5'), [
            'file_ktp' => $dummyFile,
            'file_buku_tabungan' => $dummyFile,
            'file_ijazah' => $dummyFile,
            'file_transkrip' => $dummyFile,
            'file_cv' => $dummyFile,
            'file_surat_kesediaan' => $dummyFile,
            'file_sertifikat_pjj' => $dummyFile,
        ]);

        $response->assertRedirect(route('tutor.dashboard'));
        $response->assertSessionHas('success', 'Pendaftaran Anda berhasil dikirim dan saat ini sedang ditinjau oleh Admin.');
        $this->assertEquals(5, $profile->fresh()->completed_step);
        $this->assertEquals('Pending', $profile->fresh()->status);
    }

    public function test_tutor_registration_accepts_zero_for_identity_numbers()
    {
        // 1. Seed necessary structures
        $period = RecruitmentPeriod::create([
            'name' => 'Test Period 2',
            'start_date' => now()->subDay(),
            'end_date' => now()->addMonth(),
            'is_active' => true,
        ]);

        // 2. Register user
        $user = User::create([
            'name' => 'Faqih Tutor 2',
            'email' => 'faqih2@tutor.com',
            'password' => bcrypt('password123'),
            'role' => 'tutor'
        ]);

        $profile = TutorProfile::create([
            'user_id' => $user->id,
            'recruitment_period_id' => $period->id,
            'status' => 'Draft',
            'full_name_with_titles' => 'Faqih Tutor 2',
            'completed_step' => 0
        ]);

        $this->actingAs($user);

        // 3. Save Step 1 (Biodata) with "0" for NIP, NIDN, NUPTK, NPWP
        $response = $this->post(route('tutor.wizard.step1'), [
            'nik' => '1234567890123457',
            'full_name_with_titles' => 'Faqih Tutor 2, M.Pd.',
            'gender' => 'Laki-laki',
            'phone' => '08123456780',
            'bank_name' => 'BSI',
            'bank_account_number' => '1234567890',
            'bank_account_name' => 'Faqih Tutor 2',
            'date_of_birth' => '1990-01-01',
            'address' => 'Test Address',
            'nip' => '000000000',
            'nidn' => '000',
            'nuptk' => '00000',
            'npwp' => '00',
        ]);
        $response->assertRedirect(route('tutor.wizard', ['step' => 2]));
        $this->assertEquals(1, $profile->fresh()->completed_step);
        $this->assertEquals('0', $profile->fresh()->nip);
        $this->assertEquals('0', $profile->fresh()->nidn);
        $this->assertEquals('0', $profile->fresh()->nuptk);
        $this->assertEquals('0', $profile->fresh()->npwp);
    }

    public function test_tutor_registration_sends_welcome_email()
    {
        Mail::fake();

        // 1. Seed necessary structures
        $period = RecruitmentPeriod::create([
            'name' => 'Test Period 3',
            'start_date' => now()->subDay(),
            'end_date' => now()->addMonth(),
            'is_active' => true,
        ]);

        // 2. Submit register request
        $response = $this->post(route('register.post'), [
            'nik' => '1122334455667788',
            'name' => 'Tutor Baru',
            'email' => 'tutorbaru@gmail.com',
        ]);

        $response->assertRedirect(route('register.success'));
        Mail::assertSent(TutorRegisteredMail::class, function ($mail) {
            return $mail->hasTo('tutorbaru@gmail.com') &&
                   $mail->user->name === 'Tutor Baru' &&
                   $mail->nik === '1122334455667788';
        });
    }

    public function test_tutor_registration_and_login_sanitizes_nik()
    {
        Mail::fake();

        // 1. Seed active period
        $period = RecruitmentPeriod::create([
            'name' => 'Test Period NIK Sanitize',
            'start_date' => now()->subDay(),
            'end_date' => now()->addMonth(),
            'is_active' => true,
        ]);

        // 2. Register user with spaces and dashes in NIK
        $response = $this->post(route('register.post'), [
            'nik' => '9988-7766 5544 3322',
            'name' => 'Tutor Sanitize NIK',
            'email' => 'tutorsanitize@gmail.com',
        ]);

        $response->assertRedirect(route('register.success'));
        
        // Assert stored NIK is clean
        $profile = TutorProfile::where('full_name_with_titles', 'Tutor Sanitize NIK')->first();
        $this->assertNotNull($profile);
        $this->assertEquals('9988776655443322', $profile->nik);

        // 3. Attempt login with formatted NIK
        $loginResponse = $this->post(route('login.post'), [
            'login_identifier' => '9988 7766-5544.3322',
            'password' => 'PJJ-12345',
        ]);

        $loginResponse->assertRedirect(route('tutor.dashboard'));
        $this->assertAuthenticatedAs($profile->user);
    }

    public function test_tutor_approval_sends_email()
    {
        Mail::fake();

        // 1. Seed necessary structures
        $period = RecruitmentPeriod::create([
            'name' => 'Test Period 4',
            'start_date' => now()->subDay(),
            'end_date' => now()->addMonth(),
            'is_active' => true,
        ]);

        $faculty = Faculty::create(['name' => 'FTIK']);
        $program = StudyProgram::create(['faculty_id' => $faculty->id, 'name' => 'PAI']);
        $course = Course::create([
            'study_program_id' => $program->id,
            'code' => 'PAI-101',
            'name' => 'Test Course',
            'credits' => 3
        ]);

        // Create admin user
        $admin = User::create([
            'name' => 'Admin PJJ',
            'email' => 'admin@uinsi.ac.id',
            'password' => bcrypt('password123'),
            'role' => 'admin'
        ]);

        // Create tutor user and profile
        $tutor = User::create([
            'name' => 'Calon Tutor',
            'email' => 'calontutor@gmail.com',
            'password' => bcrypt('password123'),
            'role' => 'tutor'
        ]);

        $profile = TutorProfile::create([
            'user_id' => $tutor->id,
            'recruitment_period_id' => $period->id,
            'status' => 'Pending',
            'full_name_with_titles' => 'Calon Tutor',
            'completed_step' => 5
        ]);
        $profile->courses()->attach($course->id);

        $this->actingAs($admin);

        // Approve tutor
        $response = $this->post(route('admin.tutors.approve', $profile->id));
        $response->assertRedirect();
        
        $this->assertEquals('Approved', $profile->fresh()->status);

        Mail::assertSent(TutorApprovedMail::class, function ($mail) use ($profile) {
            return $mail->hasTo('calontutor@gmail.com') &&
                   $mail->tutor->id === $profile->id;
        });
    }

    public function test_tutor_rejection_sends_email()
    {
        Mail::fake();

        // 1. Seed necessary structures
        $period = RecruitmentPeriod::create([
            'name' => 'Test Period 5',
            'start_date' => now()->subDay(),
            'end_date' => now()->addMonth(),
            'is_active' => true,
        ]);

        // Create admin user
        $admin = User::create([
            'name' => 'Admin PJJ',
            'email' => 'admin@uinsi.ac.id',
            'password' => bcrypt('password123'),
            'role' => 'admin'
        ]);

        // Create tutor user and profile
        $tutor = User::create([
            'name' => 'Calon Tutor',
            'email' => 'calontutor2@gmail.com',
            'password' => bcrypt('password123'),
            'role' => 'tutor'
        ]);

        $profile = TutorProfile::create([
            'user_id' => $tutor->id,
            'recruitment_period_id' => $period->id,
            'status' => 'Pending',
            'full_name_with_titles' => 'Calon Tutor',
            'completed_step' => 5
        ]);

        $this->actingAs($admin);

        // Reject tutor
        $response = $this->post(route('admin.tutors.reject', $profile->id), [
            'rejection_reason' => 'Dokumen transkrip nilai tidak terbaca.'
        ]);
        $response->assertRedirect();
        
        $this->assertEquals('Rejected', $profile->fresh()->status);
        $this->assertEquals('Dokumen transkrip nilai tidak terbaca.', $profile->fresh()->rejection_reason);

        Mail::assertSent(TutorRejectedMail::class, function ($mail) use ($profile) {
            return $mail->hasTo('calontutor2@gmail.com') &&
                   $mail->tutor->id === $profile->id &&
                   $mail->tutor->rejection_reason === 'Dokumen transkrip nilai tidak terbaca.';
        });
    }
}
