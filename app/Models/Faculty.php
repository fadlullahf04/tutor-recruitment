<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    protected $fillable = ['name'];

    /**
     * Get the study programs for the faculty.
     */
    public function programs()
    {
        return $this->hasMany(StudyProgram::class);
    }

    /**
     * Get the study programs for the faculty (for snake_case/camelCase support).
     */
    public function studyPrograms()
    {
        return $this->hasMany(StudyProgram::class);
    }
}
