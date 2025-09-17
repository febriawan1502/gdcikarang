@extends('layouts.app')

@section('title', 'Daftar Material')

@section('content')
<div class="page-content">
    <div class="page-header">
        <div class="page-title">
            <h1>Daftar Material</h1>
            <h4>Kelola data material</h4>
        </div>
        <div class="page-stats">
            <ul>
                <li>
                    <a href="{{ route('material.create') }}" class="btn btn-primary">
                        <i class="fa fa-plus"></i> Tambah Material
                    </a>
                </li>
            </ul>
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
                <div class="portlet-header">
                    <h3>
                        <i class="fa fa-list"></i>
                        Data Material
                    </h3>
                </div>
                <div class="portlet-content">
                    @if($materials->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="15%">Nomor KR</th>
                                        <th width="25%">Deskripsi Material</th>
                                        <th width="10%">Quantity</th>
                                        <th width="10%">Satuan</th>
                                        <th width="10%">Status</th>
                                        <th width="15%">Harga Satuan</th>
                                        <th width="10%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($materials as $index => $material)
                                        <tr>
                                            <td>{{ $materials->firstItem() + $index }}</td>
                                            <td>
                                                <strong>{{ $material->nomor_kr }}</strong>
                                            </td>
                                            <td>
                                                {{ Str::limit($material->material_description, 50) }}
                                                @if($material->pabrikan)
                                                    <br><small class="text-muted">{{ $material->pabrikan }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge badge-info">
                                                    {{ number_format($material->qty, 0, ',', '.') }}
                                                </span>
                                            </td>
                                            <td>{{ $material->base_unit_of_measure }}</td>
                                            <td>
                                                @if($material->status === 'aktif')
                                                    <span class="badge badge-success">Aktif</span>
                                                @else
                                                    <span class="badge badge-danger">Tidak Aktif</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($material->unit_price)
                                                    {{ $material->currency ?? 'IDR' }} {{ number_format($material->unit_price, 0, ',', '.') }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-xs">
                                                    <a href="{{ route('material.show', $material) }}" class="btn btn-info" title="Detail">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('material.edit', $material) }}" class="btn btn-warning" title="Edit">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-danger" title="Hapus" 
                                                            onclick="confirmDelete('{{ $material->id }}', '{{ $material->nomor_kr }}')">
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
                            <div class="col-md-6">
                                <div class="dataTables_paginate paging_bootstrap">
                                    {{ $materials->links() }}
                                </div>
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

@push('scripts')
<script>
function confirmDelete(materialId, materialName) {
    document.getElementById('materialName').textContent = materialName;
    document.getElementById('deleteForm').action = '/material/' + materialId;
    $('#deleteModal').modal('show');
}
</script>
@endpush