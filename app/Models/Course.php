<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = ['study_program_id', 'code', 'name', 'credits'];

    /**
     * Get the study program that owns the course.
     */
    public function studyProgram()
    {
        return $this->belongsTo(StudyProgram::class);
    }
}
