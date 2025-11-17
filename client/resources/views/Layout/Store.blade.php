<!DOCTYPE html>
<html lang="en" data-layout-mode="detached">
<head>
    <meta charset="utf-8" />
    <title>MartPlace</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Public Storefront" name="description" />
    <meta content="MartPlace" name="author" />
    <link href="{{ asset('assets/css/vendor.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset('assets/js/config.js') }}"></script>
    <style>
        .store-topbar { position: sticky; top: 0; z-index: 1020; backdrop-filter: saturate(180%) blur(6px); }
        .store-brand { letter-spacing:.5px }
        .store-container { max-width: 1200px; margin: 0 auto; }
    </style>
    </head>
<body>
    <header class="store-topbar bg-white shadow-sm">
        <div class="store-container d-flex align-items-center justify-content-between py-2 px-3">
            <a href="{{ route('store.landing') }}" class="d-flex align-items-center gap-2 text-reset text-decoration-none">
                <i class="ri-store-2-line fs-24 text-primary"></i>
                <span class="fw-bold store-brand">MartPlace</span>
            </a>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('login') }}" class="btn btn-sm btn-light">Login</a>
            </div>
        </div>
    </header>

    <main class="py-3">
        <div class="store-container">
            @yield('content')
        </div>
    </main>

    <script src="{{ asset('assets/js/vendor.min.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>