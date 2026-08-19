@extends('layouts.app')

@section('title', 'Manajemen Waktu Pendaftaran')
@section('header_title', 'Periode Pendaftaran Rekrutmen')
@section('header_subtitle', 'Kelola rentang waktu aktif untuk pendaftaran calon tutor online.')

@section('content')
    <div style="display: flex; justify-content: flex-end; margin-bottom: 1.5rem;">
        <button type="button" class="btn btn-primary" onclick="openAddPeriodModal()">
            <i class="fa-solid fa-calendar-plus"></i> Tambah Periode Pendaftaran
        </button>
    </div>

    <!-- Periods Table Card -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fa-solid fa-calendar-days"></i> Daftar Periode Rekrutmen</h3>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama Periode</th>
                            <th>Mulai</th>
                            <th>Berakhir</th>
                            <th>Status Pendaftaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($periods as $period)
                            <tr>
                                <td style="font-weight: 600;">{{ $period->name }}</td>
                                <td>{{ $period->start_date->translatedFormat('d M Y H:i') }} WIB</td>
                                <td>{{ $period->end_date->translatedFormat('d M Y H:i') }} WIB</td>
                                <td>
                                    @if($period->is_active)
                                        <span class="badge badge-approved"><i class="fa-solid fa-unlock"></i> AKTIF / DIBUKA</span>
                                    @else
                                        <span class="badge badge-draft"><i class="fa-solid fa-lock"></i> NONAKTIF / DITUTUP</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="display: flex; gap: 0.5rem;">
                                        <button type="button" class="btn btn-outline btn-sm" onclick="openEditPeriodModal({{ $period->id }}, '{{ addslashes($period->name) }}', '{{ $period->start_date->format('Y-m-d\TH:i') }}', '{{ $period->end_date->format('Y-m-d\TH:i') }}', {{ $period->is_active ? 1 : 0 }})">
                                            Edit <i class="fa-solid fa-pen"></i>
                                        </button>
                                        <form action="{{ route('admin.master.periods.destroy', $period->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus periode pendaftaran ini?');">
                                                Hapus <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align: center; color: var(--color-text-muted); padding: 3rem 0;">
                                    Belum ada data periode pendaftaran. Silakan buat satu untuk membuka pendaftaran tutor!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Period -->
    <div class="modal-overlay" id="add-period-modal">
        <div class="modal-container">
            <div class="card-header">
                <h3 class="card-title"><i class="fa-solid fa-plus"></i> Tambah Periode Pendaftaran</h3>
                <button type="button" class="btn btn-outline btn-sm" style="padding: 0.25rem 0.5rem;" onclick="closeAddPeriodModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form action="{{ route('admin.master.periods.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="name" class="form-label">Nama Periode Pendaftaran <span style="color: var(--color-danger);">*</span></label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="Contoh: Rekrutmen Semester Ganjil 2026/2027" required>
                    </div>
                    <div class="form-group">
                        <label for="start_date" class="form-label">Tanggal Mulai Pendaftaran <span style="color: var(--color-danger);">*</span></label>
                        <input type="datetime-local" id="start_date" name="start_date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="end_date" class="form-label">Tanggal Berakhir Pendaftaran <span style="color: var(--color-danger);">*</span></label>
                        <input type="datetime-local" id="end_date" name="end_date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-check" style="margin-top: 1rem;">
                            <input type="checkbox" name="is_active" value="1" class="form-check-input">
                            <span style="font-weight: 500; font-size: 0.9rem;">Setel Sebagai Periode Aktif</span>
                        </label>
                        <span style="font-size: 0.75rem; color: var(--color-text-muted); display: block; margin-top: 0.25rem; margin-left: 1.6rem;">
                            (Catatan: Mengaktifkan periode ini akan otomatis menonaktifkan periode lainnya)
                        </span>
                    </div>
                </div>
                <div class="card-footer" style="padding: 1rem 1.5rem; display: flex; justify-content: flex-end; gap: 0.75rem; border-top: 1px solid #e2e8f0; background-color: var(--color-bg-primary);">
                    <button type="button" class="btn btn-outline" onclick="closeAddPeriodModal()">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Period -->
    <div class="modal-overlay" id="edit-period-modal">
        <div class="modal-container">
            <div class="card-header">
                <h3 class="card-title"><i class="fa-solid fa-pen"></i> Edit Periode Pendaftaran</h3>
                <button type="button" class="btn btn-outline btn-sm" style="padding: 0.25rem 0.5rem;" onclick="closeEditPeriodModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form id="edit-period-form" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label for="edit_name" class="form-label">Nama Periode Pendaftaran <span style="color: var(--color-danger);">*</span></label>
                        <input type="text" id="edit_name" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_start_date" class="form-label">Tanggal Mulai Pendaftaran <span style="color: var(--color-danger);">*</span></label>
                        <input type="datetime-local" id="edit_start_date" name="start_date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_end_date" class="form-label">Tanggal Berakhir Pendaftaran <span style="color: var(--color-danger);">*</span></label>
                        <input type="datetime-local" id="edit_end_date" name="end_date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-check" style="margin-top: 1rem;">
                            <input type="checkbox" name="is_active" id="edit_is_active" value="1" class="form-check-input">
                            <span style="font-weight: 500; font-size: 0.9rem;">Setel Sebagai Periode Aktif</span>
                        </label>
                        <span style="font-size: 0.75rem; color: var(--color-text-muted); display: block; margin-top: 0.25rem; margin-left: 1.6rem;">
                            (Catatan: Mengaktifkan periode ini akan otomatis menonaktifkan periode lainnya)
                        </span>
                    </div>
                </div>
                <div class="card-footer" style="padding: 1rem 1.5rem; display: flex; justify-content: flex-end; gap: 0.75rem; border-top: 1px solid #e2e8f0; background-color: var(--color-bg-primary);">
                    <button type="button" class="btn btn-outline" onclick="closeEditPeriodModal()">Batal</button>
                    <button type="submit" class="btn btn-primary">Perbarui</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function openAddPeriodModal() {
            document.getElementById('add-period-modal').style.display = 'flex';
        }
        
        function closeAddPeriodModal() {
            document.getElementById('add-period-modal').style.display = 'none';
        }

        function openEditPeriodModal(id, name, startDate, endDate, isActive) {
            document.getElementById('edit-period-form').action = "{{ url('/admin/master/periods') }}/" + id;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_start_date').value = startDate;
            document.getElementById('edit_end_date').value = endDate;
            document.getElementById('edit_is_active').checked = isActive === 1;
            document.getElementById('edit-period-modal').style.display = 'flex';
        }
        
        function closeEditPeriodModal() {
            document.getElementById('edit-period-modal').style.display = 'none';
        }
    </script>
@endsection
