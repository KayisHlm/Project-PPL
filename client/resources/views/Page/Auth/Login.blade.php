<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Log In | Platform E-Commerce</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Platform E-Commerce" name="description" />
    <meta content="PPL Team" name="author" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

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

                    <!-- Alert untuk error -->
                    <div id="error-alert" class="alert alert-danger d-none" role="alert"></div>

                    <!-- Alert untuk loading -->
                    <div id="loading-alert" class="alert alert-info d-none" role="alert">
                        <i class="mdi mdi-loading mdi-spin me-1"></i> Logging in...
                    </div>

                    <!-- Form Login -->
                    <form id="login-form" class="text-start mb-3">
                        <div class="mb-3">
                            <label class="form-label" for="email">Email</label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                class="form-control" 
                                placeholder="Enter your email"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="password">Password</label>
                            <input 
                                type="password" 
                                id="password" 
                                name="password"
                                class="form-control" 
                                placeholder="Enter your password"
                                required>
                        </div>

                        <div class="d-flex justify-content-between mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="remember-me">
                                <label class="form-check-label" for="remember-me">Remember me</label>
                            </div>

                            <a href="#" class="text-muted border-bottom border-dashed">Forget Password</a>
                        </div>

                        <div class="d-grid">
                            <button class="btn btn-primary fw-semibold" type="submit" id="login-btn">
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

    <!-- Login Handler Script -->
    <script>
        // API Base URL
        const API_URL = 'http://localhost:3001/api/auth';

        // Get form elements
        const loginForm = document.getElementById('login-form');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const loginBtn = document.getElementById('login-btn');
        const errorAlert = document.getElementById('error-alert');
        const loadingAlert = document.getElementById('loading-alert');

        // Handle form submit
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // Get input values
            const email = emailInput.value.trim();
            const password = passwordInput.value;

            // Reset alerts
            hideError();
            showLoading();

            // Disable button
            loginBtn.disabled = true;
            loginBtn.innerHTML = '<i class="mdi mdi-loading mdi-spin me-1"></i> Logging in...';

            // Send login request
            fetch(API_URL + '/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    email: email,
                    password: password
                })
            })
            .then(function(response) {
                return response.json().then(function(data) {
                    return {
                        status: response.status,
                        ok: response.ok,
                        data: data
                    };
                });
            })
            .then(function(result) {
                hideLoading();

                console.log('=== LOGIN RESPONSE ===');
                console.log('Status:', result.status);
                console.log('Data:', result.data);

                if (result.ok && result.data.code === 200) {
                    // Login berhasil!
                    console.log('Login successful!');
                    console.log('Token:', result.data.token);
                    console.log('User:', result.data.user);

                    // Simpan token ke localStorage
                    try {
                        localStorage.setItem('auth_token', result.data.token);
                        localStorage.setItem('user_data', JSON.stringify(result.data.user));
                        console.log('Token saved to localStorage');
                    } catch (error) {
                        console.error('Error saving to localStorage:', error);
                    }

                    // Show success message
                    showSuccess('Login successful! Redirecting...');

                    /* Redirect ke dashboard
                    setTimeout(function() {
                        window.location.href = '{{ route("dashboard-admin.dashboard") }}';
                    }, 1500); */

                } else {
                    // Login gagal - tampilkan error
                    console.log('Login failed:', result.data);
                    
                    var errorMessage = result.data.message || 'Login failed. Please try again.';
                    
                    // Custom error message berdasarkan code
                    if (result.data.code === 404) {
                        errorMessage = 'Email not found. Please check your email or sign up.';
                    } else if (result.data.code === 401) {
                        errorMessage = 'Incorrect password. Please try again.';
                    } else if (result.data.code === 400) {
                        errorMessage = 'Please fill in all fields correctly.';
                    }

                    showError(errorMessage);
                    resetButton();
                }
            })
            .catch(function(error) {
                console.error('Connection error:', error);
                hideLoading();
                showError('Connection error. Please check if the server is running.');
                resetButton();
            });
        });

        // Helper functions
        function showError(message) {
            errorAlert.textContent = message;
            errorAlert.classList.remove('d-none');
            errorAlert.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }

        function hideError() {
            errorAlert.classList.add('d-none');
        }

        function showLoading() {
            loadingAlert.classList.remove('d-none');
        }

        function hideLoading() {
            loadingAlert.classList.add('d-none');
        }

        function showSuccess(message) {
            hideError();
            var successAlert = document.createElement('div');
            successAlert.className = 'alert alert-success';
            successAlert.innerHTML = '<i class="mdi mdi-check-circle me-1"></i> ' + message;
            errorAlert.parentNode.insertBefore(successAlert, errorAlert);
        }

        function resetButton() {
            loginBtn.disabled = false;
            loginBtn.textContent = 'Login';
        }
    </script>

</body>

</html>