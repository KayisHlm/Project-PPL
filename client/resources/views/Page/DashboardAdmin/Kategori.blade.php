@extends('Layout.Admin')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="header-title mb-0">Daftar Kategori</h4>
                    <div class="d-flex gap-2">
                        <div class="input-group w-auto">
                            <span class="input-group-text"><i class="ri-search-line"></i></span>
                            <input type="text" class="form-control form-control-sm" id="admin-category-search" placeholder="Cari kategori...">
                        </div>
                        <a href="{{ route('dashboard-admin.tambah-kategori') }}" class="btn btn-primary btn-sm">
                            <i class="ri-add-line me-1"></i> Tambah Kategori
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-striped align-middle" id="admin-category-table">
                            <thead>
                                <tr>
                                    <th style="width:60px">No</th>
                                    <th>Nama Kategori</th>
                                    <th>Dibuat Pada</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php($i = 1)
                                @forelse(($categories ?? []) as $c)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $c['name'] ?? '' }}</td>
                                    <td>{{ isset($c['createdAt']) ? date('d M Y', strtotime($c['createdAt'])) : '-' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Belum ada kategori</td>
                                </tr>
                                @endforelse
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
