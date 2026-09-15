<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\StudyProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    /**
     * Display courses.
     */
    public function index()
    {
        $courses = Course::with('studyProgram.faculty')->orderBy('semester')->orderBy('code')->get();
        $programs = StudyProgram::orderBy('name')->get();
        return view('admin.master.courses', compact('courses', 'programs'));
    }

    /**
     * Store a course.
     */
    public function store(Request $request)
    {
        $request->validate([
            'study_program_id' => 'required|exists:study_programs,id',
            'code' => 'required|string|max:50|unique:courses,code',
            'name' => 'required|string|max:255',
            'credits' => 'required|integer|min:1|max:10',
            'semester' => 'required|integer|min:1|max:14',
        ], [
            'study_program_id.required' => 'Program studi wajib dipilih.',
            'code.required' => 'Kode mata kuliah wajib diisi.',
            'code.unique' => 'Kode mata kuliah sudah terdaftar.',
            'name.required' => 'Nama mata kuliah wajib diisi.',
            'credits.required' => 'Jumlah SKS wajib diisi.',
            'semester.required' => 'Semester wajib diisi.',
        ]);

        Course::create($request->only('study_program_id', 'code', 'name', 'credits', 'semester'));

        return redirect()->route('admin.master.courses.index')
            ->with('success', 'Mata Kuliah berhasil ditambahkan.');
    }

    /**
     * Update a course.
     */
    public function update(Request $request, $idmk)
    {
        $course = Course::findOrFail($idmk);

        $request->validate([
            'study_program_id' => 'required|exists:study_programs,id',
            'code' => "required|string|max:50|unique:courses,code,{$course->idmk},idmk",
            'name' => 'required|string|max:255',
            'credits' => 'required|integer|min:1|max:10',
            'semester' => 'required|integer|min:1|max:14',
        ], [
            'study_program_id.required' => 'Program studi wajib dipilih.',
            'code.required' => 'Kode mata kuliah wajib diisi.',
            'code.unique' => 'Kode mata kuliah sudah terdaftar.',
            'name.required' => 'Nama mata kuliah wajib diisi.',
            'credits.required' => 'Jumlah SKS wajib diisi.',
            'semester.required' => 'Semester wajib diisi.',
        ]);

        $course->update($request->only('study_program_id', 'code', 'name', 'credits', 'semester'));

        return redirect()->route('admin.master.courses.index')
            ->with('success', 'Mata Kuliah berhasil diperbarui.');
    }

    /**
     * Destroy a course.
     */
    public function destroy($idmk)
    {
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Hanya Super Admin yang dapat menghapus data.');
        }

        $course = Course::findOrFail($idmk);
        $course->delete();

        return redirect()->route('admin.master.courses.index')
            ->with('success', 'Mata Kuliah berhasil dihapus.');
    }
}
