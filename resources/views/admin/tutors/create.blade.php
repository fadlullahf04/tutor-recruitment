@extends('layouts.app')

@section('title', 'Tambah Calon Tutor')
@section('header_title', 'Tambah Calon Tutor Baru')
@section('header_subtitle', 'Daftarkan akun calon tutor baru secara manual di database.')

@section('content')
    <div style="margin-bottom: 1.5rem;">
        <a href="{{ route('admin.tutors.index') }}" class="btn btn-outline btn-sm">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar
        </a>
    </div>

    <div class="card" style="max-width: 650px; margin: 0 auto; border-radius: var(--border-radius-lg);">
        <div class="card-header">
            <h3 class="card-title"><i class="fa-solid fa-user-plus"></i> Formulir Registrasi Calon Tutor</h3>
        </div>
        <div class="card-body" style="padding: 2.5rem;">
            @if($errors->any())
                <div class="alert alert-danger" style="margin-bottom: 1.5rem;">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <div>
                        @foreach ($errors->all() as $error)
                            {{ $error }}<br>
                        @endforeach
                    </div>
                </div>
            @endif

            <form action="{{ route('admin.tutors.store') }}" method="POST">
                @csrf

                <div class="form-group" style="margin-bottom: 1.25rem;">
                    <label for="nik" class="form-label" style="font-weight: 600;">NIK (Nomor Induk Kependudukan) <span style="color: var(--color-danger);">*</span></label>
                    <input type="text" id="nik" name="nik" class="form-control" value="{{ old('nik') }}" required placeholder="16 digit NIK KTP calon tutor" maxlength="25" autofocus>
                    <span style="font-size: 0.75rem; color: var(--color-text-muted);">Harus berupa 16 digit angka unik.</span>
                </div>

                <div class="form-group" style="margin-bottom: 1.25rem;">
                    <label for="name" class="form-label" style="font-weight: 600;">Nama Lengkap <span style="color: var(--color-danger);">*</span></label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required placeholder="Nama lengkap calon tutor beserta gelar">
                </div>

                <div class="form-group" style="margin-bottom: 1.25rem;">
                    <label for="email" class="form-label" style="font-weight: 600;">Alamat Email <span style="color: var(--color-danger);">*</span></label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required placeholder="Email aktif calon tutor">
                </div>

                <div class="form-group" style="margin-bottom: 1.75rem;">
                    <label for="recruitment_period_id" class="form-label" style="font-weight: 600;">Periode Rekrutmen <span style="color: var(--color-danger);">*</span></label>
                    <select id="recruitment_period_id" name="recruitment_period_id" class="form-control form-select" required>
                        <option value="" disabled selected>-- Pilih Periode Rekrutmen --</option>
                        @foreach($periods as $period)
                            <option value="{{ $period->id }}" {{ old('recruitment_period_id') == $period->id ? 'selected' : '' }}>
                                {{ $period->name }} {{ $period->is_active ? '(Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="alert alert-info" style="font-size: 0.8rem; margin-bottom: 2rem;">
                    <i class="fa-solid fa-circle-info"></i>
                    <div>
                        <strong>Info Pengiriman Akun:</strong><br>
                        Sistem akan secara otomatis membuat password acak sementara. Kredensial masuk ini akan dikirimkan ke email terdaftar di atas dan dicatat pada sistem log email.
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 0.75rem; border-top: 1px solid #f1f5f9; padding-top: 1.5rem;">
                    <a href="{{ route('admin.tutors.index') }}" class="btn btn-outline">Batal</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-floppy-disk"></i> Daftarkan Tutor
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
