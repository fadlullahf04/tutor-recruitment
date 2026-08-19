<?php

namespace App\Http\Controllers;

use App\Models\RecruitmentPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeriodController extends Controller
{
    /**
     * Display a listing of recruitment periods.
     */
    public function index()
    {
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Hanya Super Admin yang dapat mengakses manajemen periode pendaftaran.');
        }

        $periods = RecruitmentPeriod::orderBy('start_date', 'desc')->get();
        return view('admin.master.periods', compact('periods'));
    }

    /**
     * Store a newly created period.
     */
    public function store(Request $request)
    {
        if (!Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'nullable|boolean'
        ], [
            'name.required' => 'Nama periode wajib diisi.',
            'start_date.required' => 'Tanggal mulai wajib diisi.',
            'end_date.required' => 'Tanggal berakhir wajib diisi.',
            'end_date.after' => 'Tanggal berakhir harus setelah tanggal mulai.'
        ]);

        $isActive = $request->boolean('is_active');

        // If setting this one active, deactivate all others
        if ($isActive) {
            RecruitmentPeriod::query()->update(['is_active' => false]);
        }

        RecruitmentPeriod::create([
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $isActive
        ]);

        return redirect()->route('admin.master.periods.index')
            ->with('success', 'Periode pendaftaran berhasil ditambahkan.');
    }

    /**
     * Update the specified period.
     */
    public function update(Request $request, $id)
    {
        if (!Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $period = RecruitmentPeriod::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'nullable|boolean'
        ], [
            'name.required' => 'Nama periode wajib diisi.',
            'start_date.required' => 'Tanggal mulai wajib diisi.',
            'end_date.required' => 'Tanggal berakhir wajib diisi.',
            'end_date.after' => 'Tanggal berakhir harus setelah tanggal mulai.'
        ]);

        $isActive = $request->boolean('is_active');

        if ($isActive) {
            RecruitmentPeriod::where('id', '!=', $period->id)->update(['is_active' => false]);
        }

        $period->update([
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $isActive
        ]);

        return redirect()->route('admin.master.periods.index')
            ->with('success', 'Periode pendaftaran berhasil diperbarui.');
    }

    /**
     * Remove the specified period.
     */
    public function destroy($id)
    {
        if (!Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $period = RecruitmentPeriod::findOrFail($id);
        $period->delete();

        return redirect()->route('admin.master.periods.index')
            ->with('success', 'Periode pendaftaran berhasil dihapus.');
    }
}
