@extends('layouts.app')

@section('title', 'Master Data Program Studi')
@section('header_title', 'Master Data Program Studi')
@section('header_subtitle', 'Kelola data Program Studi (Prodi) untuk lingkungan UPT PJJ.')

@section('content')
    <div style="display: flex; justify-content: flex-end; margin-bottom: 1.5rem;">
        <button type="button" class="btn btn-primary" onclick="openAddProgramModal()">
            <i class="fa-solid fa-square-plus"></i> Tambah Program Studi
        </button>
    </div>

    <!-- Programs Table Card -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fa-solid fa-scroll"></i> Daftar Program Studi</h3>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama Program Studi</th>
                            <th>Fakultas Induk</th>
                            <th>Jumlah Mata Kuliah</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($programs as $program)
                            <tr>
                                <td style="font-weight: 600;">{{ $program->name }}</td>
                                <td>{{ $program->faculty ? $program->faculty->name : '-' }}</td>
                                <td>{{ $program->courses_count }} Mata Kuliah</td>
                                <td>
                                    <div style="display: flex; gap: 0.5rem;">
                                        <button type="button" class="btn btn-outline btn-sm" onclick="openEditProgramModal({{ $program->id }}, '{{ addslashes($program->name) }}', {{ $program->faculty_id }})">
                                            Edit <i class="fa-solid fa-pen"></i>
                                        </button>
                                        @if(Auth::user()->isSuperAdmin())
                                            <form action="{{ route('admin.master.programs.destroy', $program->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus program studi ini? Semua mata kuliah di dalamnya akan ikut terhapus.');">
                                                    Hapus <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; color: var(--color-text-muted); padding: 3rem 0;">
                                    Belum ada data program studi. Silakan tambahkan baru.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Program Studi -->
    <div class="modal-overlay" id="add-program-modal">
        <div class="modal-container">
            <div class="card-header">
                <h3 class="card-title"><i class="fa-solid fa-plus"></i> Tambah Program Studi</h3>
                <button type="button" class="btn btn-outline btn-sm" style="padding: 0.25rem 0.5rem;" onclick="closeAddProgramModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form action="{{ route('admin.master.programs.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="faculty_id" class="form-label">Fakultas Induk <span style="color: var(--color-danger);">*</span></label>
                        <select id="faculty_id" name="faculty_id" class="form-control form-select" required>
                            <option value="" disabled selected>-- Pilih Fakultas --</option>
                            @foreach($faculties as $faculty)
                                <option value="{{ $faculty->id }}">{{ $faculty->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="name" class="form-label">Nama Program Studi <span style="color: var(--color-danger);">*</span></label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="Contoh: PJJ Pendidikan Agama Islam (PAI)" required>
                    </div>
                </div>
                <div class="card-footer" style="padding: 1rem 1.5rem; display: flex; justify-content: flex-end; gap: 0.75rem; border-top: 1px solid #e2e8f0; background-color: var(--color-bg-primary);">
                    <button type="button" class="btn btn-outline" onclick="closeAddProgramModal()">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Program Studi -->
    <div class="modal-overlay" id="edit-program-modal">
        <div class="modal-container">
            <div class="card-header">
                <h3 class="card-title"><i class="fa-solid fa-pen"></i> Edit Program Studi</h3>
                <button type="button" class="btn btn-outline btn-sm" style="padding: 0.25rem 0.5rem;" onclick="closeEditProgramModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form id="edit-program-form" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label for="edit_faculty_id" class="form-label">Fakultas Induk <span style="color: var(--color-danger);">*</span></label>
                        <select id="edit_faculty_id" name="faculty_id" class="form-control form-select" required>
                            @foreach($faculties as $faculty)
                                <option value="{{ $faculty->id }}">{{ $faculty->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_name" class="form-label">Nama Program Studi <span style="color: var(--color-danger);">*</span></label>
                        <input type="text" id="edit_name" name="name" class="form-control" required>
                    </div>
                </div>
                <div class="card-footer" style="padding: 1rem 1.5rem; display: flex; justify-content: flex-end; gap: 0.75rem; border-top: 1px solid #e2e8f0; background-color: var(--color-bg-primary);">
                    <button type="button" class="btn btn-outline" onclick="closeEditProgramModal()">Batal</button>
                    <button type="submit" class="btn btn-primary">Perbarui</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function openAddProgramModal() {
            document.getElementById('add-program-modal').style.display = 'flex';
        }
        
        function closeAddProgramModal() {
            document.getElementById('add-program-modal').style.display = 'none';
        }

        function openEditProgramModal(id, name, facultyId) {
            document.getElementById('edit-program-form').action = "{{ url('/admin/master/programs') }}/" + id;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_faculty_id').value = facultyId;
            document.getElementById('edit-program-modal').style.display = 'flex';
        }
        
        function closeEditProgramModal() {
            document.getElementById('edit-program-modal').style.display = 'none';
        }
    </script>
@endsection
