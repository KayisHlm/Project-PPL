@extends('Layout.Store')

@section('content')
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body py-2">
            <div class="d-flex flex-nowrap gap-2 align-items-center overflow-auto">
                <div class="input-group w-auto" style="min-width:280px">
                    <span class="input-group-text"><i class="ri-search-line"></i></span>
                    <input type="text" class="form-control form-control-sm" id="store-search" placeholder="Cari produk atau nama toko...">
                </div>
                <select class="form-select form-select-sm w-auto" id="store-filter-condition" style="min-width:140px">
                    <option value="">Semua Kondisi</option>
                    <option>Baru</option>
                    <option>Bekas</option>
                </select>
                <select class="form-select form-select-sm w-auto" id="store-filter-category" style="min-width:180px">
                    <option value="">Semua Kategori</option>
                    <option>Elektronik</option>
                    <option>Fashion</option>
                    <option>Makanan & Minuman</option>
                    <option>Aksesoris</option>
                    <option>Rumah Tangga</option>
                    <option>Olahraga</option>
                </select>
                <select class="form-select form-select-sm w-auto" id="store-filter-province" style="min-width:180px">
                    <option value="">Semua Provinsi</option>
                    <option>DKI Jakarta</option>
                    <option>Jawa Barat</option>
                    <option>Jawa Tengah</option>
                    <option>Jawa Timur</option>
                    <option>DI Yogyakarta</option>
                    <option>Banten</option>
                </select>
                <div class="input-group w-auto" style="min-width:200px">
                    <span class="input-group-text"><i class="ri-map-pin-line"></i></span>
                    <input type="text" class="form-control form-control-sm" id="store-filter-city" placeholder="Kota/Kabupaten">
                </div>
                <button type="button" class="btn btn-sm btn-light" id="store-filter-reset"><i class="ri-refresh-line"></i> Reset</button>
            </div>
        </div>
    </div>

    <div class="row row-cols-xl-5 row-cols-lg-4 row-cols-md-3 row-cols-2 g-3" id="store-grid">
        @forelse($products as $index => $product)
        <div class="col" 
             data-index="{{ $index }}" 
             data-name="{{ $product['name'] }}" 
             data-store="{{ $product['shopName'] ?? 'Toko' }}" 
             data-category="{{ $product['category'] ?? 'Lainnya' }}" 
             data-price="{{ $product['price'] }}" 
             data-rating="{{ $product['averageRating'] ?? 0 }}" 
             data-comments="{{ $product['reviewCount'] ?? 0 }}" 
             data-province="" 
             data-city="" 
             data-condition="Baru">
            <div class="card h-100 shadow-sm border-0" 
                 style="transition:transform .2s, box-shadow .2s; cursor: pointer;" 
                 onmouseenter="this.style.transform='translateY(-4px)';this.style.boxShadow='0 .5rem 1rem rgba(0,0,0,.15)';" 
                 onmouseleave="this.style.transform='none';this.style.boxShadow='';">
                @if(!empty($product['images']) && count($product['images']) > 0)
                    <img src="{{ $product['images'][0]['imageUrl'] }}" 
                         class="card-img-top" 
                         alt="{{ $product['name'] }}" 
                         style="height: 180px; object-fit: cover; border-radius: 0.375rem 0.375rem 0 0;">
                @else
                    <div class="bg-light d-flex align-items-center justify-content-center" 
                         style="height: 180px; border-radius: 0.375rem 0.375rem 0 0;">
                        <i class="ri-image-line" style="font-size: 3rem; color: #ccc;"></i>
                    </div>
                @endif
                <div class="card-body p-2">
                    <h6 class="mb-1 text-truncate" style="font-size: 0.875rem;" title="{{ $product['name'] }}">{{ $product['name'] }}</h6>
                    <p class="mb-1 fw-bold" style="font-size: 1rem; color: #000;">Rp{{ number_format($product['price'], 0, ',', '.') }}</p>
                    <div class="d-flex align-items-center gap-1 mb-1">
                        <i class="ri-star-fill text-warning" style="font-size: 0.75rem;"></i>
                        <span class="store-rating" style="font-size: 0.75rem;">{{ number_format($product['average_rating'] ?? 0, 1) }}</span>
                        <span class="text-muted" style="font-size: 0.7rem;">({{ $product['review_count'] ?? 0 }})</span>
                    </div>
                    <p class="mb-2 text-muted text-truncate" style="font-size: 0.75rem;" title="{{ $product['shop_name'] ?? 'Toko Produk' }}">
                        <i class="ri-store-2-line"></i> {{ $product['shop_name'] ?? 'Toko Produk' }}
                    </p>
                    <div class="d-flex gap-1">
                        <a href="{{ route('store.detail', ['id' => $product['id']]) }}" class="btn btn-outline-secondary btn-sm flex-fill" style="font-size: 0.7rem; padding: 0.25rem 0.5rem;">Detail</a>
                        <button class="btn btn-primary btn-sm flex-fill" data-action="review" data-index="{{ $index }}" data-product-id="{{ $product['id'] }}" style="font-size: 0.7rem; padding: 0.25rem 0.5rem;">Review</button>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info text-center">
                <i class="ri-information-line"></i> Belum ada produk yang tersedia.
            </div>
        </div>
        @endforelse
    </div>

    @include('Component.Review-Modal')
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
  var grid = document.getElementById('store-grid');
  var qInput = document.getElementById('store-search');
  var condSel = document.getElementById('store-filter-condition');
  var catSel = document.getElementById('store-filter-category');
  var provSel = document.getElementById('store-filter-province');
  var cityInput = document.getElementById('store-filter-city');
  var resetBtn = document.getElementById('store-filter-reset');
  function apply(){
    var q = (qInput && qInput.value || '').toLowerCase();
    var cond = (condSel && condSel.value) || '';
    var cat = (catSel && catSel.value) || '';
    var prov = (provSel && provSel.value) || '';
    var city = (cityInput && cityInput.value || '').toLowerCase();
    Array.from(grid.children).forEach(function(col){
      var name = (col.getAttribute('data-name')||'').toLowerCase();
      var store = (col.getAttribute('data-store')||'').toLowerCase();
      var category = col.getAttribute('data-category')||'';
      var province = col.getAttribute('data-province')||'';
      var cityAttr = (col.getAttribute('data-city')||'').toLowerCase();
      var condition = col.getAttribute('data-condition')||'';
      var matchQ = name.indexOf(q) !== -1 || store.indexOf(q) !== -1;
      var matchCond = cond === '' || condition === cond;
      var matchCat = cat === '' || category === cat;
      var matchProv = prov === '' || province === prov;
      var matchCity = city === '' || cityAttr.indexOf(city) !== -1;
      var show = matchQ && matchCond && matchCat && matchProv && matchCity;
      col.style.display = show ? '' : 'none';
    });
  }
  if(qInput) qInput.addEventListener('input', apply);
  if(condSel) condSel.addEventListener('change', apply);
  if(catSel) catSel.addEventListener('change', apply);
  if(provSel) provSel.addEventListener('change', apply);
  if(cityInput) cityInput.addEventListener('input', apply);
  if(resetBtn) resetBtn.addEventListener('click', function(){
    if(qInput) qInput.value = '';
    if(condSel) condSel.value = '';
    if(catSel) catSel.value = '';
    if(provSel) provSel.value = '';
    if(cityInput) cityInput.value = '';
    apply();
  });
  apply();

  window.StoreCatalog = window.StoreCatalog || {};
  window.StoreCatalog.addReview = function(idx, payload){
    var card = grid.querySelector('[data-index="'+idx+'"]');
    if(!card) return;
    var ratingEl = card.querySelector('.store-rating');
    var commentsEl = card.querySelector('.store-comments');
    var prevCount = parseInt(commentsEl.textContent||'0');
    var prevAvg = parseFloat(ratingEl.textContent||'0');
    var newCount = prevCount + 1;
    var newAvg = ((prevAvg * prevCount) + payload.rating) / newCount;
    ratingEl.textContent = newAvg.toFixed(1);
    commentsEl.textContent = String(newCount);
  };

  grid.addEventListener('click', function(e){
    var t = e.target;
    var action = t.getAttribute('data-action');
    if(action === 'review'){
      var idx = t.getAttribute('data-index');
      var productId = t.getAttribute('data-product-id');
      var input = document.getElementById('review-target-index');
      input.value = productId || String(idx); // Use product ID, fallback to index
      var modalEl = document.getElementById('reviewModal');
      var modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
      modal.show();
    }
  });
});
</script>
@endpush