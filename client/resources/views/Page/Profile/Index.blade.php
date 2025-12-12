@extends('Layout.Admin')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="row g-3">
                <div class="col-xl-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-header d-flex align-items-center gap-2">
                            <span class="avatar-title bg-primary-subtle text-primary rounded-circle" style="width:38px;height:38px"><i class="ri-user-settings-line"></i></span>
                            <h4 class="header-title mb-0">Profil</h4>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-3">
                                <div class="avatar-xxl mx-auto position-relative">
                                    <img src="{{ ($seller['pic_photo_path'] ?? null) ?: asset('assets/images/users/avatar-1.jpg') }}" class="rounded-circle" style="width:120px;height:120px;object-fit:cover" alt="avatar">
                                </div>
                            </div>
                            <div class="mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="ri-shield-user-line"></i>
                                    <span class="fw-semibold">{{ $user['name'] ?? 'Nama Lengkap' }}</span>
                                </div>
                            </div>
                            <div class="mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="ri-mail-line"></i>
                                    <span>{{ $user['email'] ?? 'email@example.com' }}</span>
                                </div>
                            </div>
                            <div class="mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="ri-phone-line"></i>
                                    <span>{{ $user['phone'] ?? '08xxxxxxxxxx' }}</span>
                                </div>
                            </div>
                            <div class="mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="ri-award-line"></i>
                                    <span>{{ ucfirst(str_replace('_', ' ', $user['role'] ?? 'User')) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-8">
                    <div class="card shadow-sm border-0">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h4 class="header-title mb-0">Informasi Toko</h4>
                            <span class="badge bg-light text-body">{{ $seller ? 'Seller ID: '.$seller['seller_id'] : '' }}</span>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="ri-store-2-line"></i>
                                        <span class="fw-semibold">{{ $seller['shop_name'] ?? 'Nama Toko' }}</span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="d-flex align-items-start gap-2">
                                        <i class="ri-file-text-line mt-1"></i>
                                        <span>{{ $seller['shop_description'] ?? 'Deskripsi toko belum tersedia' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="ri-phone-line"></i>
                                        <span>No. Toko: {{ $seller['shop_phone'] ?? '-' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="ri-at-line"></i>
                                        <span>Email PIC: {{ $seller['pic_email'] ?? '-' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="ri-map-pin-line"></i>
                                        <span>Alamat: {{ $seller['shop_address'] ?? '-' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="ri-hashtag"></i>
                                        <span>RT/RW: {{ ($seller['pic_rt'] ?? '-') }}/{{ ($seller['pic_rw'] ?? '-') }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="ri-community-line"></i>
                                        <span>Kelurahan: {{ $seller['village'] ?? '-' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="ri-government-line"></i>
                                        <span>Kecamatan: {{ $seller['district'] ?? '-' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="ri-building-2-line"></i>
                                        <span>Kota/Kab: {{ $seller['city'] ?? '-' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="ri-flag-line"></i>
                                        <span>Provinsi: {{ $seller['province'] ?? '-' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="ri-id-card-line"></i>
                                        <span>No KTP PIC: {{ $seller['pic_ktp_number'] ?? '-' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="ri-check-double-line"></i>
                                        <span>Status: {{ ucfirst(str_replace('_', ' ', $seller['status'] ?? 'unknown')) }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="ri-calendar-check-line"></i>
                                        <span>Verified At: {{ $seller['verified_at'] ?? '-' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="ri-time-line"></i>
                                        <span>Dibuat: {{ $seller['created_at'] ?? '-' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="ri-time-fill"></i>
                                        <span>Diperbarui: {{ $seller['updated_at'] ?? '-' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="ri-image-line"></i>
                                        <span>Foto PIC: 
                                            @if(!empty($seller['pic_photo_path']))
                                                <a href="{{ $seller['pic_photo_path'] }}" target="_blank">Lihat</a>
                                            @else
                                                -
                                            @endif
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="ri-file-paper-2-line"></i>
                                        <span>File KTP: 
                                            @if(!empty($seller['pic_ktp_path']))
                                                <a href="{{ $seller['pic_ktp_path'] }}" target="_blank">Lihat</a>
                                            @else
                                                -
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
