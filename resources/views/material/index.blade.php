@extends('layouts.app')

@section('title', 'Daftar Material')

@section('content')
    <div class="page-header">
        <div class="page-title">
            <h3>Daftar Material</h3>
            <span style="color: #888;">Kelola data material</span>
        </div>
        <div class="page-stats">
            <div class="pull-right">
                <button type="button" class="btn btn-default" onclick="window.location.reload()">
                    <i class="fa fa-refresh"></i> Refresh
                </button>
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#importModal">
                    <i class="fa fa-upload"></i> Import Excel
                </button>
                <a href="{{ route('dashboard.export') }}" class="btn btn-warning" target="_blank">
                    <i class="fa fa-file-excel-o"></i> Export Excel
                </a>
                <a href="{{ route('material.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus"></i> Tambah Material
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <i class="fa fa-check"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <i class="fa fa-times"></i> {{ session('error') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="portlet">
                <div class="portlet-header" style="display: flex; justify-content: space-between; align-items: center; padding: 10px 15px;">
                    <h3 style="margin: 0; font-size: 18px;">
                        <i class="fa fa-list"></i>
                        Data Material
                    </h3>
                    <div style="display: flex; gap: 15px; align-items: center;">
                        <form action="{{ route('material.index') }}" method="GET" id="searchForm" style="margin: 0;">
                            <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                            <div class="input-group input-group-sm" style="width: 250px;">
                                <input type="text" name="search" class="form-control" placeholder="Cari kode atau deskripsi..." value="{{ request('search') }}">
                                <span class="input-group-btn">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fa fa-search"></i>
                                    </button>
                                    @if(request('search'))
                                    <a href="{{ route('material.index', ['per_page' => request('per_page', 10)]) }}" class="btn btn-default" title="Clear">
                                        <i class="fa fa-times"></i>
                                    </a>
                                    @endif
                                </span>
                            </div>
                        </form>
                        <form action="{{ route('material.index') }}" method="GET" id="perPageForm" style="margin: 0;">
                            <input type="hidden" name="search" value="{{ request('search') }}">
                            <label style="font-weight: normal; margin-right: 5px;">Tampilkan</label>
                            <select name="per_page" class="form-control input-sm" style="display: inline-block; width: auto;" onchange="document.getElementById('perPageForm').submit()">
                                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                            </select>
                            <span style="margin-left: 5px;">entri</span>
                        </form>
                    </div>
                </div>
                <div class="portlet-content" style="padding-top: 0;">
                    @if($materials->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" style="margin-top: 10px;">
                                <thead>
                                    <tr>
                                        <th width="3%">No</th>
                                        <th width="12%">
                                            <a href="{{ route('material.index', array_merge(request()->all(), ['sort_by' => 'material_code', 'sort_direction' => request('sort_by') == 'material_code' && request('sort_direction') == 'asc' ? 'desc' : 'asc'])) }}" style="color: inherit; text-decoration: none;">
                                                Kode Material
                                                @if(request('sort_by') == 'material_code')
                                                    <i class="fa fa-sort-{{ request('sort_direction') == 'asc' ? 'up' : 'down' }}"></i>
                                                @else
                                                    <i class="fa fa-sort" style="opacity: 0.3;"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th width="28%">
                                            <a href="{{ route('material.index', array_merge(request()->all(), ['sort_by' => 'material_description', 'sort_direction' => request('sort_by') == 'material_description' && request('sort_direction') == 'asc' ? 'desc' : 'asc'])) }}" style="color: inherit; text-decoration: none;">
                                                Deskripsi Material
                                                @if(request('sort_by') == 'material_description')
                                                    <i class="fa fa-sort-{{ request('sort_direction') == 'asc' ? 'up' : 'down' }}"></i>
                                                @else
                                                    <i class="fa fa-sort" style="opacity: 0.3;"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th width="8%" class="text-center">
                                            <a href="{{ route('material.index', array_merge(request()->all(), ['sort_by' => 'unrestricted_use_stock', 'sort_direction' => request('sort_by') == 'unrestricted_use_stock' && request('sort_direction') == 'asc' ? 'desc' : 'asc'])) }}" style="color: inherit; text-decoration: none;">
                                                Stock
                                                @if(request('sort_by') == 'unrestricted_use_stock')
                                                    <i class="fa fa-sort-{{ request('sort_direction') == 'asc' ? 'up' : 'down' }}"></i>
                                                @else
                                                    <i class="fa fa-sort" style="opacity: 0.3;"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th width="8%" class="text-center">Satuan</th>
                                        <th width="10%" class="text-center">Rak</th>
                                        <th width="15%" class="text-right">
                                            <a href="{{ route('material.index', array_merge(request()->all(), ['sort_by' => 'total_nilai', 'sort_direction' => request('sort_by') == 'total_nilai' && request('sort_direction') == 'asc' ? 'desc' : 'asc'])) }}" style="color: inherit; text-decoration: none;">
                                                Total Nilai
                                                @if(request('sort_by') == 'total_nilai')
                                                    <i class="fa fa-sort-{{ request('sort_direction') == 'asc' ? 'up' : 'down' }}"></i>
                                                @else
                                                    <i class="fa fa-sort" style="opacity: 0.3;"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th width="14%" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($materials as $index => $material)
                                        <tr>
                                            <td>{{ $materials->firstItem() + $index }}</td>
                                            <td>
                                                <strong>{{ $material->material_code }}</strong>
                                            </td>
                                            <td>
                                                {{ Str::limit($material->material_description, 50) }}
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-info">
                                                    {{ number_format($material->unrestricted_use_stock, 0, ',', '.') }}
                                                </span>
                                            </td>
                                            <td class="text-center">{{ $material->base_unit_of_measure }}</td>
                                            <td class="text-center">
                                                {{ $material->rak ?? '-' }}
                                            </td>
                                            <td class="text-right">
                                                @php
                                                    $totalNilai = $material->harga_satuan * $material->unrestricted_use_stock;
                                                @endphp
                                                @if($material->harga_satuan)
                                                    {{ $material->currency ?? 'IDR' }} {{ number_format($totalNilai, 0, ',', '.') }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-xs">
                                                    <a href="{{ route('material.show', $material) }}" class="btn btn-info" title="Detail">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('material.edit', $material) }}" class="btn btn-warning" title="Edit">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <a href="{{ route('material.generate-barcode', $material) }}" class="btn btn-success" title="Generate Barcode">
                                                        <i class="fa fa-qrcode"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-danger" title="Hapus" 
                                                            onclick="confirmDelete('{{ $material->id }}', '{{ $material->material_code }}')">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="dataTables_info">
                                    Menampilkan {{ $materials->firstItem() }} sampai {{ $materials->lastItem() }} 
                                    dari {{ $materials->total() }} data
                                </div>
                            </div>
                            <div class="col-md-6 text-right">
                                {{ $materials->links() }}
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle"></i> Belum ada data material. 
                            <a href="{{ route('material.create') }}">Tambah material pertama</a>.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Konfirmasi Hapus</h4>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus material <strong id="materialName"></strong>?</p>
                <p class="text-danger"><small>Data yang sudah dihapus tidak dapat dikembalikan.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

<!-- Modal Import Excel -->
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="importModalLabel">Import Data Material via Excel</h4>
            </div>
            <form action="{{ route('material.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="file">Pilih File Excel (.xlsx, .xls, .csv)</label>
                        <input type="file" name="file" id="file" class="form-control" required accept=".xlsx,.xls,.csv">
                        <p class="help-block">Pastikan format header sesuai: material_code, material_description, dll.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> Upload & Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(materialId, materialName) {
    document.getElementById('materialName').textContent = materialName;
    document.getElementById('deleteForm').action = '/material/' + materialId;
    $('#deleteModal').modal('show');
}
</script>
@endpush

@push('styles')
<style>
    /* HIDE Global Layout Header specifically for this page */
    #content > .page-header {
        display: none !important;
    }

    /* Override GLOBAL CONTENT padding */
    #content {
        padding-top: 5px !important; /* Reduced from default 45px */
    }

    /* Override GLOBAL page-content specifics for this page */
    #content .page-content {
        margin-top: 0 !important;
        padding-top: 0 !important;
    }

    /* Local Header Styles with Flexbox */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 5px !important;
        padding-bottom: 10px !important;
        padding-top: 5px !important;
        min-height: auto;
        border-bottom: none !important;
    }
    
    /* Ensure title takes only needed space */
    .page-title {
        margin: 0;
    }
    
    .page-title h3 {
        margin: 0;
        line-height: 1;
        font-weight: bold;
        font-size: 24px;
        color: #555;
    }
    
    .page-title span {
        font-size: 13px;
        color: #888;
        display: block;
        margin-top: 4px;
        line-height: 1;
    }

    /* Reset button container spacing */
    .page-stats {
        margin: 0;
    }
    
    /* Table Borders & Alignment */
    .table-responsive {
        border-left: 1px solid #ddd;
        border-right: 1px solid #ddd;
    }
    
    .table-bordered {
        border-left: 1px solid #ddd !important;
        border-right: 1px solid #ddd !important;
    }
    
    .text-center { text-align: center !important; vertical-align: middle !important; }
    .text-right { text-align: right !important; vertical-align: middle !important; }
    .text-left { text-align: left !important; vertical-align: middle !important; }
    
    /* Fix vertical align for all table cells */
    .table tbody tr td {
        vertical-align: middle;
    }
</style>
@endpush