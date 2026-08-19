@extends('layouts.app')

@section('title', 'Edit Calon Tutor')
@section('header_title', 'Edit Identitas Calon Tutor')
@section('header_subtitle', 'Sunting informasi identitas diri dan akun calon tutor secara langsung.')

@section('content')
    <div style="margin-bottom: 1.5rem;">
        <a href="{{ route('admin.tutors.show', $tutor->id) }}" class="btn btn-outline btn-sm">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Detail
        </a>
    </div>

    <div class="card" style="max-width: 700px; margin: 0 auto; border-radius: var(--border-radius-lg);">
        <div class="card-header">
            <h3 class="card-title"><i class="fa-solid fa-user-gear"></i> Formulir Perubahan Data Identitas</h3>
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

            <form action="{{ route('admin.tutors.update', $tutor->id) }}" method="POST">
                @csrf
                @method('PUT')

                <h4 style="font-size: 1rem; border-bottom: 1.5px solid #f1f5f9; padding-bottom: 0.5rem; margin-bottom: 1.25rem; color: var(--color-primary); font-weight: 700;">
                    <i class="fa-solid fa-id-card"></i> Informasi Akun & Identitas
                </h4>

                <div class="form-group" style="margin-bottom: 1.25rem;">
                    <label for="nik" class="form-label" style="font-weight: 600;">NIK (Nomor Induk Kependudukan) <span style="color: var(--color-danger);">*</span></label>
                    <input type="text" id="nik" name="nik" class="form-control" value="{{ old('nik', $tutor->nik) }}" required placeholder="16 digit NIK" maxlength="25">
                </div>

                <div class="form-group" style="margin-bottom: 1.25rem;">
                    <label for="name" class="form-label" style="font-weight: 600;">Nama Lengkap <span style="color: var(--color-danger);">*</span></label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $tutor->full_name_with_titles) }}" required placeholder="Nama lengkap tutor">
                </div>

                <div class="form-group" style="margin-bottom: 1.25rem;">
                    <label for="email" class="form-label" style="font-weight: 600;">Alamat Email <span style="color: var(--color-danger);">*</span></label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $tutor->user ? $tutor->user->email : '') }}" required placeholder="Email aktif">
                </div>

                <div class="form-group" style="margin-bottom: 2rem;">
                    <label for="recruitment_period_id" class="form-label" style="font-weight: 600;">Periode Rekrutmen <span style="color: var(--color-danger);">*</span></label>
                    <select id="recruitment_period_id" name="recruitment_period_id" class="form-control form-select" required>
                        @foreach($periods as $period)
                            <option value="{{ $period->id }}" {{ old('recruitment_period_id', $tutor->recruitment_period_id) == $period->id ? 'selected' : '' }}>
                                {{ $period->name }} {{ $period->is_active ? '(Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 0.75rem; border-top: 1px solid #f1f5f9; padding-top: 1.5rem;">
                    <a href="{{ route('admin.tutors.show', $tutor->id) }}" class="btn btn-outline">Batal</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
