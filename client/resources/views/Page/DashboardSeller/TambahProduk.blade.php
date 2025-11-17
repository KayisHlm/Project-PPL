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
                            <div id="seller-product-alert" class="alert alert-success d-none" role="alert"></div>
                            <form id="seller-product-form" action="#" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Foto Produk</label>
                                    <div class="input-group">
                                        <input type="file" class="form-control" id="seller-product-images" name="images[]" multiple accept="image/*">
                                        <span class="input-group-text"><i class="ri-image-add-line"></i></span>
                                    </div>
                                    <div class="form-text">Unggah hingga 6 foto untuk tampilan terbaik.</div>
                                    <ul class="list-group mt-2" id="seller-product-images-list"></ul>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-8">
                                        <label class="form-label" for="seller-product-name">Nama Produk</label>
                                        <input type="text" class="form-control form-control-lg" id="seller-product-name" placeholder="cth. Earphone X" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label" for="seller-product-condition">Kondisi</label>
                                        <select class="form-select" id="seller-product-condition" required>
                                            <option value="">Pilih</option>
                                            <option>Baru</option>
                                            <option>Bekas</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label" for="seller-product-price">Harga</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" class="form-control" id="seller-product-price" min="0" step="100" placeholder="250000" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label" for="seller-product-weight">Berat</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" id="seller-product-weight" min="1" step="1" placeholder="500" required>
                                            <span class="input-group-text">gram</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label" for="seller-product-min">Minimal Beli</label>
                                        <input type="number" class="form-control" id="seller-product-min" min="1" step="1" value="1" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="seller-product-category">Kategori</label>
                                        <select class="form-select" id="seller-product-category" required>
                                            <option value="">Pilih kategori</option>
                                            <option>Elektronik</option>
                                            <option>Fashion</option>
                                            <option>Makanan & Minuman</option>
                                            <option>Aksesoris</option>
                                            <option>Rumah Tangga</option>
                                            <option>Olahraga</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="seller-product-warranty">Tipe Garansi</label>
                                        <select class="form-select" id="seller-product-warranty" required>
                                            <option value="">Pilih garansi</option>
                                            <option>Tidak ada</option>
                                            <option>Toko</option>
                                            <option>Resmi</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="seller-product-year">Tahun Rilis</label>
                                        <input type="number" class="form-control" id="seller-product-year" min="2000" step="1" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="seller-product-claim">Cara Klaim Garansi</label>
                                        <textarea class="form-control" id="seller-product-claim" rows="3" placeholder="cth. Hubungi layanan pelanggan dengan bukti pembelian" required></textarea>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label" for="seller-product-description">Deskripsi</label>
                                        <textarea class="form-control" id="seller-product-description" rows="5" placeholder="Jelaskan detail produk, spesifikasi, fitur" required></textarea>
                                    </div>
                                </div>

                                <div class="d-flex gap-2 mt-3">
                                    <button type="submit" class="btn btn-primary" id="seller-product-submit" disabled>
                                        <i class="ri-check-line me-1"></i> Simpan
                                    </button>
                                    <button type="reset" class="btn btn-light">
                                        <i class="ri-refresh-line me-1"></i> Reset
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-header d-flex align-items-center gap-2">
                            <span class="avatar-title bg-info-subtle text-info rounded-circle" style="width:38px;height:38px"><i class="ri-eye-line"></i></span>
                            <h5 class="header-title mb-0">Preview Produk</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="row row-cols-4 g-2" id="seller-product-preview-images"></div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <h6 class="mb-0" id="seller-product-preview-name">Nama Produk</h6>
                                <span class="badge bg-primary-subtle text-primary" id="seller-product-preview-condition">Kondisi</span>
                            </div>
                            <p class="mb-1 text-muted" id="seller-product-preview-category">Kategori</p>
                            <p class="mb-2 fw-bold" id="seller-product-preview-price">Rp 0</p>
                            <div class="d-flex gap-2">
                                <span class="badge bg-light text-body" id="seller-product-preview-weight">0 gram</span>
                                <span class="badge bg-light text-body" id="seller-product-preview-min">Min 1</span>
                                <span class="badge bg-light text-body" id="seller-product-preview-year">Tahun 2000</span>
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
  var alertBox = document.getElementById('seller-product-alert');
  var imagesInput = document.getElementById('seller-product-images');
  var imagesList = document.getElementById('seller-product-images-list');
  var previewImages = document.getElementById('seller-product-preview-images');
  var nameInput = document.getElementById('seller-product-name');
  var priceInput = document.getElementById('seller-product-price');
  var conditionSel = document.getElementById('seller-product-condition');
  var weightInput = document.getElementById('seller-product-weight');
  var minInput = document.getElementById('seller-product-min');
  var categorySel = document.getElementById('seller-product-category');
  var descInput = document.getElementById('seller-product-description');
  var warrantySel = document.getElementById('seller-product-warranty');
  var yearInput = document.getElementById('seller-product-year');
  var claimInput = document.getElementById('seller-product-claim');
  var submitBtn = document.getElementById('seller-product-submit');
  var selectedFiles = [];

  function rupiah(n){
    var s = parseInt(n || 0).toString();
    return 'Rp ' + s.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
  }

  var prevName = document.getElementById('seller-product-preview-name');
  var prevPrice = document.getElementById('seller-product-preview-price');
  var prevCond = document.getElementById('seller-product-preview-condition');
  var prevCat = document.getElementById('seller-product-preview-category');
  var prevWeight = document.getElementById('seller-product-preview-weight');
  var prevMin = document.getElementById('seller-product-preview-min');
  var prevYear = document.getElementById('seller-product-preview-year');

  function updatePreview(){
    if(prevName) prevName.textContent = nameInput.value || 'Nama Produk';
    if(prevPrice) prevPrice.textContent = rupiah(priceInput.value || 0);
    if(prevCond) prevCond.textContent = conditionSel.value || 'Kondisi';
    if(prevCat) prevCat.textContent = categorySel.value || 'Kategori';
    if(prevWeight) prevWeight.textContent = (weightInput.value || 0) + ' gram';
    if(prevMin) prevMin.textContent = 'Min ' + (minInput.value || 1);
    if(prevYear) prevYear.textContent = 'Tahun ' + (yearInput.value || new Date().getFullYear());
    var ok = (nameInput.value||'').trim().length >= 3 && parseInt(priceInput.value||0) > 0 && categorySel.value !== '' && conditionSel.value !== '' && (descInput.value||'').trim().length > 0 && warrantySel.value !== '' && claimInput.value.trim().length > 0 && parseInt(minInput.value||0) >= 1 && parseInt(weightInput.value||0) >= 1;
    submitBtn.disabled = !ok;
  }

  function syncInputFromSelected(){
    var dt = new DataTransfer();
    for(var i=0;i<selectedFiles.length;i++){ dt.items.add(selectedFiles[i].file); }
    imagesInput.files = dt.files;
  }

  function renderSelected(){
    imagesList.innerHTML = '';
    previewImages.innerHTML = '';
    var ordered = selectedFiles.slice().sort(function(a,b){ return (b.pinned?1:0) - (a.pinned?0:0); });
    for(var i=0;i<ordered.length;i++){
      var f = ordered[i].file;
      var idx = selectedFiles.indexOf(ordered[i]);
      var url = URL.createObjectURL(f);
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
      sizeEl.className = 'text-muted';
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
        badge.textContent = 'Tersemat';
        right.appendChild(badge);
      }
      var pinBtn = document.createElement('button');
      pinBtn.type = 'button';
      pinBtn.className = 'btn btn-sm ' + (ordered[i].pinned ? 'btn-primary' : 'btn-outline-primary');
      pinBtn.textContent = ordered[i].pinned ? 'Lepas' : 'Sematkan';
      pinBtn.setAttribute('data-action','pin');
      pinBtn.setAttribute('data-index', String(idx));
      var rmBtn = document.createElement('button');
      rmBtn.type = 'button';
      rmBtn.className = 'btn btn-sm btn-outline-danger';
      rmBtn.textContent = 'Hapus';
      rmBtn.setAttribute('data-action','remove');
      rmBtn.setAttribute('data-index', String(idx));
      right.appendChild(pinBtn);
      right.appendChild(rmBtn);
      li.appendChild(left);
      li.appendChild(right);
      imagesList.appendChild(li);
      var col2 = document.createElement('div');
      col2.className = 'col';
      var img2 = document.createElement('img');
      img2.src = url;
      img2.className = 'img-fluid rounded';
      img2.style.height = '70px';
      img2.style.objectFit = 'cover';
      if(ordered[i].pinned){ img2.className += ' border border-primary'; }
      col2.appendChild(img2);
      previewImages.appendChild(col2);
    }
  }

  if(yearInput){
    yearInput.value = new Date().getFullYear();
  }

  ['input','change'].forEach(function(ev){
    if(nameInput) nameInput.addEventListener(ev, updatePreview);
    if(priceInput) priceInput.addEventListener(ev, updatePreview);
    if(conditionSel) conditionSel.addEventListener(ev, updatePreview);
    if(weightInput) weightInput.addEventListener(ev, updatePreview);
    if(minInput) minInput.addEventListener(ev, updatePreview);
    if(categorySel) categorySel.addEventListener(ev, updatePreview);
    if(descInput) descInput.addEventListener(ev, updatePreview);
    if(warrantySel) warrantySel.addEventListener(ev, updatePreview);
    if(yearInput) yearInput.addEventListener(ev, updatePreview);
    if(claimInput) claimInput.addEventListener(ev, updatePreview);
  });

  if(imagesInput){
    imagesInput.addEventListener('change', function(){
      var newly = Array.from(imagesInput.files || []);
      for(var i=0;i<newly.length;i++){
        if(selectedFiles.length < 6){ selectedFiles.push({ file: newly[i], pinned: false }); }
      }
      syncInputFromSelected();
      renderSelected();
      updatePreview();
    });
  }

  updatePreview();

  if(form){
    form.addEventListener('submit', function(e){
      e.preventDefault();
      alertBox.textContent = 'Produk berhasil ditambahkan';
      alertBox.classList.remove('d-none');
      setTimeout(function(){ alertBox.classList.add('d-none'); }, 2500);
      form.reset();
      selectedFiles = [];
      imagesInput.value = '';
      imagesList.innerHTML = '';
      previewImages.innerHTML = '';
      yearInput.value = new Date().getFullYear();
      updatePreview();
    });
  }

  if(imagesList){
    imagesList.addEventListener('click', function(e){
      var t = e.target;
      var act = t.getAttribute('data-action');
      var idx = parseInt(t.getAttribute('data-index')||'-1');
      if(act === 'remove' && idx >= 0){
        selectedFiles.splice(idx,1);
        syncInputFromSelected();
        renderSelected();
        updatePreview();
      }
      if(act === 'pin' && idx >= 0){
        selectedFiles[idx].pinned = !selectedFiles[idx].pinned;
        renderSelected();
        updatePreview();
      }
    });
  }
});
</script>
@endpush