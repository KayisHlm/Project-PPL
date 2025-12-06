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
                                        <h5 class="text-muted fs-13 fw-bold text-uppercase">Total Produk</h5>
                                        <h3 class="my-2 py-1 fw-bold">{{ $totalProducts ?? 0 }}</h3>
                                        <p class="mb-0 text-muted"><span class="text-muted"><i class="ri-box-3-line"></i></span><span class="text-nowrap">Produk terdaftar</span></p>
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
                                        <h5 class="text-muted fs-13 fw-bold text-uppercase">Rata-rata Rating</h5>
                                        <h3 class="my-2 py-1 fw-bold">{{ number_format($averageRating ?? 0, 1) }}/5.0</h3>
                                        <p class="mb-0 text-muted"><span class="text-muted"><i class="ri-star-line"></i></span><span class="text-nowrap">Rating produk</span></p>
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
                                        <h5 class="text-muted fs-13 fw-bold text-uppercase">Total Review</h5>
                                        <h3 class="my-2 py-1 fw-bold">{{ $totalReviews ?? 0 }}</h3>
                                        <p class="mb-0 text-muted"><span class="text-muted"><i class="ri-chat-3-line"></i></span><span class="text-nowrap">Review diterima</span></p>
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

                <!-- Charts Section -->
                <div class="row mt-3">
                    <!-- Chart 1: Stock by Product -->
                    <div class="col-xl-6 col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="header-title mb-0">Sebaran Stok per Produk</h4>
                            </div>
                            <div class="card-body">
                                <div id="chart-stock-by-product" style="min-height: 300px;"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Chart 2: Rating by Product -->
                    <div class="col-xl-6 col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="header-title mb-0">Sebaran Rating per Produk</h4>
                            </div>
                            <div class="card-body">
                                <div id="chart-rating-by-product" style="min-height: 300px;"></div>
                            </div>
                        </div>
                    </div>
                </div><!-- end row -->

                <div class="row mt-3">
                    <!-- Chart 3: Reviewers by Province -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="header-title mb-0">Sebaran Pemberi Rating per Provinsi</h4>
                            </div>
                            <div class="card-body">
                                <div id="chart-reviewers-by-province" style="min-height: 300px;"></div>
                            </div>
                        </div>
                    </div>
                </div><!-- end row -->

            </div> <!-- container -->
        </div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
  // Chart 1: Stock by Product
  var stockByProductData = @json($stockByProduct ?? []);
  if (window.ApexCharts && stockByProductData.length > 0) {
    var productNames = stockByProductData.map(item => item.name);
    var stockValues = stockByProductData.map(item => parseInt(item.stock));
    
    new ApexCharts(document.querySelector('#chart-stock-by-product'), {
      chart: { type: 'bar', height: 300, toolbar: { show: false } },
      series: [{ name: 'Stok', data: stockValues }],
      xaxis: { 
        categories: productNames,
        labels: {
          rotate: -45,
          rotateAlways: true,
          style: { fontSize: '10px' }
        }
      },
      yaxis: { title: { text: 'Jumlah Stok' } },
      colors: ['#45bbe0'],
      plotOptions: {
        bar: {
          borderRadius: 4,
          dataLabels: { position: 'top' }
        }
      },
      dataLabels: {
        enabled: true,
        offsetY: -20,
        style: { fontSize: '12px', colors: ["#304758"] }
      }
    }).render();
  } else if(document.querySelector('#chart-stock-by-product')) {
    document.querySelector('#chart-stock-by-product').innerHTML = '<p class="text-center text-muted py-5">Belum ada data stok produk</p>';
  }

  // Chart 2: Rating by Product
  var ratingByProductData = @json($ratingByProduct ?? []);
  if (window.ApexCharts && ratingByProductData.length > 0) {
    var ratingProductNames = ratingByProductData.map(item => item.name);
    var ratingValues = ratingByProductData.map(item => parseFloat(item.rating));
    
    new ApexCharts(document.querySelector('#chart-rating-by-product'), {
      chart: { type: 'bar', height: 300, toolbar: { show: false } },
      series: [{ name: 'Rating', data: ratingValues }],
      xaxis: { 
        categories: ratingProductNames,
        labels: {
          rotate: -45,
          rotateAlways: true,
          style: { fontSize: '10px' }
        }
      },
      yaxis: { 
        title: { text: 'Rating' },
        min: 0,
        max: 5
      },
      colors: ['#777edd'],
      plotOptions: {
        bar: {
          borderRadius: 4,
          dataLabels: { position: 'top' }
        }
      },
      dataLabels: {
        enabled: true,
        formatter: function(val) { return val.toFixed(1); },
        offsetY: -20,
        style: { fontSize: '12px', colors: ["#304758"] }
      }
    }).render();
  } else if(document.querySelector('#chart-rating-by-product')) {
    document.querySelector('#chart-rating-by-product').innerHTML = '<p class="text-center text-muted py-5">Belum ada data rating produk</p>';
  }

  // Chart 3: Reviewers by Province
  var reviewersByProvinceData = @json($reviewersByProvince ?? []);
  if (window.ApexCharts && reviewersByProvinceData.length > 0) {
    var provinces = reviewersByProvinceData.map(item => item.province);
    var reviewerCounts = reviewersByProvinceData.map(item => parseInt(item.count));
    
    new ApexCharts(document.querySelector('#chart-reviewers-by-province'), {
      chart: { type: 'bar', height: 300, toolbar: { show: false } },
      series: [{ name: 'Jumlah Pemberi Rating', data: reviewerCounts }],
      xaxis: { 
        categories: provinces,
        labels: {
          rotate: -45,
          rotateAlways: true,
          style: { fontSize: '10px' }
        }
      },
      yaxis: { title: { text: 'Jumlah Reviewer' } },
      colors: ['#0acf97'],
      plotOptions: {
        bar: {
          borderRadius: 4,
          horizontal: false,
          columnWidth: '55%'
        }
      },
      dataLabels: {
        enabled: true,
        offsetY: -20,
        style: { fontSize: '12px', colors: ["#304758"] }
      }
    }).render();
  } else if(document.querySelector('#chart-reviewers-by-province')) {
    document.querySelector('#chart-reviewers-by-province').innerHTML = '<p class="text-center text-muted py-5">Belum ada data pemberi rating berdasarkan provinsi</p>';
  }
});
</script>
@endpush