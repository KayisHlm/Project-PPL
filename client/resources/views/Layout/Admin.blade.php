<!DOCTYPE html>
<html lang="en" data-layout-mode="detached">


<!-- Mirrored from coderthemes.com/highdmin/layouts/layouts-detached.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 13 Nov 2025 11:06:09 GMT -->
<head>
    <meta charset="utf-8" />
    <title>Dashboard Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />

    <!-- Vector Maps css -->
    <link href="{{ asset('assets/vendor/jsvectormap/jsvectormap.min.css') }}" rel="stylesheet" type="text/css">

    <!-- Vendor css -->
    <link href="{{ asset('assets/css/vendor.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- App css -->
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />

    <!-- Icons css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Theme Config Js -->
    <script src="{{ asset('assets/js/config.js') }}"></script>
    <style>
        .app-topbar { top: 0; }
        .sidenav-menu { top: 0; }
        .page-content { min-height: calc(100vh - var(--highdmin-topbar-height)); }
    </style>
</head>

<body>
    <!-- Begin page -->
    <div class="wrapper">

        <!-- Menu -->
        <!-- Sidenav Menu Start -->
        <div class="sidenav-menu">

            <!-- Brand Logo -->
            <a href="#" class="logo">
                <span class="logo-light">
                    <span class="logo-lg"><img src="assets/images/logo.png" alt="logo"></span>
                    <span class="logo-sm"><img src="assets/images/logo-sm.png" alt="small logo"></span>
                </span>

                <span class="logo-dark">
                    <span class="logo-lg"><img src="assets/images/logo-dark.png" alt="dark logo"></span>
                    <span class="logo-sm"><img src="assets/images/logo-sm.png" alt="small logo"></span>
                </span>
            </a>

            <div data-simplebar>

                <!-- User -->
                <div class="sidenav-user">
                    <a href="{{ route('dashboard-admin.profile') }}" class="px-2 d-flex align-items-center justify-content-center text-reset text-decoration-none">
                        <img src="assets/images/users/avatar-1.jpg" width="42" class="rounded-circle me-2 d-flex" alt="user-image">
                        <span class="d-flex flex-column gap-1 sidebar-user-name">
                            <h4 class="my-0 fw-bold fs-15">Maxine Kennedy</h4>
                            <h6 class="my-0">Admin Head</h6>
                        </span>
                    </a>
                </div>

                <!--- Sidenav Menu -->
                <ul class="side-nav">
                    <li class="side-nav-title">Dashboard</li>

                    @php $role = (session('user_data')['role'] ?? null); @endphp
                    <li class="side-nav-item">
                        <a href="{{ route($role === 'seller' ? 'dashboard-seller.dashboard' : 'dashboard-admin.dashboard') }}" class="side-nav-link">
                            <span class="menu-icon"><i class="ri-dashboard-3-line"></i></span>
                            <span class="menu-text"> Dashboard </span>
                        </a>
                    </li>
                    @if ($role === 'platform_admin')
                    <li class="side-nav-item">
                        <a href="{{ route('dashboard-admin.kategori') }}" class="side-nav-link">
                            <span class="menu-icon"><i class="ri-price-tag-3-line"></i></span>
                            <span class="menu-text"> Kategori </span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('dashboard-admin.pending-sellers') }}" class="side-nav-link">
                            <span class="menu-icon"><i class="ri-user-search-line"></i></span>
                            <span class="menu-text"> Pending Sellers </span>
                        </a>
                    </li>
                    @endif

                    <li class="side-nav-item">
                        <a href="{{ route($role === 'seller' ? 'dashboard-seller.produk' : 'dashboard-admin.produk') }}" class="side-nav-link">
                            <span class="menu-icon"><i class="ri-shopping-bag-3-line"></i></span>
                            <span class="menu-text"> Katalog Produk </span>
                        </a>
                    </li>

                    @if ($role === 'platform_admin')
                    <li class="side-nav-item">
                        <a href="{{ route('dashboard-admin.tambah-kategori') }}" class="side-nav-link">
                            <span class="menu-icon"><i class="ri-add-circle-line"></i></span>
                            <span class="menu-text"> Tambah Kategori </span>
                        </a>
                    </li>
                    @endif

                    @if ($role === 'seller')
                    <li class="side-nav-item">
                        <a href="{{ route('dashboard-seller.tambah-produk') }}" class="side-nav-link">
                            <span class="menu-icon"><i class="ri-add-box-line"></i></span>
                            <span class="menu-text"> Tambah Produk </span>
                        </a>
                    </li>
                    @endif

                    <li class="side-nav-item">
                        <a href="{{ route($role === 'seller' ? 'dashboard-seller.laporan' : 'dashboard-admin.laporan') }}" class="side-nav-link">
                            <span class="menu-icon"><i class="ri-file-chart-line"></i></span>
                            <span class="menu-text"> Laporan </span>
                        </a>
                    </li>

                    <li class="side-nav-item mt-2 border-top pt-2">
                        <a href="#" class="side-nav-link" onclick="document.getElementById('logout-form').submit(); return false;">
                            <span class="menu-icon"><i class="ri-logout-box-line"></i></span>
                            <span class="menu-text"> Logout </span>
                        </a>
                        <form id="logout-form" action="{{ route('login.logout') }}" method="post" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>

  
                <div class="clearfix"></div>
            </div>
        </div>
        <!-- Sidenav Menu End -->

        
        <!-- Topbar Start -->
        <header class="app-topbar">
            <div class="page-container topbar-menu">
                <div class="d-flex align-items-center gap-2">
                    <!-- Topbar Page Title -->
                    <div class="topbar-item d-none d-md-flex">                        
                        <div>
                            @php $role = (session('user_data')['role'] ?? null); @endphp
                            <h4 class="page-title fs-18 fw-bold mb-0">Dashboard {{ $role === 'seller' ? 'Penjual' : 'Admin' }}</h4>                     
                        </div>          
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2">

                    <!-- Search for small devices -->
                    <div class="topbar-item d-flex d-xl-none">
                        <button class="topbar-link" data-bs-toggle="modal" data-bs-target="#searchModal" type="button">
                            <i class="ri-search-line fs-22"></i>
                        </button>
                    </div>

                    <!-- Button Trigger Search Modal -->
                    <div class="topbar-search d-none d-xl-flex gap-2 me-2 align-items-center" data-bs-toggle="modal"
                        data-bs-target="#searchModal" type="button">
                        <i class="ri-search-line fs-18"></i>
                        <span class="me-2">Search something..</span>
                    </div>

                    <!-- User Dropdown -->
                    <div class="topbar-item nav-user">
                        <div class="px-2 d-flex align-items-center">
                            <img src="assets/images/users/avatar-1.jpg" width="32" class="rounded-circle me-lg-2 d-flex" alt="user-image">
                            <span class="d-lg-flex flex-column gap-1 d-none">
                                <h5 class="my-0">Maxine K.</h5>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- Topbar End -->

        <!-- Search Modal -->
        <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content bg-transparent">
                    <form>
                        <div class="card mb-1">
                            <div class="px-3 py-2 d-flex flex-row align-items-center" id="top-search">
                                <i class="ri-search-line fs-22"></i>
                                <input type="search" class="form-control border-0" id="search-modal-input"
                                    placeholder="Search for actions, people,">
                                <button type="submit" class="btn p-0" data-bs-dismiss="modal" aria-label="Close">[esc]</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @yield('content')
    
    </div>
    <!-- END wrapper -->


    <!-- Vendor js -->
    <script src="{{ asset('assets/js/vendor.min.js') }}"></script>

    <!-- App js -->
    <script src="{{ asset('assets/js/app.js') }}"></script>

    <!-- Apex Chart js -->
    <script src="{{ asset('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>

    <!-- Projects Analytics Dashboard App js -->
    <script src="{{ asset('assets/js/pages/dashboard.js') }}"></script>

    @stack('scripts')

</body>


<!-- Mirrored from coderthemes.com/highdmin/layouts/layouts-detached.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 13 Nov 2025 11:06:09 GMT -->
</html>
