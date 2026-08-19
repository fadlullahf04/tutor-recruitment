@extends('layouts.app')

@section('title', 'Master Data Mata Kuliah')
@section('header_title', 'Master Data Mata Kuliah')
@section('header_subtitle', 'Kelola daftar mata kuliah yang ditawarkan untuk rekrutmen tutor online.')

@section('content')
    <div style="display: flex; justify-content: flex-end; margin-bottom: 1.5rem;">
        <button type="button" class="btn btn-primary" onclick="openAddCourseModal()">
            <i class="fa-solid fa-square-plus"></i> Tambah Mata Kuliah
        </button>
    </div>

    <!-- Courses Table Card -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fa-solid fa-book"></i> Daftar Mata Kuliah</h3>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Kode MK</th>
                            <th>Nama Mata Kuliah</th>
                            <th>Program Studi / Fakultas</th>
                            <th>SKS</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($courses as $course)
                            <tr>
                                <td style="font-weight: 700; font-family: monospace; color: var(--color-primary);">{{ $course->code }}</td>
                                <td style="font-weight: 600;">{{ $course->name }}</td>
                                <td>
                                    @if($course->studyProgram)
                                        {{ $course->studyProgram->name }}<br>
                                        <span style="font-size: 0.75rem; color: var(--color-text-muted);">{{ $course->studyProgram->faculty ? $course->studyProgram->faculty->name : '' }}</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $course->credits }} SKS</td>
                                <td>
                                    <div style="display: flex; gap: 0.5rem;">
                                        <button type="button" class="btn btn-outline btn-sm" onclick="openEditCourseModal({{ $course->id }}, '{{ addslashes($course->code) }}', '{{ addslashes($course->name) }}', {{ $course->study_program_id }}, {{ $course->credits }})">
                                            Edit <i class="fa-solid fa-pen"></i>
                                        </button>
                                        @if(Auth::user()->isSuperAdmin())
                                            <form action="{{ route('admin.master.courses.destroy', $course->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus mata kuliah ini?');">
                                                    Hapus <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align: center; color: var(--color-text-muted); padding: 3rem 0;">
                                    Belum ada data mata kuliah. Silakan tambahkan baru.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Course -->
    <div class="modal-overlay" id="add-course-modal">
        <div class="modal-container">
            <div class="card-header">
                <h3 class="card-title"><i class="fa-solid fa-plus"></i> Tambah Mata Kuliah</h3>
                <button type="button" class="btn btn-outline btn-sm" style="padding: 0.25rem 0.5rem;" onclick="closeAddCourseModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form action="{{ route('admin.master.courses.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="study_program_id" class="form-label">Program Studi <span style="color: var(--color-danger);">*</span></label>
                        <select id="study_program_id" name="study_program_id" class="form-control form-select" required>
                            <option value="" disabled selected>-- Pilih Program Studi --</option>
                            @foreach($programs as $program)
                                <option value="{{ $program->id }}">{{ $program->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="code" class="form-label">Kode Mata Kuliah <span style="color: var(--color-danger);">*</span></label>
                        <input type="text" id="code" name="code" class="form-control" placeholder="Contoh: PAI-302, TBI-101" required>
                    </div>
                    <div class="form-group">
                        <label for="name" class="form-label">Nama Mata Kuliah <span style="color: var(--color-danger);">*</span></label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="Masukkan nama mata kuliah" required>
                    </div>
                    <div class="form-group">
                        <label for="credits" class="form-label">Jumlah SKS <span style="color: var(--color-danger);">*</span></label>
                        <input type="number" id="credits" name="credits" class="form-control" min="1" max="10" placeholder="Contoh: 3" required>
                    </div>
                </div>
                <div class="card-footer" style="padding: 1rem 1.5rem; display: flex; justify-content: flex-end; gap: 0.75rem; border-top: 1px solid #e2e8f0; background-color: var(--color-bg-primary);">
                    <button type="button" class="btn btn-outline" onclick="closeAddCourseModal()">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Course -->
    <div class="modal-overlay" id="edit-course-modal">
        <div class="modal-container">
            <div class="card-header">
                <h3 class="card-title"><i class="fa-solid fa-pen"></i> Edit Mata Kuliah</h3>
                <button type="button" class="btn btn-outline btn-sm" style="padding: 0.25rem 0.5rem;" onclick="closeEditCourseModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form id="edit-course-form" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label for="edit_study_program_id" class="form-label">Program Studi <span style="color: var(--color-danger);">*</span></label>
                        <select id="edit_study_program_id" name="study_program_id" class="form-control form-select" required>
                            @foreach($programs as $program)
                                <option value="{{ $program->id }}">{{ $program->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_code" class="form-label">Kode Mata Kuliah <span style="color: var(--color-danger);">*</span></label>
                        <input type="text" id="edit_code" name="code" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_name" class="form-label">Nama Mata Kuliah <span style="color: var(--color-danger);">*</span></label>
                        <input type="text" id="edit_name" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_credits" class="form-label">Jumlah SKS <span style="color: var(--color-danger);">*</span></label>
                        <input type="number" id="edit_credits" name="credits" class="form-control" min="1" max="10" required>
                    </div>
                </div>
                <div class="card-footer" style="padding: 1rem 1.5rem; display: flex; justify-content: flex-end; gap: 0.75rem; border-top: 1px solid #e2e8f0; background-color: var(--color-bg-primary);">
                    <button type="button" class="btn btn-outline" onclick="closeEditCourseModal()">Batal</button>
                    <button type="submit" class="btn btn-primary">Perbarui</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function openAddCourseModal() {
            document.getElementById('add-course-modal').style.display = 'flex';
        }
        
        function closeAddCourseModal() {
            document.getElementById('add-course-modal').style.display = 'none';
        }

        function openEditCourseModal(id, code, name, studyProgramId, credits) {
            document.getElementById('edit-course-form').action = "{{ url('/admin/master/courses') }}/" + id;
            document.getElementById('edit_code').value = code;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_study_program_id').value = studyProgramId;
            document.getElementById('edit_credits').value = credits;
            document.getElementById('edit-course-modal').style.display = 'flex';
        }
        
        function closeEditCourseModal() {
            document.getElementById('edit-course-modal').style.display = 'none';
        }
    </script>
@endsection
