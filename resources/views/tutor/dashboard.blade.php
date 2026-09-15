@extends('layouts.app')

@section('title', 'Status Pendaftaran Tutor')
@section('header_title', 'Status Pendaftaran Anda')
@section('header_subtitle', 'Pantau perkembangan verifikasi berkas pendaftaran Anda disini.')

@section('content')
    <div class="card" style="border-radius: var(--border-radius-lg); overflow: hidden;">
        <!-- Status Header Banner -->
        @if($profile->status === 'Draft')
            @php
                $hasSelectedCourse = $profile->courses->isNotEmpty();
                $isWizardCompleted = $profile->completed_step >= 5;
            @endphp
            
            @if(!$isWizardCompleted)
                <div style="background: linear-gradient(135deg, #64748b 0%, #475569 100%); color: white; padding: 2rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                    <div>
                        <span class="badge badge-draft" style="background-color: rgba(255,255,255,0.2); color: white; margin-bottom: 0.5rem;">DRAFT</span>
                        <h2 style="color: white; font-size: 1.5rem; font-weight: 700;">Pendaftaran Belum Lengkap</h2>
                        <p style="opacity: 0.9; font-size: 0.9rem;">Silakan lengkapi biodata, data akademik, dan dokumen persyaratan Anda.</p>
                    </div>
                    <a href="{{ route('tutor.wizard', ['step' => $profile->completed_step + 1]) }}" class="btn" style="background-color: white; color: #1e293b; font-weight: 600;">
                        Lanjutkan Pengisian <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            @else
                @if(!$hasSelectedCourse)
                    <div style="background: linear-gradient(135deg, #e11d48 0%, #be123c 100%); color: white; padding: 2rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                        <div>
                            <span class="badge" style="background-color: rgba(255,255,255,0.2); color: white; margin-bottom: 0.5rem; display: inline-block;">BELUM PILIH MATA KULIAH</span>
                            <h2 style="color: white; font-size: 1.5rem; font-weight: 700;">Pilih Mata Kuliah Terlebih Dahulu</h2>
                            <p style="opacity: 0.9; font-size: 0.9rem;">Anda telah melengkapi berkas, tetapi wajib memilih minimal satu mata kuliah sebelum mengirim pendaftaran.</p>
                        </div>
                        <a href="{{ route('tutor.wizard', ['step' => 4]) }}" class="btn" style="background-color: white; color: #be123c; font-weight: 600;">
                            Pilih Mata Kuliah <i class="fa-solid fa-book-open"></i>
                        </a>
                    </div>
                @else
                    <div style="background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); color: white; padding: 2rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                        <div>
                            <span class="badge" style="background-color: rgba(255,255,255,0.2); color: white; margin-bottom: 0.5rem; display: inline-block;">SIAP DIKIRIM</span>
                            <h2 style="color: white; font-size: 1.5rem; font-weight: 700;">Kirim Pendaftaran Anda</h2>
                            <p style="opacity: 0.9; font-size: 0.9rem;">Semua berkas dan pilihan mata kuliah telah lengkap. Kirim pendaftaran Anda sekarang.</p>
                        </div>
                        <form action="{{ route('tutor.submit') }}" method="POST" style="margin: 0;">
                            @csrf
                            <button type="submit" class="btn" style="background-color: white; color: #1d4ed8; font-weight: 600; border: none; padding: 0.75rem 1.5rem; cursor: pointer; border-radius: var(--border-radius-md);">
                                Kirim Pendaftaran <i class="fa-solid fa-paper-plane"></i>
                            </button>
                        </form>
                    </div>
                @endif
            @endif
        @elseif($profile->status === 'Pending')
            <div style="background: linear-gradient(135deg, #d97706 0%, #b45309 100%); color: white; padding: 2rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                <div>
                    <span class="badge badge-pending" style="background-color: rgba(255,255,255,0.2); color: white; margin-bottom: 0.5rem;">PENDING</span>
                    <h2 style="color: white; font-size: 1.5rem; font-weight: 700;">Menunggu Verifikasi Dokumen</h2>
                    <p style="opacity: 0.9; font-size: 0.9rem;">Berkas Anda telah dikirim dan sedang diperiksa oleh tim admin seleksi UPT PJJ.</p>
                </div>
                <div style="background-color: rgba(255,255,255,0.15); padding: 0.75rem 1.25rem; border-radius: var(--border-radius-md); font-family: monospace; font-size: 1.1rem; border: 1px dashed rgba(255,255,255,0.3);">
                    No: {{ $profile->registration_number }}
                </div>
            </div>
        @elseif($profile->status === 'Approved')
            <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 2rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                <div>
                    <span class="badge badge-approved" style="background-color: rgba(255,255,255,0.2); color: white; margin-bottom: 0.5rem;">APPROVED</span>
                    <h2 style="color: white; font-size: 1.5rem; font-weight: 700;">Selamat! Anda Diterima</h2>
                    <p style="opacity: 0.9; font-size: 0.9rem;">Pendaftaran Anda telah disetujui sebagai Tutor Online UPT PJJ UIN Siber Cirebon.</p>
                </div>
                <div style="background-color: rgba(255,255,255,0.15); padding: 0.75rem 1.25rem; border-radius: var(--border-radius-md); font-family: monospace; font-size: 1.1rem; border: 1px dashed rgba(255,255,255,0.3);">
                    No: {{ $profile->registration_number }}
                </div>
            </div>
        @elseif($profile->status === 'Rejected')
            @php
                $hasSelectedCourse = $profile->courses->isNotEmpty();
            @endphp
            @if(!$hasSelectedCourse)
                <div style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; padding: 2rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                    <div>
                        <span class="badge badge-rejected" style="background-color: rgba(255,255,255,0.2); color: white; margin-bottom: 0.5rem;">REJECTED</span>
                        <h2 style="color: white; font-size: 1.5rem; font-weight: 700;">Pendaftaran Perlu Revisi</h2>
                        <p style="opacity: 0.9; font-size: 0.9rem;">Berkas pendaftaran Anda perlu direvisi. Harap pilih minimal satu mata kuliah sebelum mengirim kembali pendaftaran.</p>
                    </div>
                    <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                        <a href="{{ route('tutor.wizard', ['step' => 1]) }}" class="btn" style="background-color: rgba(255,255,255,0.2); color: white; border: 1px solid white; font-weight: 600;">
                            Revisi Berkas <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <a href="{{ route('tutor.wizard', ['step' => 4]) }}" class="btn" style="background-color: white; color: #dc2626; font-weight: 600;">
                            Pilih Mata Kuliah <i class="fa-solid fa-book-open"></i>
                        </a>
                    </div>
                </div>
            @else
                <div style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; padding: 2rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                    <div>
                        <span class="badge badge-rejected" style="background-color: rgba(255,255,255,0.2); color: white; margin-bottom: 0.5rem;">REJECTED</span>
                        <h2 style="color: white; font-size: 1.5rem; font-weight: 700;">Pendaftaran Perlu Revisi</h2>
                        <p style="opacity: 0.9; font-size: 0.9rem;">Berkas pendaftaran Anda perlu direvisi. Jika revisi sudah selesai, silakan kirim ulang pendaftaran.</p>
                    </div>
                    <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                        <a href="{{ route('tutor.wizard', ['step' => 1]) }}" class="btn" style="background-color: rgba(255,255,255,0.2); color: white; border: 1px solid white; font-weight: 600;">
                            Revisi Berkas <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <form action="{{ route('tutor.submit') }}" method="POST" style="margin: 0;">
                            @csrf
                            <button type="submit" class="btn" style="background-color: white; color: #dc2626; font-weight: 700; border: none; padding: 0.75rem 1.5rem; cursor: pointer; border-radius: var(--border-radius-md);">
                                Kirim Ulang Pendaftaran <i class="fa-solid fa-paper-plane"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        @endif

        <div class="card-body" style="padding: 2rem;">
            @if($profile->status === 'Rejected' && $profile->rejection_reason)
                <div class="alert alert-danger" style="margin-bottom: 2rem; display: block; border-left: 5px solid #b91c1c;">
                    <strong style="display: block; margin-bottom: 0.25rem;"><i class="fa-solid fa-comment-dots"></i> Alasan Penolakan / Catatan Admin:</strong>
                    <p style="font-size: 0.95rem;">{{ $profile->rejection_reason }}</p>
                </div>
            @endif

            <!-- Profile Summary -->
            <h3 style="font-size: 1.2rem; margin-bottom: 1.5rem; border-bottom: 2px solid #f1f5f9; padding-bottom: 0.5rem; color: #334155;">
                <i class="fa-solid fa-clipboard-user"></i> Ringkasan Data Pendaftaran
            </h3>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
                <!-- Section 1: Biodata -->
                <div>
                    <h4 style="font-size: 0.95rem; text-transform: uppercase; color: var(--color-primary); margin-bottom: 1rem; font-weight: 700;">
                        1. Biodata Diri
                    </h4>
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;" class="summary-table">
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.5rem 0; color: var(--color-text-muted); width: 120px;">NIK</td>
                            <td style="padding: 0.5rem 0; font-weight: 600;">{{ $profile->nik ?: '-' }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.5rem 0; color: var(--color-text-muted);">Nama Lengkap</td>
                            <td style="padding: 0.5rem 0; font-weight: 600;">{{ $profile->full_name_with_titles ?: '-' }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.5rem 0; color: var(--color-text-muted);">Email</td>
                            <td style="padding: 0.5rem 0; font-weight: 600;">{{ $profile->user ? $profile->user->email : '-' }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.5rem 0; color: var(--color-text-muted);">Jenis Kelamin</td>
                            <td style="padding: 0.5rem 0; font-weight: 600;">{{ $profile->gender ?: '-' }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.5rem 0; color: var(--color-text-muted);">Tanggal Lahir</td>
                            <td style="padding: 0.5rem 0; font-weight: 600;">{{ $profile->date_of_birth ? \Carbon\Carbon::parse($profile->date_of_birth)->translatedFormat('d F Y') : '-' }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.5rem 0; color: var(--color-text-muted);">No HP</td>
                            <td style="padding: 0.5rem 0; font-weight: 600;">{{ $profile->phone ?: '-' }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.5rem 0; color: var(--color-text-muted);">Alamat</td>
                            <td style="padding: 0.5rem 0; font-weight: 600;">{{ $profile->address ?: '-' }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.5rem 0; color: var(--color-text-muted);">NIP</td>
                            <td style="padding: 0.5rem 0; font-weight: 600;">{{ $profile->nip ?: '-' }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.5rem 0; color: var(--color-text-muted);">NIDN</td>
                            <td style="padding: 0.5rem 0; font-weight: 600;">{{ $profile->nidn ?: '-' }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.5rem 0; color: var(--color-text-muted);">NUPTK</td>
                            <td style="padding: 0.5rem 0; font-weight: 600;">{{ $profile->nuptk ?: '-' }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.5rem 0; color: var(--color-text-muted);">Bank / Rekening</td>
                            <td style="padding: 0.5rem 0; font-weight: 600;">
                                @if($profile->bank_name)
                                    {{ $profile->bank_name }} - {{ $profile->bank_account_number }}<br>
                                    <span style="font-size: 0.8rem; font-weight: normal; color: var(--color-text-muted);">a.n. {{ $profile->bank_account_name }}</span>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>

                <!-- Section 2: Data Instansi -->
                <div>
                    <h4 style="font-size: 0.95rem; text-transform: uppercase; color: var(--color-primary); margin-bottom: 1rem; font-weight: 700;">
                        2. Data Instansi
                    </h4>
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;" class="summary-table">
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.5rem 0; color: var(--color-text-muted); width: 120px;">Nama Instansi</td>
                            <td style="padding: 0.5rem 0; font-weight: 600;">{{ $profile->institution_name ?: '-' }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.5rem 0; color: var(--color-text-muted);">Status Pekerjaan</td>
                            <td style="padding: 0.5rem 0; font-weight: 600;">{{ $profile->employment_status ?: '-' }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.5rem 0; color: var(--color-text-muted);">Masa Kerja</td>
                            <td style="padding: 0.5rem 0; font-weight: 600;">{{ $profile->work_duration ?: '-' }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.5rem 0; color: var(--color-text-muted);">Bidang Pekerjaan</td>
                            <td style="padding: 0.5rem 0; font-weight: 600;">{{ $profile->work_field ?: '-' }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.5rem 0; color: var(--color-text-muted);">Pangkat/Golongan</td>
                            <td style="padding: 0.5rem 0; font-weight: 600;">{{ $profile->rank_group ?: '-' }}</td>
                        </tr>
                    </table>
                </div>

                <!-- Section 3: Riwayat Pendidikan -->
                <div>
                    <h4 style="font-size: 0.95rem; text-transform: uppercase; color: var(--color-primary); margin-bottom: 1rem; font-weight: 700;">
                        3. Riwayat Pendidikan
                    </h4>
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;" class="summary-table">
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.5rem 0; color: var(--color-text-muted); width: 120px;">Universitas</td>
                            <td style="padding: 0.5rem 0; font-weight: 600;">{{ $profile->university_name ?: '-' }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.5rem 0; color: var(--color-text-muted);">Jenjang</td>
                            <td style="padding: 0.5rem 0; font-weight: 600;">{{ $profile->last_education ?: '-' }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.5rem 0; color: var(--color-text-muted);">Bidang Studi</td>
                            <td style="padding: 0.5rem 0; font-weight: 600;">{{ $profile->field_of_study ?: '-' }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.5rem 0; color: var(--color-text-muted);">Tahun Lulus</td>
                            <td style="padding: 0.5rem 0; font-weight: 600;">{{ $profile->graduation_year ?: '-' }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.5rem 0; color: var(--color-text-muted);">Gelar Depan</td>
                            <td style="padding: 0.5rem 0; font-weight: 600;">{{ $profile->title_front ?: '-' }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.5rem 0; color: var(--color-text-muted);">Gelar Belakang</td>
                            <td style="padding: 0.5rem 0; font-weight: 600;">{{ $profile->title_back ?: '-' }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.5rem 0; color: var(--color-text-muted);">Bidang Keahlian</td>
                            <td style="padding: 0.5rem 0; font-weight: 600;">{{ $profile->field_of_expertise ?: '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Section 4: Mata Kuliah Pilihan -->
            <div style="margin-top: 2.5rem; border-top: 2px solid #f1f5f9; padding-top: 2rem;">
                <h4 style="font-size: 0.95rem; text-transform: uppercase; color: var(--color-primary); margin-bottom: 1.25rem; font-weight: 700;">
                    <i class="fa-solid fa-book-bookmark"></i> 4. Pilihan Mata Kuliah Yang Ditawarkan
                </h4>

                @php
                    $selectedCourses = $profile->courses;
                @endphp
                @if($selectedCourses->isEmpty())
                    <div class="alert alert-warning" style="font-size: 0.85rem;">
                        <i class="fa-solid fa-circle-info"></i>
                        <div>Belum ada mata kuliah pilihan yang terdaftar. Silakan pilih melalui menu <a href="{{ route('tutor.wizard', ['step' => 4]) }}" style="font-weight: 600; text-decoration: underline;">Pilih Mata Kuliah</a>.</div>
                    </div>
                @else
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1rem;">
                        @foreach($selectedCourses as $course)
                            <div style="border: 1px solid #d1fae5; border-radius: var(--border-radius-md); padding: 1rem; background-color: var(--color-primary-light); display: flex; align-items: flex-start; gap: 0.75rem;">
                                <div style="font-size: 1.25rem; color: var(--color-primary); margin-top: 0.1rem;">
                                    <i class="fa-solid fa-book-open"></i>
                                </div>
                                <div>
                                    <span style="display: block; font-weight: 700; font-family: monospace; font-size: 0.8rem; color: var(--color-primary);">{{ $course->code }} ({{ $course->idmk }})</span>
                                    <span style="display: block; font-size: 0.9rem; font-weight: 600; color: #1e293b; line-height: 1.3;">{{ $course->name }}</span>
                                    <span style="display: block; font-size: 0.75rem; color: var(--color-text-muted); margin-top: 0.25rem;">
                                        Semester {{ $course->semester }} | SKS: {{ $course->credits }} | {{ $course->studyProgram ? $course->studyProgram->name : '' }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Section 5: Documents Uploaded -->
            <div style="margin-top: 2.5rem; border-top: 2px solid #f1f5f9; padding-top: 2rem;">
                <h4 style="font-size: 0.95rem; text-transform: uppercase; color: var(--color-primary); margin-bottom: 1rem; font-weight: 700;">
                    5. Berkas Dokumen Persyaratan
                </h4>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem;">
                    @php
                        $docs = [
                            'KTP' => ['field' => 'file_ktp', 'icon' => 'fa-id-card'],
                            'NPWP' => ['field' => 'file_npwp', 'icon' => 'fa-file-invoice'],
                            'Buku Tabungan' => ['field' => 'file_buku_tabungan', 'icon' => 'fa-wallet'],
                            'Ijazah Terakhir' => ['field' => 'file_ijazah', 'icon' => 'fa-graduation-cap'],
                            'Transkrip Nilai' => ['field' => 'file_transkrip', 'icon' => 'fa-file-lines'],
                            'CV' => ['field' => 'file_cv', 'icon' => 'fa-file-pdf'],
                            'Surat Kesediaan' => ['field' => 'file_surat_kesediaan', 'icon' => 'fa-file-signature'],
                            'Sertifikat Mengelola PJJ' => ['field' => 'file_sertifikat_pjj', 'icon' => 'fa-certificate']
                        ];
                    @endphp

                    @foreach($docs as $label => $info)
                        @php $file = $profile->{$info['field']}; @endphp
                        <div style="border: 1px solid #e2e8f0; border-radius: var(--border-radius-md); padding: 1rem; display: flex; align-items: center; gap: 0.75rem; background-color: #f8fafc;">
                            <div style="font-size: 1.5rem; color: {{ $file ? 'var(--color-primary)' : 'var(--color-text-muted)' }};">
                                <i class="fa-solid {{ $info['icon'] }}"></i>
                            </div>
                            <div style="overflow: hidden; flex: 1;">
                                <span style="display: block; font-size: 0.85rem; font-weight: 600; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;">{{ $label }}</span>
                                @if($file)
                                    <a href="{{ Storage::url($file) }}" target="_blank" style="font-size: 0.75rem; font-weight: 500; display: inline-flex; align-items: center; gap: 0.25rem;">
                                        Lihat File <i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 0.65rem;"></i>
                                    </a>
                                @else
                                    <span style="font-size: 0.75rem; color: var(--color-danger); font-weight: 500;">Belum Diunggah</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
