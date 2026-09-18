@extends('layouts.app')

@section('title', 'Master Data Mata Kuliah')
@section('header_title', 'Master Data Mata Kuliah')
@section('header_subtitle', 'Kelola daftar mata kuliah yang ditawarkan untuk rekrutmen tutor online.')

@section('styles')
<style>
    .table-sort-header {
        color: inherit;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        cursor: pointer;
        transition: color 0.15s ease-in-out;
        user-select: none;
    }
    .table-sort-header:hover {
        color: var(--color-primary) !important;
    }
    .table-sort-header.active {
        color: var(--color-primary) !important;
        font-weight: 700;
    }
    .table-sort-icon {
        font-size: 0.85rem;
        transition: opacity 0.15s;
    }
    .table-sort-icon.inactive {
        opacity: 0.3;
    }
</style>
@endsection

@section('content')
    @php
        $getSortUrl = function($column) use ($sortBy, $sortDirection, $search) {
            $nextDirection = ($sortBy === $column && $sortDirection === 'asc') ? 'desc' : 'asc';
            $params = [
                'sort_by' => $column,
                'sort_direction' => $nextDirection
            ];
            if (!empty($search)) {
                $params['search'] = $search;
            }
            return route('admin.master.courses.index', $params);
        };
    @endphp

    <!-- Search & Action Bar -->
    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card-body">
            <div style="display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 1rem;">
                <!-- Search Form -->
                <form action="{{ route('admin.master.courses.index') }}" method="GET" style="display: flex; gap: 0.75rem; flex: 1; min-width: 280px; max-width: 650px; align-items: flex-end;">
                    <input type="hidden" name="sort_by" value="{{ $sortBy }}">
                    <input type="hidden" name="sort_direction" value="{{ $sortDirection }}">
                    
                    <div class="form-group" style="flex: 1; margin-bottom: 0;">
                        <label for="search" class="form-label">Pencarian Mata Kuliah</label>
                        <div style="position: relative;">
                            <input type="text" id="search" name="search" class="form-control" value="{{ $search }}" placeholder="Cari berdasarkan kode MK atau nama MK..." style="padding-left: 2.25rem;">
                            <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 0.85rem; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.9rem;"></i>
                        </div>
                    </div>

                    <div style="display: flex; gap: 0.5rem;">
                        <button type="submit" class="btn btn-primary" style="white-space: nowrap;">
                            <i class="fa-solid fa-magnifying-glass"></i> Cari
                        </button>
                        @if(!empty($search))
                            <a href="{{ route('admin.master.courses.index', ['sort_by' => $sortBy, 'sort_direction' => $sortDirection]) }}" class="btn btn-outline" style="white-space: nowrap;" title="Reset Pencarian">
                                <i class="fa-solid fa-rotate-left"></i> Reset
                            </a>
                        @endif
                    </div>
                </form>

                <!-- Add Button -->
                <div>
                    <button type="button" class="btn btn-primary" onclick="openAddCourseModal()" style="white-space: nowrap;">
                        <i class="fa-solid fa-square-plus"></i> Tambah Mata Kuliah
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Courses Table Card -->
    <div class="card">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
            <h3 class="card-title">
                <i class="fa-solid fa-book"></i> Daftar Mata Kuliah
                @if(!empty($search))
                    <span style="font-size: 0.85rem; font-weight: 500; color: var(--color-text-muted); margin-left: 0.5rem;">
                        (Hasil pencarian: "{{ $search }}")
                    </span>
                @endif
            </h3>
            <span style="font-size: 0.85rem; color: var(--color-text-muted); font-weight: 500;">
                Total: <strong>{{ $courses->total() }}</strong> mata kuliah
            </span>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>
                                <a href="{{ $getSortUrl('idmk') }}" class="table-sort-header {{ $sortBy === 'idmk' ? 'active' : '' }}" title="Urutkan berdasarkan ID MK">
                                    <span>ID MK</span>
                                    @if($sortBy === 'idmk')
                                        <i class="fa-solid fa-arrow-{{ $sortDirection === 'asc' ? 'up-1-9' : 'down-9-1' }} table-sort-icon"></i>
                                    @else
                                        <i class="fa-solid fa-sort table-sort-icon inactive"></i>
                                    @endif
                                </a>
                            </th>
                            <th>
                                <a href="{{ $getSortUrl('code') }}" class="table-sort-header {{ $sortBy === 'code' ? 'active' : '' }}" title="Urutkan berdasarkan Kode MK">
                                    <span>Kode MK</span>
                                    @if($sortBy === 'code')
                                        <i class="fa-solid fa-arrow-{{ $sortDirection === 'asc' ? 'up-a-z' : 'down-z-a' }} table-sort-icon"></i>
                                    @else
                                        <i class="fa-solid fa-sort table-sort-icon inactive"></i>
                                    @endif
                                </a>
                            </th>
                            <th>
                                <a href="{{ $getSortUrl('name') }}" class="table-sort-header {{ $sortBy === 'name' ? 'active' : '' }}" title="Urutkan berdasarkan Nama Mata Kuliah">
                                    <span>Nama Mata Kuliah</span>
                                    @if($sortBy === 'name')
                                        <i class="fa-solid fa-arrow-{{ $sortDirection === 'asc' ? 'up-a-z' : 'down-z-a' }} table-sort-icon"></i>
                                    @else
                                        <i class="fa-solid fa-sort table-sort-icon inactive"></i>
                                    @endif
                                </a>
                            </th>
                            <th>
                                <a href="{{ $getSortUrl('study_program') }}" class="table-sort-header {{ in_array($sortBy, ['study_program', 'program']) ? 'active' : '' }}" title="Urutkan berdasarkan Program Studi">
                                    <span>Program Studi / Fakultas</span>
                                    @if(in_array($sortBy, ['study_program', 'program']))
                                        <i class="fa-solid fa-arrow-{{ $sortDirection === 'asc' ? 'up-a-z' : 'down-z-a' }} table-sort-icon"></i>
                                    @else
                                        <i class="fa-solid fa-sort table-sort-icon inactive"></i>
                                    @endif
                                </a>
                            </th>
                            <th>
                                <a href="{{ $getSortUrl('semester') }}" class="table-sort-header {{ $sortBy === 'semester' ? 'active' : '' }}" title="Urutkan berdasarkan Semester">
                                    <span>Semester</span>
                                    @if($sortBy === 'semester')
                                        <i class="fa-solid fa-arrow-{{ $sortDirection === 'asc' ? 'up-1-9' : 'down-9-1' }} table-sort-icon"></i>
                                    @else
                                        <i class="fa-solid fa-sort table-sort-icon inactive"></i>
                                    @endif
                                </a>
                            </th>
                            <th>
                                <a href="{{ $getSortUrl('credits') }}" class="table-sort-header {{ $sortBy === 'credits' ? 'active' : '' }}" title="Urutkan berdasarkan SKS">
                                    <span>SKS</span>
                                    @if($sortBy === 'credits')
                                        <i class="fa-solid fa-arrow-{{ $sortDirection === 'asc' ? 'up-1-9' : 'down-9-1' }} table-sort-icon"></i>
                                    @else
                                        <i class="fa-solid fa-sort table-sort-icon inactive"></i>
                                    @endif
                                </a>
                            </th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($courses as $course)
                            <tr>
                                <td style="font-weight: 700; font-family: monospace; color: #475569;">{{ $course->idmk }}</td>
                                <td style="font-weight: 700; font-family: monospace; color: var(--color-primary);">{{ $course->code }}</td>
                                <td style="font-weight: 600;">{{ $course->name }}</td>
                                <td>
                                    @if($course->studyProgram)
                                        <div style="font-weight: 600; color: #1e293b;">{{ $course->studyProgram->name }}</div>
                                        <span style="font-size: 0.75rem; color: var(--color-text-muted);">{{ $course->studyProgram->faculty ? $course->studyProgram->faculty->name : '' }}</span>
                                    @else
                                        <span style="color: var(--color-text-muted);">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge" style="background-color: #f1f5f9; color: #334155; font-weight: 600;">Semester {{ $course->semester }}</span>
                                </td>
                                <td>{{ $course->credits }} SKS</td>
                                <td>
                                    <div style="display: flex; gap: 0.5rem;">
                                        <button type="button" class="btn btn-outline btn-sm" onclick="openEditCourseModal('{{ addslashes($course->idmk) }}', '{{ addslashes($course->code) }}', '{{ addslashes($course->name) }}', {{ $course->study_program_id }}, {{ $course->credits }}, {{ $course->semester }})">
                                            Edit <i class="fa-solid fa-pen"></i>
                                        </button>
                                        @if(Auth::user()->isSuperAdmin())
                                            <form action="{{ route('admin.master.courses.destroy', $course->idmk) }}" method="POST">
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
                                <td colspan="7" style="text-align: center; color: var(--color-text-muted); padding: 3rem 1rem;">
                                    @if(!empty($search))
                                        <i class="fa-solid fa-magnifying-glass" style="font-size: 2.5rem; display: block; margin-bottom: 0.75rem; color: #cbd5e1;"></i>
                                        <div style="font-weight: 600; font-size: 1rem; color: #475569; margin-bottom: 0.25rem;">Tidak ditemukan mata kuliah</div>
                                        <div style="font-size: 0.875rem;">Tidak ada data yang sesuai dengan kata kunci "<strong>{{ $search }}</strong>".</div>
                                        <a href="{{ route('admin.master.courses.index') }}" class="btn btn-outline btn-sm" style="margin-top: 1rem;">
                                            <i class="fa-solid fa-rotate-left"></i> Reset Pencarian
                                        </a>
                                    @else
                                        <i class="fa-solid fa-book-open" style="font-size: 2.5rem; display: block; margin-bottom: 0.75rem; color: #cbd5e1;"></i>
                                        <div style="font-weight: 600; font-size: 1rem; color: #475569; margin-bottom: 0.25rem;">Belum ada data mata kuliah</div>
                                        <div style="font-size: 0.875rem;">Silakan klik tombol "Tambah Mata Kuliah" di atas.</div>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($courses->hasPages())
            <div style="padding: 1rem 1.5rem; border-top: 1px solid #e2e8f0;">
                {{ $courses->links() }}
            </div>
        @endif
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
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="semester" class="form-label">Semester <span style="color: var(--color-danger);">*</span></label>
                            <input type="number" id="semester" name="semester" class="form-control" min="1" max="14" value="1" placeholder="Contoh: 1" required>
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="credits" class="form-label">Jumlah SKS <span style="color: var(--color-danger);">*</span></label>
                            <input type="number" id="credits" name="credits" class="form-control" min="1" max="10" placeholder="Contoh: 3" required>
                        </div>
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
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="edit_semester" class="form-label">Semester <span style="color: var(--color-danger);">*</span></label>
                            <input type="number" id="edit_semester" name="semester" class="form-control" min="1" max="14" required>
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="edit_credits" class="form-label">Jumlah SKS <span style="color: var(--color-danger);">*</span></label>
                            <input type="number" id="edit_credits" name="credits" class="form-control" min="1" max="10" required>
                        </div>
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

        function openEditCourseModal(idmk, code, name, studyProgramId, credits, semester) {
            document.getElementById('edit-course-form').action = "{{ url('/admin/master/courses') }}/" + encodeURIComponent(idmk);
            document.getElementById('edit_code').value = code;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_study_program_id').value = studyProgramId;
            document.getElementById('edit_semester').value = semester;
            document.getElementById('edit_credits').value = credits;
            document.getElementById('edit-course-modal').style.display = 'flex';
        }
        
        function closeEditCourseModal() {
            document.getElementById('edit-course-modal').style.display = 'none';
        }
    </script>
@endsection
