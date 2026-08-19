@extends('layouts.app')

@section('title', 'Master Data Fakultas')
@section('header_title', 'Master Data Fakultas')
@section('header_subtitle', 'Kelola data fakultas di lingkungan UIN Siber Syekh Nurjati.')

@section('content')
    <div style="display: flex; justify-content: flex-end; margin-bottom: 1.5rem;">
        <button type="button" class="btn btn-primary" onclick="openAddFacultyModal()">
            <i class="fa-solid fa-square-plus"></i> Tambah Fakultas
        </button>
    </div>

    <!-- Faculties Table Card -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fa-solid fa-building-columns"></i> Daftar Fakultas</h3>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama Fakultas</th>
                            <th>Jumlah Program Studi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($faculties as $faculty)
                            <tr>
                                <td style="font-weight: 600;">{{ $faculty->name }}</td>
                                <td>{{ $faculty->programs_count }} Program Studi</td>
                                <td>
                                    <div style="display: flex; gap: 0.5rem;">
                                        <button type="button" class="btn btn-outline btn-sm" onclick="openEditFacultyModal({{ $faculty->id }}, '{{ addslashes($faculty->name) }}')">
                                            Edit <i class="fa-solid fa-pen"></i>
                                        </button>
                                        @if(Auth::user()->isSuperAdmin())
                                            <form action="{{ route('admin.master.faculties.destroy', $faculty->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus fakultas ini? Semua program studi dan mata kuliah di dalamnya akan ikut terhapus.');">
                                                    Hapus <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" style="text-align: center; color: var(--color-text-muted); padding: 3rem 0;">
                                    Belum ada data fakultas. Silakan tambahkan baru.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Fakultas -->
    <div class="modal-overlay" id="add-faculty-modal">
        <div class="modal-container">
            <div class="card-header">
                <h3 class="card-title"><i class="fa-solid fa-plus"></i> Tambah Fakultas</h3>
                <button type="button" class="btn btn-outline btn-sm" style="padding: 0.25rem 0.5rem;" onclick="closeAddFacultyModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form action="{{ route('admin.master.faculties.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="name" class="form-label">Nama Fakultas <span style="color: var(--color-danger);">*</span></label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="Contoh: Fakultas Tarbiyah dan Ilmu Keguruan (FTIK)" required>
                    </div>
                </div>
                <div class="card-footer" style="padding: 1rem 1.5rem; display: flex; justify-content: flex-end; gap: 0.75rem; border-top: 1px solid #e2e8f0; background-color: var(--color-bg-primary);">
                    <button type="button" class="btn btn-outline" onclick="closeAddFacultyModal()">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Fakultas -->
    <div class="modal-overlay" id="edit-faculty-modal">
        <div class="modal-container">
            <div class="card-header">
                <h3 class="card-title"><i class="fa-solid fa-pen"></i> Edit Fakultas</h3>
                <button type="button" class="btn btn-outline btn-sm" style="padding: 0.25rem 0.5rem;" onclick="closeEditFacultyModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form id="edit-faculty-form" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label for="edit_name" class="form-label">Nama Fakultas <span style="color: var(--color-danger);">*</span></label>
                        <input type="text" id="edit_name" name="name" class="form-control" required>
                    </div>
                </div>
                <div class="card-footer" style="padding: 1rem 1.5rem; display: flex; justify-content: flex-end; gap: 0.75rem; border-top: 1px solid #e2e8f0; background-color: var(--color-bg-primary);">
                    <button type="button" class="btn btn-outline" onclick="closeEditFacultyModal()">Batal</button>
                    <button type="submit" class="btn btn-primary">Perbarui</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function openAddFacultyModal() {
            document.getElementById('add-faculty-modal').style.display = 'flex';
        }
        
        function closeAddFacultyModal() {
            document.getElementById('add-faculty-modal').style.display = 'none';
        }

        function openEditFacultyModal(id, name) {
            document.getElementById('edit-faculty-form').action = "{{ url('/admin/master/faculties') }}/" + id;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit-faculty-modal').style.display = 'flex';
        }
        
        function closeEditFacultyModal() {
            document.getElementById('edit-faculty-modal').style.display = 'none';
        }
    </script>
@endsection
