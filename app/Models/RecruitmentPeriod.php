<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecruitmentPeriod extends Model
{
    protected $fillable = ['name', 'start_date', 'end_date', 'is_active'];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Get the tutor profiles registered in this period.
     */
    public function tutorProfiles()
    {
        return $this->hasMany(TutorProfile::class);
    }
}
