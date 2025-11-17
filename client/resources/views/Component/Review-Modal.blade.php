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
            <label class="form-label">Provinsi</label>
            <select class="form-select" id="review-province" required>
              <option value="">Pilih Provinsi</option>
              <option>DKI Jakarta</option>
              <option>Jawa Barat</option>
              <option>Jawa Tengah</option>
              <option>Jawa Timur</option>
              <option>DI Yogyakarta</option>
              <option>Banten</option>
            </select>
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
  var provinceSel = document.getElementById('review-province');
  var ratingSel = document.getElementById('review-rating');
  var commentInput = document.getElementById('review-comment');
  var raterEl = document.getElementById('rater');
  function validEmail(v){ return /.+@.+\..+/.test(v); }
  function valid(){
    return (nameInput.value||'').trim().length >= 3 && (phoneInput.value||'').trim().length >= 8 && validEmail(emailInput.value||'') && (provinceSel && provinceSel.value !== '') && parseInt(ratingSel.value||0) >= 1 && (commentInput.value||'').trim().length >= 3;
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
  if(form){
    form.addEventListener('submit', function(e){
      e.preventDefault();
      if(!valid()) return;
      var idx = parseInt(targetIdxInput.value||'-1');
      var payload = { name: nameInput.value, phone: phoneInput.value, email: emailInput.value, province: provinceSel ? provinceSel.value : '', rating: parseInt(ratingSel.value), comment: commentInput.value, ts: new Date().toISOString() };
      if(window.StoreCatalog && window.StoreCatalog.addReview){ window.StoreCatalog.addReview(idx, payload); }
      alertBox.textContent = 'Terima kasih atas ulasan Anda. Email ucapan terima kasih telah dikirim.';
      alertBox.classList.remove('d-none');
      setTimeout(function(){ alertBox.classList.add('d-none'); }, 3000);
      form.reset();
      ratingSel.value = '';
      if(window.__reviewRater && typeof window.__reviewRater.setRating === 'function'){
        window.__reviewRater.setRating(0);
      }
      var modalEl = document.getElementById('reviewModal');
      var modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
      modal.hide();
    });
  }
});
</script>
@endpush