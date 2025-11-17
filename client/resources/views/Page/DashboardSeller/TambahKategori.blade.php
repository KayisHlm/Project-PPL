@extends('Layout.Admin')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="row g-3">
                <div class="col-xl-8">
                    <div class="card shadow-sm border-0">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <span class="avatar-title bg-primary-subtle text-primary rounded-circle" style="width:38px;height:38px"><i class="ri-price-tag-3-line"></i></span>
                                <h4 class="header-title mb-0">Tambah Kategori</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="seller-category-alert" class="alert alert-success d-none" role="alert"></div>
                            <form id="seller-category-form" action="#" method="post" class="needs-validation" novalidate>
                                @csrf
                                <div class="mb-3">
                                    <label for="seller-category-name" class="form-label">Nama Kategori</label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text"><i class="ri-price-tag-3-line"></i></span>
                                        <input type="text" class="form-control" id="seller-category-name" placeholder="cth. Aksesoris" required>
                                    </div>
                                    <div class="form-text">Gunakan nama yang singkat dan jelas.</div>
                                    <div class="progress mt-2" style="height:6px">
                                        <div class="progress-bar" id="seller-category-strength" role="progressbar" style="width:0%"></div>
                                    </div>
                                    <div class="mt-2 d-flex align-items-center gap-2">
                                        <span class="badge bg-primary-subtle text-primary d-none" id="seller-category-preview"></span>
                                    </div>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary" id="seller-category-submit" disabled>
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
                            <span class="avatar-title bg-info-subtle text-info rounded-circle" style="width:38px;height:38px"><i class="ri-list-check"></i></span>
                            <h5 class="header-title mb-0">Kategori Saya</h5>
                        </div>
                        <div class="card-body">
                            <div id="seller-category-list" class="d-flex flex-wrap gap-2">
                                <span class="badge bg-light text-body">Elektronik</span>
                                <span class="badge bg-light text-body">Fashion</span>
                                <span class="badge bg-light text-body">Aksesoris</span>
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
  var form = document.getElementById('seller-category-form');
  var nameInput = document.getElementById('seller-category-name');
  var preview = document.getElementById('seller-category-preview');
  var strength = document.getElementById('seller-category-strength');
  var submitBtn = document.getElementById('seller-category-submit');
  var alertBox = document.getElementById('seller-category-alert');
  var list = document.getElementById('seller-category-list');
  function updateUI(){
    var v = (nameInput.value || '').trim();
    var len = v.length;
    submitBtn.disabled = len < 2;
    preview.textContent = v;
    preview.classList.toggle('d-none', len === 0);
    var pct = Math.min(len * 10, 100);
    strength.style.width = pct + '%';
    strength.classList.remove('bg-danger','bg-warning','bg-success');
    strength.classList.add(len < 3 ? 'bg-danger' : len < 7 ? 'bg-warning' : 'bg-success');
  }
  if(nameInput){
    nameInput.addEventListener('input', updateUI);
    updateUI();
  }
  if(form){
    form.addEventListener('submit', function(e){
      e.preventDefault();
      var v = (nameInput.value || '').trim();
      if(v.length < 2) return;
      alertBox.textContent = 'Kategori "' + v + '" berhasil ditambahkan';
      alertBox.classList.remove('d-none');
      var badge = document.createElement('span');
      badge.className = 'badge bg-light text-body';
      badge.textContent = v;
      if(list) list.appendChild(badge);
      form.reset();
      updateUI();
      setTimeout(function(){ alertBox.classList.add('d-none'); }, 2500);
    });
  }
});
</script>
@endpush