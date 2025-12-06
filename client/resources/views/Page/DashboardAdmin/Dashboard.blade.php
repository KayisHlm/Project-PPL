@extends('Layout.Admin')

@section('content')
        <div class="page-content">
            <div class="page-container">

                <div class="row row-cols-xxl-4 row-cols-md-2 row-cols-1">
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center gap-2 justify-content-between">
                                    <div>
                                        <h5 class="text-muted fs-13 fw-bold text-uppercase" title="Total Products">
                                            Total Produk</h5>
                                        <h3 class="my-2 py-1 fw-bold">{{ count($products ?? []) }}</h3>
                                        <p class="mb-0 text-muted">
                                            <span class="text-success me-1"><i class="ri-shopping-bag-3-line"></i></span>
                                            <span class="text-nowrap">Produk terdaftar</span>
                                        </p>
                                    </div>
                                    <div class="avatar-xl flex-shrink-0">
                                        <span class="avatar-title bg-primary-subtle text-primary rounded-circle fs-42">
                                            <iconify-icon icon="solar:bag-check-bold-duotone"></iconify-icon>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- end col -->

                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center gap-2 justify-content-between">
                                    <div>
                                        <h5 class="text-muted fs-13 fw-bold text-uppercase" title="Approved Sellers">Toko Disetujui</h5>
                                        <h3 class="my-2 py-1 fw-bold">{{ $approvedCount ?? 0 }}</h3>
                                        <p class="mb-0 text-muted">
                                            <span class="text-success me-1"><i class="ri-store-2-line"></i></span>
                                            <span class="text-nowrap">Seller aktif</span>
                                        </p>
                                    </div>
                                    <div class="avatar-xl flex-shrink-0">
                                        <span class="avatar-title bg-success-subtle text-success rounded-circle fs-42">
                                            <iconify-icon icon="solar:shop-bold-duotone"></iconify-icon>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- end col -->

                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center gap-2 justify-content-between">
                                    <div>
                                        <h5 class="text-muted fs-13 fw-bold text-uppercase" title="Pending Sellers">Toko Belum Disetujui
                                        </h5>
                                        <h3 class="my-2 py-1 fw-bold">{{ $pendingCount ?? 0 }}</h3>
                                        <p class="mb-0 text-muted">
                                            <span class="text-warning me-1"><i class="ri-time-line"></i></span>
                                            <span class="text-nowrap">Menunggu persetujuan</span>
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
                    </div><!-- end col -->

                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center gap-2 justify-content-between">
                                    <div>
                                        <h5 class="text-muted fs-13 fw-bold text-uppercase"
                                            title="Average Rating">AVG Rating</h5>
                                        <h3 class="my-2 py-1 fw-bold">{{ number_format($avgRating ?? 0, 1) }}/5.0</h3>
                                        <p class="mb-0 text-muted">
                                            <span class="text-info me-1"><i class="ri-chat-3-line"></i></span>
                                            <span class="text-nowrap">{{ $totalReviews ?? 0 }} Review</span>
                                        </p>
                                    </div>
                                    <div class="avatar-xl flex-shrink-0">
                                        <span class="avatar-title bg-info-subtle text-info rounded-circle fs-42">
                                            <iconify-icon icon="solar:star-bold-duotone"></iconify-icon>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- end col -->
                </div><!-- end row -->

                <!-- Charts Section -->
                <div class="row mt-3">
                    <!-- Chart 1: Products by Category -->
                    <div class="col-xl-6 col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="header-title mb-0">Produk per Kategori</h4>
                            </div>
                            <div class="card-body">
                                <div id="products-by-category-chart" style="min-height: 300px;"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Chart 2: Shops by Province -->
                    <div class="col-xl-6 col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="header-title mb-0">Toko per Provinsi</h4>
                            </div>
                            <div class="card-body">
                                <div id="shops-by-province-chart" style="min-height: 300px;"></div>
                            </div>
                        </div>
                    </div>
                </div><!-- end row -->

                <div class="row mt-3">
                    <!-- Chart 3: Sellers Approval Status -->
                    <div class="col-xl-6 col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="header-title mb-0">Status Persetujuan Penjual</h4>
                            </div>
                            <div class="card-body">
                                <div id="sellers-active-status-chart" style="min-height: 300px;"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Chart 4: Reviews & Rating Distribution -->
                    <div class="col-xl-6 col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="header-title mb-0">Distribusi Rating</h4>
                            </div>
                            <div class="card-body">
                                <div id="reviews-rating-chart" style="min-height: 300px;"></div>
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
  // Chart 1: Products by Category (Donut Chart)
  var productsByCategoryData = @json($productsByCategory ?? []);
  if (productsByCategoryData.length > 0) {
    var categories = productsByCategoryData.map(item => item.category);
    var counts = productsByCategoryData.map(item => parseInt(item.count));
    
    var productsByCategoryOptions = {
      series: counts,
      chart: {
        type: 'donut',
        height: 300
      },
      labels: categories,
      colors: ['#3b7ddd', '#28a745', '#ffc107', '#dc3545', '#6c757d', '#17a2b8', '#fd7e14'],
      legend: {
        position: 'bottom'
      },
      dataLabels: {
        enabled: true,
        formatter: function (val) {
          return val.toFixed(1) + "%"
        }
      },
      responsive: [{
        breakpoint: 480,
        options: {
          chart: {
            height: 250
          },
          legend: {
            position: 'bottom'
          }
        }
      }]
    };
    
    var productsByCategoryChart = new ApexCharts(
      document.querySelector("#products-by-category-chart"), 
      productsByCategoryOptions
    );
    productsByCategoryChart.render();
  } else {
    document.querySelector("#products-by-category-chart").innerHTML = '<p class="text-center text-muted">Tidak ada data</p>';
  }

  // Chart 2: Shops by Province (Bar Chart)
  var shopsByProvinceData = @json($shopsByProvince ?? []);
  if (shopsByProvinceData.length > 0) {
    var provinces = shopsByProvinceData.map(item => item.province);
    var shopCounts = shopsByProvinceData.map(item => parseInt(item.count));
    
    var shopsByProvinceOptions = {
      series: [{
        name: 'Jumlah Toko',
        data: shopCounts
      }],
      chart: {
        type: 'bar',
        height: 300,
        toolbar: {
          show: false
        }
      },
      plotOptions: {
        bar: {
          horizontal: false,
          columnWidth: '55%',
          borderRadius: 4
        }
      },
      dataLabels: {
        enabled: false
      },
      colors: ['#28a745'],
      xaxis: {
        categories: provinces,
        labels: {
          rotate: -45,
          rotateAlways: true,
          style: {
            fontSize: '10px'
          }
        }
      },
      yaxis: {
        title: {
          text: 'Jumlah Toko'
        }
      },
      grid: {
        borderColor: '#f1f1f1'
      }
    };
    
    var shopsByProvinceChart = new ApexCharts(
      document.querySelector("#shops-by-province-chart"), 
      shopsByProvinceOptions
    );
    shopsByProvinceChart.render();
  } else {
    document.querySelector("#shops-by-province-chart").innerHTML = '<p class="text-center text-muted">Tidak ada data</p>';
  }

  // Chart 3: Sellers Approval Status (Donut Chart)
  var sellersActiveStatusData = @json($sellersActiveStatus ?? []);
  if (sellersActiveStatusData.length > 0) {
    var statuses = sellersActiveStatusData.map(item => {
      if (item.status === 'approved') return 'Disetujui';
      if (item.status === 'pending') return 'Belum Disetujui';
      return 'Ditolak';
    });
    var statusCounts = sellersActiveStatusData.map(item => parseInt(item.count));
    
    var sellersActiveStatusOptions = {
      series: statusCounts,
      chart: {
        type: 'donut',
        height: 300
      },
      labels: statuses,
      colors: ['#28a745', '#ffc107', '#dc3545'],
      legend: {
        position: 'bottom'
      },
      dataLabels: {
        enabled: true,
        formatter: function (val) {
          return val.toFixed(1) + "%"
        }
      }
    };
    
    var sellersActiveStatusChart = new ApexCharts(
      document.querySelector("#sellers-active-status-chart"), 
      sellersActiveStatusOptions
    );
    sellersActiveStatusChart.render();
  } else {
    document.querySelector("#sellers-active-status-chart").innerHTML = '<p class="text-center text-muted">Tidak ada data</p>';
  }

  // Chart 4: Reviews Rating Distribution (Bar Chart)
  var reviewsRatingStatsData = @json($reviewsRatingStats ?? []);
  if (reviewsRatingStatsData && reviewsRatingStatsData.total_reviews > 0) {
    var ratingLabels = ['1 Bintang', '2 Bintang', '3 Bintang', '4 Bintang', '5 Bintang'];
    var ratingCounts = [
      parseInt(reviewsRatingStatsData.one_star || 0),
      parseInt(reviewsRatingStatsData.two_star || 0),
      parseInt(reviewsRatingStatsData.three_star || 0),
      parseInt(reviewsRatingStatsData.four_star || 0),
      parseInt(reviewsRatingStatsData.five_star || 0)
    ];
    
    var reviewsRatingOptions = {
      series: [{
        name: 'Jumlah Review',
        data: ratingCounts
      }],
      chart: {
        type: 'bar',
        height: 300,
        toolbar: {
          show: false
        }
      },
      plotOptions: {
        bar: {
          horizontal: true,
          borderRadius: 4
        }
      },
      dataLabels: {
        enabled: true
      },
      colors: ['#ffc107'],
      xaxis: {
        categories: ratingLabels,
        title: {
          text: 'Jumlah Review'
        }
      },
      yaxis: {
        title: {
          text: 'Rating'
        }
      },
      grid: {
        borderColor: '#f1f1f1'
      }
    };
    
    var reviewsRatingChart = new ApexCharts(
      document.querySelector("#reviews-rating-chart"), 
      reviewsRatingOptions
    );
    reviewsRatingChart.render();
  } else {
    document.querySelector("#reviews-rating-chart").innerHTML = '<p class="text-center text-muted">Tidak ada data review</p>';
  }
});
</script>
@endpush
