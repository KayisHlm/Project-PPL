@extends('Layout.Store')

@section('content')
    <div class="mb-2">
        <a href="{{ url()->previous() }}" class="btn btn-sm btn-outline-secondary">
            <i class="ri-arrow-left-line me-1"></i> Kembali
        </a>
    </div>
    <div class="row g-3">
        <div class="col-xl-7">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    @if(!empty($product['images']) && count($product['images']) > 0)
                        <!-- Carousel untuk Product Images -->
                        <div id="productImageCarousel" class="carousel slide mb-3" data-bs-ride="carousel">
                            <div class="carousel-inner rounded" style="background:#f8f9fa;">
                                @foreach($product['images'] as $index => $img)
                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                        <img src="{{ $img['imageUrl'] }}" 
                                             class="d-block w-100" 
                                             style="object-fit:contain;max-height:500px;cursor:pointer;" 
                                             alt="Product Image {{ $index + 1 }}"
                                             onclick="this.requestFullscreen()">
                                    </div>
                                @endforeach
                            </div>
                            
                            @if(count($product['images']) > 1)
                                <!-- Previous Button -->
                                <button class="carousel-control-prev" type="button" data-bs-target="#productImageCarousel" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <!-- Next Button -->
                                <button class="carousel-control-next" type="button" data-bs-target="#productImageCarousel" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                                
                                <!-- Custom Thumbnail Navigation -->
                                <div class="d-flex justify-content-center gap-2 mt-3">
                                    @foreach($product['images'] as $index => $img)
                                        <button type="button" 
                                                data-bs-target="#productImageCarousel" 
                                                data-bs-slide-to="{{ $index }}" 
                                                class="border-0 p-0 {{ $index === 0 ? 'active-thumbnail' : '' }}" 
                                                aria-current="{{ $index === 0 ? 'true' : 'false' }}" 
                                                aria-label="Slide {{ $index + 1 }}"
                                                style="width:70px;height:70px;border-radius:8px;overflow:hidden;cursor:pointer;opacity:0.6;transition:all 0.3s;"
                                                onmouseover="this.style.opacity='1';this.style.transform='scale(1.05)'" 
                                                onmouseout="this.style.opacity=this.classList.contains('active-thumbnail')?'1':'0.6';this.style.transform='scale(1)'">
                                            <img src="{{ $img['imageUrl'] }}" 
                                                 class="w-100 h-100" 
                                                 style="object-fit:cover;pointer-events:none;" 
                                                 alt="Thumbnail {{ $index + 1 }}">
                                        </button>
                                    @endforeach
                                </div>
                            @endif
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
                            {{ number_format($product['averageRating'] ?? $product['average_rating'] ?? 0, 1) }} 
                            ({{ $product['reviewCount'] ?? $product['review_count'] ?? 0 }} ulasan)
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
                        @if(isset($product['shopName']) || isset($product['shop_name']))
                            <span class="badge bg-light text-body">
                                <i class="ri-store-2-line"></i> {{ $product['shopName'] ?? $product['shop_name'] ?? 'Toko' }}
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
                    @if(!isset($isProductOwner) || !$isProductOwner)
                    <button class="btn btn-sm btn-primary" id="detail-review-btn">Tulis Ulasan</button>
                    @endif
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

    @if(!isset($isProductOwner) || !$isProductOwner)
    @include('Component.Review-Modal')
    @endif
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
  // Carousel thumbnail active state management
  var carousel = document.getElementById('productImageCarousel');
  if(carousel){
    carousel.addEventListener('slide.bs.carousel', function(event){
      var thumbnails = document.querySelectorAll('[data-bs-target="#productImageCarousel"]');
      thumbnails.forEach(function(thumb, idx){
        if(idx === event.to){
          thumb.classList.add('active-thumbnail');
          thumb.style.opacity = '1';
          thumb.style.border = '2px solid #0d6efd';
        } else {
          thumb.classList.remove('active-thumbnail');
          thumb.style.opacity = '0.6';
          thumb.style.border = 'none';
        }
      });
    });
  }

  var btn = document.getElementById('detail-review-btn');
  var reviews = document.getElementById('detail-reviews');
  
  @if(!isset($isProductOwner) || !$isProductOwner)
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
  @endif
});
</script>
@endpush
