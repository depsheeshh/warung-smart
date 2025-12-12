<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>WarungSmart - Landing Page</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- FontAwesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <!-- AOS CSS -->
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

<style>
/* =======================================
   ROOT VARIABLE
======================================= */
:root {
  --primary: #c0392b;
  --primary-dark: #a32d22;

  --light-bg: #fff5f5;
  --dark-bg: #111;

  --card-light: rgba(255,255,255,0.85);
  --card-dark: #222;

  --text-light: #2b2b2b;
  --text-dark: #f1f1f1;

  --shadow: 0px 6px 18px rgba(0,0,0,0.10);
}

/* =======================================
   GLOBAL
======================================= */
body {
  font-family: 'Segoe UI', sans-serif;
  background: var(--light-bg);
  color: var(--text-light);
  overflow-x: hidden;
  transition: background .3s ease, color .3s ease;
}

body.dark-mode {
  background: var(--dark-bg) !important;
  color: var(--text-dark) !important;
}

body.dark-mode section {
  background: var(--dark-bg) !important;
  color: var(--text-dark) !important;
}

/* =======================================
   NAVBAR
======================================= */
.navbar {
  padding: 14px 0;
  background: var(--primary);
  box-shadow: var(--shadow);
  transition: background .3s ease;
}

.dark-mode .navbar {
  background: #000 !important;
}

/* =======================================
   HERO
======================================= */
.hero {
  padding: 90px 0;
  text-align: center;
  background: linear-gradient(135deg, #ffe8e8, #ffdcdc);
  color: var(--text-light);
  transition: background .4s ease, color .4s ease;
}

.dark-mode .hero {
  background: linear-gradient(135deg, #222, #000) !important;
  color: var(--text-dark);
}

.hero-img {
  max-width: 420px;
  filter: drop-shadow(0px 8px 25px rgba(0,0,0,0.25));
}

/* =======================================
   CTA
======================================= */
.cta-box {
  padding: 80px 0;
  text-align: center;
  background: linear-gradient(135deg, var(--primary), var(--primary-dark));
  color: white;
  transition: background .4s ease, color .4s ease;
}

.dark-mode .cta-box {
    background: linear-gradient(135deg, #000, #1a1a1a) !important;
    color: var(--text-dark) !important;
}

/* CTA & Hero Buttons */
.hero .btn,
.cta-box .btn {
  background: #fff;
  color: var(--primary);
  font-weight: 700;
  border-radius: 8px;
}

.dark-mode .hero .btn,
.dark-mode .cta-box .btn {
  background: #fff !important;
  color: var(--primary) !important;
}

/* =======================================
   CARD
======================================= */
.card-modern {
  border-radius: 16px;
  padding: 32px;
  background: var(--card-light);
  box-shadow: var(--shadow);
  backdrop-filter: blur(10px);
  transition: background .3s ease, color .3s ease, transform .3s ease;
}

.card-modern:hover {
  transform: translateY(-5px);
  box-shadow: 0px 12px 24px rgba(0,0,0,0.12);
}

.dark-mode .card-modern {
  background: var(--card-dark) !important;
  color: var(--text-dark) !important;
}

/* =======================================
   TESTIMONIAL
======================================= */
.testimonial-box {
  border-radius: 14px;
  background: #fff;
  padding: 25px;
  box-shadow: var(--shadow);
  transition: background .3s ease, color .3s ease;
}

.dark-mode .testimonial-box {
  background: #2b2b2b !important;
  color: var(--text-dark) !important;
}

/* =======================================
   FIX - Section yang sebelumnya tidak ikut gelap
======================================= */
.dark-mode #features,
.dark-mode #benefits,
.dark-mode #testimoni {
  background: var(--dark-bg) !important;
}

/* =======================================
   ICON RED
======================================= */
.dark-mode i {
  color: #e74c3c !important;
}

/* =======================================
   FOOTER
======================================= */
footer {
  background: var(--primary);
  color: white;
  transition: background .3s ease;
}

.dark-mode footer {
  background: #000 !important;
  color: white !important;
}

.dark-mode footer a {
  color: white !important;
}
</style>


</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
  <div class="container">
    <a class="navbar-brand" href="#">WarungSmart</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse text-end" id="navMenu">
      <ul class="navbar-nav ms-auto align-items-center">

        <!-- Dark Mode Toggle -->
        <li class="nav-item me-3">
          <button id="toggleDark" class="btn btn-link text-white p-0" title="Ganti Mode">
            <i class="fas fa-moon fa-lg"></i>
          </button>
        </li>

        <li class="nav-item"><a class="nav-link" href="#features">Fitur</a></li>
        <li class="nav-item"><a class="nav-link" href="#benefits">Keunggulan</a></li>
        <li class="nav-item"><a class="nav-link" href="#about">Tentang</a></li>
        <li class="nav-item"><a class="nav-link" href="#testimoni">Testimoni</a></li>

        @guest
        <li class="nav-item">
          <a class="btn btn-light ms-3 px-4 text-danger" href="{{ route('login') }}">Login</a>
        </li>
        <li class="nav-item">
          <a class="btn btn-outline-light ms-2 px-4" href="{{ route('register') }}">Register</a>
        </li>
        @endguest
      </ul>
    </div>
  </div>
</nav>


<!-- HERO -->
<section class="hero text-center">
  <div class="container d-flex flex-column flex-md-row align-items-center justify-content-center">
    <div data-aos="fade-right">
      <h1>Solusi Digital untuk Warung Masa Kini</h1>
      <p class="mt-3">Kelola stok, transaksi, kasbon, laporan — semua dalam satu aplikasi modern.</p>
      <a href="{{ route('register') }}" class="btn btn-light text-danger mt-3 px-4 py-2 fw-bold">
        Mulai Sekarang
      </a>
    </div>

    <div class="ms-md-5 mt-5 mt-md-0" data-aos="fade-left">
      <img src="img/hero.png" class="hero-img">
    </div>
  </div>
</section>

<!-- FITUR -->
<section id="features" class="py-5">
  <div class="container">
    <h2 class="section-title text-center" data-aos="fade-up">Fitur Utama</h2>

    <div class="row mt-4">
      <!-- Card -->
      <div class="col-md-4 mb-4" data-aos="zoom-in">
        <div class="card-modern h-100 text-center">
          <i class="fa fa-box fa-2x text-danger mb-3"></i>
          <h5>Manajemen Stok</h5>
          <p class="mt-2">Pantau stok secara real-time, hindari kehabisan barang.</p>
        </div>
      </div>

      <div class="col-md-4 mb-4" data-aos="zoom-in" data-aos-delay="150">
        <div class="card-modern h-100 text-center">
          <i class="fa fa-star fa-2x text-danger mb-3"></i>
          <h5>Membership & Diskon</h5>
          <p class="mt-2">Hadiahkan pelanggan setia dengan poin & diskon.</p>
        </div>
      </div>

      <div class="col-md-4 mb-4" data-aos="zoom-in" data-aos-delay="300">
        <div class="card-modern h-100 text-center">
          <i class="fa fa-coins fa-2x text-danger mb-3"></i>
          <h5>Laporan Keuangan</h5>
          <p class="mt-2">Laporan lengkap untuk melihat keuntungan harian/ bulanan.</p>
        </div>
      </div>
    </div>

  </div>
</section>

<!-- BENEFITS SECTION -->
<section id="benefits" class="py-5">
  <div class="container">
    <h2 class="section-title text-center" data-aos="fade-up">Kenapa Memilih WarungSmart?</h2>

    <div class="row mt-4">
      <div class="col-md-3 text-center" data-aos="fade-up">
        <i class="fa fa-bolt text-danger fa-2x mb-2"></i>
        <h6>Cepat & Mudah</h6>
        <p>Sistem dirancang agar mudah dipakai siapa saja.</p>
      </div>
      <div class="col-md-3 text-center" data-aos="fade-up" data-aos-delay="150">
        <i class="fa fa-lock text-danger fa-2x mb-2"></i>
        <h6>Aman & Terproteksi</h6>
        <p>Data transaksi tersimpan aman di server.</p>
      </div>
      <div class="col-md-3 text-center" data-aos="fade-up" data-aos-delay="300">
        <i class="fa fa-chart-line text-danger fa-2x mb-2"></i>
        <h6>Analitik Modern</h6>
        <p>Grafik & laporan otomatis, siap cetak.</p>
      </div>
      <div class="col-md-3 text-center" data-aos="fade-up" data-aos-delay="450">
        <i class="fa fa-hand-holding-heart text-danger fa-2x mb-2"></i>
        <h6>Dukungan Cepat</h6>
        <p>Tim support siap membantu kapan saja.</p>
      </div>
    </div>

  </div>
</section>

<!-- TESTIMONI -->
<section id="testimoni" class="py-5">
  <div class="container">
    <h2 class="section-title text-center" data-aos="fade-up">Apa Kata Pengguna?</h2>

    <div class="row mt-4">
      {{-- Testimoni Customer --}}
      <div class="col-md-6 mb-3" data-aos="fade-right">
        <div class="testimonial-box">
          <p>“Sebagai pelanggan, saya bisa belanja lebih mudah dan transparan. Kasbon pun tercatat rapi.”</p>
          <strong>- Yanto Gok Gok Gok (Customer)</strong>
        </div>
      </div>

      {{-- Testimoni Supplier --}}
      <div class="col-md-6 mb-3" data-aos="fade-left">
        <div class="testimonial-box">
          <p>“Sebagai supplier, saya bisa kelola produk dan pesanan tanpa ribet. Semua terpantau jelas.”</p>
          <strong>- Luhut (Supplier)</strong>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CTA TERAKHIR -->
<section class="cta-box">
  <div class="container">
    <h2 class="fw-bold mb-3">Siap Membawa Warung Anda ke Era Digital?</h2>
    <p class="mb-4">Daftar sekarang dan rasakan kemudahannya.</p>
    <a href="{{ route('register') }}" class="btn btn-light text-danger px-4 py-2 fw-bold">
      Daftar Gratis Sekarang
    </a>
  </div>
</section>

<!-- FOOTER -->
<footer class="text-center text-white py-4" style="background:#c0392b;">
  <p class="mb-1">&copy; {{ date('Y') }} WarungSmart. Semua Hak Dilindungi.</p>
  <small>
    <a href="#features" class="text-white">Fitur</a> |
    <a href="#benefits" class="text-white">Keunggulan</a> |
    <a href="#about" class="text-white">Tentang</a>
  </small>
</footer>

<!-- SCRIPTS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>AOS.init({ duration: 1000, once: true })</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script>
  gsap.from(".hero h1", {y: -40, opacity: 0, duration: 1});
  gsap.from(".hero p", {y: 20, opacity: 0, duration: 1, delay: .3});
  gsap.from(".hero-img", {x: 60, opacity: 0, duration: 1.2, delay: .5});
</script>
<script>
  const toggleDark = document.getElementById('toggleDark');
  const body = document.body;
  const icon = toggleDark.querySelector('i');

  // Load mode awal (jika sebelumnya pernah dipilih)
  if(localStorage.getItem('mode') === 'dark') {
    body.classList.add('dark-mode');
    body.classList.remove('light-mode');
    icon.classList.remove('fa-moon');
    icon.classList.add('fa-sun');
  }

  toggleDark.addEventListener('click', () => {
    body.classList.toggle('dark-mode');
    body.classList.toggle('light-mode');

    if(body.classList.contains('dark-mode')){
      icon.classList.remove('fa-moon');
      icon.classList.add('fa-sun');
      localStorage.setItem('mode', 'dark');
    } else {
      icon.classList.remove('fa-sun');
      icon.classList.add('fa-moon');
      localStorage.setItem('mode', 'light');
    }
  });
</script>


</body>
</html>
