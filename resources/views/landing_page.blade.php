<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi DKP3 - Sistem Absensi & Magang Digital</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>


    <link rel="stylesheet" href="{{ asset('css/landing-page.css') }}">

</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="{{ asset('storage/assets/logobjm.png') }}" alt="Logo DKP3" class="me-2 rounded-circle"> <span class="d-none d-sm-inline">Absensi DKP3</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#hero">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about-dkp3">Tentang DKP3</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Fitur</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#penerimaan-magang">Magang</a>
                    </li>
                    <li class="nav-item ms-lg-3">
                        <a href="{{ route('auth.login') }}" class="btn btn-primary rounded-pill px-4">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section id="hero" class="hero-section d-flex align-items-center">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 text-center text-lg-start mb-4 mb-lg-0">
                    <h1 class="animate__animated animate__fadeInLeft">Kelola Absensi & Kegiatan Magang Lebih Mudah</h1>
                    <p class="lead animate__animated animate__fadeInLeft animate__delay-0.5s">
                        Absensi DKP3 adalah platform digital terintegrasi untuk memantau kehadiran dan mencatat kegiatan peserta magang secara efisien.
                    </p>
                    <a href="{{ route('pendaftaran.create') }}" class="btn btn-light btn-lg rounded-pill px-5 animate__animated animate__fadeInLeft animate__delay-1s">Daftar Magang Sekarang</a>
                </div>
                <div class="col-lg-6 text-center animate__animated animate__fadeInRight animate__delay-0.8s">
                    <img src="{{ asset('storage/assets/landing_page/landing_4.jpg') }}" alt="Absensi Magang Illustration" class="img-fluid hero-illustration">
                </div>
            </div>
        </div>
    </section>

    <section id="about-dkp3" class="about-dkp3-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <img src="{{ asset('storage/assets/landing_page/landing_3.jpg') }}" alt="Gedung DKP3" class="img-fluid rounded-3 shadow-lg">
                </div>
                <div class="col-lg-6">
                    <h2 class="mb-4">Tentang Dinas Ketahanan Pangan, Pertanian, dan Perikanan (DKP3)</h2>
                    <p>Dinas Ketahanan Pangan, Pertanian, dan Perikanan (DKP3) adalah sebuah instansi pemerintah yang memiliki peran strategis dalam menjaga ketahanan pangan, memajukan sektor pertanian, dan mengembangkan potensi perikanan di wilayah kota Banjarmasin dan sekitarnya. Kami berkomitmen untuk mewujudkan ketersediaan pangan yang cukup, aman, dan bergizi bagi seluruh masyarakat.</p>
                    <p>Melalui berbagai program dan inovasi, DKP3 berupaya meningkatkan produktivitas pertanian, membina para petani dan nelayan, serta memastikan distribusi pangan yang merata. Kami juga aktif dalam riset dan pengembangan untuk menghadapi tantangan masa depan di sektor pangan dan pertanian.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="features" class="features-section">
        <div class="container">
            <h2>Fitur Unggulan Kami</h2>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <div class="col">
                    <div class="feature-card">
                        <i class="bi bi-clock-history icon"></i>
                        <h3>Absensi Real-time</h3>
                        <p>Catat kehadiran peserta magang dengan mudah dan pantau status absensi kapan saja, di mana saja.</p>
                    </div>
                </div>
                <div class="col">
                    <div class="feature-card">
                        <i class="bi bi-journal-check icon"></i>
                        <h3>Catatan Kegiatan Harian</h3>
                        <p>Peserta dapat mengisi laporan kegiatan harian, mempermudah pembimbing dalam memantau progress.</p>
                    </div>
                </div>
                <div class="col">
                    <div class="feature-card">
                        <i class="bi bi-clipboard-data icon"></i>
                        <h3>Penilaian & Laporan</h3>
                        <p>Pembimbing dapat memberikan penilaian komprehensif dan menghasilkan laporan kinerja peserta magang.</p>
                    </div>
                </div>
                <div class="col">
                    <div class="feature-card">
                        <i class="bi bi-people icon"></i>
                        <h3>Manajemen Peserta & Pembimbing</h3>
                        <p>Kelola data peserta dan pembimbing dengan sistematis dalam satu platform terpusat.</p>
                    </div>
                </div>
                <div class="col">
                    <div class="feature-card">
                        <i class="bi bi-file-earmark-bar-graph icon"></i>
                        <h3>Statistik & Ringkasan</h3>
                        <p>Dapatkan insight dari data absensi dan kegiatan melalui laporan statistik yang mudah dipahami.</p>
                    </div>
                </div>
                <div class="col">
                    <div class="feature-card">
                        <i class="bi bi-phone icon"></i>
                        <h3>Akses Mobile-Friendly</h3>
                        <p>Antarmuka yang responsif memastikan pengalaman terbaik di berbagai perangkat, termasuk smartphone.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="penerimaan-magang" class="magang-section">
        <div class="container text-center">
            <h2 class="mb-4">Program Magang di DKP3 Kota Banjarmasin</h2>
            <p class="lead mb-4">Kami membuka kesempatan bagi individu berbakat yang ingin mendapatkan pengalaman praktis di sektor pangan, pertanian, dan perikanan.</p>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow-lg border-0 rounded-3">
                        <div class="card-body p-5">
                            <h3 class="card-title mb-3 text-primary">Siapa yang Kami Cari?</h3>
                            <p class="card-text text-muted">Kami mencari mahasiswa atau lulusan baru dari berbagai disiplin ilmu, khususnya yang berkaitan dengan pertanian, perikanan, teknologi pangan, manajemen, administrasi, dan teknologi informasi. Kandidat diharapkan memiliki semangat belajar tinggi, inisiatif, dan siap berkontribusi.</p>
                            <a href="{{ route('pendaftaran.create') }}" class="btn btn-primary btn-lg rounded-pill mt-4 px-5">Ajukan Permohonan Magang</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="cta" class="cta-section">
        <div class="container">
            <h2>Siap Mengoptimalkan Manajemen Magang Anda?</h2>
            <p class="lead mb-4">Mulai kelola absensi dan kegiatan magang Anda dengan lebih efisien bersama Absensi DKP3.</p>
            <a href="{{ route('pendaftaran.create') }}" class="btn btn-light btn-lg rounded-pill px-5">Daftar Magang Sekarang</a>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <p class="mb-0">&copy; {{ date('Y') }} Absensi DKP3. Hak Cipta Dilindungi Undang-Undang.</p>
            <ul class="list-inline mt-2 mb-0">
                <li class="list-inline-item"><a href="#">Kebijakan Privasi</a></li>
                <li class="list-inline-item">·</li>
                <li class="list-inline-item"><a href="#">Syarat & Ketentuan</a></li>
            </ul>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="{{ asset('js/landing-page.js') }}"></script>
</body>
</html>