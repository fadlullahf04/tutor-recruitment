<?php

namespace App\Http\Controllers;

use App\Models\StudyProgram;
use App\Models\Faculty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgramController extends Controller
{
    /**
     * Display study programs.
     */
    public function index()
    {
        $programs = StudyProgram::with('faculty')->withCount('courses')->orderBy('name')->get();
        $faculties = Faculty::orderBy('name')->get();
        return view('admin.master.programs', compact('programs', 'faculties'));
    }

    /**
     * Store a program.
     */
    public function store(Request $request)
    {
        $request->validate([
            'faculty_id' => 'required|exists:faculties,id',
            'name' => 'required|string|max:255|unique:study_programs,name'
        ], [
            'faculty_id.required' => 'Fakultas wajib dipilih.',
            'name.required' => 'Nama program studi wajib diisi.',
            'name.unique' => 'Nama program studi sudah ada.'
        ]);

        StudyProgram::create($request->only('faculty_id', 'name'));

        return redirect()->route('admin.master.programs.index')
            ->with('success', 'Program Studi berhasil ditambahkan.');
    }

    /**
     * Update a program.
     */
    public function update(Request $request, $id)
    {
        $program = StudyProgram::findOrFail($id);

        $request->validate([
            'faculty_id' => 'required|exists:faculties,id',
            'name' => "required|string|max:255|unique:study_programs,name,{$program->id}"
        ], [
            'faculty_id.required' => 'Fakultas wajib dipilih.',
            'name.required' => 'Nama program studi wajib diisi.',
            'name.unique' => 'Nama program studi sudah ada.'
        ]);

        $program->update($request->only('faculty_id', 'name'));

        return redirect()->route('admin.master.programs.index')
            ->with('success', 'Program Studi berhasil diperbarui.');
    }

    /**
     * Destroy a program.
     */
    public function destroy($id)
    {
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Hanya Super Admin yang dapat menghapus data.');
        }

        $program = StudyProgram::findOrFail($id);
        $program->delete();

        return redirect()->route('admin.master.programs.index')
            ->with('success', 'Program Studi berhasil dihapus.');
    }
}
