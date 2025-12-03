<div class="modal fade" id="reviewModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Komentar & Rating Produk</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="review-alert" class="alert alert-success d-none" role="alert"></div>
        <form id="review-form" action="#" method="post" class="needs-validation" novalidate>
          @csrf
          <input type="hidden" id="review-target-index" value="-1" />
          <div class="mb-2">
            <label class="form-label">Nama</label>
            <input type="text" class="form-control" id="review-name" placeholder="Nama lengkap" required />
          </div>
          <div class="mb-2">
            <label class="form-label">Nomor HP</label>
            <input type="tel" class="form-control" id="review-phone" placeholder="08xxxxxxxxxx" required />
          </div>
          <div class="mb-2">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" id="review-email" placeholder="email@example.com" required />
          </div>
          <div class="mb-2">
            <label class="form-label">Rating</label>
            <div id="rater" dir="ltr"></div>
            <input type="hidden" id="review-rating" required />
          </div>
          <div class="mb-2">
            <label class="form-label">Komentar</label>
            <textarea class="form-control" id="review-comment" rows="3" placeholder="Tulis komentar" required></textarea>
          </div>
          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary" id="review-submit">Kirim</button>
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script src="{{ asset('assets/vendor/rater-js/index.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function(){
  var form = document.getElementById('review-form');
  var alertBox = document.getElementById('review-alert');
  var targetIdxInput = document.getElementById('review-target-index');
  var nameInput = document.getElementById('review-name');
  var phoneInput = document.getElementById('review-phone');
  var emailInput = document.getElementById('review-email');
  var ratingSel = document.getElementById('review-rating');
  var commentInput = document.getElementById('review-comment');
  var raterEl = document.getElementById('rater');
  function validEmail(v){ return /.+@.+\..+/.test(v); }
  function valid(){
    return (nameInput.value||'').trim().length >= 3 && (phoneInput.value||'').trim().length >= 8 && validEmail(emailInput.value||'') && parseInt(ratingSel.value||0) >= 1 && (commentInput.value||'').trim().length >= 3;
  }
  if(raterEl && typeof raterJs === 'function'){
    window.__reviewRater = raterJs({
      starSize: 28,
      element: raterEl,
      rateCallback: function(rate, done){
        this.setRating(rate);
        ratingSel.value = String(Math.round(rate));
        done();
      }
    });
  }
  
  // Reset form function
  function resetForm() {
    form.reset();
    if (window.__reviewRater) {
      window.__reviewRater.setRating(0);
    }
    ratingSel.value = '';
    alertBox.classList.add('d-none');
    var submitBtn = document.getElementById('review-submit');
    submitBtn.disabled = false;
    submitBtn.textContent = 'Kirim';
  }
  
  // Reset form when modal is closed
  var modalEl = document.getElementById('reviewModal');
  if (modalEl) {
    modalEl.addEventListener('hidden.bs.modal', function() {
      resetForm();
    });
  }
  
  if(form){
    form.addEventListener('submit', function(e){
      e.preventDefault();
      console.log('Form submit triggered');
      
      // Validate
      if(!valid()) {
        console.log('Validation failed');
        alertBox.className = 'alert alert-warning';
        alertBox.textContent = 'Mohon lengkapi semua field dengan benar';
        alertBox.classList.remove('d-none');
        return;
      }
      
      console.log('Validation passed');
      
      var submitBtn = document.getElementById('review-submit');
      submitBtn.disabled = true;
      submitBtn.textContent = 'Mengirim...';
      
      var productId = targetIdxInput.value || window.location.pathname.split('/').pop();
      var payload = { 
        name: nameInput.value.trim(), 
        no_telp: phoneInput.value.trim() || null,
        email: emailInput.value.trim(), 
        rating: parseInt(ratingSel.value), 
        comment: commentInput.value.trim() 
      };
      
      console.log('Sending review:', payload);
      
      fetch('/api/reviews/products/' + productId, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(payload)
      })
      .then(function(res){ 
        console.log('Response status:', res.status);
        return res.json(); 
      })
      .then(function(data){
        console.log('Response data:', data);
        if(data.success){
          alertBox.className = 'alert alert-success';
          alertBox.textContent = 'Terima kasih atas ulasan Anda!';
          alertBox.classList.remove('d-none');
          
          // Refresh halaman setelah 1.5 detik
          setTimeout(function(){
            window.location.reload();
          }, 1500);
        } else {
          alertBox.className = 'alert alert-danger';
          alertBox.textContent = data.message || 'Gagal menambahkan review';
          alertBox.classList.remove('d-none');
          submitBtn.disabled = false;
          submitBtn.textContent = 'Kirim';
        }
      })
      .catch(function(err){
        console.error('Error:', err);
        alertBox.className = 'alert alert-danger';
        alertBox.textContent = 'Terjadi kesalahan. Silakan coba lagi.';
        alertBox.classList.remove('d-none');
        submitBtn.disabled = false;
        submitBtn.textContent = 'Kirim';
      });
    });
  }
});
</script>
@endpush