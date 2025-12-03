@extends('Layout.Store')

@section('content')
    <div class="row g-3">
        <div class="col-xl-7">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    @if(!empty($product['images']) && count($product['images']) > 0)
                        @php
                            $imageCount = count($product['images']);
                            $colClass = match($imageCount) {
                                1 => 'row-cols-1',
                                2 => 'row-cols-2',
                                3 => 'row-cols-3',
                                default => 'row-cols-4'
                            };
                        @endphp
                        <div class="row {{ $colClass }} g-3 mb-3">
                            @foreach($product['images'] as $img)
                                <div class="col">
                                    <img src="{{ $img['imageUrl'] }}" 
                                         class="img-fluid rounded w-100" 
                                         style="object-fit:contain;cursor:pointer;border:2px solid #e9ecef;background:#f8f9fa;max-height:400px" 
                                         alt="Product Image"
                                         onclick="this.requestFullscreen()">
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="mb-3">
                            <div class="bg-light d-flex align-items-center justify-content-center rounded" 
                                 style="height:250px">
                                <i class="ri-image-line" style="font-size: 4rem; color: #ccc;"></i>
                            </div>
                        </div>
                    @endif
                    <h4 class="fw-bold mb-1">{{ $product['name'] }}</h4>
                    <div class="d-flex align-items-center gap-2 mb-2">
                        @if($product['category'])
                            <span class="badge bg-light text-body">{{ $product['category'] }}</span>
                        @endif
                        <span class="badge bg-light text-body">
                            <i class="ri-star-fill text-warning"></i> 
                            {{ number_format($product['average_rating'] ?? 0, 1) }} 
                            ({{ $product['review_count'] ?? 0 }} ulasan)
                        </span>
                        <span class="badge bg-light text-body">
                            Stok: {{ $product['stock'] ?? 0 }}
                        </span>
                    </div>
                    <p class="fw-bold fs-3 text-primary mb-2">Rp{{ number_format($product['price'], 0, ',', '.') }}</p>
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <span class="badge bg-light text-body">
                            <i class="ri-scales-3-line"></i> {{ $product['weight'] }} gram
                        </span>
                        @if($product['shop_name'])
                            <span class="badge bg-light text-body">
                                <i class="ri-store-2-line"></i> {{ $product['shop_name'] }}
                            </span>
                        @endif
                    </div>
                    <h6 class="fw-semibold">Deskripsi</h6>
                    <p class="text-muted">{{ $product['description'] ?? 'Deskripsi belum tersedia' }}</p>
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
                        @forelse($product['reviews'] ?? [] as $r)
                        <div class="p-2 rounded bg-light bg-opacity-50">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <div>
                                    <span class="fw-semibold d-block">{{ $r['name'] }}</span>
                                    @if(isset($r['email']))
                                        <small class="text-muted">{{ $r['email'] }}</small>
                                    @endif
                                </div>
                                <span class="badge bg-warning text-dark">
                                    <i class="ri-star-fill"></i> {{ $r['rating'] }}
                                </span>
                            </div>
                            @if(!empty($r['comment']))
                                <div class="text-muted mt-2">{{ $r['comment'] }}</div>
                            @endif
                            @if(isset($r['created_at']))
                                <small class="text-muted">{{ \Carbon\Carbon::parse($r['created_at'])->diffForHumans() }}</small>
                            @endif
                        </div>
                        @empty
                        <div class="text-center text-muted py-3">
                            <i class="ri-message-3-line" style="font-size: 2rem;"></i>
                            <p class="mb-0">Belum ada ulasan untuk produk ini</p>
                        </div>
                        @endforelse
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
      input.value = '{{ $product["id"] }}';
      var modalEl = document.getElementById('reviewModal');
      var modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
      modal.show();
    });
  }
});
</script>
@endpush