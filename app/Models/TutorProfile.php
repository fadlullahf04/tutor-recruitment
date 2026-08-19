<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TutorProfile extends Model
{
    protected $fillable = [
        'user_id',
        'recruitment_period_id',
        'registration_number',
        'status',
        'rejection_reason',
        'completed_step',
        
        // Step 1: Biodata
        'nip',
        'nik',
        'full_name_with_titles',
        'gender',
        'phone',
        'npwp',
        'bank_account_number',
        'bank_name',
        'bank_account_name',
        'date_of_birth',
        'address',
        'nidn',
        'nuptk',

        // Step 2: Pendidikan
        'last_education',
        'title_front',
        'title_back',
        'university_name',
        'graduation_year',
        'field_of_expertise',
        'field_of_study',

        // Step 3: Instansi
        'institution_name',
        'employment_status',
        'work_duration',
        'work_field',
        'rank_group',

        // Step 4: Berkas
        'file_ktp',
        'file_npwp',
        'file_buku_tabungan',
        'file_ijazah',
        'file_cv',
        'file_surat_kesediaan',
        'file_transkrip',
        'file_sertifikat_pjj',
    ];

    /**
     * Get the user that owns the profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the recruitment period associated with the tutor profile.
     */
    public function period()
    {
        return $this->belongsTo(RecruitmentPeriod::class, 'recruitment_period_id');
    }

    /**
     * Get the courses selected by the tutor.
     */
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'tutor_courses', 'tutor_profile_id', 'course_id')->withTimestamps();
    }
}
