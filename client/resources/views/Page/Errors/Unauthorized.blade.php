<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>401 - Unauthorized Access</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Platform E-Commerce" name="description" />
    <meta content="PPL Team" name="author" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

    <!-- Vendor css -->
    <link href="{{ asset('assets/css/vendor.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- App css -->
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />

    <!-- Icons css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Theme Config Js -->
    <script src="{{ asset('assets/js/config.js') }}"></script>
</head>

<body>
    <div class="auth-bg d-flex min-vh-100 justify-content-center align-items-center">
        <div class="row g-0 justify-content-center w-100 m-xxl-5 px-xxl-4 m-3">
            <div class="col-xl-4 col-lg-5 col-md-6">
                <div class="card overflow-hidden text-center h-100 p-xxl-4 p-3 mb-0">
                    
                    <div class="mb-4">
                        <h1 class="display-1 fw-bold text-danger">401</h1>
                        <h4 class="fw-semibold mb-3">Unauthorized Access</h4>
                        <p class="text-muted mb-0">
                            Maaf, Anda tidak memiliki izin untuk mengakses halaman ini.
                        </p>
                    </div>

                    @if(session('error_details'))
                        <div class="alert alert-danger text-start" role="alert">
                            <i class="ri-error-warning-line me-1"></i>
                            {{ session('error_details') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Vendor js -->
    <script src="{{ asset('assets/js/vendor.min.js') }}"></script>

    <!-- App js -->
    <script src="{{ asset('assets/js/app.js') }}"></script>

</body>
</html>
