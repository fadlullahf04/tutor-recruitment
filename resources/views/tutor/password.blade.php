@extends('layouts.app')

@section('title', 'Ganti Password')
@section('header_title', 'Ganti Password Akun')
@section('header_subtitle', 'Perbarui kata sandi akun Anda untuk meningkatkan keamanan akses.')

@section('content')
    <div class="card" style="border-radius: var(--border-radius-lg); padding: 2.5rem; max-width: 600px; margin: 0 auto;">
        <h3 style="font-size: 1.2rem; margin-bottom: 1.5rem; border-bottom: 2px solid #f1f5f9; padding-bottom: 0.5rem; color: #334155;">
            <i class="fa-solid fa-key"></i> Perbarui Kata Sandi
        </h3>

        @if ($errors->has('current_password') || $errors->has('new_password'))
            <div class="alert alert-danger" style="margin-bottom: 1.5rem;">
                <i class="fa-solid fa-circle-exclamation"></i>
                <div>
                    @foreach ($errors->all() as $error)
                        {{ $error }}<br>
                    @endforeach
                </div>
            </div>
        @endif

        <form action="{{ route('change-password') }}" method="POST">
            @csrf
            
            <div class="form-group" style="margin-bottom: 1.25rem;">
                <label for="current_password" class="form-label" style="font-weight: 600;">Password Saat Ini <span style="color: var(--color-danger);">*</span></label>
                <input type="password" id="current_password" name="current_password" class="form-control" required placeholder="Masukkan password Anda saat ini">
            </div>
            
            <div class="form-group" style="margin-bottom: 1.25rem;">
                <label for="new_password" class="form-label" style="font-weight: 600;">Password Baru <span style="color: var(--color-danger);">*</span></label>
                <input type="password" id="new_password" name="new_password" class="form-control" required placeholder="Minimal 6 karakter">
            </div>

            <div class="form-group" style="margin-bottom: 1.75rem;">
                <label for="new_password_confirmation" class="form-label" style="font-weight: 600;">Konfirmasi Password Baru <span style="color: var(--color-danger);">*</span></label>
                <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="form-control" required placeholder="Ulangi password baru Anda">
            </div>

            <div style="display: flex; justify-content: flex-end; border-top: 1px solid #f1f5f9; padding-top: 1.5rem; gap: 0.75rem;">
                <a href="{{ route('tutor.dashboard') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-warning">
                    <i class="fa-solid fa-lock-open"></i> Perbarui Password
                </button>
            </div>
        </form>
    </div>
@endsection
