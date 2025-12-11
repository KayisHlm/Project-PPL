@extends('Layout.Admin')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title mb-0">Laporan</h4>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#laporan-penjual-status">Penjual Aktif/Tidak Aktif (PDF)</a></li>
                        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#laporan-toko-provinsi">Toko per Provinsi (PDF)</a></li>
                        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#laporan-produk-rating">Produk & Rating (PDF)</a></li>
                    </ul>
                    <div class="tab-content border border-light border-dashed p-3">
                        <div class="tab-pane fade show active" id="laporan-penjual-status">
                            <p class="mb-2">Unduh daftar akun penjual aktif dan tidak aktif.</p>
                            <a href="{{ route('dashboard-admin.pdf-akun') }}" class="btn btn-soft-primary btn-sm">Download PDF</a>
                        </div>
                        <div class="tab-pane fade" id="laporan-toko-provinsi">
                            <p class="mb-2">Unduh daftar penjual (toko) per provinsi.</p>
                            <a href="{{ route('dashboard-admin.pdf-seller-province') }}" class="btn btn-soft-primary btn-sm">Download PDF</a>
                        </div>
                        <div class="tab-pane fade" id="laporan-produk-rating">
                            <p class="mb-2">Unduh daftar produk beserta rating, nama toko, kategori, harga, dan provinsi.</p>
                            <a href="javascript:void(0);" class="btn btn-soft-primary btn-sm">Download PDF</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection