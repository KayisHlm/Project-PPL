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
                                    <option>Elektronik</option>
                                    <option>Fashion</option>
                                    <option>Makanan & Minuman</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row row-cols-xl-4 row-cols-lg-3 row-cols-md-2 row-cols-1 g-3" id="admin-product-grid">
                        <div class="col" data-name="Earphone X" data-category="Elektronik" data-price="250000" data-rating="4.5">
                            <div class="card h-100 shadow-sm border-0 position-relative" style="transition:transform .2s, box-shadow .2s" onmouseenter="this.style.transform='translateY(-4px)';this.style.boxShadow='0 .5rem 1rem rgba(0,0,0,.15)';" onmouseleave="this.style.transform='none';this.style.boxShadow='';">
                                <span class="badge bg-primary-subtle text-primary position-absolute top-0 end-0 m-2">★ 4.5</span>
                                <img src="assets/images/products/product-1.jpg" class="card-img-top" alt="Produk" style="transition:transform .2s" onmouseenter="this.style.transform='scale(1.03)'" onmouseleave="this.style.transform='none'">
                                <div class="card-body">
                                    <h6 class="mb-1">Earphone X</h6>
                                    <p class="mb-1 text-muted">Elektronik</p>
                                    <p class="mb-0 fw-bold">Rp 250.000</p>
                                </div>
                            </div>
                        </div>
                        <div class="col" data-name="Sneakers Z" data-category="Fashion" data-price="550000" data-rating="4.2">
                            <div class="card h-100 shadow-sm border-0 position-relative" style="transition:transform .2s, box-shadow .2s" onmouseenter="this.style.transform='translateY(-4px)';this.style.boxShadow='0 .5rem 1rem rgba(0,0,0,.15)';" onmouseleave="this.style.transform='none';this.style.boxShadow='';">
                                <span class="badge bg-primary-subtle text-primary position-absolute top-0 end-0 m-2">★ 4.2</span>
                                <img src="assets/images/products/product-2.jpg" class="card-img-top" alt="Produk" style="transition:transform .2s" onmouseenter="this.style.transform='scale(1.03)'" onmouseleave="this.style.transform='none'">
                                <div class="card-body">
                                    <h6 class="mb-1">Sneakers Z</h6>
                                    <p class="mb-1 text-muted">Fashion</p>
                                    <p class="mb-0 fw-bold">Rp 550.000</p>
                                </div>
                            </div>
                        </div>
                        <div class="col" data-name="Coklat Premium" data-category="Makanan & Minuman" data-price="85000" data-rating="4.8">
                            <div class="card h-100 shadow-sm border-0 position-relative" style="transition:transform .2s, box-shadow .2s" onmouseenter="this.style.transform='translateY(-4px)';this.style.boxShadow='0 .5rem 1rem rgba(0,0,0,.15)';" onmouseleave="this.style.transform='none';this.style.boxShadow='';">
                                <span class="badge bg-primary-subtle text-primary position-absolute top-0 end-0 m-2">★ 4.8</span>
                                <img src="assets/images/products/product-3.jpg" class="card-img-top" alt="Produk" style="transition:transform .2s" onmouseenter="this.style.transform='scale(1.03)'" onmouseleave="this.style.transform='none'">
                                <div class="card-body">
                                    <h6 class="mb-1">Coklat Premium</h6>
                                    <p class="mb-1 text-muted">Makanan & Minuman</p>
                                    <p class="mb-0 fw-bold">Rp 85.000</p>
                                </div>
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