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

    <div class="row row-cols-xl-4 row-cols-lg-3 row-cols-md-2 row-cols-1 g-3" id="store-grid">
        <div class="col" data-index="0" data-name="Earphone X" data-store="AudioHub" data-category="Elektronik" data-price="250000" data-rating="4.5" data-comments="3" data-province="Jawa Barat" data-city="Bandung" data-condition="Baru">
            <div class="card h-100 shadow-sm border-0 position-relative" style="transition:transform .2s, box-shadow .2s" onmouseenter="this.style.transform='translateY(-4px)';this.style.boxShadow='0 .5rem 1rem rgba(0,0,0,.15)';" onmouseleave="this.style.transform='none';this.style.boxShadow='';">
                <span class="badge bg-primary-subtle text-primary position-absolute top-0 end-0 m-2">★ <span class="store-rating">4.5</span> (<span class="store-comments">3</span>)</span>
                <img src="assets/images/products/product-1.jpg" class="card-img-top" alt="Produk" style="transition:transform .2s" onmouseenter="this.style.transform='scale(1.03)'" onmouseleave="this.style.transform='none'">
                <div class="card-body">
                    <h6 class="mb-1">Earphone X</h6>
                    <p class="mb-1 text-muted">AudioHub • Elektronik • Bandung, Jawa Barat</p>
                    <p class="mb-2 fw-bold">Rp 250.000</p>
                    <div class="d-flex gap-2">
                        <a href="{{ route('store.detail', ['id' => 0]) }}" class="btn btn-sm btn-light">Detail</a>
                        <button class="btn btn-sm btn-primary" data-action="review" data-index="0">Beri Komentar & Rating</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col" data-index="1" data-name="Sneakers Z" data-store="StyleMart" data-category="Fashion" data-price="550000" data-rating="4.2" data-comments="5" data-province="DKI Jakarta" data-city="Jakarta" data-condition="Bekas">
            <div class="card h-100 shadow-sm border-0 position-relative" style="transition:transform .2s, box-shadow .2s" onmouseenter="this.style.transform='translateY(-4px)';this.style.boxShadow='0 .5rem 1rem rgba(0,0,0,.15)';" onmouseleave="this.style.transform='none';this.style.boxShadow='';">
                <span class="badge bg-primary-subtle text-primary position-absolute top-0 end-0 m-2">★ <span class="store-rating">4.2</span> (<span class="store-comments">5</span>)</span>
                <img src="assets/images/products/product-2.jpg" class="card-img-top" alt="Produk" style="transition:transform .2s" onmouseenter="this.style.transform='scale(1.03)'" onmouseleave="this.style.transform='none'">
                <div class="card-body">
                    <h6 class="mb-1">Sneakers Z</h6>
                    <p class="mb-1 text-muted">StyleMart • Fashion • Jakarta, DKI Jakarta</p>
                    <p class="mb-2 fw-bold">Rp 550.000</p>
                    <div class="d-flex gap-2">
                        <a href="{{ route('store.detail', ['id' => 1]) }}" class="btn btn-sm btn-light">Detail</a>
                        <button class="btn btn-sm btn-primary" data-action="review" data-index="1">Beri Komentar & Rating</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col" data-index="2" data-name="Coklat Premium" data-store="Sweet House" data-category="Makanan & Minuman" data-price="85000" data-rating="4.8" data-comments="11" data-province="DI Yogyakarta" data-city="Yogyakarta" data-condition="Baru">
            <div class="card h-100 shadow-sm border-0 position-relative" style="transition:transform .2s, box-shadow .2s" onmouseenter="this.style.transform='translateY(-4px)';this.style.boxShadow='0 .5rem 1rem rgba(0,0,0,.15)';" onmouseleave="this.style.transform='none';this.style.boxShadow='';">
                <span class="badge bg-primary-subtle text-primary position-absolute top-0 end-0 m-2">★ <span class="store-rating">4.8</span> (<span class="store-comments">11</span>)</span>
                <img src="assets/images/products/product-3.jpg" class="card-img-top" alt="Produk" style="transition:transform .2s" onmouseenter="this.style.transform='scale(1.03)'" onmouseleave="this.style.transform='none'">
                <div class="card-body">
                    <h6 class="mb-1">Coklat Premium</h6>
                    <p class="mb-1 text-muted">Sweet House • Makanan & Minuman • Yogyakarta, DI Yogyakarta</p>
                    <p class="mb-2 fw-bold">Rp 85.000</p>
                    <div class="d-flex gap-2">
                        <a href="{{ route('store.detail', ['id' => 2]) }}" class="btn btn-sm btn-light">Detail</a>
                        <button class="btn btn-sm btn-primary" data-action="review" data-index="2">Beri Komentar & Rating</button>
                    </div>
                </div>
            </div>
        </div>
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
      var input = document.getElementById('review-target-index');
      input.value = String(idx);
      var modalEl = document.getElementById('reviewModal');
      var modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
      modal.show();
    }
  });
});
</script>
@endpush