@extends('Layout.Store')

@section('content')
    <div class="row g-3">
        <div class="col-xl-7">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="row row-cols-4 g-2 mb-2">
                        @foreach(($product['images'] ?? ['assets/images/products/product-1.jpg']) as $img)
                            <div class="col"><img src="{{ $img }}" class="img-fluid rounded" style="height:80px;object-fit:cover" alt="img"></div>
                        @endforeach
                    </div>
                    <h4 class="fw-bold mb-1">{{ $product['name'] ?? 'Nama Produk' }}</h4>
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge bg-primary-subtle text-primary">{{ $product['condition'] ?? 'Kondisi' }}</span>
                        <span class="badge bg-light text-body">{{ $product['category'] ?? 'Kategori' }}</span>
                        <span class="badge bg-light text-body">{{ isset($product['year']) ? 'Tahun '.$product['year'] : 'Tahun '.date('Y') }}</span>
                    </div>
                    <p class="fw-bold fs-5">{{ isset($product['price']) ? 'Rp '.number_format($product['price'],0,',','.') : 'Rp 0' }}</p>
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <span class="badge bg-light text-body">{{ isset($product['weight']) ? $product['weight'].' gram' : '0 gram' }}</span>
                        <span class="badge bg-light text-body">{{ isset($product['min']) ? 'Min '.$product['min'] : 'Min 1' }}</span>
                        <span class="badge bg-light text-body">{{ $product['warranty'] ?? 'Garansi' }}</span>
                    </div>
                    <h6 class="fw-semibold">Deskripsi</h6>
                    <p class="text-muted">{{ $product['description'] ?? 'Deskripsi belum tersedia' }}</p>
                    <h6 class="fw-semibold">Cara Klaim Garansi</h6>
                    <p class="text-muted">{{ $product['claim'] ?? 'Informasi klaim belum tersedia' }}</p>
                </div>
            </div>
        </div>

        <div class="col-xl-5">
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="header-title mb-0">Ulasan</h5>
                    <button class="btn btn-sm btn-primary" id="detail-review-btn">Tulis Ulasan</button>
                </div>
                <div class="card-body">
                    <div id="detail-reviews" class="d-flex flex-column gap-2">
                        @foreach(($product['reviews'] ?? []) as $r)
                        <div class="p-2 rounded bg-light bg-opacity-50">
                            <div class="d-flex justify-content-between">
                                <span class="fw-semibold">{{ $r['name'] ?? 'Anonim' }}</span>
                                <span class="badge bg-primary-subtle text-primary">★ {{ $r['rating'] ?? 0 }}</span>
                            </div>
                            <div class="text-muted">{{ $r['comment'] ?? '' }}</div>
                        </div>
                        @endforeach
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
  var btn = document.getElementById('detail-review-btn');
  var reviews = document.getElementById('detail-reviews');
  window.StoreCatalog = window.StoreCatalog || {};
  window.StoreCatalog.addReview = function(idx, payload){
    var item = document.createElement('div');
    item.className = 'p-2 rounded bg-light bg-opacity-50';
    var top = document.createElement('div');
    top.className = 'd-flex justify-content-between';
    var nameEl = document.createElement('span');
    nameEl.className = 'fw-semibold';
    nameEl.textContent = payload.name;
    var ratingEl = document.createElement('span');
    ratingEl.className = 'badge bg-primary-subtle text-primary';
    ratingEl.textContent = '★ ' + payload.rating;
    top.appendChild(nameEl);
    top.appendChild(ratingEl);
    var commentEl = document.createElement('div');
    commentEl.className = 'text-muted';
    commentEl.textContent = payload.comment;
    item.appendChild(top);
    item.appendChild(commentEl);
    if(reviews) reviews.prepend(item);
  };
  if(btn){
    btn.addEventListener('click', function(){
      var input = document.getElementById('review-target-index');
      input.value = 'detail';
      var modalEl = document.getElementById('reviewModal');
      var modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
      modal.show();
    });
  }
});
</script>
@endpush