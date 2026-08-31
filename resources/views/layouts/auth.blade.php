<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Pendaftaran Tutor Online UPT PJJ') - UIN Siber Cirebon</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom Style CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-card">
            <div class="auth-header">
                <div class="auth-logo" style="background-color: transparent; overflow: hidden; border-radius: 0; width: 120px; height: 120px; margin-bottom: 1rem; display: inline-flex; align-items: center; justify-content: center;">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo UINSSC" style="width: 100%; height: 100%; object-fit: contain;">
                </div>
                <h2 class="auth-title">UPT PJJ</h2>
                <p class="auth-subtitle">Rekrutmen Tutor Online UIN Siber Syekh Nurjati</p>
            </div>

            @yield('content')
        </div>
    </div>
</body>
</html>
