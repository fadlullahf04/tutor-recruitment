@extends('layouts.app')

@section('title', 'Tinjau Calon Tutor')
@section('header_title', 'Tinjau Berkas Pendaftar')
@section('header_subtitle', 'Periksa seluruh kesesuaian biodata diri, riwayat pendidikan, dan berkas persyaratan.')

@section('content')
    <div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <a href="{{ route('admin.tutors.index') }}" class="btn btn-outline btn-sm">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar
        </a>
        @if(Auth::user()->isSuperAdmin())
            <div style="display: flex; gap: 0.75rem;">
                <a href="{{ route('admin.tutors.edit', $tutor->id) }}" class="btn btn-warning btn-sm" style="color: #1e293b; font-weight: 600;">
                    <i class="fa-solid fa-user-pen"></i> Edit Profil
                </a>
                <form action="{{ route('admin.tutors.destroy', $tutor->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" style="font-weight: 600;" onclick="return confirm('Apakah Anda yakin ingin menghapus calon tutor ini secara permanen dari sistem?')">
                        <i class="fa-solid fa-trash-can"></i> Hapus Tutor
                    </button>
                </form>
            </div>
        @endif
    </div>

    <div class="card" style="border-radius: var(--border-radius-lg); overflow: hidden; margin-bottom: 2rem;">
        <!-- Banner Status -->
        @if($tutor->status === 'Pending')
            <div style="background: linear-gradient(135deg, #d97706 0%, #b45309 100%); color: white; padding: 1.5rem 2rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                <div>
                    <span class="badge" style="background-color: rgba(255,255,255,0.2); color: white; font-weight: 600; margin-bottom: 0.25rem;">MENUNGGU REVIEW</span>
                    <h2 style="color: white; font-size: 1.35rem; font-weight: 700;">Menunggu Keputusan Verifikasi</h2>
                </div>
                <div style="display: flex; gap: 0.75rem;">
                    <button type="button" class="btn" style="background-color: white; color: var(--color-primary); font-weight: 600;" onclick="confirmApprove()">
                        <i class="fa-solid fa-check"></i> Setujui Pendaftaran
                    </button>
                    <button type="button" class="btn btn-danger" style="font-weight: 600;" onclick="openRejectionModal()">
                        <i class="fa-solid fa-xmark"></i> Tolak / Minta Revisi
                    </button>
                </div>
            </div>
        @elseif($tutor->status === 'Approved')
            <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 1.5rem 2rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                <div>
                    <span class="badge" style="background-color: rgba(255,255,255,0.2); color: white; font-weight: 600; margin-bottom: 0.25rem;">DISETUJUI</span>
                    <h2 style="color: white; font-size: 1.35rem; font-weight: 700;"><i class="fa-solid fa-circle-check"></i> Pendaftaran Telah Disetujui</h2>
                </div>
                <div>
                    <button type="button" class="btn btn-danger" style="font-weight: 600;" onclick="openRejectionModal()">
                        <i class="fa-solid fa-xmark"></i> Tolak / Minta Revisi
                    </button>
                </div>
            </div>
        @elseif($tutor->status === 'Rejected')
            <div style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; padding: 1.5rem 2rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                <div>
                    <span class="badge" style="background-color: rgba(255,255,255,0.2); color: white; font-weight: 600; margin-bottom: 0.25rem;">DITOLAK / REVISI</span>
                    <h2 style="color: white; font-size: 1.35rem; font-weight: 700;"><i class="fa-solid fa-circle-xmark"></i> Pendaftaran Ditolak / Meminta Revisi</h2>
                </div>
                <div>
                    <button type="button" class="btn" style="background-color: white; color: var(--color-primary); font-weight: 600;" onclick="confirmApprove()">
                        <i class="fa-solid fa-check"></i> Setujui Pendaftaran
                    </button>
                </div>
            </div>
        @elseif($tutor->status === 'Draft')
            <div style="background: linear-gradient(135deg, #64748b 0%, #475569 100%); color: white; padding: 1.5rem 2rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                <div>
                    <span class="badge" style="background-color: rgba(255,255,255,0.2); color: white; font-weight: 600; margin-bottom: 0.25rem;">DRAFT</span>
                    <h2 style="color: white; font-size: 1.35rem; font-weight: 700;"><i class="fa-solid fa-file-signature"></i> Pendaftaran Belum Lengkap (Draft)</h2>
                </div>
                <div style="display: flex; gap: 0.75rem;">
                    <button type="button" class="btn" style="background-color: white; color: var(--color-primary); font-weight: 600;" onclick="confirmApprove()">
                        <i class="fa-solid fa-check"></i> Setujui Pendaftaran
                    </button>
                    <button type="button" class="btn btn-danger" style="font-weight: 600;" onclick="openRejectionModal()">
                        <i class="fa-solid fa-xmark"></i> Tolak / Minta Revisi
                    </button>
                </div>
            </div>
        @endif

        <div class="card-body" style="padding: 2.5rem;">
            @if($tutor->status === 'Rejected' && $tutor->rejection_reason)
                <div class="alert alert-danger" style="margin-bottom: 2rem; display: block; border-left: 5px solid #b91c1c;">
                    <strong><i class="fa-solid fa-comment-dots"></i> Alasan Penolakan:</strong>
                    <p style="margin-top: 0.25rem;">{{ $tutor->rejection_reason }}</p>
                </div>
            @endif

            <!-- Info Utama -->
            <div style="display: flex; gap: 1.5rem; margin-bottom: 2.5rem; background-color: #f8fafc; padding: 1.25rem; border-radius: var(--border-radius-md); border: 1px solid #e2e8f0; align-items: center; justify-content: space-between;">
                <div>
                    <div style="font-size: 0.8rem; color: var(--color-text-muted); text-transform: uppercase; font-weight: 600;">No. Registrasi</div>
                    <div style="font-size: 1.25rem; font-weight: 700; font-family: monospace; color: #0f172a;">{{ $tutor->registration_number ?: '-' }}</div>
                </div>
                <div>
                    <div style="font-size: 0.8rem; color: var(--color-text-muted); text-transform: uppercase; font-weight: 600;">Periode Rekrutmen</div>
                    <div style="font-size: 1rem; font-weight: 600; color: #0f172a;">{{ $tutor->period ? $tutor->period->name : '-' }}</div>
                </div>
                <div>
                    <div style="font-size: 0.8rem; color: var(--color-text-muted); text-transform: uppercase; font-weight: 600;">Tanggal Submit</div>
                    <div style="font-size: 1rem; font-weight: 600; color: #0f172a;">{{ $tutor->updated_at->translatedFormat('d F Y H:i') }} WIB</div>
                </div>
            </div>

            <!-- Detail Grid -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 2.5rem;">
                <!-- Biodata -->
                <div>
                    <h3 style="font-size: 1.15rem; margin-bottom: 1.25rem; border-bottom: 2px solid #f1f5f9; padding-bottom: 0.5rem; color: #1e293b;">
                        <i class="fa-solid fa-user-check"></i> 1. Informasi Biodata
                    </h3>
                    <table style="width: 100%; font-size: 0.95rem;" class="summary-table">
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.65rem 0; color: var(--color-text-muted); width: 140px;">NIK</td>
                            <td style="padding: 0.65rem 0; font-weight: 600;">{{ $tutor->nik ?: '-' }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.65rem 0; color: var(--color-text-muted);">Nama & Gelar</td>
                            <td style="padding: 0.65rem 0; font-weight: 600;">{{ $tutor->full_name_with_titles ?: '-' }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.65rem 0; color: var(--color-text-muted);">Email</td>
                            <td style="padding: 0.65rem 0; font-weight: 600;">{{ $tutor->user ? $tutor->user->email : '-' }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.65rem 0; color: var(--color-text-muted);">Jenis Kelamin</td>
                            <td style="padding: 0.65rem 0; font-weight: 600;">{{ $tutor->gender ?: '-' }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.65rem 0; color: var(--color-text-muted);">Tanggal Lahir</td>
                            <td style="padding: 0.65rem 0; font-weight: 600;">{{ $tutor->date_of_birth ? \Carbon\Carbon::parse($tutor->date_of_birth)->translatedFormat('d F Y') : '-' }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.65rem 0; color: var(--color-text-muted);">No HP / WA</td>
                            <td style="padding: 0.65rem 0; font-weight: 600;">{{ $tutor->phone ?: '-' }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.65rem 0; color: var(--color-text-muted);">Alamat</td>
                            <td style="padding: 0.65rem 0; font-weight: 600;">{{ $tutor->address ?: '-' }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.65rem 0; color: var(--color-text-muted);">NIP</td>
                            <td style="padding: 0.65rem 0; font-weight: 600;">{{ $tutor->nip ?: '-' }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.65rem 0; color: var(--color-text-muted);">NIDN</td>
                            <td style="padding: 0.65rem 0; font-weight: 600;">{{ $tutor->nidn ?: '-' }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.65rem 0; color: var(--color-text-muted);">NUPTK</td>
                            <td style="padding: 0.65rem 0; font-weight: 600;">{{ $tutor->nuptk ?: '-' }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.65rem 0; color: var(--color-text-muted);">NPWP</td>
                            <td style="padding: 0.65rem 0; font-weight: 600;">{{ $tutor->npwp ?: '-' }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.65rem 0; color: var(--color-text-muted);">Bank Penerima</td>
                            <td style="padding: 0.65rem 0; font-weight: 600;">{{ $tutor->bank_name ?: '-' }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.65rem 0; color: var(--color-text-muted);">No. Rekening</td>
                            <td style="padding: 0.65rem 0; font-weight: 600;">{{ $tutor->bank_account_number ?: '-' }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.65rem 0; color: var(--color-text-muted);">Pemilik Rekening</td>
                            <td style="padding: 0.65rem 0; font-weight: 600;">{{ $tutor->bank_account_name ?: '-' }}</td>
                        </tr>
                    </table>

                    <h3 style="font-size: 1.15rem; margin-top: 2.5rem; margin-bottom: 1.25rem; border-bottom: 2px solid #f1f5f9; padding-bottom: 0.5rem; color: #1e293b;">
                        <i class="fa-solid fa-building"></i> 3. Data Instansi
                    </h3>
                    <table style="width: 100%; font-size: 0.95rem;" class="summary-table">
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.65rem 0; color: var(--color-text-muted); width: 140px;">Nama Instansi</td>
                            <td style="padding: 0.65rem 0; font-weight: 600;">{{ $tutor->institution_name ?: '-' }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.65rem 0; color: var(--color-text-muted);">Status Pekerjaan</td>
                            <td style="padding: 0.65rem 0; font-weight: 600;">{{ $tutor->employment_status ?: '-' }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.65rem 0; color: var(--color-text-muted);">Masa Kerja</td>
                            <td style="padding: 0.65rem 0; font-weight: 600;">{{ $tutor->work_duration ?: '-' }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.65rem 0; color: var(--color-text-muted);">Bidang Pekerjaan</td>
                            <td style="padding: 0.65rem 0; font-weight: 600;">{{ $tutor->work_field ?: '-' }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.65rem 0; color: var(--color-text-muted);">Pangkat / Golongan</td>
                            <td style="padding: 0.65rem 0; font-weight: 600;">{{ $tutor->rank_group ?: '-' }}</td>
                        </tr>
                    </table>
                </div>

                <!-- Akademik & Berkas -->
                <div>
                    <h3 style="font-size: 1.15rem; margin-bottom: 1.25rem; border-bottom: 2px solid #f1f5f9; padding-bottom: 0.5rem; color: #1e293b;">
                        <i class="fa-solid fa-graduation-cap"></i> 2. Riwayat Akademik
                    </h3>
                    <table style="width: 100%; font-size: 0.95rem; margin-bottom: 2.5rem;" class="summary-table">
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.65rem 0; color: var(--color-text-muted); width: 140px;">Pendidikan Terakhir</td>
                            <td style="padding: 0.65rem 0; font-weight: 600;">{{ $tutor->last_education ?: '-' }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.65rem 0; color: var(--color-text-muted);">Universitas</td>
                            <td style="padding: 0.65rem 0; font-weight: 600;">{{ $tutor->university_name ?: '-' }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.65rem 0; color: var(--color-text-muted);">Bidang Studi</td>
                            <td style="padding: 0.65rem 0; font-weight: 600;">{{ $tutor->field_of_study ?: '-' }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.65rem 0; color: var(--color-text-muted);">Tahun Lulus</td>
                            <td style="padding: 0.65rem 0; font-weight: 600;">{{ $tutor->graduation_year ?: '-' }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.65rem 0; color: var(--color-text-muted);">Gelar Depan/Belakang</td>
                            <td style="padding: 0.65rem 0; font-weight: 600;">
                                {{ $tutor->title_front ?: '-' }} / {{ $tutor->title_back ?: '-' }}
                            </td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.65rem 0; color: var(--color-text-muted);">Bidang Keahlian</td>
                            <td style="padding: 0.65rem 0; font-weight: 600;">{{ $tutor->field_of_expertise ?: '-' }}</td>
                        </tr>
                    </table>

                    <h3 style="font-size: 1.15rem; margin-bottom: 1.25rem; border-bottom: 2px solid #f1f5f9; padding-bottom: 0.5rem; color: #1e293b;">
                        <i class="fa-solid fa-folder-open"></i> 4. Berkas Pendukung (Click to View)
                    </h3>
                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
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
                            @php $file = $tutor->{$info['field']}; @endphp
                            <div style="border: 1px solid #e2e8f0; border-radius: var(--border-radius-md); padding: 0.75rem 1rem; display: flex; align-items: center; justify-content: space-between; background-color: #f8fafc;">
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <i class="fa-solid {{ $info['icon'] }}" style="color: {{ $file ? 'var(--color-primary)' : 'var(--color-text-muted)' }}; font-size: 1.2rem;"></i>
                                    <span style="font-weight: 500; font-size: 0.9rem;">{{ $label }}</span>
                                </div>
                                @if($file)
                                    <a href="{{ Storage::url($file) }}" target="_blank" class="btn btn-outline btn-sm" style="padding: 0.25rem 0.75rem; font-size: 0.75rem;">
                                        Lihat <i class="fa-solid fa-external-link" style="font-size: 0.65rem;"></i>
                                    </a>
                                @else
                                    <span style="font-size: 0.75rem; color: var(--color-danger); font-weight: 600;">Belum Diunggah</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Selected Courses Section -->
            <div style="margin-top: 2.5rem; border-top: 2px solid #f1f5f9; padding-top: 2rem;">
                <h3 style="font-size: 1.15rem; margin-bottom: 1.25rem; color: #1e293b;">
                    <i class="fa-solid fa-book-open-reader"></i> 5. Pilihan Mata Kuliah Yang Ingin Diajar
                </h3>
                @php
                    $selectedCourses = $tutor->courses;
                @endphp
                @if($selectedCourses->isEmpty())
                    <div style="padding: 1.5rem; background-color: #f8fafc; border: 1.5px dashed #cbd5e1; border-radius: var(--border-radius-md); text-align: center; color: var(--color-text-muted);">
                        <i class="fa-solid fa-book-open" style="font-size: 2rem; display: block; margin-bottom: 0.5rem;"></i>
                        Calon tutor belum memilih mata kuliah yang ditawarkan.
                    </div>
                @else
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1rem;">
                        @foreach($selectedCourses as $course)
                            <div style="border: 1px solid #d1fae5; border-radius: var(--border-radius-md); padding: 1rem; background-color: var(--color-primary-light); display: flex; align-items: flex-start; gap: 0.75rem;">
                                <div style="font-size: 1.25rem; color: var(--color-primary); margin-top: 0.1rem;">
                                    <i class="fa-solid fa-check-double"></i>
                                </div>
                                <div>
                                    <span style="display: block; font-weight: 700; font-family: monospace; font-size: 0.8rem; color: var(--color-primary);">{{ $course->code }}</span>
                                    <span style="display: block; font-size: 0.9rem; font-weight: 600; color: #1e293b; line-height: 1.3;">{{ $course->name }}</span>
                                    <span style="display: block; font-size: 0.75rem; color: var(--color-text-muted); margin-top: 0.25rem;">
                                        SKS: {{ $course->credits }} | {{ $course->studyProgram ? $course->studyProgram->name : '' }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Forms for Approve / Reject (hidden by default unless triggered) -->
    <form id="approve-form" action="{{ route('admin.tutors.approve', $tutor->id) }}" method="POST" style="display: none;">
        @csrf
    </form>

    <!-- Modal Rejection -->
    <div class="modal-overlay" id="rejection-modal">
        <div class="modal-container">
            <div class="card-header" style="background-color: var(--color-bg-primary);">
                <h3 class="card-title" style="color: var(--color-danger);"><i class="fa-solid fa-circle-exclamation"></i> Tolak & Minta Revisi Berkas</h3>
                <button type="button" class="btn btn-outline btn-sm" style="padding: 0.25rem 0.5rem;" onclick="closeRejectionModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form action="{{ route('admin.tutors.reject', $tutor->id) }}" method="POST">
                @csrf
                <div class="card-body">
                    <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 1rem;">
                        Tentukan alasan penolakan berkas ini. Calon tutor akan dapat membaca catatan ini dan melakukan pengisian ulang (revisi) pada data pendaftaran mereka.
                    </p>
                    <div class="form-group">
                        <label for="rejection_reason" class="form-label">Alasan Penolakan / Feedback Revisi <span style="color: var(--color-danger);">*</span></label>
                        <textarea id="rejection_reason" name="rejection_reason" class="form-control" rows="4" placeholder="Contoh: Dokumen KTP terpotong/buram, silakan upload ulang dengan foto yang lebih jelas." required></textarea>
                    </div>
                </div>
                <div class="card-footer" style="padding: 1rem 1.5rem; display: flex; justify-content: flex-end; gap: 0.75rem; border-top: 1px solid #e2e8f0; background-color: var(--color-bg-primary);">
                    <button type="button" class="btn btn-outline" onclick="closeRejectionModal()">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak & Minta Revisi</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function confirmApprove() {
            if (confirm("Apakah Anda yakin menyetujui pendaftaran calon tutor ini? Setelah disetujui, tutor terdaftar akan memiliki status kelulusan.")) {
                document.getElementById('approve-form').submit();
            }
        }
        
        function openRejectionModal() {
            document.getElementById('rejection-modal').style.display = 'flex';
        }
        
        function closeRejectionModal() {
            document.getElementById('rejection-modal').style.display = 'none';
        }
    </script>
@endsection
