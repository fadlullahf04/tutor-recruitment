@extends('layouts.app')

@section('title', 'Lengkapi Berkas Pendaftaran')
@section('header_title', 'Formulir Pendaftaran Tutor')
@section('header_subtitle', 'Lengkapi seluruh data secara akurat untuk mempermudah proses seleksi.')

@section('content')
    <div class="card" style="border-radius: var(--border-radius-lg); padding: 2.5rem; margin-bottom: 2rem;">
        <!-- Wizard Steps Navigation -->
        <div class="wizard-header">
            @php
                $progressPercent = (($step - 1) / 4) * 80; // maps to line fill for 5 steps
            @endphp
            <div class="wizard-progress-bar" style="width: {{ $progressPercent }}%; left: 10%;"></div>
            
            <!-- Step 1 Node -->
            <div class="wizard-step-node {{ $step === 1 ? 'active' : ($profile->completed_step >= 1 ? 'completed' : '') }}">
                <div class="wizard-step-circle">
                    @if($profile->completed_step >= 1 && $step !== 1)
                        <i class="fa-solid fa-check"></i>
                    @else
                        1
                    @endif
                </div>
                <div class="wizard-step-label">Biodata Diri</div>
            </div>
            
            <!-- Step 2 Node -->
            <div class="wizard-step-node {{ $step === 2 ? 'active' : ($profile->completed_step >= 2 ? 'completed' : '') }}">
                <div class="wizard-step-circle">
                    @if($profile->completed_step >= 2 && $step !== 2)
                        <i class="fa-solid fa-check"></i>
                    @else
                        2
                    @endif
                </div>
                <div class="wizard-step-label">Data Instansi</div>
            </div>
            
            <!-- Step 3 Node -->
            <div class="wizard-step-node {{ $step === 3 ? 'active' : ($profile->completed_step >= 3 ? 'completed' : '') }}">
                <div class="wizard-step-circle">
                    @if($profile->completed_step >= 3 && $step !== 3)
                        <i class="fa-solid fa-check"></i>
                    @else
                        3
                    @endif
                </div>
                <div class="wizard-step-label">Riwayat Pendidikan</div>
            </div>
            
            <!-- Step 4 Node -->
            <div class="wizard-step-node {{ $step === 4 ? 'active' : ($profile->completed_step >= 4 ? 'completed' : '') }}">
                <div class="wizard-step-circle">
                    @if($profile->completed_step >= 4 && $step !== 4)
                        <i class="fa-solid fa-check"></i>
                    @else
                        4
                    @endif
                </div>
                <div class="wizard-step-label">Pilih Mata Kuliah</div>
            </div>
            
            <!-- Step 5 Node -->
            <div class="wizard-step-node {{ $step === 5 ? 'active' : ($profile->completed_step >= 5 ? 'completed' : '') }}">
                <div class="wizard-step-circle">
                    5
                </div>
                <div class="wizard-step-label">Unggah Dokumen</div>
            </div>
        </div>

        <!-- Wizard Forms -->
        @if($step === 1)
            <!-- STEP 1: BIODATA -->
            <form action="{{ route('tutor.wizard.step1') }}" method="POST">
                @csrf
                <h3 style="font-size: 1.25rem; margin-bottom: 1.5rem; color: var(--color-primary); border-bottom: 1.5px solid #f1f5f9; padding-bottom: 0.5rem;">
                    <i class="fa-solid fa-user"></i> Langkah 1: Pengisian Biodata Pribadi
                </h3>

                <!-- Group 1: Data Pribadi & Kontak -->
                <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: var(--border-radius-md); padding: 1.5rem; margin-bottom: 1.5rem;">
                    <h4 style="font-size: 0.95rem; text-transform: uppercase; color: var(--color-primary); margin-bottom: 1.25rem; font-weight: 700; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fa-solid fa-address-card"></i> Data Pribadi & Kontak
                    </h4>
                    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.25rem 1.5rem;">
                        <div class="form-group" style="grid-column: span 1; margin-bottom: 0;">
                            <label for="nik" class="form-label">NIK (Nomor Induk Kependudukan) <span style="color: var(--color-danger);">*</span></label>
                            <input type="text" id="nik" name="nik" class="form-control @error('nik') is-invalid @enderror" value="{{ old('nik', $profile->nik) }}" required placeholder="16 digit NIK KTP Anda" maxlength="25">
                            @error('nik') <div class="form-error">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group" style="grid-column: span 1; margin-bottom: 0;">
                            <label for="full_name_with_titles" class="form-label">Nama Lengkap & Gelar <span style="color: var(--color-danger);">*</span></label>
                            <input type="text" id="full_name_with_titles" name="full_name_with_titles" class="form-control @error('full_name_with_titles') is-invalid @enderror" value="{{ old('full_name_with_titles', $profile->full_name_with_titles) }}" required placeholder="Contoh: Dr. Faqih F., M.Pd.">
                            @error('full_name_with_titles') <div class="form-error">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group" style="grid-column: span 1; margin-bottom: 0;">
                            <label for="email" class="form-label">Email <span style="color: var(--color-danger);">*</span></label>
                            <input type="email" id="email" class="form-control" value="{{ Auth::user()->email }}" readonly style="background-color: #f1f5f9; cursor: not-allowed; border-color: #cbd5e1; color: #475569;">
                        </div>

                        <div class="form-group" style="grid-column: span 1; margin-bottom: 0;">
                            <label for="gender" class="form-label">Jenis Kelamin <span style="color: var(--color-danger);">*</span></label>
                            <select id="gender" name="gender" class="form-control form-select @error('gender') is-invalid @enderror" required>
                                <option value="" disabled selected>-- Pilih Jenis Kelamin --</option>
                                <option value="Laki-laki" {{ old('gender', $profile->gender) === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ old('gender', $profile->gender) === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('gender') <div class="form-error">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group" style="grid-column: span 1; margin-bottom: 0;">
                            <label for="date_of_birth" class="form-label">Tanggal Lahir <span style="color: var(--color-danger);">*</span></label>
                            <input type="date" id="date_of_birth" name="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror" value="{{ old('date_of_birth', $profile->date_of_birth) }}" required>
                            @error('date_of_birth') <div class="form-error">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group" style="grid-column: span 1; margin-bottom: 0;">
                            <label for="phone" class="form-label">Nomor HP / WhatsApp <span style="color: var(--color-danger);">*</span></label>
                            <input type="text" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $profile->phone) }}" required placeholder="Contoh: 08123456789">
                            @error('phone') <div class="form-error">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group" style="grid-column: span 2; margin-bottom: 0;">
                            <label for="address" class="form-label">Alamat Lengkap <span style="color: var(--color-danger);">*</span></label>
                            <textarea id="address" name="address" class="form-control @error('address') is-invalid @enderror" required rows="1" style="height: 42px;" placeholder="Alamat domisili">{{ old('address', $profile->address) }}</textarea>
                            @error('address') <div class="form-error">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <!-- Group 2: Nomor Identitas Tambahan -->
                <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: var(--border-radius-md); padding: 1.5rem; margin-bottom: 1.5rem;">
                    <h4 style="font-size: 0.95rem; text-transform: uppercase; color: var(--color-primary); margin-bottom: 1.25rem; font-weight: 700; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fa-solid fa-list-numeric"></i> Nomor Identitas Tambahan
                    </h4>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.25rem 1.5rem;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="nip" class="form-label">NIP (Nomor Induk Pegawai) <span style="color: var(--color-danger);">*</span></label>
                            <span style="font-size: 0.75rem; color: var(--color-text-muted); display: block; margin-top: -0.25rem; margin-bottom: 0.25rem;">(Isi 0 jika tidak memiliki)</span>
                            <input type="text" id="nip" name="nip" class="form-control @error('nip') is-invalid @enderror" value="{{ old('nip', $profile->nip) }}" required placeholder="Masukkan NIP Anda atau 0">
                            @error('nip') <div class="form-error">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="nidn" class="form-label">NIDN (Nomor Induk Dosen) <span style="color: var(--color-danger);">*</span></label>
                            <span style="font-size: 0.75rem; color: var(--color-text-muted); display: block; margin-top: -0.25rem; margin-bottom: 0.25rem;">(Isi 0 jika tidak memiliki)</span>
                            <input type="text" id="nidn" name="nidn" class="form-control @error('nidn') is-invalid @enderror" value="{{ old('nidn', $profile->nidn) }}" required placeholder="Masukkan NIDN Anda atau 0">
                            @error('nidn') <div class="form-error">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="nuptk" class="form-label">NUPTK (Nomor Pendidik) <span style="color: var(--color-danger);">*</span></label>
                            <span style="font-size: 0.75rem; color: var(--color-text-muted); display: block; margin-top: -0.25rem; margin-bottom: 0.25rem;">(Isi 0 jika tidak memiliki)</span>
                            <input type="text" id="nuptk" name="nuptk" class="form-control @error('nuptk') is-invalid @enderror" value="{{ old('nuptk', $profile->nuptk) }}" required placeholder="Masukkan NUPTK Anda atau 0">
                            @error('nuptk') <div class="form-error">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="npwp" class="form-label">NPWP (Nomor Wajib Pajak) <span style="color: var(--color-danger);">*</span></label>
                            <span style="font-size: 0.75rem; color: var(--color-text-muted); display: block; margin-top: -0.25rem; margin-bottom: 0.25rem;">(Isi 0 jika tidak memiliki)</span>
                            <input type="text" id="npwp" name="npwp" class="form-control @error('npwp') is-invalid @enderror" value="{{ old('npwp', $profile->npwp) }}" required placeholder="Masukkan NPWP Anda atau 0">
                            @error('npwp') <div class="form-error">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <!-- Group 3: Informasi Rekening Pembayaran -->
                <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: var(--border-radius-md); padding: 1.5rem; margin-bottom: 1.5rem;">
                    <h4 style="font-size: 0.95rem; text-transform: uppercase; color: var(--color-primary); margin-bottom: 1.25rem; font-weight: 700; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fa-solid fa-credit-card"></i> Informasi Rekening Bank (Untuk Pembayaran)
                    </h4>
                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.25rem 1.5rem;">
                        <div class="form-group" style="grid-column: span 1; margin-bottom: 0;">
                            <label for="bank_name" class="form-label">Nama Bank <span style="color: var(--color-danger);">*</span></label>
                            <select id="bank_name" name="bank_name" class="form-control form-select @error('bank_name') is-invalid @enderror" required>
                                <option value="" disabled selected>-- Pilih Bank --</option>
                                <option value="BRI" {{ old('bank_name', $profile->bank_name) === 'BRI' ? 'selected' : '' }}>Bank Rakyat Indonesia (BRI)</option>
                                <option value="BSI" {{ old('bank_name', $profile->bank_name) === 'BSI' ? 'selected' : '' }}>Bank Syariah Indonesia (BSI)</option>
                            </select>
                            @error('bank_name') <div class="form-error">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group" style="grid-column: span 1; margin-bottom: 0;">
                            <label for="bank_account_number" class="form-label">Nomor Rekening <span style="color: var(--color-danger);">*</span></label>
                            <input type="text" id="bank_account_number" name="bank_account_number" class="form-control @error('bank_account_number') is-invalid @enderror" value="{{ old('bank_account_number', $profile->bank_account_number) }}" required placeholder="Masukkan nomor rekening">
                            @error('bank_account_number') <div class="form-error">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group" style="grid-column: span 1; margin-bottom: 0;">
                            <label for="bank_account_name" class="form-label">Nama Pemilik Rekening <span style="color: var(--color-danger);">*</span></label>
                            <input type="text" id="bank_account_name" name="bank_account_name" class="form-control @error('bank_account_name') is-invalid @enderror" value="{{ old('bank_account_name', $profile->bank_account_name) }}" required placeholder="Sesuai nama di buku tabungan">
                            @error('bank_account_name') <div class="form-error">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; margin-top: 2.5rem; gap: 1rem; border-top: 1px solid #f1f5f9; padding-top: 1.5rem;">
                    <button type="submit" class="btn btn-primary">
                        Selanjutnya <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </div>
            </form>

        @elseif($step === 2)
            <!-- STEP 2: DATA INSTANSI -->
            <form action="{{ route('tutor.wizard.step2') }}" method="POST">
                @csrf
                <h3 style="font-size: 1.25rem; margin-bottom: 1.5rem; color: var(--color-primary); border-bottom: 1.5px solid #f1f5f9; padding-bottom: 0.5rem;">
                    <i class="fa-solid fa-building"></i> Langkah 2: Pengisian Data Instansi Asal
                </h3>

                <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: var(--border-radius-md); padding: 2rem; margin-bottom: 1.5rem;">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;">
                        <div class="form-group" style="grid-column: 1 / -1;">
                            <label for="institution_name" class="form-label">Nama Instansi Tempat Bekerja <span style="color: var(--color-danger);">*</span></label>
                            <input type="text" id="institution_name" name="institution_name" class="form-control @error('institution_name') is-invalid @enderror" value="{{ old('institution_name', $profile->institution_name) }}" required placeholder="Contoh: Universitas Islam Negeri Siber Cirebon, SMA N 1 Cirebon">
                            @error('institution_name') <div class="form-error">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label for="employment_status" class="form-label">Status Kepegawaian <span style="color: var(--color-danger);">*</span></label>
                            <select id="employment_status" name="employment_status" class="form-control form-select @error('employment_status') is-invalid @enderror" required>
                                <option value="" disabled selected>-- Pilih Status Pekerjaan --</option>
                                <option value="ASN" {{ old('employment_status', $profile->employment_status) === 'ASN' ? 'selected' : '' }}>Aparatur Sipil Negara (ASN)</option>
                                <option value="Non ASN" {{ old('employment_status', $profile->employment_status) === 'Non ASN' ? 'selected' : '' }}>Non ASN / Swasta</option>
                            </select>
                            @error('employment_status') <div class="form-error">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label for="rank_group" class="form-label">Golongan / Pangkat <span style="color: var(--color-danger);">*</span></label>
                            <select id="rank_group" name="rank_group" class="form-control form-select @error('rank_group') is-invalid @enderror" required>
                                <option value="" disabled selected>-- Pilih Pangkat/Golongan --</option>
                                <option value="III" {{ old('rank_group', $profile->rank_group) === 'III' ? 'selected' : '' }}>Golongan III (Penata)</option>
                                <option value="IV" {{ old('rank_group', $profile->rank_group) === 'IV' ? 'selected' : '' }}>Golongan IV (Pembina)</option>
                                <option value="PPPK Gol X" {{ old('rank_group', $profile->rank_group) === 'PPPK Gol X' ? 'selected' : '' }}>PPPK Gol X</option>
                                <option value="PPPK Gol XI" {{ old('rank_group', $profile->rank_group) === 'PPPK Gol XI' ? 'selected' : '' }}>PPPK Gol XI</option>
                                <option value="PPPK Gol XIII" {{ old('rank_group', $profile->rank_group) === 'PPPK Gol XIII' ? 'selected' : '' }}>PPPK Gol XIII</option>
                                <option value="PPPK Gol XVI" {{ old('rank_group', $profile->rank_group) === 'PPPK Gol XVI' ? 'selected' : '' }}>PPPK Gol XVI</option>
                                <option value="Non ASN" {{ old('rank_group', $profile->rank_group) === 'Non ASN' ? 'selected' : '' }}>Non ASN / Tanpa Golongan</option>
                            </select>
                            @error('rank_group') <div class="form-error">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label for="work_field" class="form-label">Profesi / Bidang Kerja <span style="color: var(--color-danger);">*</span></label>
                            <input type="text" id="work_field" name="work_field" class="form-control @error('work_field') is-invalid @enderror" value="{{ old('work_field', $profile->work_field) }}" required placeholder="Contoh: Dosen, Guru, Praktisi Teknologi">
                            @error('work_field') <div class="form-error">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label for="work_duration" class="form-label">Masa Kerja <span style="color: var(--color-danger);">*</span></label>
                            <input type="text" id="work_duration" name="work_duration" class="form-control @error('work_duration') is-invalid @enderror" value="{{ old('work_duration', $profile->work_duration) }}" required placeholder="Contoh: 3 Tahun 5 Bulan">
                            @error('work_duration') <div class="form-error">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <div style="display: flex; justify-content: space-between; margin-top: 2.5rem; gap: 1rem; border-top: 1px solid #f1f5f9; padding-top: 1.5rem;">
                    <a href="{{ route('tutor.wizard', ['step' => 1]) }}" class="btn btn-outline">
                        <i class="fa-solid fa-arrow-left"></i> Sebelumnya
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Selanjutnya <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </div>
            </form>

        @elseif($step === 3)
            <!-- STEP 3: PENDIDIKAN -->
            <form action="{{ route('tutor.wizard.step3') }}" method="POST">
                @csrf
                <h3 style="font-size: 1.25rem; margin-bottom: 1.5rem; color: var(--color-primary); border-bottom: 1.5px solid #f1f5f9; padding-bottom: 0.5rem;">
                    <i class="fa-solid fa-graduation-cap"></i> Langkah 3: Kelengkapan Riwayat Pendidikan
                </h3>

                <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: var(--border-radius-md); padding: 2rem; margin-bottom: 1.5rem;">
                    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem;">
                        <div class="form-group" style="grid-column: span 1; margin-bottom: 0;">
                            <label for="last_education" class="form-label">Jenjang Pendidikan Terakhir <span style="color: var(--color-danger);">*</span></label>
                            <select id="last_education" name="last_education" class="form-control form-select @error('last_education') is-invalid @enderror" required>
                                <option value="" disabled selected>-- Pilih Pendidikan Terakhir --</option>
                                <option value="S2" {{ old('last_education', $profile->last_education) === 'S2' ? 'selected' : '' }}>Magister (S2)</option>
                                <option value="S3" {{ old('last_education', $profile->last_education) === 'S3' ? 'selected' : '' }}>Doktor (S3)</option>
                            </select>
                            @error('last_education') <div class="form-error">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group" style="grid-column: span 2; margin-bottom: 0;">
                            <label for="university_name" class="form-label">Nama Universitas / PTN / PTS <span style="color: var(--color-danger);">*</span></label>
                            <input type="text" id="university_name" name="university_name" class="form-control @error('university_name') is-invalid @enderror" value="{{ old('university_name', $profile->university_name) }}" required placeholder="Nama lengkap universitas">
                            @error('university_name') <div class="form-error">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group" style="grid-column: span 1; margin-bottom: 0;">
                            <label for="field_of_study" class="form-label">Program Studi / Bidang Studi <span style="color: var(--color-danger);">*</span></label>
                            <input type="text" id="field_of_study" name="field_of_study" class="form-control @error('field_of_study') is-invalid @enderror" value="{{ old('field_of_study', $profile->field_of_study) }}" required placeholder="Bidang studi">
                            @error('field_of_study') <div class="form-error">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group" style="grid-column: span 1; margin-bottom: 0;">
                            <label for="graduation_year" class="form-label">Tahun Kelulusan <span style="color: var(--color-danger);">*</span></label>
                            <input type="text" id="graduation_year" name="graduation_year" class="form-control @error('graduation_year') is-invalid @enderror" value="{{ old('graduation_year', $profile->graduation_year) }}" required placeholder="Tahun lulus" maxlength="4">
                            @error('graduation_year') <div class="form-error">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group" style="grid-column: span 1; margin-bottom: 0;">
                            <label for="title_front" class="form-label">Gelar Depan (Opsional)</label>
                            <input type="text" id="title_front" name="title_front" class="form-control @error('title_front') is-invalid @enderror" value="{{ old('title_front', $profile->title_front) }}" placeholder="Contoh: Dr.">
                            @error('title_front') <div class="form-error">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group" style="grid-column: span 1; margin-bottom: 0;">
                            <label for="title_back" class="form-label">Gelar Belakang (Opsional)</label>
                            <input type="text" id="title_back" name="title_back" class="form-control @error('title_back') is-invalid @enderror" value="{{ old('title_back', $profile->title_back) }}" placeholder="Contoh: M.Pd.">
                            @error('title_back') <div class="form-error">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group" style="grid-column: span 1; margin-bottom: 0;">
                            <label for="field_of_expertise" class="form-label">Keahlian Spesifik <span style="color: var(--color-danger);">*</span></label>
                            <input type="text" id="field_of_expertise" name="field_of_expertise" class="form-control @error('field_of_expertise') is-invalid @enderror" value="{{ old('field_of_expertise', $profile->field_of_expertise) }}" required placeholder="Kompetensi utama">
                            @error('field_of_expertise') <div class="form-error">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <div style="display: flex; justify-content: space-between; margin-top: 2.5rem; gap: 1rem; border-top: 1px solid #f1f5f9; padding-top: 1.5rem;">
                    <a href="{{ route('tutor.wizard', ['step' => 2]) }}" class="btn btn-outline">
                        <i class="fa-solid fa-arrow-left"></i> Sebelumnya
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Selanjutnya <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </div>
            </form>

        @elseif($step === 4)
            <!-- STEP 4: PILIH MATA KULIAH -->
            @php
                $selectedCourse = $profile->courses->first();
                $selectedCourseId = $selectedCourse ? $selectedCourse->id : null;
                $selectedProgramId = $selectedCourse ? $selectedCourse->study_program_id : null;
                $selectedFacultyId = null;
                if ($selectedCourse && $selectedCourse->studyProgram) {
                    $selectedFacultyId = $selectedCourse->studyProgram->faculty_id;
                }
            @endphp

            <form action="{{ route('tutor.wizard.step4') }}" method="POST">
                @csrf
                <h3 style="font-size: 1.25rem; margin-bottom: 1.5rem; color: var(--color-primary); border-bottom: 1.5px solid #f1f5f9; padding-bottom: 0.5rem;">
                    <i class="fa-solid fa-book-open"></i> Langkah 4: Pemilihan Mata Kuliah
                </h3>

                <p style="font-size: 0.9rem; color: var(--color-text-muted); margin-bottom: 1.5rem;">
                    Silakan pilih **satu (1)** mata kuliah yang ditawarkan di bawah ini yang sesuai dengan bidang keahlian utama Anda.
                </p>

                <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: var(--border-radius-md); padding: 1.5rem; margin-bottom: 1.5rem;">
                    <h4 style="font-size: 0.95rem; text-transform: uppercase; color: var(--color-primary); margin-bottom: 1.25rem; font-weight: 700; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fa-solid fa-book-open"></i> Pilihan Mata Kuliah
                    </h4>
                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.25rem 1.5rem;">
                        <div class="form-group" style="grid-column: span 1; margin-bottom: 0;">
                            <label for="faculty_id" class="form-label">Fakultas Sasaran <span style="color: var(--color-danger);">*</span></label>
                            <select id="faculty_id" name="faculty_id" class="form-control form-select" required>
                                <option value="" disabled selected>-- Pilih Fakultas --</option>
                                @foreach($faculties as $faculty)
                                    <option value="{{ $faculty->id }}">{{ $faculty->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group" style="grid-column: span 1; margin-bottom: 0;">
                            <label for="study_program_id" class="form-label">Program Studi <span style="color: var(--color-danger);">*</span></label>
                            <select id="study_program_id" name="study_program_id" class="form-control form-select" required disabled>
                                <option value="" disabled selected>-- Pilih Program Studi --</option>
                            </select>
                        </div>

                        <div class="form-group" style="grid-column: span 1; margin-bottom: 0;">
                            <label for="course_id" class="form-label">Mata Kuliah Yang Diajar <span style="color: var(--color-danger);">*</span></label>
                            <select id="course_id" name="course_id" class="form-control form-select @error('course_id') is-invalid @enderror" required disabled>
                                <option value="" disabled selected>-- Pilih Mata Kuliah --</option>
                            </select>
                            @error('course_id') <div class="form-error">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                @if($selectedCourse)
                    <div style="margin-bottom: 1.5rem; border: 1px solid #d1fae5; border-radius: var(--border-radius-md); padding: 1rem; background-color: var(--color-primary-light); display: flex; align-items: center; gap: 0.75rem;">
                        <div style="font-size: 1.25rem; color: var(--color-primary);">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                        <div style="font-size: 0.85rem;">
                            <strong>Mata Kuliah Terpilih Saat Ini:</strong> {{ $selectedCourse->code }} - {{ $selectedCourse->name }} ({{ $selectedCourse->credits }} SKS)
                        </div>
                    </div>
                @endif

                <div style="display: flex; justify-content: space-between; margin-top: 2.5rem; gap: 1rem; border-top: 1px solid #f1f5f9; padding-top: 1.5rem;">
                    <a href="{{ route('tutor.wizard', ['step' => 3]) }}" class="btn btn-outline">
                        <i class="fa-solid fa-arrow-left"></i> Sebelumnya
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Selanjutnya <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </div>
            </form>

        @elseif($step === 5)
            <!-- STEP 5: UNGGAH DOKUMEN -->
            <form action="{{ route('tutor.wizard.step5') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <h3 style="font-size: 1.25rem; margin-bottom: 1.5rem; color: var(--color-primary); border-bottom: 1.5px solid #f1f5f9; padding-bottom: 0.5rem;">
                    <i class="fa-solid fa-folder-open"></i> Langkah 5: Unggah Berkas Persyaratan
                </h3>

                <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 1.5rem;">
                    Silakan unggah dokumen pendukung pendaftaran di bawah ini. Format file yang diterima: <strong>PDF, JPG, JPEG, atau PNG</strong> dengan ukuran maksimal <strong>2MB</strong> per berkas.
                </p>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
                    @php
                        $fileInputs = [
                            'file_ktp' => ['label' => 'Kartu Tanda Penduduk (KTP)', 'required' => true],
                            'file_npwp' => ['label' => 'NPWP (Opsional jika belum punya)', 'required' => false],
                            'file_buku_tabungan' => ['label' => 'Halaman Depan Buku Tabungan', 'required' => true],
                            'file_ijazah' => ['label' => 'Ijazah Terakhir (S2/S3)', 'required' => true],
                            'file_transkrip' => ['label' => 'Transkrip Nilai', 'required' => true],
                            'file_cv' => ['label' => 'Curriculum Vitae Terkini', 'required' => true],
                            'file_surat_kesediaan' => ['label' => 'Surat Kesediaan Mengajar UPT PJJ', 'required' => true],
                            'file_sertifikat_pjj' => ['label' => 'Sertifikat Mengelola PJJ', 'required' => true]
                        ];
                    @endphp

                    @foreach($fileInputs as $field => $inputInfo)
                        @php
                            $existingFile = $profile->{$field};
                        @endphp
                        <div class="form-group" style="border: 1.5px solid #e2e8f0; border-radius: var(--border-radius-md); padding: 1.25rem; background-color: #f8fafc; display: flex; flex-direction: column; justify-content: space-between; min-height: 190px;">
                            <label class="form-label" style="font-weight: 600; display: flex; justify-content: space-between; margin-bottom: 0.75rem;">
                                <span style="font-size: 0.85rem; line-height: 1.2;">
                                    {{ $inputInfo['label'] }}
                                    @if($inputInfo['required'] && !$existingFile)
                                        <span style="color: var(--color-danger);">*</span>
                                    @endif
                                </span>
                                @if($existingFile)
                                    <span style="color: var(--color-success); font-size: 0.8rem; font-weight: 600; white-space: nowrap;"><i class="fa-solid fa-circle-check"></i> Unggah Ok</span>
                                @endif
                            </label>

                            <div class="file-upload-box" id="box-{{ $field }}" style="padding: 1rem 0.5rem; flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 100px;">
                                <i class="fa-solid fa-cloud-arrow-up file-upload-icon" style="font-size: 1.5rem; margin-bottom: 0.25rem;"></i>
                                <div style="font-size: 0.75rem; color: var(--color-text-muted);">
                                    Klik atau seret file ke sini
                                </div>
                                <input type="file" name="{{ $field }}" id="{{ $field }}" class="file-upload-input" onchange="updateFileLabel('{{ $field }}')" {{ $inputInfo['required'] && !$existingFile ? 'required' : '' }}>
                                <div class="file-selected-name" id="label-{{ $field }}" style="font-size: 0.75rem; word-break: break-all; margin-top: 0.25rem; max-height: 32px; overflow: hidden;"></div>
                            </div>

                            @if($existingFile)
                                <div style="margin-top: 0.5rem; text-align: left;">
                                    <a href="{{ Storage::url($existingFile) }}" target="_blank" style="font-size: 0.75rem; display: inline-flex; align-items: center; gap: 0.25rem; font-weight: 600;">
                                        <i class="fa-solid fa-eye"></i> Lihat Dokumen Saat Ini
                                    </a>
                                </div>
                            @endif
                            
                            @error($field)
                                <div class="form-error" style="font-size: 0.8rem;">{{ $message }}</div>
                            @enderror
                        </div>
                    @endforeach
                </div>

                <div style="display: flex; justify-content: space-between; margin-top: 2.5rem; gap: 1rem; border-top: 1px solid #f1f5f9; padding-top: 1.5rem;">
                    <a href="{{ route('tutor.wizard', ['step' => 4]) }}" class="btn btn-outline">
                        <i class="fa-solid fa-arrow-left"></i> Sebelumnya
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Simpan & Selesai <i class="fa-solid fa-circle-check"></i>
                    </button>
                </div>
            </form>
        @endif
    </div>
@endsection

@section('scripts')
    <script>
        function updateFileLabel(fieldId) {
            const input = document.getElementById(fieldId);
            const label = document.getElementById('label-' + fieldId);
            const box = document.getElementById('box-' + fieldId);
            
            if (input.files && input.files.length > 0) {
                const fileName = input.files[0].name;
                label.innerText = fileName;
                box.style.borderColor = 'var(--color-primary)';
                box.style.backgroundColor = 'var(--color-primary-light)';
            } else {
                label.innerText = '';
                box.style.borderColor = '#cbd5e1';
                box.style.backgroundColor = '#f8fafc';
            }
        }
    </script>

    @if($step === 4)
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
                            opt.value = course.id;
                            opt.textContent = `${course.code} - ${course.name} (${course.credits} SKS)`;
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
    @endif
@endsection
