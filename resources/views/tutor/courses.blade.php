@extends('layouts.app')

@section('title', 'Pilih Mata Kuliah')
@section('header_title', 'Pilihan Mata Kuliah Yang Ditawarkan')
@section('header_subtitle', 'Pilihlah salah satu mata kuliah yang ingin Anda ajar sesuai dengan kompetensi Anda.')

@section('content')
    @php
        $selectedCourse = $profile->courses->first();
        $selectedCourseId = $selectedCourse ? $selectedCourse->idmk : null;
        $selectedProgramId = $selectedCourse ? $selectedCourse->study_program_id : null;
        $selectedFacultyId = null;
        if ($selectedCourse && $selectedCourse->studyProgram) {
            $selectedFacultyId = $selectedCourse->studyProgram->faculty_id;
        }
    @endphp

    <div class="card" style="border-radius: var(--border-radius-lg); padding: 2.5rem; margin-bottom: 2rem;">
        <h3 style="font-size: 1.2rem; margin-bottom: 1.5rem; border-bottom: 2px solid #f1f5f9; padding-bottom: 0.5rem; color: #334155;">
            <i class="fa-solid fa-book-open"></i> Pemilihan Mata Kuliah
        </h3>

        @if(in_array($profile->status, ['Draft', 'Rejected']))
            <form action="{{ route('tutor.save-courses') }}" method="POST">
                @csrf
                <p style="font-size: 0.9rem; color: var(--color-text-muted); margin-bottom: 1.5rem;">
                    Silakan pilih **satu (1)** mata kuliah yang ditawarkan di bawah ini. Anda hanya dapat memilih satu mata kuliah yang paling sesuai dengan bidang keahlian utama Anda:
                </p>

                <div style="max-width: 600px; margin-bottom: 2rem;">
                    <div class="form-group" style="margin-bottom: 1.25rem;">
                        <label for="faculty_id" class="form-label" style="font-weight: 600;">Pilih Fakultas <span style="color: var(--color-danger);">*</span></label>
                        <select id="faculty_id" name="faculty_id" class="form-control form-select" required>
                            <option value="" disabled selected>-- Pilih Fakultas --</option>
                            @foreach($faculties as $faculty)
                                <option value="{{ $faculty->id }}">{{ $faculty->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group" style="margin-bottom: 1.25rem;">
                        <label for="study_program_id" class="form-label" style="font-weight: 600;">Pilih Program Studi <span style="color: var(--color-danger);">*</span></label>
                        <select id="study_program_id" name="study_program_id" class="form-control form-select" required disabled>
                            <option value="" disabled selected>-- Pilih Program Studi --</option>
                        </select>
                    </div>

                    <div class="form-group" style="margin-bottom: 2rem;">
                        <label for="course_id" class="form-label" style="font-weight: 600;">Pilih Mata Kuliah <span style="color: var(--color-danger);">*</span></label>
                        <select id="course_id" name="course_id" class="form-control form-select" required disabled>
                            <option value="" disabled selected>-- Pilih Mata Kuliah --</option>
                        </select>
                    </div>
                </div>

                @error('course_id')
                    <div class="alert alert-danger" style="margin-bottom: 1.5rem;">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <div>{{ $message }}</div>
                    </div>
                @enderror

                <div style="display: flex; justify-content: flex-end; border-top: 1px solid #f1f5f9; padding-top: 1.5rem;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Pilihan Mata Kuliah
                    </button>
                </div>
            </form>
        @else
            <!-- Read-only state when submitted -->
            <div class="alert alert-info" style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fa-solid fa-circle-info"></i>
                <div>Pendaftaran Anda telah dikirim. Pilihan mata kuliah tidak dapat diubah kembali.</div>
            </div>

            @php
                $selectedCourse = $profile->courses->first();
            @endphp

            @if(!$selectedCourse)
                <div class="alert alert-warning" style="font-size: 0.85rem;">
                    <i class="fa-solid fa-circle-info"></i>
                    <div>Tidak ada mata kuliah pilihan yang terdaftar.</div>
                </div>
            @else
                <h4 style="font-size: 0.95rem; color: var(--color-text-muted); margin-bottom: 1rem;">Mata Kuliah Yang Dipilih:</h4>
                <div style="max-width: 400px; border: 1px solid #d1fae5; border-radius: var(--border-radius-md); padding: 1.25rem; background-color: var(--color-primary-light); display: flex; align-items: flex-start; gap: 0.75rem;">
                    <div style="font-size: 1.5rem; color: var(--color-primary); margin-top: 0.1rem;">
                        <i class="fa-solid fa-check-double"></i>
                    </div>
                    <div>
                        <span style="display: block; font-weight: 700; font-family: monospace; font-size: 0.85rem; color: var(--color-primary);">{{ $selectedCourse->code }}</span>
                        <span style="display: block; font-size: 1rem; font-weight: 600; color: #1e293b; line-height: 1.3; margin: 0.2rem 0;">{{ $selectedCourse->name }}</span>
                        <span style="display: block; font-size: 0.8rem; color: var(--color-text-muted);">
                            Semester {{ $selectedCourse->semester }} | SKS: {{ $selectedCourse->credits }} | {{ $selectedCourse->studyProgram ? $selectedCourse->studyProgram->name : '' }}
                        </span>
                    </div>
                </div>
            @endif
        @endif
    </div>
@endsection

@section('scripts')
    <script>
        const facultiesData = @json($faculties);

        const facultySelect = document.getElementById('faculty_id');
        const programSelect = document.getElementById('study_program_id');
        const courseSelect = document.getElementById('course_id');

        if (facultySelect) {
            facultySelect.addEventListener('change', function() {
                const facultyId = this.value;
                programSelect.innerHTML = '<option value="" disabled selected>-- Pilih Program Studi --</option>';
                courseSelect.innerHTML = '<option value="" disabled selected>-- Pilih Mata Kuliah --</option>';
                courseSelect.disabled = true;
                
                const selectedFaculty = facultiesData.find(f => f.id == facultyId);
                if (selectedFaculty && selectedFaculty.study_programs) {
                    selectedFaculty.study_programs.forEach(prog => {
                        const opt = document.createElement('option');
                        opt.value = prog.id;
                        opt.textContent = prog.name;
                        programSelect.appendChild(opt);
                    });
                    programSelect.disabled = false;
                } else {
                    programSelect.disabled = true;
                }
            });

            programSelect.addEventListener('change', function() {
                const programId = this.value;
                courseSelect.innerHTML = '<option value="" disabled selected>-- Pilih Mata Kuliah --</option>';
                
                const facultyId = facultySelect.value;
                const selectedFaculty = facultiesData.find(f => f.id == facultyId);
                if (selectedFaculty && selectedFaculty.study_programs) {
                    const selectedProg = selectedFaculty.study_programs.find(p => p.id == programId);
                    if (selectedProg && selectedProg.courses) {
                        selectedProg.courses.forEach(course => {
                            const opt = document.createElement('option');
                            opt.value = course.idmk;
                            opt.textContent = `[Sem ${course.semester}] ${course.code} - ${course.name} (${course.credits} SKS)`;
                            courseSelect.appendChild(opt);
                        });
                        courseSelect.disabled = false;
                    } else {
                        courseSelect.disabled = true;
                    }
                } else {
                    courseSelect.disabled = true;
                }
            });

            // Pre-populate if there is an existing selection
            const selectedFacultyId = @json($selectedFacultyId);
            const selectedProgramId = @json($selectedProgramId);
            const selectedCourseId = @json($selectedCourseId);

            if (selectedFacultyId) {
                facultySelect.value = selectedFacultyId;
                facultySelect.dispatchEvent(new Event('change'));
                
                if (selectedProgramId) {
                    programSelect.value = selectedProgramId;
                    programSelect.dispatchEvent(new Event('change'));
                    
                    if (selectedCourseId) {
                        courseSelect.value = selectedCourseId;
                    }
                }
            }
        }
    </script>
@endsection
