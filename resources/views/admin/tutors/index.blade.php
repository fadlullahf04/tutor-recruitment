@extends('layouts.app')

@section('title', 'Daftar Calon Tutor')
@section('header_title', 'Manajemen Calon Tutor')
@section('header_subtitle', 'Tinjau, setujui, atau tolak berkas pendaftaran calon tutor online.')

@section('content')
    <!-- Filter Card -->
    <div class="card" style="margin-bottom: 2rem;">
        <div class="card-body">
            <form action="{{ route('admin.tutors.index') }}" method="GET" style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: flex-end;">
                <div class="form-group" style="flex: 1; min-width: 200px; margin-bottom: 0;">
                    <label for="search" class="form-label">Cari Calon Tutor</label>
                    <input type="text" id="search" name="search" class="form-control" value="{{ $search }}" placeholder="Nama, NIK, atau No. Registrasi">
                </div>

                <div class="form-group" style="width: 180px; margin-bottom: 0;">
                    <label for="status" class="form-label">Status Verifikasi</label>
                    <select id="status" name="status" class="form-control form-select">
                        <option value="">-- Semua Status --</option>
                        @if(Auth::user()->isSuperAdmin())
                            <option value="Draft" {{ $status === 'Draft' ? 'selected' : '' }}>Draft</option>
                        @endif
                        <option value="Pending" {{ $status === 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Approved" {{ $status === 'Approved' ? 'selected' : '' }}>Disetujui</option>
                        <option value="Rejected" {{ $status === 'Rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>

                <div class="form-group" style="width: 220px; margin-bottom: 0;">
                    <label for="period_id" class="form-label">Periode Rekrutmen</label>
                    <select id="period_id" name="period_id" class="form-control form-select">
                        <option value="">-- Semua Periode --</option>
                        @foreach($periods as $period)
                            <option value="{{ $period->id }}" {{ (string)$periodId === (string)$period->id ? 'selected' : '' }}>{{ $period->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="display: flex; gap: 0.5rem;">
                    <button type="submit" class="btn btn-primary">
                        Filter <i class="fa-solid fa-filter"></i>
                    </button>
                    @if($search || $status || $periodId)
                        <a href="{{ route('admin.tutors.index') }}" class="btn btn-outline">Reset</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Tutors List Card -->
    <div class="card">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <h3 class="card-title"><i class="fa-solid fa-list-ul"></i> Hasil Penyaringan Pendaftar</h3>
            <div style="display: flex; gap: 0.5rem;">
                <a href="{{ route('admin.tutors.export') }}" class="btn btn-outline btn-sm" style="color: #16a34a; border-color: #16a34a; font-weight: 600; padding: 0.5rem 1rem;">
                    <i class="fa-solid fa-file-excel"></i> Export Excel
                </a>
                @if(Auth::user()->isSuperAdmin())
                    <a href="{{ route('admin.tutors.create') }}" class="btn btn-primary btn-sm" style="padding: 0.5rem 1rem;">
                        <i class="fa-solid fa-user-plus"></i> Tambah Calon Tutor Baru
                    </a>
                @endif
            </div>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No. Registrasi</th>
                            <th>Nama Lengkap</th>
                            <th>NIK</th>
                            <th>Periode</th>
                            <th>Status</th>
                            <th>Alasan Ditolak</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tutors as $tutor)
                            <tr>
                                <td style="font-weight: 600; font-family: monospace;">{{ $tutor->registration_number ?: '-' }}</td>
                                <td style="font-weight: 600;">{{ $tutor->full_name_with_titles }}</td>
                                <td>{{ $tutor->nik }}</td>
                                <td style="font-size: 0.85rem;">{{ $tutor->period ? $tutor->period->name : '-' }}</td>
                                <td>
                                    @if($tutor->status === 'Draft')
                                        <span class="badge badge-draft">DRAFT</span>
                                    @elseif($tutor->status === 'Pending')
                                        <span class="badge badge-pending">PENDING REVIEW</span>
                                    @elseif($tutor->status === 'Approved')
                                        <span class="badge badge-approved">DISETUJUI</span>
                                    @elseif($tutor->status === 'Rejected')
                                        <span class="badge badge-rejected">DITOLAK</span>
                                    @endif
                                </td>
                                <td>
                                    @if($tutor->status === 'Rejected')
                                        <span style="color: var(--color-danger); font-size: 0.85rem; font-weight: 500;">
                                            {{ $tutor->rejection_reason ?: 'Tidak ada alasan spesifik.' }}
                                        </span>
                                    @else
                                        <span style="color: var(--color-text-muted);">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap;">
                                        @if($tutor->status !== 'Approved')
                                            <button type="button" class="btn btn-primary btn-sm" style="padding: 0.25rem 0.5rem; font-size: 0.8rem; background-color: var(--color-success); color: white;" title="Setujui Pendaftaran" onclick="quickApprove({{ $tutor->id }}, '{{ addslashes($tutor->full_name_with_titles) }}')">
                                                <i class="fa-solid fa-check"></i>
                                            </button>
                                        @endif
                                        @if($tutor->status !== 'Rejected')
                                            <button type="button" class="btn btn-danger btn-sm" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;" title="Tolak & Minta Revisi" onclick="quickReject({{ $tutor->id }}, '{{ addslashes($tutor->full_name_with_titles) }}')">
                                                <i class="fa-solid fa-xmark"></i>
                                            </button>
                                        @endif

                                        <a href="{{ route('admin.tutors.show', $tutor->id) }}" class="btn btn-outline btn-sm" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;" title="Tinjau Berkas">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        @if(Auth::user()->isSuperAdmin())
                                            <form action="{{ route('admin.tutors.destroy', $tutor->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;" title="Hapus Permanen" onclick="return confirm('Apakah Anda yakin ingin menghapus calon tutor ini secara permanen dari sistem?')">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align: center; color: var(--color-text-muted); padding: 3rem 0;">
                                    <i class="fa-solid fa-folder-open" style="font-size: 2.5rem; display: block; margin-bottom: 0.5rem;"></i>
                                    Tidak ada data calon tutor yang cocok dengan kriteria filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Pagination -->
        @if($tutors->hasPages())
            <div style="padding: 1rem 1.5rem; border-top: 1px solid #e2e8f0; display: flex; justify-content: center;">
                {{ $tutors->links() }}
            </div>
        @endif
    </div>

    <!-- Hidden form for quick approve -->
    <form id="quick-approve-form" method="POST" style="display: none;">
        @csrf
    </form>

    <!-- Modal Rejection -->
    <div class="modal-overlay" id="quick-rejection-modal">
        <div class="modal-container">
            <div class="card-header" style="background-color: var(--color-bg-primary);">
                <h3 class="card-title" style="color: var(--color-danger);"><i class="fa-solid fa-circle-exclamation"></i> Tolak & Minta Revisi Berkas</h3>
                <button type="button" class="btn btn-outline btn-sm" style="padding: 0.25rem 0.5rem;" onclick="closeQuickRejectionModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form id="quick-reject-form" method="POST">
                @csrf
                <div class="card-body">
                    <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 1rem;">
                        Tentukan alasan penolakan berkas untuk calon tutor <strong id="reject-tutor-name"></strong>. Calon tutor akan dapat membaca catatan ini dan melakukan pengisian ulang (revisi) pada data pendaftaran mereka.
                    </p>
                    <div class="form-group">
                        <label for="quick_rejection_reason" class="form-label">Alasan Penolakan / Feedback Revisi <span style="color: var(--color-danger);">*</span></label>
                        <textarea id="quick_rejection_reason" name="rejection_reason" class="form-control" rows="4" placeholder="Contoh: Dokumen KTP terpotong/buram, silakan upload ulang dengan foto yang lebih jelas." required></textarea>
                    </div>
                </div>
                <div class="card-footer" style="padding: 1rem 1.5rem; display: flex; justify-content: flex-end; gap: 0.75rem; border-top: 1px solid #e2e8f0; background-color: var(--color-bg-primary);">
                    <button type="button" class="btn btn-outline" onclick="closeQuickRejectionModal()">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak & Minta Revisi</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function quickApprove(id, name) {
            if (confirm("Apakah Anda yakin menyetujui pendaftaran calon tutor " + name + "? Setelah disetujui, tutor terdaftar akan memiliki status kelulusan.")) {
                const form = document.getElementById('quick-approve-form');
                form.action = "{{ url('admin/tutors') }}/" + id + "/approve";
                form.submit();
            }
        }
        
        function quickReject(id, name) {
            document.getElementById('reject-tutor-name').innerText = name;
            const form = document.getElementById('quick-reject-form');
            form.action = "{{ url('admin/tutors') }}/" + id + "/reject";
            document.getElementById('quick-rejection-modal').style.display = 'flex';
        }
        
        function closeQuickRejectionModal() {
            document.getElementById('quick-rejection-modal').style.display = 'none';
        }
    </script>
@endsection
