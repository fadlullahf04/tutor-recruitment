<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FacultyController extends Controller
{
    /**
     * Display a listing of the faculties.
     */
    public function index()
    {
        $faculties = Faculty::withCount('programs')->orderBy('name')->get();
        return view('admin.master.faculties', compact('faculties'));
    }

    /**
     * Store a newly created faculty in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:faculties,name'
        ], [
            'name.required' => 'Nama fakultas wajib diisi.',
            'name.unique' => 'Nama fakultas sudah ada.'
        ]);

        Faculty::create($request->only('name'));

        return redirect()->route('admin.master.faculties.index')
            ->with('success', 'Fakultas berhasil ditambahkan.');
    }

    /**
     * Update the specified faculty in storage.
     */
    public function update(Request $request, $id)
    {
        $faculty = Faculty::findOrFail($id);

        $request->validate([
            'name' => "required|string|max:255|unique:faculties,name,{$faculty->id}"
        ], [
            'name.required' => 'Nama fakultas wajib diisi.',
            'name.unique' => 'Nama fakultas sudah ada.'
        ]);

        $faculty->update($request->only('name'));

        return redirect()->route('admin.master.faculties.index')
            ->with('success', 'Fakultas berhasil diperbarui.');
    }

    /**
     * Remove the specified faculty from storage.
     */
    public function destroy($id)
    {
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Hanya Super Admin yang dapat menghapus data.');
        }

        $faculty = Faculty::findOrFail($id);
        $faculty->delete();

        return redirect()->route('admin.master.faculties.index')
            ->with('success', 'Fakultas berhasil dihapus.');
    }
}
