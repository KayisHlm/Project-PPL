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
                        <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#laporan-stok-produk">Stok per Produk (PDF)</a></li>
                        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#laporan-rating-produk">Rating per Produk (PDF)</a></li>
                        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#laporan-stok-tipis">Produk dengan Stok Tipis (PDF)</a></li>
                    </ul>
                    <div class="tab-content border border-light border-dashed p-3">
                        <div class="tab-pane fade show active" id="laporan-stok-produk">
                            <p class="mb-2">Unduh daftar stok produk diurutkan berdasarkan stok menurun. Termasuk rating, kategori produk, dan harga.</p>
                            <a href="javascript:void(0);" class="btn btn-soft-primary btn-sm">Download PDF</a>
                        </div>
                        <div class="tab-pane fade" id="laporan-rating-produk">
                            <p class="mb-2">Unduh daftar produk diurutkan berdasarkan rating menurun. Termasuk stok, kategori produk, dan harga.</p>
                            <a href="javascript:void(0);" class="btn btn-soft-primary btn-sm">Download PDF</a>
                        </div>
                        <div class="tab-pane fade" id="laporan-stok-tipis">
                            <p class="mb-2">Unduh daftar produk yang harus segera dipesan (stok kurang dari 2).</p>
                            <a href="javascript:void(0);" class="btn btn-soft-primary btn-sm">Download PDF</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection