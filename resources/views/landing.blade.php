<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Rekrutmen Tutor Online UPT PJJ - UIN Siber Cirebon</title>
    
    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom Style CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
</head>
<body>

    <!-- Header / Navbar -->
    <nav class="landing-nav">
        <div class="nav-container">
            <div class="nav-brand">
                <img src="{{ asset('images/logo.png') }}" alt="Logo UINSSC" class="nav-logo">
                <div class="nav-title">
                    UPT PJJ
                    <span>UIN Siber Cirebon</span>
                </div>
            </div>
            
            <ul class="nav-links">
                <li><a href="#home" class="nav-link active">Home</a></li>
                <li><a href="#alur" class="nav-link">Alur Rekrutmen</a></li>
                <li><a href="#pengumuman" class="nav-link">Pengumuman</a></li>
                <li><a href="#template" class="nav-link">Unduh Template</a></li>
            </ul>
            
            <div class="nav-actions">
                @auth
                    @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-primary" style="padding: 0.5rem 1.25rem; font-size: 0.9rem; font-weight: 600; border-radius: var(--border-radius-sm);">
                            <i class="fa-solid fa-chart-line"></i> Dashboard Admin
                        </a>
                    @else
                        <a href="{{ route('tutor.dashboard') }}" class="btn btn-primary" style="padding: 0.5rem 1.25rem; font-size: 0.9rem; font-weight: 600; border-radius: var(--border-radius-sm);">
                            <i class="fa-solid fa-gauge-high"></i> Dashboard Tutor
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline" style="padding: 0.5rem 1.25rem; font-size: 0.9rem; font-weight: 600; border-radius: var(--border-radius-sm);">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-primary" style="padding: 0.5rem 1.25rem; font-size: 0.9rem; font-weight: 600; border-radius: var(--border-radius-sm);">
                        Daftar
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-container">
            <div class="hero-content">
                <div class="hero-badge">
                    <i class="fa-solid fa-graduation-cap"></i> Penerimaan Tutor Online UPT PJJ
                </div>
                <h1 class="hero-title">
                    Bergabung Sebagai <span>Tutor Online</span> UIN Siber Cirebon
                </h1>
                <p class="hero-description">
                    Mari berkontribusi membangun universitas Islam siber pertama di Indonesia. Kembangkan karir mengajar Anda secara fleksibel melalui Unit Pelaksana Teknis Pembelajaran Jarak Jauh (UPT PJJ) UIN Siber Cirebon.
                </p>
                <div class="hero-buttons">
                    @auth
                        @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-primary btn-large">
                                Masuk ke Dashboard <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        @else
                            <a href="{{ route('tutor.dashboard') }}" class="btn btn-primary btn-large">
                                Lengkapi Pendaftaran <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        @endif
                    @else
                        <a href="{{ route('register') }}" class="btn btn-primary btn-large">
                            Daftar Sekarang <i class="fa-solid fa-user-plus"></i>
                        </a>
                        <a href="#alur" class="btn btn-outline btn-large">
                            Pelajari Alur <i class="fa-solid fa-chevron-down"></i>
                        </a>
                    @endauth
                </div>
            </div>
            
            <div class="hero-image-wrapper">
                <div class="hero-image-card">
                    <!-- Elegant visual layout with brand colors instead of generic placeholder -->
                    <div style="width: 320px; height: 280px; background: linear-gradient(135deg, var(--color-primary-light) 0%, #ffffff 100%); border-radius: var(--border-radius-md); display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 2rem; border: 1.5px solid rgba(16, 185, 129, 0.15);">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo UINSSC" style="width: 100px; height: 100px; object-fit: contain; margin-bottom: 1.5rem;">
                        <h3 style="font-size: 1.25rem; font-weight: 700; text-align: center; color: var(--color-primary); margin-bottom: 0.5rem;">E-learning UPT PJJ</h3>
                        <p style="font-size: 0.8rem; color: var(--color-text-muted); text-align: center; line-height: 1.4;">Transformasi Pendidikan Tinggi Keagamaan Islam Berbasis Siber</p>
                    </div>
                </div>
                <div class="hero-stats">
                    <div class="hero-stats-num">100%</div>
                    <div class="hero-stats-label">Online & Fleksibel</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Home / Tentang App & UPT PJJ Section -->
    <section id="home" class="landing-section" style="background-color: white; border-bottom: 1px solid #f1f5f9;">
        <div class="section-header">
            <span class="section-subtitle">Tentang Kami</span>
            <h2 class="section-title">UPT PJJ & Aplikasi Rekrutmen Tutor</h2>
            <p class="section-desc">Mengenal lebih dekat sistem pembelajaran jarak jauh dan mekanisme rekrutmen kami.</p>
        </div>
        
        <div class="about-grid">
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                <h3 style="font-size: 1.5rem; font-weight: 700; color: #0f172a; border-left: 4px solid var(--color-primary); padding-left: 1rem;">
                    UPT PJJ UIN Siber Cirebon
                </h3>
                <p style="color: var(--color-text-muted); line-height: 1.7; font-size: 0.95rem;">
                    Unit Pelaksana Teknis Pembelajaran Jarak Jauh (UPT PJJ) UIN Siber Syekh Nurjati Cirebon merupakan pionir sekaligus pusat pengembangan keilmuan Islam berbasis digital di bawah Kementerian Agama RI. Kami berkomitmen untuk menyelenggarakan pendidikan tinggi berkualitas tanpa batas ruang dan waktu.
                </p>
                <p style="color: var(--color-text-muted); line-height: 1.7; font-size: 0.95rem;">
                    Untuk mendukung keberhasilan e-learning, kami membuka kesempatan emas bagi dosen dan praktisi terbaik di seluruh Indonesia untuk bergabung sebagai tutor online guna mendampingi mahasiswa dalam berdiskusi, memberikan materi, serta mengevaluasi pembelajaran secara digital.
                </p>
            </div>
            
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                <h3 style="font-size: 1.5rem; font-weight: 700; color: #0f172a; border-left: 4px solid var(--color-secondary); padding-left: 1rem;">
                    Sistem Rekrutmen Tutor Online
                </h3>
                <p style="color: var(--color-text-muted); line-height: 1.7; font-size: 0.95rem;">
                    Aplikasi Rekrutmen Tutor ini dirancang khusus untuk mewadahi seleksi penerimaan tutor online secara akuntabel, transparan, dan efisien. Calon tutor dapat mendaftarkan diri, melengkapi profil akademik, memilih mata kuliah yang dikuasai, serta mengunggah kelengkapan administrasi secara mandiri.
                </p>
                
                <div class="about-features">
                    <div class="about-feature-card">
                        <div class="about-feature-icon">
                            <i class="fa-solid fa-bolt"></i>
                        </div>
                        <h4 class="about-feature-title">Proses Cepat</h4>
                        <p class="about-feature-desc">Pendaftaran dan unggah berkas dilakukan secara paperless dalam 5 langkah praktis.</p>
                    </div>
                    <div class="about-feature-card">
                        <div class="about-feature-icon" style="background-color: var(--color-secondary-light); color: var(--color-secondary);">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>
                        <h4 class="about-feature-title">Transparan</h4>
                        <p class="about-feature-desc">Pelacakan status pendaftaran dan pengumuman hasil seleksi langsung di dashboard Anda.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Alur Rekrutmen Section -->
    <section id="alur" class="landing-section">
        <div class="section-header">
            <span class="section-subtitle">Mekanisme Pendaftaran</span>
            <h2 class="section-title">Alur Sistem Rekrutmen Tutor</h2>
            <p class="section-desc">Berikut adalah 6 tahapan yang harus dilalui oleh setiap calon tutor online.</p>
        </div>
        
        <div class="alur-timeline-container">
            <div class="alur-grid">
                <!-- Step 1 -->
                <div class="alur-card">
                    <div class="alur-number">1</div>
                    <div class="alur-icon"><i class="fa-solid fa-user-plus"></i></div>
                    <h4 class="alur-title">Registrasi Akun</h4>
                    <p class="alur-desc">Calon tutor membuat akun pendaftaran menggunakan email aktif dan password pribadi pada form pendaftaran.</p>
                </div>
                
                <!-- Step 2 -->
                <div class="alur-card">
                    <div class="alur-number">2</div>
                    <div class="alur-icon"><i class="fa-solid fa-address-card"></i></div>
                    <h4 class="alur-title">Lengkapi Biodata</h4>
                    <p class="alur-desc">Mengisi biodata pribadi secara lengkap meliputi NIK, kontak, data rekening bank, alamat, serta instansi asal.</p>
                </div>
                
                <!-- Step 3 -->
                <div class="alur-card">
                    <div class="alur-number">3</div>
                    <div class="alur-icon"><i class="fa-solid fa-graduation-cap"></i></div>
                    <h4 class="alur-title">Pendidikan Terakhir</h4>
                    <p class="alur-desc">Memasukkan riwayat pendidikan tinggi terakhir (minimal Magister/S2 atau Doktor/S3) beserta gelar akademiknya.</p>
                </div>
                
                <!-- Step 4 -->
                <div class="alur-card">
                    <div class="alur-number">4</div>
                    <div class="alur-icon"><i class="fa-solid fa-book-open"></i></div>
                    <h4 class="alur-title">Pilih Mata Kuliah</h4>
                    <p class="alur-desc">Memilih program studi dan mata kuliah e-learning yang diajukan untuk ditutor sesuai bidang keahlian Anda.</p>
                </div>
                
                <!-- Step 5 -->
                <div class="alur-card">
                    <div class="alur-number">5</div>
                    <div class="alur-icon"><i class="fa-solid fa-upload"></i></div>
                    <h4 class="alur-title">Unggah Dokumen</h4>
                    <p class="alur-desc">Mengunggah file KTP, NPWP, buku tabungan, ijazah, CV terkini, sertifikat PJJ, serta surat kesediaan mengajar.</p>
                </div>
                
                <!-- Step 6 -->
                <div class="alur-card">
                    <div class="alur-number">6</div>
                    <div class="alur-icon" style="color: var(--color-success);"><i class="fa-solid fa-circle-check"></i></div>
                    <h4 class="alur-title">Seleksi & Hasil</h4>
                    <p class="alur-desc">Dokumen diverifikasi oleh administrator. Hasil pengumuman kelulusan dapat dilihat langsung via dashboard.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pengumuman Section -->
    <section id="pengumuman" class="landing-section" style="background-color: #f8fafc; border-top: 1px solid #f1f5f9; border-bottom: 1px solid #f1f5f9;">
        <div class="section-header">
            <span class="section-subtitle">Informasi Terbaru</span>
            <h2 class="section-title">Pengumuman Pembukaan Rekrutmen</h2>
            <p class="section-desc">Cek status dan tanggal pembukaan pendaftaran tutor aktif di bawah ini.</p>
        </div>
        
        <div class="pengumuman-box">
            <div class="pengumuman-visual">
                <i class="fa-solid fa-bullhorn"></i>
                <div>
                    @if($activePeriod)
                        <div class="status-badge open">
                            <span class="pulse-dot"></span> Pendaftaran Dibuka
                        </div>
                    @else
                        <div class="status-badge closed">
                            <i class="fa-solid fa-circle-xmark"></i> Pendaftaran Ditutup
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="pengumuman-details">
                @if($activePeriod)
                    <h3>Penerimaan Calon Tutor Online Sedang Berlangsung!</h3>
                    <p class="period-name">{{ $activePeriod->name }}</p>
                    <p style="color: var(--color-text-muted); font-size: 0.95rem; line-height: 1.6;">
                        UPT PJJ UIN Siber Cirebon secara resmi membuka penerimaan tutor baru. Silakan mendaftarkan akun dan melengkapi berkas Anda sebelum periode pendaftaran berakhir.
                    </p>
                    
                    <div class="period-dates">
                        <div class="period-date-item">
                            <i class="fa-solid fa-calendar-check"></i>
                            <strong>Mulai Pendaftaran:</strong> 
                            {{ \Carbon\Carbon::parse($activePeriod->start_date)->locale('id')->isoFormat('D MMMM Y') }}
                        </div>
                        <div class="period-date-item">
                            <i class="fa-solid fa-calendar-xmark"></i>
                            <strong>Batas Akhir:</strong> 
                            {{ \Carbon\Carbon::parse($activePeriod->end_date)->locale('id')->isoFormat('D MMMM Y') }}
                        </div>
                    </div>
                    
                    <div style="margin-top: 0.5rem;">
                        @guest
                            <a href="{{ route('register') }}" class="btn btn-primary" style="padding: 0.65rem 1.5rem; font-weight: 600;">
                                <i class="fa-solid fa-user-plus"></i> Daftar Akun Tutor
                            </a>
                        @endguest
                    </div>
                @else
                    <h3>Pendaftaran Tutor Belum Dibuka</h3>
                    <p style="color: var(--color-text-muted); font-size: 0.95rem; line-height: 1.6;">
                        Saat ini tidak ada periode pendaftaran rekrutmen tutor online yang sedang aktif. Halaman pendaftaran akan dibuka kembali sesuai jadwal akademik UPT PJJ UIN Siber Cirebon.
                    </p>
                    <div style="background: #fee2e2; border-left: 4px solid var(--color-danger); padding: 1rem; border-radius: var(--border-radius-sm); color: #991b1b; font-size: 0.9rem;">
                        <i class="fa-solid fa-circle-info"></i> Silakan periksa kembali halaman ini secara berkala untuk pengumuman jadwal seleksi berikutnya.
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Unduh Template Section -->
    <section id="template" class="landing-section">
        <div class="section-header">
            <span class="section-subtitle">Unduh Berkas</span>
            <h2 class="section-title">File Template Surat Persyaratan</h2>
            <p class="section-desc">Unduh berkas pernyataan di bawah ini, isi data secara lengkap, lalu tanda tangani sebelum diunggah ke sistem.</p>
        </div>
        
        <div class="template-grid">
            <!-- Surat Kesediaan Mengajar -->
            <div class="template-card">
                <div class="template-icon">
                    <i class="fa-solid fa-file-signature"></i>
                </div>
                <div class="template-info">
                    <h4 class="template-title">Surat Kesediaan Mengajar UPT PJJ</h4>
                    <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 1rem; line-height: 1.5;">
                        Draf surat pernyataan resmi menyatakan kesanggupan mengajar mata kuliah PJJ UIN Siber Cirebon sesuai jadwal akademik.
                    </p>
                    <div class="template-meta">
                        <span><i class="fa-solid fa-file-pdf" style="color: var(--color-danger);"></i> PDF (209 KB)</span>
                        <span><i class="fa-solid fa-file-word" style="color: #2b579a;"></i> Word (3.5 MB)</span>
                    </div>
                    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                        <a href="{{ route('template.download', 'kesediaan-pdf') }}" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.85rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.5rem;">
                            <i class="fa-solid fa-cloud-arrow-down"></i> Unduh PDF
                        </a>
                        <a href="{{ route('template.download', 'kesediaan-docx') }}" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.85rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.5rem;">
                            <i class="fa-solid fa-file-word"></i> Unduh Word (DOCX)
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Pakta Integritas -->
            <div class="template-card">
                <div class="template-icon" style="background-color: var(--color-primary-light); color: var(--color-primary);">
                    <i class="fa-solid fa-shield-check"></i>
                </div>
                <div class="template-info">
                    <h4 class="template-title">Pakta Integritas Calon Tutor</h4>
                    <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 1rem; line-height: 1.5;">
                        Pernyataan integritas moral, komitmen pelaksanaan tugas pembelajaran online, serta bebas dari narkoba dan tuntutan hukum.
                    </p>
                    <div class="template-meta">
                        <span><i class="fa-solid fa-file-pdf" style="color: var(--color-danger);"></i> PDF</span>
                        <span><i class="fa-solid fa-weight-hanging"></i> 611 B</span>
                    </div>
                    <a href="{{ route('template.download', 'pakta-pdf') }}" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.85rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.5rem;">
                        <i class="fa-solid fa-cloud-arrow-down"></i> Unduh File
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="landing-footer">
        <div class="footer-container">
            <div style="grid-column: span 1;">
                <div class="footer-info-logo">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo UINSSC" class="footer-logo">
                    <div class="footer-brand">
                        UPT PJJ
                        <span>UIN Siber Cirebon</span>
                    </div>
                </div>
                <p class="footer-desc">
                    Pusat penyelenggaraan pendidikan siber Keagamaan Islam pertama di Indonesia yang unggul, terjangkau, dan bereputasi internasional.
                </p>
                <div class="footer-socials">
                    <a href="#" class="footer-social-link"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="footer-social-link"><i class="fa-brands fa-x-twitter"></i></a>
                    <a href="#" class="footer-social-link"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" class="footer-social-link"><i class="fa-brands fa-youtube"></i></a>
                </div>
            </div>
            
            <div>
                <h4 class="footer-title">Navigasi Cepat</h4>
                <ul class="footer-links">
                    <li class="footer-link"><a href="#home">Home</a></li>
                    <li class="footer-link"><a href="#alur">Alur Rekrutmen</a></li>
                    <li class="footer-link"><a href="#pengumuman">Pengumuman</a></li>
                    <li class="footer-link"><a href="#template">Unduh Template</a></li>
                </ul>
            </div>
            
            <div>
                <h4 class="footer-title">Akun Pendaftaran</h4>
                <ul class="footer-links">
                    @guest
                        <li class="footer-link"><a href="{{ route('login') }}">Masuk Calon Tutor</a></li>
                        <li class="footer-link"><a href="{{ route('register') }}">Registrasi Tutor Baru</a></li>
                    @else
                        @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin())
                            <li class="footer-link"><a href="{{ route('admin.dashboard') }}">Dashboard Admin</a></li>
                        @else
                            <li class="footer-link"><a href="{{ route('tutor.dashboard') }}">Dashboard Tutor</a></li>
                        @endif
                    @endguest
                </ul>
            </div>
            
            <div>
                <h4 class="footer-title">Hubungi Kami</h4>
                <div class="footer-contact">
                    <div class="footer-contact-item">
                        <i class="fa-solid fa-location-dot"></i>
                        <span>Jl. Perjuangan, Sunyaragi, Kec. Kesambi, Kota Cirebon, Jawa Barat 45131</span>
                    </div>
                    <div class="footer-contact-item">
                        <i class="fa-solid fa-envelope"></i>
                        <span>pjj@syekhnurjati.ac.id</span>
                    </div>
                    <div class="footer-contact-item">
                        <i class="fa-solid fa-phone"></i>
                        <span>(0231) 481264</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="footer-bottom">
            <div class="footer-copyright">
                &copy; {{ date('Y') }} UPT PJJ UIN Siber Syekh Nurjati Cirebon. All rights reserved.
            </div>
            <div class="footer-bottom-links">
                <a href="#">Kebijakan Privasi</a>
                <a href="#">Syarat & Ketentuan</a>
            </div>
        </div>
    </footer>

    <!-- Simple Scrollspy Active State Javascript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sections = document.querySelectorAll('section[id]');
            const navLinks = document.querySelectorAll('.nav-link');
            
            window.addEventListener('scroll', () => {
                let current = '';
                sections.forEach(section => {
                    const sectionTop = section.offsetTop;
                    const sectionHeight = section.clientHeight;
                    // adjust offsets for sticky header
                    if (pageYOffset >= (sectionTop - 120)) {
                        current = section.getAttribute('id');
                    }
                });
                
                navLinks.forEach(link => {
                    link.classList.remove('active');
                    if (link.getAttribute('href') === `#${current}`) {
                        link.classList.add('active');
                    }
                });
            });
        });
    </script>
</body>
</html>
