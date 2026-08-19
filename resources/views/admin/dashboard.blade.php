@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('header_title', 'Dashboard Analisis')
@section('header_subtitle', 'Tinjau ringkasan statistik rekrutmen tutor online UPT PJJ.')

@section('content')
    <!-- Statistics Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div>
                <div class="stat-value">{{ $stats['total_applicants'] }}</div>
                <div class="stat-label">Total Pendaftar</div>
            </div>
            <div class="stat-icon primary">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>
        
        <div class="stat-card">
            <div>
                <div class="stat-value">{{ $stats['pending_review'] }}</div>
                <div class="stat-label">Menunggu Review</div>
            </div>
            <div class="stat-icon warning">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
        </div>
        
        <div class="stat-card">
            <div>
                <div class="stat-value">{{ $stats['approved_tutors'] }}</div>
                <div class="stat-label">Tutor Disetujui</div>
            </div>
            <div class="stat-icon primary" style="background-color: var(--color-success-light); color: var(--color-success);">
                <i class="fa-solid fa-user-check"></i>
            </div>
        </div>
        
        <div class="stat-card">
            <div>
                <div class="stat-value">{{ $stats['rejected_tutors'] }}</div>
                <div class="stat-label">Pendaftaran Ditolak</div>
            </div>
            <div class="stat-icon danger">
                <i class="fa-solid fa-user-xmark"></i>
            </div>
        </div>
    </div>

    <div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); margin-bottom: 2rem;">
        <div class="stat-card" style="padding: 1.25rem;">
            <div>
                <div class="stat-value" style="font-size: 1.5rem;">{{ $stats['drafts'] }}</div>
                <div class="stat-label" style="font-size: 0.75rem;">Masih Draft</div>
            </div>
            <i class="fa-solid fa-file-pen" style="color: var(--color-text-muted); font-size: 1.25rem;"></i>
        </div>
        <div class="stat-card" style="padding: 1.25rem;">
            <div>
                <div class="stat-value" style="font-size: 1.5rem;">{{ $stats['faculties'] }}</div>
                <div class="stat-label" style="font-size: 0.75rem;">Fakultas</div>
            </div>
            <i class="fa-solid fa-building-columns" style="color: var(--color-text-muted); font-size: 1.25rem;"></i>
        </div>
        <div class="stat-card" style="padding: 1.25rem;">
            <div>
                <div class="stat-value" style="font-size: 1.5rem;">{{ $stats['programs'] }}</div>
                <div class="stat-label" style="font-size: 0.75rem;">Program Studi</div>
            </div>
            <i class="fa-solid fa-scroll" style="color: var(--color-text-muted); font-size: 1.25rem;"></i>
        </div>
        <div class="stat-card" style="padding: 1.25rem;">
            <div>
                <div class="stat-value" style="font-size: 1.5rem;">{{ $stats['courses'] }}</div>
                <div class="stat-label" style="font-size: 0.75rem;">Mata Kuliah</div>
            </div>
            <i class="fa-solid fa-book" style="color: var(--color-text-muted); font-size: 1.25rem;"></i>
        </div>
    </div>

    <!-- Recent Applicants Table -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fa-solid fa-file-waveform"></i> Pendaftaran Terbaru Masuk</h3>
            <a href="{{ route('admin.tutors.index') }}" class="btn btn-outline btn-sm">Lihat Semua</a>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No. Registrasi</th>
                            <th>Nama Lengkap</th>
                            <th>NIK</th>
                            <th>Status</th>
                            <th>Tanggal Masuk</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentTutors as $tutor)
                            <tr>
                                <td style="font-weight: 600; font-family: monospace;">{{ $tutor->registration_number ?: '-' }}</td>
                                <td style="font-weight: 600;">{{ $tutor->full_name_with_titles }}</td>
                                <td>{{ $tutor->nik ?: '-' }}</td>
                                <td>
                                    @if($tutor->status === 'Draft')
                                        <span class="badge badge-draft">DRAFT</span>
                                    @elseif($tutor->status === 'Pending')
                                        <span class="badge badge-pending">PENDING REVIEW</span>
                                    @elseif($tutor->status === 'Approved')
                                        <span class="badge badge-approved">DISETUJUI</span>
                                    @elseif($tutor->status === 'Rejected')
                                        <span class="badge badge-rejected">DITOLAK / REVISI</span>
                                    @endif
                                </td>
                                <td>{{ $tutor->updated_at->translatedFormat('d M Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.tutors.show', $tutor->id) }}" class="btn btn-primary btn-sm" style="padding: 0.35rem 0.75rem; font-size: 0.8rem;">
                                        Review <i class="fa-solid fa-magnifying-glass"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center; color: var(--color-text-muted); padding: 3rem 0;">
                                    <i class="fa-solid fa-inbox" style="font-size: 2.5rem; display: block; margin-bottom: 0.5rem;"></i>
                                    Belum ada calon tutor yang melengkapi/mengirim berkas pendaftaran.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
