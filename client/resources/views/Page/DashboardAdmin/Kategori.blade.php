@extends('Layout.Admin')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="header-title mb-0">Daftar Kategori</h4>
                    <div class="input-group w-auto">
                        <span class="input-group-text"><i class="ri-search-line"></i></span>
                        <input type="text" class="form-control form-control-sm" id="admin-category-search" placeholder="Cari kategori...">
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-striped align-middle" id="admin-category-table">
                            <thead>
                                <tr>
                                    <th style="width:60px">No</th>
                                    <th>Nama Kategori</th>
                                    <th class="text-end">Jumlah Produk</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Elektronik</td>
                                    <td class="text-end">120</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Fashion</td>
                                    <td class="text-end">95</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Makanan & Minuman</td>
                                    <td class="text-end">80</td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>Olahraga</td>
                                    <td class="text-end">64</td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>Kecantikan</td>
                                    <td class="text-end">72</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
  var input = document.getElementById('admin-category-search');
  var table = document.getElementById('admin-category-table');
  if(!input || !table) return;
  input.addEventListener('input', function(){
    var q = input.value.toLowerCase();
    table.querySelectorAll('tbody tr').forEach(function(tr){
      var name = tr.children[1].textContent.toLowerCase();
      var show = name.indexOf(q) !== -1;
      tr.style.display = show ? '' : 'none';
    });
  });
});
</script>
@endpush