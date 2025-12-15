<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>@yield('title') - Dashboard WarungSmart</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Roboto:wght@500;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css') }}" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    <style>
        .modal-backdrop {
            z-index: 1040 !important;
        }
        .modal {
            z-index: 1050 !important;
        }
        /* ===============================
   NOTIFICATION DROPDOWN FINAL
=============================== */
.notif-dropdown {
    width: 360px;
    max-width: 90vw;
    box-shadow: 0 10px 30px rgba(0,0,0,0.35);
    max-height: 60vh;
    overflow-y: auto;
}

/* Item notif */
.notif-item {
    white-space: normal;
    word-break: break-word;
    font-size: 14px;
    line-height: 1.5;
}

/* Timestamp */
.notif-item small {
    font-size: 12px;
    opacity: 0.7;
}

.sidebar {
  height: 100vh;
  overflow-y: auto; /* tetap bisa scroll */
}

/* sembunyikan scrollbar di browser berbasis WebKit */
.sidebar::-webkit-scrollbar {
  display: none;
}

/* untuk Firefox */
.sidebar {
  scrollbar-width: none;
}

/* Hilangkan scrollbar tapi tetap bisa scroll dengan mouse/trackpad */
.notif-dropdown {
  max-height: 300px;       /* batasi tinggi dropdown agar rapi */
  overflow-y: auto;        /* tetap bisa scroll */
  scrollbar-width: none;   /* Firefox */
}

.notif-dropdown::-webkit-scrollbar {
  display: none;           /* Chrome, Safari, Edge */
}


/* ===============================
   MOBILE (iPhone XR & sejenis)
=============================== */
@media (max-width: 576px) {
    .notif-dropdown {
        width: 90vw;
        max-width: 250px;
        right: 10px !important;
        left: auto !important;
    }
}

    </style>

    @stack('style')
</head>

<body>
    <div class="container-fluid position-relative d-flex p-0">

        <!-- Sidebar -->
        @include('partials.sidebar')

        <!-- Content -->
        <div class="content">
            @include('partials.navbar')


            <main class="py-4">
                @include('partials.alerts')
                @yield('content')
            </main>

            @include('partials.footer')
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('lib/chart/chart.min.js') }}"></script>
    <script src="{{ asset('lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('lib/tempusdominus/js/moment.min.js') }}"></script>
    <script src="{{ asset('lib/tempusdominus/js/moment-timezone.min.js') }}"></script>
    <script src="{{ asset('lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>

    <script>
function togglePassword(id, el) {
    const input = document.getElementById(id);
    const icon = el.querySelector('i');

    if (input.type === "password") {
        input.type = "text";
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = "password";
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>

    @stack('scripts')
</body>

</html>
