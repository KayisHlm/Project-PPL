<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Log In | Platform E-Commerce</title>
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
                    <h4 class="fw-semibold mb-2 fs-18">Log in to your account</h4>

                    <p class="text-muted mb-4">Enter your email address and password to access admin panel.</p>

                    <!-- Alert untuk error dari session -->
                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            <i class="mdi mdi-alert-circle me-1"></i> {{ session('error') }}
                        </div>
                    @endif

                    <!-- Alert untuk success -->
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            <i class="mdi mdi-check-circle me-1"></i> {{ session('success') }}
                        </div>
                    @endif

                    <!-- Alert untuk validation errors -->
                    @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="post" action="{{ route('login.authenticate') }}" class="text-start mb-3">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label" for="email">Email</label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                class="form-control @error('email') is-invalid @enderror" 
                                placeholder="Enter your email"
                                value="{{ old('email') }}"
                                required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="password">Password</label>
                            <input 
                                type="password" 
                                id="password" 
                                name="password"
                                class="form-control @error('password') is-invalid @enderror" 
                                placeholder="Enter your password"
                                required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="remember-me" name="remember">
                                <label class="form-check-label" for="remember-me">Remember me</label>
                            </div>

                            <a href="#" class="text-muted border-bottom border-dashed">Forget Password</a>
                        </div>

                        <div class="d-grid">
                            <button class="btn btn-primary fw-semibold" type="submit">
                                Login
                            </button>
                        </div>
                    </form>

                    <p class="text-muted fs-14 mb-4">
                        Don't have an account? 
                        <a href="{{ route('register.registerIndex') }}" class="fw-semibold text-danger ms-1">Sign Up !</a>
                    </p>

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