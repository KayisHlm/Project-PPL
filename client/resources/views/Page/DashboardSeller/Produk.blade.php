@extends('Layout.Admin')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h4 class="header-title mb-0">Katalog Produk Saya</h4>
                </div>
                <div class="card-body">
                    <div class="card mb-3 bg-light bg-opacity-50 shadow-sm border-0">
                        <div class="card-body py-2">
                            <div class="d-flex flex-nowrap gap-2 align-items-center overflow-auto">
                                <div class="input-group w-auto" style="min-width:260px">
                                    <span class="input-group-text"><i class="ri-search-line"></i></span>
                                    <input type="text" class="form-control form-control-sm" id="seller-product-search" placeholder="Cari produk...">
                                </div>
                                <select class="form-select form-select-sm w-auto" id="seller-product-sort" style="min-width:160px">
                                    <option value="name">Nama</option>
                                    <option value="price">Harga</option>
                                    <option value="rating">Rating</option>
                                </select>
                                <select class="form-select form-select-sm w-auto" id="seller-product-filter-category" style="min-width:200px">
                                    <option value="">Semua Kategori</option>
                                    @php $cats = collect($products ?? [])->pluck('category')->unique()->values(); @endphp
                                    @foreach($cats as $cat)
                                        <option>{{ $cat }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row row-cols-xl-4 row-cols-lg-3 row-cols-md-2 row-cols-1 g-3" id="seller-product-grid">
                        @foreach(($products ?? []) as $p)
                        <div class="col" data-name="{{ $p['name'] }}" data-category="{{ $p['category'] }}" data-price="{{ $p['price'] }}" data-rating="{{ $p['average_rating'] ?? '0' }}">
                            <div class="card h-100 shadow-sm border-0" 
                                 style="transition:transform .2s, box-shadow .2s; cursor: pointer; background: white;" 
                                 onmouseenter="this.style.transform='translateY(-4px)';this.style.boxShadow='0 .5rem 1rem rgba(0,0,0,.15)';" 
                                 onmouseleave="this.style.transform='none';this.style.boxShadow='';">
                                @if(!empty($p['images']) && count($p['images']) > 0)
                                    <div style="height: 180px; display: flex; align-items: center; justify-content: center; overflow: hidden; border-radius: 0.375rem 0.375rem 0 0; background: white;">
                                        <img src="{{ $p['images'][0]['imageUrl'] }}" 
                                             alt="{{ $p['name'] }}" 
                                             onerror="this.onerror=null;this.src='{{ asset('assets/images/products/product-1.jpg') }}';" 
                                             style="max-width: 100%; max-height: 100%; width: auto; height: auto; object-fit: contain;">
                                    </div>
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" 
                                         style="height: 180px; border-radius: 0.375rem 0.375rem 0 0;">
                                        <i class="ri-image-line" style="font-size: 3rem; color: #ccc;"></i>
                                    </div>
                                @endif
                                <div class="card-body p-2">
                                    <h6 class="mb-1 text-truncate" style="font-size: 0.875rem;" title="{{ $p['name'] }}">{{ $p['name'] }}</h6>
                                    <p class="mb-1 fw-bold" style="font-size: 1rem; color: #000;">Rp{{ number_format($p['price'], 0, ',', '.') }}</p>
                                    <div class="d-flex align-items-center gap-1 mb-1">
                                        <i class="ri-star-fill text-warning" style="font-size: 0.75rem;"></i>
                                        <span style="font-size: 0.75rem;">{{ number_format($p['average_rating'] ?? 0, 1) }}</span>
                                        <span class="text-muted" style="font-size: 0.7rem;">({{ $p['review_count'] ?? 0 }})</span>
                                    </div>
                                    <p class="mb-2 text-muted text-truncate" style="font-size: 0.75rem;" title="{{ $p['shop_name'] ?? 'Toko Saya' }}">
                                        <i class="ri-store-2-line"></i> {{ $p['shop_name'] ?? 'Toko Saya' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @if(empty($products))
                        <div class="col">
                            <div class="alert alert-info">Belum ada produk. Tambahkan produk baru.</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
  var grid = document.getElementById('seller-product-grid');
  var search = document.getElementById('seller-product-search');
  var sortSel = document.getElementById('seller-product-sort');
  var filterCat = document.getElementById('seller-product-filter-category');
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
