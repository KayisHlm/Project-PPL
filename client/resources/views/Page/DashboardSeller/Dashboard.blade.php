@extends('Layout.Admin')

@section('content')
        <div class="page-content">
            <div class="page-container">
                <div class="row row-cols-xxl-3 row-cols-md-2 row-cols-1">
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center gap-2 justify-content-between">
                                    <div>
                                        <h5 class="text-muted fs-13 fw-bold text-uppercase">Stok per Produk</h5>
                                        <h3 class="my-2 py-1 fw-bold">8.2k</h3>
                                        <p class="mb-0 text-muted"><span class="text-success me-1"><i class="ri-arrow-left-up-box-line"></i> 3.10%</span><span class="text-nowrap">Sejak bulan lalu</span></p>
                                    </div>
                                    <div class="avatar-xl flex-shrink-0">
                                        <span class="avatar-title bg-primary-subtle text-primary rounded-circle fs-42">
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
                                        <h5 class="text-muted fs-13 fw-bold text-uppercase">Rating per Produk</h5>
                                        <h3 class="my-2 py-1 fw-bold">4.3</h3>
                                        <p class="mb-0 text-muted"><span class="text-success me-1"><i class="ri-arrow-left-up-box-line"></i> 1.25%</span><span class="text-nowrap">Sejak bulan lalu</span></p>
                                    </div>
                                    <div class="avatar-xl flex-shrink-0">
                                        <span class="avatar-title bg-warning-subtle text-warning rounded-circle fs-42">
                                            <iconify-icon icon="solar:star-bold-duotone"></iconify-icon>
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
                                        <h5 class="text-muted fs-13 fw-bold text-uppercase">Pemberi Rating per Provinsi</h5>
                                        <h3 class="my-2 py-1 fw-bold">895</h3>
                                        <p class="mb-0 text-muted"><span class="text-danger me-1"><i class="ri-arrow-left-down-box-line"></i> 0.85%</span><span class="text-nowrap">Sejak bulan lalu</span></p>
                                    </div>
                                    <div class="avatar-xl flex-shrink-0">
                                        <span class="avatar-title bg-info-subtle text-info rounded-circle fs-42">
                                            <iconify-icon icon="solar:map-point-bold-duotone"></iconify-icon>
                                        </span>
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
document.addEventListener('DOMContentLoaded', function(){
  if(window.ApexCharts){
    new ApexCharts(document.querySelector('#chart-stock-by-product'), { chart: { type: 'bar', height: 260 }, series: [{ name: 'Stok', data: [8,3,12,5] }], xaxis: { categories: ['Earphone X','Adaptor C','Kabel D','Case A'] }, colors: ['#45bbe0'] }).render();
    new ApexCharts(document.querySelector('#chart-rating-by-product'), { chart: { type: 'bar', height: 260 }, series: [{ name: 'Rating', data: [4.5,4.0,3.8,4.2] }], xaxis: { categories: ['Earphone X','Adaptor C','Kabel D','Case A'] }, colors: ['#777edd'] }).render();
    new ApexCharts(document.querySelector('#chart-raters-by-province'), { chart: { type: 'bar', height: 260 }, series: [{ name: 'Raters', data: [12,9,7,15,6] }], xaxis: { categories: ['Jabar','Jatim','DKI','Banten','DIY'] }, colors: ['#0acf97'] }).render();
  }
});
</script>
@endpush
