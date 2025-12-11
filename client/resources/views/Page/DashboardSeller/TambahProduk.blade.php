@extends('Layout.Admin')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="row g-3">
                <div class="col-xl-8">
                    <div class="card shadow-sm border-0">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <span class="avatar-title bg-primary-subtle text-primary rounded-circle" style="width:38px;height:38px"><i class="ri-shopping-bag-3-line"></i></span>
                                <h4 class="header-title mb-0">Tambah Produk</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <form id="seller-product-form" action="{{ route('dashboard-seller.produk.create') }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                                @csrf

                                <!-- FOTO PRODUK -->
                                <div class="mb-3">
                                    <label class="form-label">Foto Produk <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="file" class="form-control" id="seller-product-images" name="images[]" multiple accept="image/*" required>
                                        <span class="input-group-text"><i class="ri-image-add-line"></i></span>
                                    </div>
                                    <div class="form-text">Unggah hingga 6 foto untuk tampilan terbaik. Minimal 1 foto.</div>
                                    <ul class="list-group mt-2" id="seller-product-images-list"></ul>
                                </div>

                                <div class="row g-3">
                                    <!-- NAMA PRODUK -->
                                    <div class="col-12">
                                        <label class="form-label" for="seller-product-name">Nama Produk <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control form-control-lg" id="seller-product-name" placeholder="cth. Earphone X" value="{{ old('name') }}" required>
                                        @error('name')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- HARGA -->
                                    <div class="col-md-6">
                                        <label class="form-label" for="seller-product-price">Harga <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" name="price" class="form-control" id="seller-product-price" min="1" placeholder="250000" value="{{ old('price') }}" required>
                                        </div>
                                        @error('price')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- STOK -->
                                    <div class="col-md-6">
                                        <label class="form-label" for="seller-product-stock">Stok <span class="text-danger">*</span></label>
                                        <input type="number" name="stock" class="form-control" id="seller-product-stock" min="0" step="1" placeholder="10" value="{{ old('stock') }}" required>
                                        @error('stock')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- KATEGORI -->
                                    <div class="col-md-6">
                                        <label class="form-label" for="seller-product-category">Kategori <span class="text-danger">*</span></label>
                                        <select class="form-select" name="category" id="seller-product-category" required>
                                            <option value="">Pilih kategori</option>
                                            @foreach(($categories ?? []) as $c)
                                                <option value="{{ $c['name'] }}" {{ old('category') == $c['name'] ? 'selected' : '' }}>{{ $c['name'] }}</option>
                                            @endforeach
                                        </select>
                                        @error('category')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- BERAT (untuk ongkir) -->
                                    <div class="col-md-6">
                                        <label class="form-label" for="seller-product-weight">Berat <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" name="weight" class="form-control" id="seller-product-weight" min="1" step="1" placeholder="500" value="{{ old('weight') }}" required>
                                            <span class="input-group-text">gram</span>
                                        </div>
                                        @error('weight')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- DESKRIPSI -->
                                    <div class="col-12">
                                        <label class="form-label" for="seller-product-description">Deskripsi <span class="text-danger">*</span></label>
                                        <textarea class="form-control" name="description" id="seller-product-description" rows="5" placeholder="Jelaskan detail produk (Minimal 10 karakter)" required>{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="d-flex gap-2 mt-4">
                                    <button type="submit" class="btn btn-primary" id="seller-product-submit">
                                        <i class="ri-check-line me-1"></i> Simpan Produk
                                    </button>
                                    <button type="reset" class="btn btn-light">
                                        <i class="ri-refresh-line me-1"></i> Reset
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- PREVIEW -->
                <div class="col-xl-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-header d-flex align-items-center gap-2">
                            <span class="avatar-title bg-info-subtle text-info rounded-circle" style="width:38px;height:38px"><i class="ri-eye-line"></i></span>
                            <h5 class="header-title mb-0">Preview Produk</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="row row-cols-3 g-2" id="seller-product-preview-images"></div>
                            </div>
                            <h6 class="mb-2" id="seller-product-preview-name">Nama Produk</h6>
                            <p class="mb-1 text-muted small" id="seller-product-preview-category">Kategori</p>
                            <p class="mb-2 fw-bold fs-5 text-primary" id="seller-product-preview-price">Rp 0</p>
                            <div class="d-flex gap-2 flex-wrap">
                                <span class="badge bg-light text-body" id="seller-product-preview-stock">Stok: 0</span>
                                <span class="badge bg-light text-body" id="seller-product-preview-weight">0 gram</span>
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
  var form = document.getElementById('seller-product-form');
  var imagesInput = document.getElementById('seller-product-images');
  var imagesList = document.getElementById('seller-product-images-list');
  var previewImages = document.getElementById('seller-product-preview-images');
  
  var nameInput = document.getElementById('seller-product-name');
  var priceInput = document.getElementById('seller-product-price');
  var stockInput = document.getElementById('seller-product-stock');
  var categorySelect = document.getElementById('seller-product-category');
  var weightInput = document.getElementById('seller-product-weight');
  var descInput = document.getElementById('seller-product-description');
  var submitBtn = document.getElementById('seller-product-submit');
  
  var prevName = document.getElementById('seller-product-preview-name');
  var prevPrice = document.getElementById('seller-product-preview-price');
  var prevCategory = document.getElementById('seller-product-preview-category');
  var prevStock = document.getElementById('seller-product-preview-stock');
  var prevWeight = document.getElementById('seller-product-preview-weight');
  
  var selectedFiles = [];

  function rupiah(n){
    var s = parseInt(n || 0).toString();
    return 'Rp ' + s.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
  }

  function updatePreview(){
    if(prevName) prevName.textContent = nameInput.value || 'Nama Produk';
    if(prevPrice) prevPrice.textContent = rupiah(priceInput.value || 0);
    if(prevCategory) prevCategory.textContent = categorySelect.value || 'Kategori';
    if(prevStock) prevStock.textContent = 'Stok: ' + (stockInput.value || 0);
    if(prevWeight) prevWeight.textContent = (weightInput.value || 0) + ' gram';
  }

  function syncInputFromSelected(){
    var dt = new DataTransfer();
    for(var i=0; i<selectedFiles.length; i++){ 
      dt.items.add(selectedFiles[i].file); 
    }
    imagesInput.files = dt.files;
  }

  function renderSelected(){
    imagesList.innerHTML = '';
    previewImages.innerHTML = '';
    
    var ordered = selectedFiles.slice().sort(function(a,b){ 
      return (b.pinned?1:0) - (a.pinned?1:0); 
    });
    
    for(var i=0; i<ordered.length; i++){
      var f = ordered[i].file;
      var idx = selectedFiles.indexOf(ordered[i]);
      var url = URL.createObjectURL(f);
      
      // List item
      var li = document.createElement('li');
      li.className = 'list-group-item d-flex align-items-center justify-content-between';
      
      var left = document.createElement('div');
      left.className = 'd-flex align-items-center gap-2';
      
      var img = document.createElement('img');
      img.src = url;
      img.className = 'rounded';
      img.style.height = '42px';
      img.style.width = '42px';
      img.style.objectFit = 'cover';
      
      var text = document.createElement('div');
      var nameEl = document.createElement('div');
      nameEl.className = 'fw-semibold';
      nameEl.textContent = f.name || 'file';
      
      var sizeEl = document.createElement('div');
      sizeEl.className = 'text-muted small';
      sizeEl.textContent = Math.round((f.size/1024)*10)/10 + ' KB';
      
      text.appendChild(nameEl);
      text.appendChild(sizeEl);
      left.appendChild(img);
      left.appendChild(text);
      
      var right = document.createElement('div');
      right.className = 'd-flex align-items-center gap-2';
      
      if(ordered[i].pinned){
        var badge = document.createElement('span');
        badge.className = 'badge bg-primary-subtle text-primary';
        badge.innerHTML = '<i class="ri-star-fill"></i> Cover';
        right.appendChild(badge);
      }
      
      var pinBtn = document.createElement('button');
      pinBtn.type = 'button';
      pinBtn.className = 'btn btn-sm ' + (ordered[i].pinned ? 'btn-primary' : 'btn-outline-primary');
      pinBtn.innerHTML = '<i class="ri-star-line"></i>';
      pinBtn.setAttribute('data-action','pin');
      pinBtn.setAttribute('data-index', String(idx));
      pinBtn.title = ordered[i].pinned ? 'Lepas sebagai cover' : 'Jadikan cover';
      
      var rmBtn = document.createElement('button');
      rmBtn.type = 'button';
      rmBtn.className = 'btn btn-sm btn-outline-danger';
      rmBtn.innerHTML = '<i class="ri-delete-bin-line"></i>';
      rmBtn.setAttribute('data-action','remove');
      rmBtn.setAttribute('data-index', String(idx));
      
      right.appendChild(pinBtn);
      right.appendChild(rmBtn);
      li.appendChild(left);
      li.appendChild(right);
      imagesList.appendChild(li);
      
      // Preview thumbnail
      var col = document.createElement('div');
      col.className = 'col';
      var imgPreview = document.createElement('img');
      imgPreview.src = url;
      imgPreview.className = 'img-fluid rounded';
      imgPreview.style.height = '80px';
      imgPreview.style.width = '100%';
      imgPreview.style.objectFit = 'cover';
      
      if(ordered[i].pinned){ 
        imgPreview.className += ' border border-2 border-primary'; 
      }
      
      col.appendChild(imgPreview);
      previewImages.appendChild(col);
    }
  }

  ['input','change'].forEach(function(ev){
    if(nameInput) nameInput.addEventListener(ev, updatePreview);
    if(priceInput) priceInput.addEventListener(ev, updatePreview);
    if(stockInput) stockInput.addEventListener(ev, updatePreview);
    if(categorySelect) categorySelect.addEventListener(ev, updatePreview);
    if(weightInput) weightInput.addEventListener(ev, updatePreview);
  });

  if(imagesInput){
    imagesInput.addEventListener('change', function(){
      var newly = Array.from(imagesInput.files || []);
      
      for(var i=0; i<newly.length; i++){
        if(selectedFiles.length < 6){ 
          selectedFiles.push({ file: newly[i], pinned: false }); 
        }
      }
      
      syncInputFromSelected();
      renderSelected();
    });
  }

  if(imagesList){
    imagesList.addEventListener('click', function(e){
      var t = e.target.closest('[data-action]');
      if(!t) return;
      
      var act = t.getAttribute('data-action');
      var idx = parseInt(t.getAttribute('data-index')||'-1');
      
      if(act === 'remove' && idx >= 0){
        selectedFiles.splice(idx,1);
        syncInputFromSelected();
        renderSelected();
      }
      
      if(act === 'pin' && idx >= 0){
        // Unpin all first
        selectedFiles.forEach(function(item){ item.pinned = false; });
        // Pin selected
        selectedFiles[idx].pinned = true;
        // Sort: pinned first
        selectedFiles.sort(function(a,b){ return (b.pinned?1:0) - (a.pinned?1:0); });
        syncInputFromSelected();
        renderSelected();
      }
    });
  }

  updatePreview();
});
</script>
@endpush