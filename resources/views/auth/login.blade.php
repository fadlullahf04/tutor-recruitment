@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    @if(session('success_reset'))
        <div class="alert alert-success">
            <i class="fa-solid fa-circle-check"></i>
            <div>{{ session('success_reset') }}</div>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <i class="fa-solid fa-circle-exclamation"></i>
            <div>
                @foreach ($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
        </div>
    @endif

    <form action="{{ route('login.post') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="login_identifier" class="form-label">Email atau NIK</label>
            <input type="text" id="login_identifier" name="login_identifier" class="form-control @error('login_identifier') is-invalid @enderror" value="{{ old('login_identifier') }}" placeholder="NIK KTP atau email terdaftar" required autofocus>
            @error('login_identifier')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                <label for="password" class="form-label" style="margin-bottom: 0;">Password</label>
                <a href="{{ route('forgot-password') }}" style="font-size: 0.85rem;">Lupa Password?</a>
            </div>
            <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Masukkan password Anda" required>
            @error('password')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group" style="margin-top: 1.5rem;">
            <label class="form-check">
                <input type="checkbox" name="remember" class="form-check-input">
                <span style="font-size: 0.9rem; color: var(--color-text-muted);">Ingat Saya</span>
            </label>
        </div>

        <button type="submit" class="btn btn-primary btn-block" style="margin-top: 1.5rem;">
            Masuk <i class="fa-solid fa-arrow-right-to-bracket"></i>
        </button>
    </form>

    <div style="text-align: center; margin-top: 2rem; border-top: 1px solid #e2e8f0; padding-top: 1.5rem; font-size: 0.9rem;">
        <span style="color: var(--color-text-muted);">Belum punya akun?</span>
        <a href="{{ route('register') }}" style="font-weight: 600;">Daftar Sekarang</a>
    </div>
@endsection
