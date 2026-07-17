<!DOCTYPE html>
<html lang="fr" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Administration de KaMa Online Library">
    <title>@yield('title', 'Administration') — KaMa</title>

    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Poppins:wght@500;600;700&display=swap">
    <link rel="stylesheet" href="{{ asset('assets/vendor/font-awesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/overlay-scrollbar/css/overlayscrollbars.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/apexcharts/css/apexcharts.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/tiny-slider/tiny-slider.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/glightbox/css/glightbox.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/flatpickr/css/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/choices/css/choices.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/stepper/css/bs-stepper.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/quill/css/quill.snow.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/dropzone/css/dropzone.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    @stack('styles')
</head>
<body class="kama-admin-body">
    <div class="kama-admin-shell">
        @include('partials.admin.sidebar')
        <div class="kama-admin-sidebar-overlay" id="adminSidebarOverlay"></div>

        <div class="kama-admin-workspace">
            @include('partials.admin.header')

            <main class="kama-admin-main">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                    </div>
                @endif

                @yield('admin-content')
            </main>
        </div>
    </div>

    @include('partials.admin.scripts')
</body>
</html>
