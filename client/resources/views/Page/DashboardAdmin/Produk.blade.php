@extends('Layout.Admin')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h4 class="header-title mb-0">Katalog Produk</h4>
                </div>
                <div class="card-body">
                    <div class="card mb-3 bg-light bg-opacity-50 shadow-sm border-0">
                        <div class="card-body py-2">
                            <div class="d-flex flex-nowrap gap-2 align-items-center overflow-auto">
                                <div class="input-group w-auto" style="min-width:260px">
                                    <span class="input-group-text"><i class="ri-search-line"></i></span>
                                    <input type="text" class="form-control form-control-sm" id="admin-product-search" placeholder="Cari produk...">
                                </div>
                                <select class="form-select form-select-sm w-auto" id="admin-product-sort" style="min-width:160px">
                                    <option value="name">Nama</option>
                                    <option value="price">Harga</option>
                                    <option value="rating">Rating</option>
                                </select>
                                <select class="form-select form-select-sm w-auto" id="admin-product-filter-category" style="min-width:200px">
                                    <option value="">Semua Kategori</option>
                                    @if(!empty($products))
                                        @php 
                                            $cats = collect($products)->pluck('category')->filter()->unique()->sort()->values(); 
                                        @endphp
                                        @foreach($cats as $cat)
                                            <option value="{{ $cat }}">{{ $cat }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            @foreach($errors->all() as $error)
                                {{ $error }}
                            @endforeach
                        </div>
                    @endif

                    @if(empty($products))
                        <div class="text-center py-5">
                            <i class="ri-inbox-line" style="font-size: 4rem; color: #ccc;"></i>
                            <p class="text-muted mt-3">Belum ada produk yang terdaftar</p>
                        </div>
                    @else
                        <div class="row row-cols-xl-4 row-cols-lg-3 row-cols-md-2 row-cols-1 g-3" id="admin-product-grid">
                            @foreach($products as $p)
                            @php
                                // Get first image from images array
                                $imageUrl = null;
                                if (isset($p['images']) && is_array($p['images']) && count($p['images']) > 0) {
                                    $imageUrl = $p['images'][0]['imageUrl'] ?? null;
                                }
                                $defaultImage = asset('assets/images/products/product-1.jpg');
                            @endphp
                            <div class="col" data-name="{{ $p['name'] ?? '' }}" data-category="{{ $p['category'] ?? '' }}" data-price="{{ $p['price'] ?? 0 }}" data-rating="{{ $p['averageRating'] ?? $p['average_rating'] ?? 0 }}">
                                <div class="card h-100 shadow-sm border-0 position-relative" style="transition:transform .2s, box-shadow .2s" onmouseenter="this.style.transform='translateY(-4px)';this.style.boxShadow='0 .5rem 1rem rgba(0,0,0,.15)';" onmouseleave="this.style.transform='none';this.style.boxShadow='';">
                                    @php
                                        $avgRating = $p['averageRating'] ?? $p['average_rating'] ?? 0;
                                    @endphp
                                    @if($avgRating > 0)
                                        <span class="badge bg-primary-subtle text-primary position-absolute top-0 end-0 m-2">★ {{ number_format($avgRating, 1) }}</span>
                                    @endif
                                    <div style="height: 200px; display: flex; align-items: center; justify-content: center; overflow: hidden; background: white;">
                                        <img src="{{ $imageUrl ?? $defaultImage }}" alt="{{ $p['name'] ?? 'Produk' }}" style="max-width: 100%; max-height: 100%; width: auto; height: auto; object-fit: contain;" onerror="this.onerror=null;this.src='{{ $defaultImage }}';"></div>
                                    <div class="card-body">
                                        <h6 class="mb-1 text-truncate">{{ $p['name'] ?? 'Nama Produk' }}</h6>
                                        <p class="mb-1 text-muted small">{{ $p['category'] ?? 'Kategori' }}</p>
                                        @if(isset($p['shopName']) || isset($p['shop_name']))
                                            <p class="mb-2 text-muted small"><i class="ri-store-2-line"></i> {{ $p['shopName'] ?? $p['shop_name'] }}</p>
                                        @endif
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <p class="mb-0 fw-bold text-primary">Rp {{ number_format($p['price'] ?? 0, 0, ',', '.') }}</p>
                                            @if(isset($p['stock']))
                                                <small class="text-muted">Stok: {{ $p['stock'] }}</small>
                                            @endif
                                        </div>
                                        <div class="d-grid gap-2">
                                            <a href="{{ route('store.detail', ['id' => $p['id']]) }}" 
                                               class="btn btn-sm btn-primary" 
                                               style="font-size: 0.75rem;">
                                                <i class="ri-eye-line"></i> Lihat Detail
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
  var grid = document.getElementById('admin-product-grid');
  var search = document.getElementById('admin-product-search');
  var sortSel = document.getElementById('admin-product-sort');
  var filterCat = document.getElementById('admin-product-filter-category');
  if(!grid) return;
  function apply(){
    var q = (search && search.value || '').toLowerCase();
    var cat = (filterCat && filterCat.value) || '';
    var items = Array.from(grid.children);
    items.forEach(function(el){
      var name = (el.getAttribute('data-name')||'').toLowerCase();
      var category = el.getAttribute('data-category')||'';
      var show = name.indexOf(q) !== -1 && (cat === '' || category === cat);
      el.style.display = show ? '' : 'none';
    });
    var visible = items.filter(function(el){ return el.style.display !== 'none'; });
    var key = sortSel ? sortSel.value : 'name';
    visible.sort(function(a,b){
      if(key === 'name'){
        return a.getAttribute('data-name').localeCompare(b.getAttribute('data-name'));
      }
      if(key === 'price'){
        return parseInt(a.getAttribute('data-price')) - parseInt(b.getAttribute('data-price'));
      }
      if(key === 'rating'){
        return parseFloat(b.getAttribute('data-rating')) - parseFloat(a.getAttribute('data-rating'));
      }
      return 0;
    });
    visible.forEach(function(el){ grid.appendChild(el); });
  }
  if(search) search.addEventListener('input', apply);
  if(sortSel) sortSel.addEventListener('change', apply);
  if(filterCat) filterCat.addEventListener('change', apply);
  apply();
});
</script>
@endpush
