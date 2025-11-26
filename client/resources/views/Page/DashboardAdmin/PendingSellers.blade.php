@extends('Layout.Admin')

@section('content')
<div class="page-content">
    <div class="page-container">

        <!-- Page Title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard-admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Pending Sellers</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Pending Sellers Verification</h4>
                </div>
            </div>
        </div>

        <!-- Statistics Card -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2 justify-content-between">
                            <div>
                                <h5 class="text-muted fs-13 fw-bold text-uppercase">Total Pending Verification</h5>
                                <h3 class="my-2 py-1 fw-bold">{{ $total ?? 0 }}</h3>
                                <p class="mb-0 text-muted">
                                    <span class="text-warning me-1"><i class="ri-time-line"></i></span>
                                    <span class="text-nowrap">Awaiting admin approval</span>
                                </p>
                            </div>
                            <div class="avatar-xl flex-shrink-0">
                                <span class="avatar-title bg-warning-subtle text-warning rounded-circle fs-42">
                                    <iconify-icon icon="solar:users-group-two-rounded-bold-duotone"></iconify-icon>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Messages -->
        @if(session('success'))
        <div class="row">
            <div class="col-12">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="ri-checkbox-circle-line me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
        @endif

        @if($errors->any())
        <div class="row">
            <div class="col-12">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="ri-error-warning-line me-2"></i>
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
        @endif

        <!-- Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="header-title">Pending Sellers List</h4>
                            <button type="button" class="btn btn-sm btn-light" onclick="location.reload()">
                                <i class="ri-refresh-line me-1"></i> Refresh
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(isset($sellers) && count($sellers) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-striped table-centered mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Shop Name</th>
                                        <th>PIC Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>KTP</th>
                                        <th>Register Date</th>
                                        <th>Status</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sellers as $index => $seller)
                                    <tr>
                                        <td class="fw-semibold">{{ $index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-2">
                                                    <span class="avatar-title bg-primary-subtle text-primary rounded-circle fs-16">
                                                        {{ strtoupper(substr($seller['shop_name'], 0, 2)) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <h5 class="mb-0 fs-14 fw-semibold">{{ $seller['shop_name'] }}</h5>
                                                    @if(isset($seller['shop_description']) && $seller['shop_description'])
                                                    <p class="mb-0 text-muted fs-12">{{ Str::limit($seller['shop_description'], 40) }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $seller['pic_name'] }}</td>
                                        <td>
                                            <small class="d-block">{{ $seller['email'] }}</small>
                                            <small class="text-muted">{{ $seller['pic_email'] }}</small>
                                        </td>
                                        <td>{{ $seller['pic_phone_number'] }}</td>
                                        <td><code class="fs-12">{{ $seller['pic_ktp_number'] }}</code></td>
                                        <td>
                                            <small class="d-block">{{ \Carbon\Carbon::parse($seller['created_at'])->format('d M Y') }}</small>
                                            <small class="text-muted">{{ \Carbon\Carbon::parse($seller['created_at'])->format('H:i') }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-warning-subtle text-warning">
                                                <i class="ri-time-line me-1"></i>{{ ucfirst($seller['status']) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <button type="button" 
                                                        class="btn btn-sm btn-soft-primary" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#detailModal{{ $seller['id'] }}"
                                                        title="View Details">
                                                    <i class="ri-eye-line"></i>
                                                </button>
                                                
                                                <button type="button" 
                                                        class="btn btn-sm btn-soft-success" 
                                                        onclick="confirmApprove({{ $seller['id'] }}, '{{ addslashes($seller['shop_name']) }}')"
                                                        title="Approve">
                                                    <i class="ri-check-line"></i>
                                                </button>
                                                
                                                <button type="button" 
                                                        class="btn btn-sm btn-soft-danger" 
                                                        onclick="confirmReject({{ $seller['id'] }}, '{{ addslashes($seller['shop_name']) }}')"
                                                        title="Reject">
                                                    <i class="ri-close-line"></i>
                                                </button>
                                            </div>

                                            <form id="approve-form-{{ $seller['id'] }}" 
                                                  action="{{ route('admin.sellers.approve', $seller['id']) }}" 
                                                  method="POST" 
                                                  style="display: none;">
                                                @csrf
                                            </form>

                                            <form id="reject-form-{{ $seller['id'] }}" 
                                                  action="{{ route('admin.sellers.reject', $seller['id']) }}" 
                                                  method="POST" 
                                                  style="display: none;">
                                                @csrf
                                            </form>
                                        </td>
                                    </tr>

                                    <!-- Detail Modal -->
                                    <div class="modal fade" id="detailModal{{ $seller['id'] }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header bg-light">
                                                    <h5 class="modal-title">
                                                        <i class="ri-store-2-line me-2"></i>
                                                        Seller Details - {{ $seller['shop_name'] }}
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <div class="card border">
                                                                <div class="card-body">
                                                                    <h6 class="text-muted text-uppercase mb-3">
                                                                        <i class="ri-store-line me-1"></i>Shop Information
                                                                    </h6>
                                                                    <table class="table table-sm table-borderless mb-0">
                                                                        <tr>
                                                                            <td class="fw-semibold" width="40%">Shop Name:</td>
                                                                            <td>{{ $seller['shop_name'] }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="fw-semibold">Description:</td>
                                                                            <td>{{ $seller['shop_description'] ?? '-' }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="fw-semibold">Status:</td>
                                                                            <td>
                                                                                <span class="badge bg-warning">{{ ucfirst($seller['status']) }}</span>
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-6">
                                                            <div class="card border">
                                                                <div class="card-body">
                                                                    <h6 class="text-muted text-uppercase mb-3">
                                                                        <i class="ri-user-line me-1"></i>PIC Information
                                                                    </h6>
                                                                    <table class="table table-sm table-borderless mb-0">
                                                                        <tr>
                                                                            <td class="fw-semibold" width="40%">Name:</td>
                                                                            <td>{{ $seller['pic_name'] }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="fw-semibold">Phone:</td>
                                                                            <td>{{ $seller['pic_phone_number'] }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="fw-semibold">Email:</td>
                                                                            <td>{{ $seller['pic_email'] }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="fw-semibold">KTP:</td>
                                                                            <td><code>{{ $seller['pic_ktp_number'] }}</code></td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="row g-3 mt-1">
                                                        <div class="col-12">
                                                            <div class="card border">
                                                                <div class="card-body">
                                                                    <h6 class="text-muted text-uppercase mb-3">
                                                                        <i class="ri-map-pin-line me-1"></i>Address Information
                                                                    </h6>
                                                                    <table class="table table-sm table-borderless mb-0">
                                                                        <tr>
                                                                            <td class="fw-semibold" width="20%">Full Address:</td>
                                                                            <td>{{ $seller['pic_address'] }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="fw-semibold">RT / RW:</td>
                                                                            <td>{{ $seller['pic_rt'] }} / {{ $seller['pic_rw'] }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="fw-semibold">Village:</td>
                                                                            <td>{{ $seller['pic_village'] }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="fw-semibold">District:</td>
                                                                            <td>{{ $seller['pic_district'] }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="fw-semibold">City:</td>
                                                                            <td>{{ $seller['pic_city'] }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="fw-semibold">Province:</td>
                                                                            <td>{{ $seller['pic_province'] }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="fw-semibold">Registered:</td>
                                                                            <td>{{ \Carbon\Carbon::parse($seller['created_at'])->format('d F Y, H:i') }}</td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                                        <i class="ri-close-line me-1"></i>Close
                                                    </button>
                                                    <button type="button" class="btn btn-success" onclick="confirmApprove({{ $seller['id'] }}, '{{ addslashes($seller['shop_name']) }}')">
                                                        <i class="ri-check-line me-1"></i> Approve
                                                    </button>
                                                    <button type="button" class="btn btn-danger" onclick="confirmReject({{ $seller['id'] }}, '{{ addslashes($seller['shop_name']) }}')">
                                                        <i class="ri-close-line me-1"></i> Reject
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-5">
                            <div class="avatar-xl mx-auto mb-3">
                                <span class="avatar-title bg-light text-muted rounded-circle fs-42">
                                    <iconify-icon icon="solar:inbox-line-bold-duotone"></iconify-icon>
                                </span>
                            </div>
                            <h5 class="text-muted">No Pending Sellers</h5>
                            <p class="text-muted">All sellers have been verified or no registration yet.</p>
                            <a href="{{ route('dashboard-admin.dashboard') }}" class="btn btn-primary">
                                <i class="ri-arrow-left-line me-1"></i> Back to Dashboard
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmApprove(sellerId, shopName) {
    Swal.fire({
        title: 'Approve Seller?',
        html: `Are you sure you want to approve <strong>${shopName}</strong>?<br><small class="text-muted">This seller will be able to start selling products.</small>`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#0acf97',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="ri-check-line me-1"></i> Yes, Approve',
        cancelButtonText: '<i class="ri-close-line me-1"></i> Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('approve-form-' + sellerId).submit();
        }
    });
}

function confirmReject(sellerId, shopName) {
    Swal.fire({
        title: 'Reject Seller?',
        html: `Are you sure you want to reject <strong>${shopName}</strong>?<br><small class="text-muted">This action cannot be undone.</small>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#fa5c7c',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="ri-close-line me-1"></i> Yes, Reject',
        cancelButtonText: '<i class="ri-close-line me-1"></i> Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('reject-form-' + sellerId).submit();
        }
    });
}

// Auto-dismiss alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
});
</script>
@endpush