<!DOCTYPE html>
<html lang="en">


<!-- Mirrored from coderthemes.com/highdmin/layouts/auth-register.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 13 Nov 2025 11:05:56 GMT -->
<head>
    <meta charset="utf-8" />
    <title>Sign Up | Highdmin - Responsive Bootstrap 5 Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

    <!-- Vendor css -->
    <link href="{{ asset('assets/css/vendor.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/dropzone/dropzone.css') }}" type="text/css" />

    <!-- App css -->
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />

    <!-- Icons css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Theme Config Js -->
    <script src="{{ asset('assets/js/config.js') }}"></script>
</head>

<body class="h-100">

    <div class="auth-bg d-flex min-vh-100 justify-content-center align-items-center">
        <div class="row g-0 justify-content-center w-100 m-xxl-5 px-xxl-4 m-3">
            <div class="col-xl-6 col-lg-7 col-md-8 col-sm-12">

                        <div class="card">
                            <div class="card-header d-flex align-items-center">
                                <h4 class="header-title">Registrasi</h4>
                            </div>

                            <div class="card-body pt-0 p-3">
                                <form id="registrationWizard" method="post" action="{{ route('register.submit') }}">
                                    @csrf
                                    <div id="validation-wizard">
                                        <ul class="nav nav-pills nav-justified form-wizard-header mb-3">
                                            <li class="nav-item" data-target-form="#credentialForm">
                                                <a href="#credential" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 py-3">
                                                    <i class="bi bi-key fs-18 align-middle me-1"></i>
                                                    <span class="d-none d-sm-inline">Credential</span>
                                                </a>
                                            </li>
                                            <li class="nav-item" data-target-form="#accountForm">
                                                <a href="#first" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 py-3">
                                                    <i class="bi bi-person-circle fs-18 align-middle me-1"></i>
                                                    <span class="d-none d-sm-inline">Profile</span>
                                                </a>
                                            </li>
                                            <li class="nav-item" data-target-form="#profileForm">
                                                <a href="#second" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 py-3">
                                                    <i class="bi bi-emoji-smile fs-18 align-middle me-1"></i>
                                                    <span class="d-none d-sm-inline">Address</span>
                                                </a>
                                            </li>
                                            <li class="nav-item" data-target-form="#otherForm">
                                                <a href="#third" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 py-3">
                                                    <i class="bi bi-check2-circle fs-18 align-middle me-1"></i>
                                                    <span class="d-none d-sm-inline">File Upload</span>
                                                </a>
                                            </li>
                                            <li class="nav-item" data-target-form="#finishForm">
                                                <a href="#finish" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 py-3">
                                                    <i class="bi bi-flag fs-18 align-middle me-1"></i>
                                                    <span class="d-none d-sm-inline">Finish</span>
                                                </a>
                                            </li>
                                        </ul>

                                        <div class="tab-content">

                                            <div class="tab-pane" id="credential">
                                                <div id="credentialForm" class="form-horizontal">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="alert alert-info py-2 px-3 mb-2 fs-14" role="alert">
                                                                Buat akun dengan email dan password yang aman.
                                                            </div>
                                                            <div class="row mb-2">
                                                                <label class="col-md-3 col-form-label fs-14" for="email">Email</label>
                                                                <div class="col-md-9">
                                                                    <input type="email" class="form-control form-control-sm" id="email" name="email" required maxlength="254" placeholder="contoh@email.com">
                                                                </div>
                                                            </div>
                                                            <div class="row mb-2">
                                                                <label class="col-md-3 col-form-label fs-14" for="password">Password</label>
                                                                <div class="col-md-9">
                                                                    <input type="password" class="form-control form-control-sm" id="password" name="password" required minlength="8" maxlength="255" placeholder="Minimal 8 karakter">
                                                                </div>
                                                            </div>
                                                            <div class="row mb-2">
                                                                <label class="col-md-3 col-form-label fs-14" for="password_confirmation">Konfirmasi Password</label>
                                                                <div class="col-md-9">
                                                                    <input type="password" class="form-control form-control-sm" id="password_confirmation" name="password_confirmation" required minlength="8" maxlength="255" placeholder="Ketik ulang password">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex justify-content-end mt-3">
                                                        <button type="button" class="btn btn-primary btn-sm" data-wizard-next="[href='#first']">Next <i class="bi bi-arrow-right ms-1"></i></button>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="tab-pane" id="first">
                                                <div id="accountForm" class="form-horizontal">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="alert alert-info py-2 px-3 mb-2 fs-14" role="alert">
                                                                Isi data profil toko dan PIC dengan lengkap dan benar.
                                                            </div>
                                                            <div class="row mb-2">
                                                                <label class="col-md-3 col-form-label fs-14" for="shopName">Nama Toko</label>
                                                                <div class="col-md-9">
                                                                    <input type="text" class="form-control form-control-sm" id="shopName" name="shopName" required maxlength="100" placeholder="Contoh: Toko Berkah Jaya">
                                                                </div>
                                                            </div>
                                                            <div class="row mb-2">
                                                                <label class="col-md-3 col-form-label fs-14" for="shopDescription">Deskripsi Singkat</label>
                                                                <div class="col-md-9">
                                                                    <textarea class="form-control form-control-sm" id="shopDescription" name="shopDescription" rows="3" required maxlength="300" placeholder="Contoh: Toko kelontong lengkap yang menyediakan berbagai kebutuhan sehari-hari dengan harga terjangkau"></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-2">
                                                                <label class="col-md-3 col-form-label fs-14" for="picName">Nama PIC</label>
                                                                <div class="col-md-9">
                                                                    <input type="text" class="form-control form-control-sm" id="picName" name="picName" required maxlength="100" placeholder="Contoh: Ahmad Suryadi">
                                                                </div>
                                                            </div>
                                                            <div class="row mb-2">
                                                                <label class="col-md-3 col-form-label fs-14" for="picPhoneNumber">No Handphone PIC</label>
                                                                <div class="col-md-9">
                                                                    <input type="tel" class="form-control form-control-sm" id="picPhoneNumber" name="picPhoneNumber" required inputmode="tel" pattern="^(\+62|0)\d{9,13}$" maxlength="16" placeholder="Contoh: 081234567890 atau +6281234567890">
                                                                </div>
                                                            </div>
                                                            <div class="row mb-2">
                                                                <label class="col-md-3 col-form-label fs-14" for="picEmail">Email PIC</label>
                                                                <div class="col-md-9">
                                                                    <input type="email" class="form-control form-control-sm" id="picEmail" name="picEmail" required maxlength="254" placeholder="Contoh: ahmad.suryadi@email.com">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex justify-content-between mt-3">
                                                        <button type="button" class="btn btn-secondary btn-sm" data-wizard-prev="[href='#credential']"><i class="bi bi-arrow-left me-1"></i> Previous</button>
                                                        <button type="button" class="btn btn-primary btn-sm" data-wizard-next="[href='#second']">Next <i class="bi bi-arrow-right ms-1"></i></button>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="tab-pane fade" id="second">
                                                <div id="profileForm" class="form-horizontal">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="alert alert-info py-2 px-3 mb-2 fs-14" role="alert">
                                                                Lengkapi alamat PIC sesuai KTP untuk verifikasi lokasi.
                                                            </div>
                                                            <div class="row mb-2">
                                                                <label class="col-md-3 col-form-label fs-14" for="picAddress">Alamat (nama jalan) PIC</label>
                                                                <div class="col-md-9">
                                                                    <input type="text" id="picAddress" name="picAddress" class="form-control form-control-sm" required maxlength="200" placeholder="Contoh: Jl. Merdeka Raya No. 123">
                                                                </div>
                                                            </div>
                                                            <div class="row mb-2">
                                                                <label class="col-md-3 col-form-label fs-14" for="picRt">RT</label>
                                                                <div class="col-md-9">
                                                                    <input type="text" id="picRt" name="picRt" class="form-control form-control-sm" required inputmode="numeric" pattern="^\d{1,3}$" maxlength="3" placeholder="Contoh: 001">
                                                                </div>
                                                            </div>
                                                            <div class="row mb-2">
                                                                <label class="col-md-3 col-form-label fs-14" for="picRw">RW</label>
                                                                <div class="col-md-9">
                                                                    <input type="text" id="picRw" name="picRw" class="form-control form-control-sm" required inputmode="numeric" pattern="^\d{1,3}$" maxlength="3" placeholder="Contoh: 005">
                                                                </div>
                                                            </div>
                                                            <div class="row mb-2">
                                                                <label class="col-md-3 col-form-label fs-14" for="picVillage">Nama Kelurahan</label>
                                                                <div class="col-md-9">
                                                                    <input type="text" id="picVillage" name="picVillage" class="form-control form-control-sm" required maxlength="100" placeholder="Contoh: Kebayoran Baru">
                                                                </div>
                                                            </div>
                                                            <div class="row mb-2">
                                                                <label class="col-md-3 col-form-label fs-14" for="picCity">Kabupaten/Kota</label>
                                                                <div class="col-md-9">
                                                                    <input type="text" id="picCity" name="picCity" class="form-control form-control-sm" required maxlength="100" placeholder="Contoh: Jakarta Selatan">
                                                                </div>
                                                            </div>
                                                            <div class="row mb-2">
                                                                <label class="col-md-3 col-form-label fs-14" for="picProvince">Provinsi</label>
                                                                <div class="col-md-9">
                                                                    <input type="text" id="picProvince" name="picProvince" class="form-control form-control-sm" required maxlength="100" placeholder="Contoh: DKI Jakarta">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex justify-content-between mt-3">
                                                        <button type="button" class="btn btn-secondary btn-sm" data-wizard-prev="[href='#first']"><i class="bi bi-arrow-left me-1"></i> Previous</button>
                                                        <button type="button" class="btn btn-primary btn-sm" data-wizard-next="[href='#third']">Next <i class="bi bi-arrow-right ms-1"></i></button>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="tab-pane fade" id="third">
                                                <div id="otherForm" class="form-horizontal">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="alert alert-info py-2 px-3 mb-2 fs-14" role="alert">
                                                                Unggah foto PIC dan KTP. Masing-masing maksimal 1 file.
                                                            </div>
                                                            <div class="row mb-2">
                                                                <label class="col-md-3 col-form-label fs-14" for="picKtpNumber">No KTP PIC</label>
                                                                <div class="col-md-9">
                                                                    <input type="text" class="form-control form-control-sm" id="picKtpNumber" name="picKtpNumber" required inputmode="numeric" pattern="^\d{16}$" maxlength="16" placeholder="16 digit NIK">
                                                                </div>
                                                            </div>
                                                            <div class="row mb-2">
                                                                <label class="col-md-3 col-form-label fs-14" for="picPhotoPath">Foto PIC</label>
                                                                <div class="col-md-9">
                                                                    <div class="dropzone p-2" id="dzFotoPic" data-plugin="dropzone" data-url="https://coderthemes.com/" data-previews-container="#file-previews-foto" data-upload-preview-template="#uploadPreviewTemplate" data-accepted-files="image/*" data-max-files="1" data-required-input="#picPhotoPath" style="min-height: 120px;">
                                                                        <div class="dz-message needsclick py-2 my-0">
                                                                            <i class="ri-upload-cloud-2-line fs-22 text-muted"></i>
                                                                            <span class="d-block fs-14 mb-0">Drop file foto di sini atau klik untuk upload.</span>
                                                                            <span class="text-muted fs-13">Hanya 1 gambar untuk foto PIC</span>
                                                                        </div>
                                                                    </div>
                                                                    <input type="text" class="form-control d-none" id="picPhotoPath" name="picPhotoPath" required>
                                                                    <div class="dropzone-previews mt-2" id="file-previews-foto"></div>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-2">
                                                                <label class="col-md-3 col-form-label fs-14" for="picKtpPath">File Upload KTP PIC</label>
                                                                <div class="col-md-9">
                                                                    <div class="dropzone p-2" id="dzKtpPic" data-plugin="dropzone" data-url="https://coderthemes.com/" data-previews-container="#file-previews-ktp" data-upload-preview-template="#uploadPreviewTemplate" data-accepted-files="image/*,application/pdf" data-max-files="1" data-required-input="#picKtpPath" style="min-height: 120px;">
                                                                        <div class="dz-message needsclick py-2 my-0">
                                                                            <i class="ri-upload-cloud-2-line fs-22 text-muted"></i>
                                                                            <span class="d-block fs-14 mb-0">Drop file KTP di sini atau klik untuk upload.</span>
                                                                            <span class="text-muted fs-13">Hanya 1 file (gambar atau PDF)</span>
                                                                        </div>
                                                                    </div>
                                                                    <input type="text" class="form-control d-none" id="picKtpPath" name="picKtpPath" required>
                                                                    <div class="dropzone-previews mt-2" id="file-previews-ktp"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex justify-content-between mt-3">
                                                        <button type="button" class="btn btn-secondary btn-sm" data-wizard-prev="[href='#second']"><i class="bi bi-arrow-left me-1"></i> Previous</button>
                                                        <button type="button" class="btn btn-primary btn-sm" data-wizard-next="[href='#finish']">Next <i class="bi bi-arrow-right ms-1"></i></button>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="tab-pane fade" id="finish">
                                                <div id="finishForm" class="form-horizontal">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="alert alert-info py-2 px-3 mb-2 fs-14" role="alert">
                                                                Tinjau ulang seluruh data sebelum submit akhir.
                                                            </div>
                                                            <div class="text-center mb-3">
                                                                <h4 class="mt-0">Validasi Akhir</h4>
                                                                <p class="mb-0 w-75 mx-auto">Silakan tinjau kembali semua data yang telah Anda isi pada langkah sebelumnya. Pastikan informasi sudah benar dan lengkap. Centang persetujuan di bawah ini sebelum melakukan submit.</p>
                                                            </div>
                                                            <div class="d-flex justify-content-center mb-3">
                                                                <div class="form-check d-inline-block text-center">
                                                                    <input type="checkbox" class="form-check-input fs-15" id="agree_final" name="agree_final" required>
                                                                    <label class="form-check-label" for="agree_final">Saya menyetujui bahwa data yang diisi sudah benar</label>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <button type="button" class="btn btn-secondary btn-sm" data-wizard-prev="[href='#third']"><i class="bi bi-arrow-left me-1"></i> Previous</button>
                                                                <button type="submit" class="btn btn-success btn-sm"><i class="bi bi-check-circle me-1"></i> Submit</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="d-none" id="uploadPreviewTemplate">
                                                <div class="card mt-1 mb-0 shadow-none border">
                                                    <div class="p-2">
                                                        <div class="row align-items-center">
                                                            <div class="col-auto">
                                                                <img data-dz-thumbnail src="#" class="avatar-sm rounded bg-light" alt="">
                                                            </div>
                                                            <div class="col ps-0">
                                                                <a class="text-muted fw-semibold" data-dz-name></a>
                                                                <p class="mb-0" data-dz-size></p>
                                                            </div>
                                                            <div class="col-auto">
                                                                <a href="#" class="btn btn-link btn-sm text-danger" data-dz-remove>
                                                                    Hapus
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="d-flex wizard justify-content-between align-items-center flex-wrap gap-2 mt-3">                    
                                                <div class="login ms-auto text-end">
                                                    <a href="{{ url('/login') }}" class="text-muted fs-14">Already have an account? <span class="text-primary">Login !</span></a>
                                                </div>
                                            </div>

                                        </div> <!-- tab-content -->
                                    </div> <!-- end #validation-wizard-->
                                </form>
                            </div>
                        </div>                
            </div>
        </div>
    </div>

    <!-- Vendor js -->
    <script src="{{ asset('assets/js/vendor.min.js') }}"></script>

    <!-- App js -->
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script src="{{ asset('assets/vendor/vanilla-wizard/js/wizard.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/dropzone/dropzone-min.js') }}"></script>
    <script src="{{ asset('assets/js/components/form-fileupload.js') }}"></script>

    <!-- Wizard Form Demo js -->
    <script src="{{ asset('assets/js/components/form-wizard.js') }}"></script>

    <script>
        // Custom navigation functions for wizard
        function goToTab(tabSelector) {
            const tabElement = document.querySelector(tabSelector);
            if (tabElement) {
                const bsTab = new bootstrap.Tab(tabElement);
                bsTab.show();
            }
        }

        // Add click handlers after DOM loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Override button onclick with proper function
            document.querySelectorAll('[data-wizard-next]').forEach(btn => {
                btn.addEventListener('click', function() {
                    const target = this.getAttribute('data-wizard-next');
                    goToTab(target);
                });
            });

            document.querySelectorAll('[data-wizard-prev]').forEach(btn => {
                btn.addEventListener('click', function() {
                    const target = this.getAttribute('data-wizard-prev');
                    goToTab(target);
                });
            });
        });
    </script>
</body>


<!-- Mirrored from coderthemes.com/highdmin/layouts/auth-register.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 13 Nov 2025 11:05:56 GMT -->
</html>