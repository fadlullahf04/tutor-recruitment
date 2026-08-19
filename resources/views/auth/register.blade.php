@extends('layouts.auth')

@section('title', 'Daftar Calon Tutor')

@section('content')
    @if(!$activePeriod)
        <div class="alert alert-danger" style="margin-bottom: 2rem;">
            <i class="fa-solid fa-circle-exclamation"></i>
            <div>
                <strong>Pendaftaran Ditutup!</strong><br>
                Saat ini tidak ada periode pendaftaran tutor online yang sedang aktif. Silakan hubungi admin UPT PJJ untuk info lebih lanjut.
            </div>
        </div>
        
        <a href="{{ route('login') }}" class="btn btn-outline btn-block">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Login
        </a>
    @else
        <div class="alert alert-warning" style="margin-bottom: 1.5rem; font-size: 0.85rem;">
            <i class="fa-solid fa-calendar-days"></i>
            <div>
                <strong>Periode Aktif:</strong><br>
                {{ $activePeriod->name }}<br>
                <span style="font-size: 0.8rem; opacity: 0.9;">Batas Akhir: {{ $activePeriod->end_date->translatedFormat('d F Y H:i') }} WIB</span>
            </div>
        </div>

        @if(session('error'))
            <div class="alert alert-danger">
                <i class="fa-solid fa-circle-exclamation"></i>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        <form action="{{ route('register.post') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="nik" class="form-label">Nomor Induk Kependudukan (NIK)</label>
                <input type="text" id="nik" name="nik" class="form-control @error('nik') is-invalid @enderror" value="{{ old('nik') }}" placeholder="16 digit nomor NIK KTP Anda" required maxlength="25" autofocus>
                @error('nik')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="name" class="form-label">Nama Lengkap (Sesuai KTP)</label>
                <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Nama lengkap tanpa gelar dahulu" required>
                @error('name')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Alamat Email Aktif</label>
                <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="contoh: calon.tutor@gmail.com" required>
                @error('email')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary btn-block" style="margin-top: 1.5rem;">
                Daftar Akun <i class="fa-solid fa-user-plus"></i>
            </button>
        </form>

        <div style="text-align: center; margin-top: 2rem; border-top: 1px solid #e2e8f0; padding-top: 1.5rem; font-size: 0.9rem;">
            <span style="color: var(--color-text-muted);">Sudah memiliki akun?</span>
            <a href="{{ route('login') }}" style="font-weight: 600;">Log In</a>
        </div>
    @endif
@endsection
