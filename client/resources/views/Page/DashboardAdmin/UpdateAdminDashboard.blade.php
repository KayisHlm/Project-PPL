{{-- filepath: /Users/indramec/GitHub/Project-PPL/client/resources/views/Page/DashboardAdmin/Dashboard.blade.php --}}
@extends('Layout.Admin')

@section('content')
<div class="page-content">
    <div class="page-container">

        <div class="row row-cols-xxl-4 row-cols-md-2 row-cols-1">
            <!-- Pending Verification Card -->
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2 justify-content-between">
                            <div>
                                <h5 class="text-muted fs-13 fw-bold text-uppercase">Pending Verification</h5>
                                <h3 class="my-2 py-1 fw-bold">{{ $pendingCount ?? 0 }}</h3>
                                <p class="mb-0">
                                    <a href="{{ route('admin.pending-sellers') }}" class="text-warning">
                                        <i class="ri-eye-line me-1"></i>View Details
                                    </a>
                                </p>
                            </div>
                            <div class="avatar-xl flex-shrink-0">
                                <span class="avatar-title bg-warning-subtle text-warning rounded-circle fs-42">
                                    <iconify-icon icon="solar:clock-circle-bold-duotone"></iconify-icon>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Other cards... -->
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2 justify-content-between">
                            <div>
                                <h5 class="text-muted fs-13 fw-bold text-uppercase">Total Sellers</h5>
                                <h3 class="my-2 py-1 fw-bold" id="total-sellers">--</h3>
                                <p class="mb-0 text-muted">
                                    <span class="text-success me-1"><i class="ri-arrow-up-line"></i></span>
                                    Active sellers
                                </p>
                            </div>
                            <div class="avatar-xl flex-shrink-0">
                                <span class="avatar-title bg-primary-subtle text-primary rounded-circle fs-42">
                                    <iconify-icon icon="solar:shop-bold-duotone"></iconify-icon>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2 justify-content-between">
                            <div>
                                <h5 class="text-muted fs-13 fw-bold text-uppercase">Total Products</h5>
                                <h3 class="my-2 py-1 fw-bold">1.2k</h3>
                                <p class="mb-0 text-muted">
                                    <span class="text-success me-1"><i class="ri-arrow-up-line"></i> 5.2%</span>
                                    This month
                                </p>
                            </div>
                            <div class="avatar-xl flex-shrink-0">
                                <span class="avatar-title bg-info-subtle text-info rounded-circle fs-42">
                                    <iconify-icon icon="solar:box-bold-duotone"></iconify-icon>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2 justify-content-between">
                            <div>
                                <h5 class="text-muted fs-13 fw-bold text-uppercase">Total Users</h5>
                                <h3 class="my-2 py-1 fw-bold">3.5k</h3>
                                <p class="mb-0 text-muted">
                                    <span class="text-success me-1"><i class="ri-arrow-up-line"></i> 8.1%</span>
                                    This month
                                </p>
                            </div>
                            <div class="avatar-xl flex-shrink-0">
                                <span class="avatar-title bg-success-subtle text-success rounded-circle fs-42">
                                    <iconify-icon icon="solar:users-group-rounded-bold-duotone"></iconify-icon>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="header-title">Quick Actions</h4>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <a href="{{ route('admin.pending-sellers') }}" class="btn btn-warning w-100">
                                    <i class="ri-user-follow-line me-1"></i>
                                    Verify Sellers ({{ $pendingCount }})
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="#" class="btn btn-primary w-100">
                                    <i class="ri-store-2-line me-1"></i>
                                    Manage Sellers
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="#" class="btn btn-info w-100">
                                    <i class="ri-box-3-line me-1"></i>
                                    Manage Products
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="#" class="btn btn-success w-100">
                                    <i class="ri-user-3-line me-1"></i>
                                    Manage Users
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fetch statistics if needed
    console.log('Admin Dashboard loaded');
});
</script>
@endpush