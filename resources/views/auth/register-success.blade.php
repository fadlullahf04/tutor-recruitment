@extends('layouts.auth')

@section('title', 'Pendaftaran Berhasil')

@section('content')
    <div style="text-align: center; margin-bottom: 2rem;">
        <div style="width: 60px; height: 60px; border-radius: 50%; background-color: var(--color-success-light); color: var(--color-success); display: inline-flex; align-items: center; justify-content: center; font-size: 2rem; margin-bottom: 1rem;">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <h3 style="font-size: 1.5rem; margin-bottom: 0.5rem;">Registrasi Berhasil!</h3>
        <p style="color: var(--color-text-muted); font-size: 0.9rem;">
            Akun calon tutor Anda telah dibuat di database sistem.
        </p>
    </div>

    <div style="background-color: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: var(--border-radius-md); padding: 1.25rem; margin-bottom: 1.5rem;">
        <div style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 0.75rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 0.5rem; font-weight: 600;">
            KREDENSIAL LOGIN ANDA
        </div>
        
        <div style="display: grid; grid-template-columns: 80px 1fr; gap: 0.5rem 1rem; font-size: 0.95rem;">
            <span style="color: var(--color-text-muted);">Nama:</span>
            <span style="font-weight: 600;">{{ session('reg_name') }}</span>

            <span style="color: var(--color-text-muted);">NIK:</span>
            <span style="font-weight: 600;">{{ session('reg_nik') }}</span>

            <span style="color: var(--color-text-muted);">Email:</span>
            <span style="font-weight: 600; color: var(--color-primary);">{{ session('reg_email') }}</span>

            <span style="color: var(--color-text-muted);">Password:</span>
            <span style="font-weight: 700; color: var(--color-secondary); font-family: monospace; font-size: 1.1rem; background-color: #fef3c7; padding: 0.1rem 0.5rem; border-radius: 4px; width: fit-content;">{{ session('reg_password') }}</span>
        </div>
    </div>

    <div class="alert alert-warning" style="font-size: 0.8rem; margin-bottom: 2rem; border-color: #fde68a;">
        <i class="fa-solid fa-info-circle"></i>
        <div>
            <strong>Pemberitahuan Sistem:</strong><br>
            Karena ini merupakan lingkungan lokal (development), password ini telah dikirim ke log email dan dicatat pada file <code>storage/logs/laravel.log</code>. Simpan credentials di atas sebelum melanjutkan.
        </div>
    </div>

    <a href="{{ route('login') }}" class="btn btn-primary btn-block">
        Lanjutkan ke Login <i class="fa-solid fa-right-to-bracket"></i>
    </a>
@endsection
