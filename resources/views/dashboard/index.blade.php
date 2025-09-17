@extends('layouts.app')

@section('title', 'Dashboard - ASI System')

@section('content')
<div class="dashboard-container">

    <!-- Statistics Cards -->
<div class="row mb-4">
    <div class="card-container col-lg-3 col-md-6 col-sm-12">
        <div class="card">
            <div class="front bg-blue">
                <div class="row">
                    <div class="col-xs-4">
                        <i class="fa fa-boxes fa-4x"></i>
                    </div>
                    <div class="col-xs-8">
                        <p class="text-elg text-strong mb-0">{{ number_format($stats['total_materials']) }}</p>
                        <span>Total Material</span>
                    </div>
                </div>
            </div>
            <div class="back bg-blue">
                <div class="row">
                    <div class="col-xs-4">
                        <a href="#"><i class="fa fa-cog fa-2x"></i> Settings</a>
                    </div>
                    <div class="col-xs-4">
                        <a href="#"><i class="fa fa-eye fa-2x"></i> View</a>
                    </div>
                    <div class="col-xs-4">
                        <a href="#"><i class="fa fa-ellipsis-h fa-2x"></i> More</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-container col-lg-3 col-md-6 col-sm-12">
        <div class="card">
            <div class="front bg-greensea">
                <div class="row">
                    <div class="col-xs-4">
                        <i class="fa fa-cubes fa-4x"></i>
                    </div>
                    <div class="col-xs-8">
                        <p class="text-elg text-strong mb-0">{{ number_format($stats['total_stock']) }}</p>
                        <span>Total Stock</span>
                    </div>
                </div>
            </div>
            <div class="back bg-greensea">
                <div class="row">
                    <div class="col-xs-4">
                        <a href="#"><i class="fa fa-cog fa-2x"></i> Settings</a>
                    </div>
                    <div class="col-xs-4">
                        <a href="#"><i class="fa fa-eye fa-2x"></i> View</a>
                    </div>
                    <div class="col-xs-4">
                        <a href="#"><i class="fa fa-ellipsis-h fa-2x"></i> More</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-container col-lg-3 col-md-6 col-sm-12">
        <div class="card">
            <div class="front bg-orange">
                <div class="row">
                    <div class="col-xs-4">
                        <i class="fa fa-users fa-4x"></i>
                    </div>
                    <div class="col-xs-8">
                        <p class="text-elg text-strong mb-0">{{ number_format($stats['total_users']) }}</p>
                        <span>Total User</span>
                    </div>
                </div>
            </div>
            <div class="back bg-orange">
                <div class="row">
                    <div class="col-xs-4">
                        <a href="#"><i class="fa fa-cog fa-2x"></i> Settings</a>
                    </div>
                    <div class="col-xs-4">
                        <a href="#"><i class="fa fa-eye fa-2x"></i> View</a>
                    </div>
                    <div class="col-xs-4">
                        <a href="#"><i class="fa fa-ellipsis-h fa-2x"></i> More</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-container col-lg-3 col-md-6 col-sm-12">
        <div class="card">
            <div class="front bg-lightred">
                <div class="row">
                    <div class="col-xs-4">
                        <i class="fa fa-clock fa-4x"></i>
                    </div>
                    <div class="col-xs-8">
                        <p class="text-elg text-strong mb-0">{{ number_format($stats['pending_materials']) }}</p>
                        <span>Material Pending</span>
                    </div>
                </div>
            </div>
            <div class="back bg-lightred">
                <div class="row">
                    <div class="col-xs-4">
                        <a href="#"><i class="fa fa-cog fa-2x"></i> Settings</a>
                    </div>
                    <div class="col-xs-4">
                        <a href="#"><i class="fa fa-eye fa-2x"></i> View</a>
                    </div>
                    <div class="col-xs-4">
                        <a href="#"><i class="fa fa-ellipsis-h fa-2x"></i> More</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Quick Actions</h5>
                <div class="btn-group" role="group">
                    <a href="{{ route('material.create') }}" class="btn btn-primary mb-10 mr-10">
                        <i class="fa fa-plus"></i> Tambah Material
                    </a>
                    <button type="button" class="btn btn-info mb-10 mr-10" onclick="refreshTable()">
                        <i class="fa fa-sync-alt"></i> Refresh Data
                    </button>
                    <button type="button" class="btn btn-warning mb-10" onclick="exportData()">
                        <i class="fa fa-download"></i> Export Excel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Materials DataTable -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-table me-2"></i>
                    Master Material
                </h5>
            </div>
            
            <div class="card-body">
                <div class="table-responsive">
                    <table id="materials-table" class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Normalisasi</th>
                                <th>Material</th>
                                <th>Stock</th>
                                <th>Status</th>
                                <th>Harga Satuan</th>
                                <th>Total Harga</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Detail Modal -->
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle me-2"></i>
                    Detail Material
                </h5>
                <button type="button" class="btn-close" data-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detail-content">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
</div>
@endsection

@push('scripts')
<script>
let materialsTable;

$(document).ready(function() {
    // Initialize DataTable
    materialsTable = $('#materials-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('dashboard.data') }}',
            type: 'GET'
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'material_code', name: 'normalisasi' },
            { data: 'material_description', name: 'material_description' },
            { data: 'unrestricted_use_stock', name: 'unrestricted_use_stock', className: 'text-center' },
            { data: 'status', name: 'status', className: 'text-center' },
            { data: 'harga_satuan', name: 'harga_satuan', className: 'text-end' },
            { data: 'total_harga', name: 'total_harga', className: 'text-end' },
            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
        ],
        order: [[2, 'desc']],
        pageLength: 25,
        responsive: true,
        language: {
            "sProcessing": "Sedang memproses...",
            "sLengthMenu": "Tampilkan _MENU_ entri",
            "sZeroRecords": "Tidak ditemukan data yang sesuai",
            "sInfo": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
            "sInfoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
            "sInfoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
            "sSearch": "Cari:",
            "oPaginate": {
                "sFirst": "Pertama",
                "sPrevious": "Sebelumnya",
                "sNext": "Selanjutnya",
                "sLast": "Terakhir"
            }
        },
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
             '<"row"<"col-sm-12"tr>>' +
             '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
    });
});

// View Detail Function
function viewDetail(id) {
    $.ajax({
        url: '{{ route('dashboard.show', ':id') }}'.replace(':id', id),
        type: 'GET',
        success: function(response) {
            if (response.success) {
                const material = response.data;
                const detailHtml = `
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">Informasi Dasar</h6>
                            <table class="table table-sm">
                                <tr><td><strong>Nomor:</strong></td><td>${material.nomor}</td></tr>
                                <tr><td><strong>Material Code:</strong></td><td>${material.material_code}</td></tr>
                                <tr><td><strong>Deskripsi:</strong></td><td>${material.material_description}</td></tr>
                                <tr><td><strong>Stock:</strong></td><td>${material.unrestricted_use_stock} ${material.base_unit_of_measure}</td></tr>
                                <tr><td><strong>Rak:</strong></td><td>${material.rak}</td></tr>
                                <tr><td><strong>Status:</strong></td><td><span class="badge bg-info">${material.status}</span></td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary">Informasi Material</h6>
                            <table class="table table-sm">
                                <tr><td><strong>Company:</strong></td><td>${material.company_code} - ${material.company_code_description}</td></tr>
                                <tr><td><strong>Plant:</strong></td><td>${material.plant} - ${material.plant_description}</td></tr>
                                <tr><td><strong>Storage:</strong></td><td>${material.storage_location} - ${material.storage_location_description}</td></tr>
                                <tr><td><strong>Material Type:</strong></td><td>${material.material_type} - ${material.material_type_description}</td></tr>
                                <tr><td><strong>Material Group:</strong></td><td>${material.material_group}</td></tr>
                                <tr><td><strong>Valuation:</strong></td><td>${material.valuation_class} - ${material.valuation_description}</td></tr>
                            </table>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6 class="text-primary">Informasi Stock & Harga</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Unrestricted Stock:</strong></td>
                                    <td>${material.unrestricted_use_stock}</td>
                                    <td><strong>Quality Inspection:</strong></td>
                                    <td>${material.quality_inspection_stock}</td>
                                </tr>
                                <tr>
                                    <td><strong>Blocked Stock:</strong></td>
                                    <td>${material.blocked_stock}</td>
                                    <td><strong>In Transit:</strong></td>
                                    <td>${material.in_transit_stock}</td>
                                </tr>
                                <tr>
                                    <td><strong>Harga Satuan:</strong></td>
                                    <td>Rp ${new Intl.NumberFormat('id-ID').format(material.harga_satuan)}</td>
                                    <td><strong>Total Harga:</strong></td>
                                    <td>Rp ${new Intl.NumberFormat('id-ID').format(material.total_harga)}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6 class="text-primary">Informasi Tambahan</h6>
                            <table class="table table-sm">
                                <tr><td><strong>Tanggal Terima:</strong></td><td>${new Date(material.tanggal_terima).toLocaleDateString('id-ID')}</td></tr>
                                <tr><td><strong>Keterangan:</strong></td><td>${material.keterangan || '-'}</td></tr>
                                <tr><td><strong>Normalisasi:</strong></td><td>${material.normalisasi || '-'}</td></tr>
                                <tr><td><strong>WBS Element:</strong></td><td>${material.wbs_element || '-'}</td></tr>
                            </table>
                        </div>
                    </div>
                `;
                
                $('#detail-content').html(detailHtml);
                $('#detailModal').modal('show');
            }
        },
        error: function() {
            Swal.fire('Error!', 'Gagal memuat detail material', 'error');
        }
    });
}

// Edit Material Function
function editMaterial(id) {
    window.location.href = '{{ route('material.edit', ':id') }}'.replace(':id', id);
}

// Delete Material Function
function deleteMaterial(id) {
    Swal.fire({
        title: 'Hapus Material?',
        text: 'Data yang dihapus tidak dapat dikembalikan!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '{{ route('dashboard.destroy', ':id') }}'.replace(':id', id),
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Berhasil!', response.message, 'success');
                        materialsTable.ajax.reload();
                    } else {
                        Swal.fire('Error!', response.message, 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error!', 'Gagal menghapus material', 'error');
                }
            });
        }
    });
}

// Refresh Table Function
function refreshTable() {
    materialsTable.ajax.reload();
    Swal.fire({
        title: 'Data Diperbarui!',
        text: 'Tabel telah diperbarui dengan data terbaru',
        icon: 'success',
        timer: 1500,
        showConfirmButton: false
    });
}

// Export Data Function
function exportData() {
    Swal.fire({
        title: 'Export Data',
        text: 'Fitur export akan segera tersedia',
        icon: 'info'
    });
}
</script>
@endpush