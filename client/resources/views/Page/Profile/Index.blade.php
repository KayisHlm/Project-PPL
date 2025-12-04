@extends('Layout.Admin')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="row g-3">
                <div class="col-xl-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-header d-flex align-items-center gap-2">
                            <span class="avatar-title bg-primary-subtle text-primary rounded-circle" style="width:38px;height:38px"><i class="ri-user-settings-line"></i></span>
                            <h4 class="header-title mb-0">Profil</h4>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-3">
                                <div class="avatar-xxl mx-auto position-relative">
                                    <img id="profile-avatar-preview" src="{{ asset('assets/images/users/avatar-1.jpg') }}" class="rounded-circle" style="width:120px;height:120px;object-fit:cover" alt="avatar">
                                </div>
                                <div class="mt-2 d-flex justify-content-center">
                                    <label class="btn btn-sm btn-light mb-0">
                                        <i class="ri-image-add-line me-1"></i> Ubah Foto
                                        <input type="file" id="profile-avatar" accept="image/*" class="d-none">
                                    </label>
                                </div>
                            </div>
                            <div class="mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="ri-shield-user-line"></i>
                                    <span id="profile-summary-name">Nama Lengkap</span>
                                </div>
                            </div>
                            <div class="mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="ri-at-line"></i>
                                    <span id="profile-summary-username">@username</span>
                                </div>
                            </div>
                            <div class="mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="ri-mail-line"></i>
                                    <span id="profile-summary-email">email@example.com</span>
                                </div>
                            </div>
                            <div class="mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="ri-phone-line"></i>
                                    <span id="profile-summary-phone">08xxxxxxxxxx</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-8">
                    <div class="card shadow-sm border-0">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h4 class="header-title mb-0">Pengaturan Profil</h4>
                            <span class="badge bg-light text-body" id="profile-last-update">Terakhir diubah: -</span>
                        </div>
                        <div class="card-body">
                            <div id="profile-alert" class="alert alert-success d-none" role="alert"></div>
                            <form id="profile-form" action="#" method="post" class="needs-validation" novalidate>
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="profile-name" class="form-label">Nama Lengkap</label>
                                        <input type="text" class="form-control" id="profile-name" placeholder="cth. Maxine Kennedy" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="profile-username" class="form-label">Username</label>
                                        <div class="input-group">
                                            <span class="input-group-text">@</span>
                                            <input type="text" class="form-control" id="profile-username" placeholder="cth. maxine" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="profile-email" class="form-label">Email</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="ri-mail-line"></i></span>
                                            <input type="email" class="form-control" id="profile-email" placeholder="email@example.com" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="profile-phone" class="form-label">Nomor HP</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="ri-phone-line"></i></span>
                                            <input type="tel" class="form-control" id="profile-phone" placeholder="08xxxxxxxxxx" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="profile-province" class="form-label">Provinsi</label>
                                        <select class="form-select" id="profile-province" required>
                                            <option value="">Pilih Provinsi</option>
                                            <option>DKI Jakarta</option>
                                            <option>Jawa Barat</option>
                                            <option>Jawa Tengah</option>
                                            <option>Jawa Timur</option>
                                            <option>DI Yogyakarta</option>
                                            <option>Banten</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="profile-city" class="form-label">Kota/Kabupaten</label>
                                        <input type="text" class="form-control" id="profile-city" placeholder="cth. Bandung" required>
                                    </div>
                                    <div class="col-12">
                                        <label for="profile-address" class="form-label">Alamat</label>
                                        <textarea class="form-control" id="profile-address" rows="3" placeholder="Nama jalan, nomor rumah" required></textarea>
                                    </div>
                                    <div class="col-12">
                                        <label for="profile-bio" class="form-label">Bio</label>
                                        <textarea class="form-control" id="profile-bio" rows="3" placeholder="Ceritakan tentang dirimu"></textarea>
                                    </div>
                                </div>

                                <div class="border-top border-light border-dashed my-3"></div>

                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="profile-password-old" class="form-label">Password Lama</label>
                                        <input type="password" class="form-control" id="profile-password-old" placeholder="••••••">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="profile-password-new" class="form-label">Password Baru</label>
                                        <input type="password" class="form-control" id="profile-password-new" placeholder="••••••">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="profile-password-confirm" class="form-label">Konfirmasi Password</label>
                                        <input type="password" class="form-control" id="profile-password-confirm" placeholder="••••••">
                                    </div>
                                </div>

                                <div class="border-top border-light border-dashed my-3"></div>

                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="profile-notif-email" checked>
                                            <label class="form-check-label" for="profile-notif-email">Notifikasi Email</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="profile-notif-wa">
                                            <label class="form-check-label" for="profile-notif-wa">Notifikasi WhatsApp</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="profile-privacy">
                                            <label class="form-check-label" for="profile-privacy">Privasi akun</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex gap-2 mt-3">
                                    <button type="submit" class="btn btn-primary" id="profile-submit" disabled>
                                        <i class="ri-check-line me-1"></i> Simpan Perubahan
                                    </button>
                                    <button type="reset" class="btn btn-light">
                                        <i class="ri-refresh-line me-1"></i> Reset
                                    </button>
                                </div>
                            </form>
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
  var form = document.getElementById('profile-form');
  var alertBox = document.getElementById('profile-alert');
  var submitBtn = document.getElementById('profile-submit');
  var nameInput = document.getElementById('profile-name');
  var usernameInput = document.getElementById('profile-username');
  var emailInput = document.getElementById('profile-email');
  var phoneInput = document.getElementById('profile-phone');
  var provinceSel = document.getElementById('profile-province');
  var cityInput = document.getElementById('profile-city');
  var addressInput = document.getElementById('profile-address');
  var bioInput = document.getElementById('profile-bio');
  var avatarInput = document.getElementById('profile-avatar');
  var avatarPreview = document.getElementById('profile-avatar-preview');
  var lastUpdate = document.getElementById('profile-last-update');
  var sumName = document.getElementById('profile-summary-name');
  var sumUser = document.getElementById('profile-summary-username');
  var sumEmail = document.getElementById('profile-summary-email');
  var sumPhone = document.getElementById('profile-summary-phone');
  function validEmail(v){ return /.+@.+\..+/.test(v); }
  function enable(){
    var ok = (nameInput.value||'').trim().length >= 3 && (usernameInput.value||'').trim().length >= 3 && validEmail(emailInput.value||'') && (phoneInput.value||'').trim().length >= 8 && provinceSel.value !== '' && (cityInput.value||'').trim().length >= 2 && (addressInput.value||'').trim().length >= 6;
    submitBtn.disabled = !ok;
  }
  function updateSummary(){
    sumName.textContent = nameInput.value || 'Nama Lengkap';
    sumUser.textContent = '@' + (usernameInput.value||'username');
    sumEmail.textContent = emailInput.value || 'email@example.com';
    sumPhone.textContent = phoneInput.value || '08xxxxxxxxxx';
  }
  ['input','change'].forEach(function(ev){
    nameInput.addEventListener(ev, function(){ enable(); updateSummary(); });
    usernameInput.addEventListener(ev, function(){ enable(); updateSummary(); });
    emailInput.addEventListener(ev, function(){ enable(); updateSummary(); });
    phoneInput.addEventListener(ev, function(){ enable(); updateSummary(); });
    provinceSel.addEventListener(ev, enable);
    cityInput.addEventListener(ev, enable);
    addressInput.addEventListener(ev, enable);
    bioInput.addEventListener(ev, enable);
  });
  if(avatarInput){
    avatarInput.addEventListener('change', function(){
      var f = avatarInput.files && avatarInput.files[0];
      if(f){ avatarPreview.src = URL.createObjectURL(f); }
    });
  }
  enable();
  updateSummary();
  if(form){
    form.addEventListener('submit', function(e){
      e.preventDefault();
      alertBox.textContent = 'Profil berhasil diperbarui';
      alertBox.classList.remove('d-none');
      lastUpdate.textContent = 'Terakhir diubah: ' + new Date().toLocaleString('id-ID');
      setTimeout(function(){ alertBox.classList.add('d-none'); }, 2500);
    });
  }
});
</script>
@endpush