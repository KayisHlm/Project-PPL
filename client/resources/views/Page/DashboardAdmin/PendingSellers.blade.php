@extends('Layout.Admin')

@section('content')
<div class="page-content">
    <div class="page-container">
        
        {{-- TOTAL PENDING --}}
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">TOTAL PENDING VERIFICATION</h6>
                        <h2 class="mb-0">{{ $total ?? 0 }}</h2>
                        <small class="text-warning">
                            <i class="bi bi-clock"></i> Awaiting admin approval
                        </small>
                    </div>
                    <div class="bg-warning bg-opacity-10 rounded-circle p-4">
                        <i class="bi bi-people fs-1 text-warning"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- PENDING SELLERS LIST --}}
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Pending Sellers List</h5>
                <button class="btn btn-sm btn-outline-primary" onclick="location.reload()">
                    <i class="bi bi-arrow-clockwise"></i> Refresh
                </button>
            </div>
            <div class="card-body">
                
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    @foreach($errors->all() as $error)
                        {{ $error }}<br>
                    @endforeach
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover">
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
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sellers ?? [] as $seller)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">
                                            <span class="text-primary fw-bold">
                                                {{ strtoupper(substr($seller['shopName'] ?? 'N', 0, 1)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <strong>{{ $seller['shopName'] ?? 'N/A' }}</strong>
                                            @if(isset($seller['shopDescription']) && $seller['shopDescription'])
                                                <br><small class="text-muted">{{ Str::limit($seller['shopDescription'], 30) }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $seller['picName'] ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $seller['userEmail'] ?? 'N/A' }}</span>
                                    @if(isset($seller['picEmail']) && $seller['picEmail'] && $seller['picEmail'] !== $seller['userEmail'])
                                        <br><small class="text-muted">{{ $seller['picEmail'] }}</small>
                                    @endif
                                </td>
                                <td>{{ $seller['picPhone'] ?? 'N/A' }}</td>
                                <td>
                                    @if(isset($seller['picKtp']) && $seller['picKtp'])
                                        <a href="#" class="text-primary" data-bs-toggle="modal" data-bs-target="#detailModal{{ $seller['id'] }}">
                                            {{ $seller['picKtp'] }}
                                        </a>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if(isset($seller['createdAt']))
                                        {{ \Carbon\Carbon::parse($seller['createdAt'])->format('d M Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-clock"></i> Pending
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" 
                                                class="btn btn-sm btn-info" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#detailModal{{ $seller['id'] }}"
                                                title="View Details">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button type="button" 
                                                class="btn btn-sm btn-success" 
                                                onclick="confirmApprove({{ $seller['id'] }}, '{{ addslashes($seller['shopName'] ?? 'seller ini') }}')"
                                                title="Approve">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                        <button type="button" 
                                                class="btn btn-sm btn-danger" 
                                                onclick="confirmReject({{ $seller['id'] }}, '{{ addslashes($seller['shopName'] ?? 'seller ini') }}')"
                                                title="Reject">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </div>

                                    {{-- Hidden forms --}}
                                    <form id="approve-form-{{ $seller['id'] }}" 
                                        action="{{ route('dashboard-admin.sellers.approve', $seller['id']) }}" 
                                        method="POST" 
                                        style="display: none;">
                                        @csrf
                                    </form>

                                    <form id="reject-form-{{ $seller['id'] }}" 
                                        action="{{ route('dashboard-admin.sellers.reject', $seller['id']) }}" 
                                        method="POST" 
                                        style="display: none;">
                                        @csrf
                                    </form>
                                </td>
                            </tr>

                            {{-- Detail Modal --}}
                            <div class="modal fade" id="detailModal{{ $seller['id'] }}" tabindex="-1">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title">
                                                <i class="bi bi-shop"></i> Detail Verifikasi Seller
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            
                                            {{-- SHOP INFO --}}
                                            <div class="card mb-3">
                                                <div class="card-header bg-light">
                                                    <h6 class="mb-0"><i class="bi bi-shop"></i> Informasi Toko</h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <strong>Nama Toko:</strong>
                                                            <p class="mb-0">{{ $seller['shopName'] ?? 'N/A' }}</p>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <strong>Deskripsi:</strong>
                                                            <p class="mb-0">{{ $seller['shopDescription'] ?? '-' }}</p>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <strong>Status:</strong>
                                                            <p class="mb-0">
                                                                <span class="badge bg-warning">{{ $seller['status'] ?? 'N/A' }}</span>
                                                            </p>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <strong>Tanggal Daftar:</strong>
                                                            <p class="mb-0">
                                                                @if(isset($seller['createdAt']))
                                                                    {{ \Carbon\Carbon::parse($seller['createdAt'])->format('d M Y, H:i') }} WIB
                                                                @else
                                                                    -
                                                                @endif
                                                            </p>
                                                        </div>
                                                        @if(isset($seller['verifiedAt']) && $seller['verifiedAt'])
                                                        <div class="col-md-6 mb-3">
                                                            <strong>Tanggal Verifikasi:</strong>
                                                            <p class="mb-0">
                                                                {{ \Carbon\Carbon::parse($seller['verifiedAt'])->format('d M Y, H:i') }} WIB
                                                            </p>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- PIC INFO --}}
                                            <div class="card mb-3">
                                                <div class="card-header bg-light">
                                                    <h6 class="mb-0"><i class="bi bi-person"></i> Informasi Penanggung Jawab (PIC)</h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <strong>Nama Lengkap:</strong>
                                                            <p class="mb-0">{{ $seller['picName'] ?? 'N/A' }}</p>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <strong>No. KTP:</strong>
                                                            <p class="mb-0">{{ $seller['picKtp'] ?? 'N/A' }}</p>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <strong>Email Akun:</strong>
                                                            <p class="mb-0">
                                                                <span class="badge bg-info">{{ $seller['userEmail'] ?? 'N/A' }}</span>
                                                            </p>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <strong>Email PIC:</strong>
                                                            <p class="mb-0">{{ $seller['picEmail'] ?? '-' }}</p>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <strong>No. Telepon:</strong>
                                                            <p class="mb-0">{{ $seller['picPhone'] ?? 'N/A' }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- ADDRESS INFO --}}
                                            <div class="card mb-3">
                                                <div class="card-header bg-light">
                                                    <h6 class="mb-0"><i class="bi bi-geo-alt"></i> Alamat Lengkap</h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-12 mb-3">
                                                            <strong>Alamat:</strong>
                                                            <p class="mb-0">{{ $seller['picAddress'] ?? '-' }}</p>
                                                        </div>
                                                        <div class="col-md-3 mb-2">
                                                            <strong>RT:</strong>
                                                            <p class="mb-0">{{ $seller['picRt'] ?? '-' }}</p>
                                                        </div>
                                                        <div class="col-md-3 mb-2">
                                                            <strong>RW:</strong>
                                                            <p class="mb-0">{{ $seller['picRw'] ?? '-' }}</p>
                                                        </div>
                                                        <div class="col-md-6 mb-2">
                                                            <strong>Provinsi:</strong>
                                                            <p class="mb-0">{{ $seller['picProvince'] ?? '-' }}</p>
                                                        </div>
                                                        <div class="col-md-6 mb-2">
                                                            <strong>Kota/Kabupaten:</strong>
                                                            <p class="mb-0">{{ $seller['picCity'] ?? '-' }}</p>
                                                        </div>
                                                        <div class="col-md-6 mb-2">
                                                            <strong>Kecamatan:</strong>
                                                            <p class="mb-0">{{ $seller['picDistrict'] ?? '-' }}</p>
                                                        </div>
                                                        <div class="col-md-6 mb-2">
                                                            <strong>Kelurahan/Desa:</strong>
                                                            <p class="mb-0">{{ $seller['picVillage'] ?? '-' }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- DOCUMENTS --}}
                                            @if(isset($seller['picPhotoPath']) || isset($seller['picKtpPath']))
                                            <div class="card">
                                                <div class="card-header bg-light">
                                                    <h6 class="mb-0"><i class="bi bi-file-earmark-image"></i> Dokumen Pendukung</h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        @if(isset($seller['picPhotoPath']) && $seller['picPhotoPath'])
                                                        <div class="col-md-6 mb-3">
                                                            <strong>Foto PIC:</strong>
                                                            <div class="mt-2">
                                                                <img src="{{ env('BACKEND_URL', 'http://localhost:3001') }}/{{ $seller['picPhotoPath'] }}" 
                                                                     class="img-fluid rounded border" 
                                                                     alt="PIC Photo"
                                                                     style="max-height: 400px; object-fit: contain; width: 100%;">
                                                            </div>
                                                        </div>
                                                        @endif

                                                        @if(isset($seller['picKtpPath']) && $seller['picKtpPath'])
                                                        <div class="col-md-6 mb-3">
                                                            <strong>Foto KTP:</strong>
                                                            <div class="mt-2">
                                                                <img src="{{ env('BACKEND_URL', 'http://localhost:3001') }}/{{ $seller['picKtpPath'] }}" 
                                                                     class="img-fluid rounded border" 
                                                                     alt="KTP Photo"
                                                                     style="max-height: 400px; object-fit: contain; width: 100%;">
                                                            </div>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            @endif

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                <i class="bi bi-x-circle"></i> Tutup
                                            </button>
                                            <button type="button" 
                                                    class="btn btn-danger" 
                                                    onclick="confirmReject({{ $seller['id'] }}, '{{ addslashes($seller['shopName'] ?? 'seller ini') }}')">
                                                <i class="bi bi-x-lg"></i> Tolak
                                            </button>
                                            <button type="button" 
                                                    class="btn btn-success" 
                                                    onclick="confirmApprove({{ $seller['id'] }}, '{{ addslashes($seller['shopName'] ?? 'seller ini') }}')">
                                                <i class="bi bi-check-lg"></i> Setujui
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                                    <p class="text-muted">Tidak ada seller yang pending</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
console.log('Sellers data:', @json($sellers ?? []));

function confirmApprove(sellerId, shopName) {
    console.log('Approve clicked for seller ID:', sellerId);
    console.log('Form action:', document.getElementById('approve-form-' + sellerId).action);
    
    Swal.fire({
        title: 'Approve Seller?',
        text: `Apakah Anda yakin ingin menyetujui "${shopName}"?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Setujui!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('approve-form-' + sellerId).submit();
        }
    });
}

function confirmReject(sellerId, shopName) {
    console.log('Reject clicked for seller ID:', sellerId);
    console.log('Form action:', document.getElementById('reject-form-' + sellerId).action);
    
    Swal.fire({
        title: 'Reject Seller?',
        text: `Apakah Anda yakin ingin menolak "${shopName}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Tolak!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('reject-form-' + sellerId).submit();
        }
    });
}

// Auto-dismiss alerts
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        var alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            var bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});
</script>
@endpush