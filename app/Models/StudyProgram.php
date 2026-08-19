<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudyProgram extends Model
{
    protected $fillable = ['faculty_id', 'name'];

    /**
     * Get the faculty that owns the study program.
     */
    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    /**
     * Get the courses for the study program.
     */
    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
