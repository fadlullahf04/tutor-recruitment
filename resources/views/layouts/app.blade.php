<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - UPT PJJ UIN Siber Cirebon</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    
    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom Style CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @yield('styles')
</head>
<body>
    <div class="layout-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header" style="display: flex; align-items: center; gap: 1rem; padding: 1.25rem 1.5rem;">
                <div class="sidebar-logo" style="width: 55px; height: 55px; background-color: transparent; overflow: hidden; padding: 0; border-radius: 0; flex-shrink: 0;">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo UINSSC" style="width: 100%; height: 100%; object-fit: contain;">
                </div>
                <div class="sidebar-brand" style="font-size: 1.15rem; line-height: 1.3;">
                    UPT PJJ
                    <span style="font-size: 0.8rem; color: var(--color-primary); font-weight: 500; display: block;">UIN Siber Cirebon</span>
                </div>
            </div>

            <nav class="sidebar-nav">
                @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin())
                    <!-- Admin Sidebar -->
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ Route::is('admin.dashboard') ? 'active' : '' }}">
                        <i class="fa-solid fa-chart-line"></i> Dashboard
                    </a>
                    
                    <a href="{{ route('admin.tutors.index') }}" class="sidebar-link {{ Route::is('admin.tutors.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-user-tie"></i> Calon Tutor
                    </a>

                    <div style="font-size: 0.75rem; text-transform: uppercase; color: #475569; margin: 1.5rem 1rem 0.5rem; font-weight: 700; letter-spacing: 0.05em;">Master Data</div>
                    
                    <a href="{{ route('admin.master.faculties.index') }}" class="sidebar-link {{ Route::is('admin.master.faculties.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-building-columns"></i> Data Fakultas
                    </a>
                    
                    <a href="{{ route('admin.master.programs.index') }}" class="sidebar-link {{ Route::is('admin.master.programs.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-scroll"></i> Program Studi
                    </a>
                    
                    <a href="{{ route('admin.master.courses.index') }}" class="sidebar-link {{ Route::is('admin.master.courses.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-book"></i> Mata Kuliah
                    </a>

                    @if(Auth::user()->isSuperAdmin())
                        <div style="font-size: 0.75rem; text-transform: uppercase; color: #475569; margin: 1.5rem 1rem 0.5rem; font-weight: 700; letter-spacing: 0.05em;">Pengaturan Sistem</div>
                        
                        <a href="{{ route('admin.master.periods.index') }}" class="sidebar-link {{ Route::is('admin.master.periods.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-calendar-days"></i> Waktu Pendaftaran
                        </a>
                        
                        <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ Route::is('admin.users.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-users-gear"></i> Kelola Pengguna
                        </a>
                    @endif
                @else
                    <!-- Tutor Sidebar -->
                    <a href="{{ route('tutor.dashboard') }}" class="sidebar-link {{ Route::is('tutor.dashboard') ? 'active' : '' }}">
                        <i class="fa-solid fa-gauge-high"></i> Status Pendaftaran
                    </a>
                    
                    @if(Auth::user()->profile && in_array(Auth::user()->profile->status, ['Draft', 'Rejected']))
                        <a href="{{ route('tutor.wizard') }}" class="sidebar-link {{ Route::is('tutor.wizard') ? 'active' : '' }}">
                            <i class="fa-solid fa-file-signature"></i> Lengkapi Berkas
                        </a>
                    @endif



                    <a href="{{ route('tutor.password') }}" class="sidebar-link {{ Route::is('tutor.password') ? 'active' : '' }}">
                        <i class="fa-solid fa-key"></i> Ganti Password
                    </a>
                @endif
            </nav>

            <div class="sidebar-footer">
                <div class="user-profile-badge">
                    <div class="user-avatar">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="user-info">
                        <span class="user-name">{{ Auth::user()->name }}</span>
                        <span class="user-role">
                            @if(Auth::user()->isSuperAdmin())
                                Super Admin
                            @elseif(Auth::user()->isAdmin())
                                Admin PJJ
                            @else
                                Calon Tutor
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Wrapper -->
        <main class="main-content">
            <!-- Main Header -->
            <header class="main-header">
                <div class="main-title-section">
                    <h1>@yield('header_title', 'Dashboard')</h1>
                    <p>@yield('header_subtitle', 'Selamat datang kembali!')</p>
                </div>
                
                <div class="header-actions">
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-outline btn-sm" style="border-radius: var(--border-radius-sm);">
                            Keluar <i class="fa-solid fa-right-from-bracket"></i>
                        </button>
                    </form>
                </div>
            </header>

            <!-- Main Body -->
            <div class="main-body">
                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="fa-solid fa-circle-check"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <div>{{ session('error') }}</div>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
    
    @yield('scripts')
</body>
</html>
