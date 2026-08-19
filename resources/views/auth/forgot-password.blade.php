@extends('layouts.auth')

@section('title', 'Lupa Password')

@section('content')
    <div style="margin-bottom: 1.5rem; text-align: center;">
        <h3 style="font-size: 1.25rem; margin-bottom: 0.5rem;">Reset Password</h3>
        <p style="color: var(--color-text-muted); font-size: 0.85rem;">
            Masukkan alamat email terdaftar Anda. Kami akan mengatur ulang password Anda dan mencatatnya ke simulasi log email Anda.
        </p>
    </div>

    @if($errors->has('email'))
        <div class="alert alert-danger">
            <i class="fa-solid fa-circle-exclamation"></i>
            <div>{{ $errors->first('email') }}</div>
        </div>
    @endif

    <form action="{{ route('forgot-password.post') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="email" class="form-label">Alamat Email Terdaftar</label>
            <input type="email" id="email" name="email" class="form-control" placeholder="Masukkan email Anda" required autofocus>
        </div>

        <button type="submit" class="btn btn-primary btn-block" style="margin-top: 1.5rem;">
            Kirim Password Baru <i class="fa-solid fa-paper-plane"></i>
        </button>
    </form>

    <div style="text-align: center; margin-top: 2rem; border-top: 1px solid #e2e8f0; padding-top: 1.5rem; font-size: 0.9rem;">
        <a href="{{ route('login') }}" style="font-weight: 600;"><i class="fa-solid fa-arrow-left"></i> Kembali ke Login</a>
    </div>
@endsection
