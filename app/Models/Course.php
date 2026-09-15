<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $primaryKey = 'idmk';

    protected $fillable = [
        'study_program_id',
        'code',
        'name',
        'credits',
        'semester',
    ];

    /**
     * Get the study program that owns the course.
     */
    public function studyProgram()
    {
        return $this->belongsTo(StudyProgram::class);
    }
}
