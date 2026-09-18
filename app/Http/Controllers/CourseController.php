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
    public function index(Request $request)
    {
        $search = $request->get('search');
        $sortBy = $request->get('sort_by', 'semester');
        $sortDirection = strtolower($request->get('sort_direction', 'asc')) === 'desc' ? 'desc' : 'asc';

        $query = Course::with('studyProgram.faculty');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('courses.code', 'like', "%{$search}%")
                  ->orWhere('courses.name', 'like', "%{$search}%");
            });
        }

        if (in_array($sortBy, ['study_program', 'program'])) {
            $query->leftJoin('study_programs', 'courses.study_program_id', '=', 'study_programs.id')
                  ->select('courses.*')
                  ->orderBy('study_programs.name', $sortDirection)
                  ->orderBy('courses.semester', 'asc')
                  ->orderBy('courses.code', 'asc');
        } elseif ($sortBy === 'semester') {
            $query->orderBy('courses.semester', $sortDirection)
                  ->orderBy('courses.code', 'asc');
        } elseif ($sortBy === 'code') {
            $query->orderBy('courses.code', $sortDirection);
        } elseif ($sortBy === 'name') {
            $query->orderBy('courses.name', $sortDirection);
        } elseif ($sortBy === 'credits') {
            $query->orderBy('courses.credits', $sortDirection);
        } elseif ($sortBy === 'idmk') {
            $query->orderBy('courses.idmk', $sortDirection);
        } else {
            $query->orderBy('courses.semester', 'asc')
                  ->orderBy('courses.code', 'asc');
        }

        $courses = $query->paginate(10)->withQueryString();
        $programs = StudyProgram::orderBy('name')->get();

        return view('admin.master.courses', compact('courses', 'programs', 'search', 'sortBy', 'sortDirection'));
    }

    /**
     * Store a course.
     */
    public function store(Request $request)
    {
        $request->validate([
            'study_program_id' => 'required|exists:study_programs,id|unique:courses,id',
            'code' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'credits' => 'required|integer|min:1|max:10',
            'semester' => 'required|integer|min:1|max:14',
        ], [
            'study_program_id.required' => 'Program studi wajib dipilih.',
            'code.required' => 'Kode mata kuliah wajib diisi.',
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
            'code' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'credits' => 'required|integer|min:1|max:10',
            'semester' => 'required|integer|min:1|max:14',
        ], [
            'study_program_id.required' => 'Program studi wajib dipilih.',
            'code.required' => 'Kode mata kuliah wajib diisi.',
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
