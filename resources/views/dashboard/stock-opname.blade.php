@extends('layouts.app')

@section('title', 'Stock Opname - ASI System')
@section('page-title', 'Stock Opname')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="fa fa-clipboard-list me-2"></i>
                    Stock Opname Material
                </h5>
            </div>
            
            <div class="card-body">
                <!-- Filter Section -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <label for="filter-company" class="form-label">Company</label>
                        <select class="form-select" id="filter-company">
                            <option value="">Semua Company</option>
                            <option value="5300">5300 - UID Jawa Barat</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label for="filter-plant" class="form-label">Plant</label>
                        <select class="form-select" id="filter-plant">
                            <option value="">Semua Plant</option>
                            <option value="5319">5319 - PLN UP3 Cimahi</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label for="filter-status" class="form-label">Status</label>
                        <select class="form-select" id="filter-status">
                            <option value="">Semua Status</option>
                            <option value="SELESAI">Selesai</option>
                            <option value="PENDING">Pending</option>
                            <option value="PROSES">Proses</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid">
                            <button type="button" class="btn btn-primary" onclick="applyFilter()">
                                <i class="fa fa-filter me-1"></i>
                                Terapkan Filter
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="d-flex flex-wrap gap-2">
                            <button type="button" class="btn btn-success" onclick="startStockOpname()">
                                <i class="fa fa-play me-1"></i>
                                Mulai Stock Opname
                            </button>
                            
                            <button type="button" class="btn btn-info" onclick="exportStockOpname()">
                                <i class="fa fa-download me-1"></i>
                                Export Excel
                            </button>
                            
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                                <i class="fa fa-arrow-left me-1"></i>
                                Kembali ke Dashboard
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Stock Opname Table -->
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="stock-opname-table">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Normalisasi</th>
                                <th>Material</th>
                                <th>Stock System</th>
                                <th>Stock Fisik</th>
                                <th>Tanggal Hitung</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    <i class="fa fa-info-circle fa-2x mb-2"></i><br>
                                    Belum ada data stock opname.<br>
                                    Klik "Mulai Stock Opname" untuk memulai proses.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stock Opname Modal -->
<div class="modal fade" id="stockOpnameModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fa fa-clipboard-check me-2"></i>
                    Input Stock Opname
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="stock-opname-form">
                    <div class="mb-3">
                        <label class="form-label">Cari Material</label>
                        <input type="text" class="form-control" id="material-name" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Stock System</label>
                        <input type="number" class="form-control" id="stock-system" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label for="stock-fisik" class="form-label">Stock Fisik <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="stock-fisik" min="0" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="keterangan-opname" class="form-label">Keterangan</label>
                        <textarea class="form-control" id="keterangan-opname" rows="3" placeholder="Catatan tambahan (opsional)"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="saveStockOpname()">
                    <i class="fa fa-save me-1"></i>
                    Simpan
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Apply Filter Function
function applyFilter() {
    Swal.fire({
        title: 'Filter Diterapkan',
        text: 'Filter akan diterapkan pada data stock opname',
        icon: 'info',
        timer: 1500,
        showConfirmButton: false
    });
}

// Start Stock Opname Function
function startStockOpname() {
    Swal.fire({
        title: 'Mulai Stock Opname?',
        text: 'Sistem akan memuat semua material untuk proses stock opname',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Mulai!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '{{ route("material.stock-opname") }}';
        }
    });
}

// Generate Report Function
function generateReport() {
    Swal.fire({
        title: 'Generate Laporan',
        text: 'Fitur generate laporan stock opname akan segera tersedia',
        icon: 'info'
    });
}

// Export Stock Opname Function
function exportStockOpname() {
    Swal.fire({
        title: 'Export Data',
        text: 'Fitur export data stock opname akan segera tersedia',
        icon: 'info'
    });
}

// Input Stock Fisik Function
function inputStockFisik(materialId, materialName, stockSystem) {
    $('#material-name').val(materialName);
    $('#stock-system').val(stockSystem);
    $('#stock-fisik').val('');
    $('#keterangan-opname').val('');
    $('#stockOpnameModal').modal('show');
}

// Save Stock Opname Function
function saveStockOpname() {
    const stockFisik = $('#stock-fisik').val();
    
    if (!stockFisik) {
        Swal.fire('Error!', 'Stock fisik harus diisi', 'error');
        return;
    }
    
    Swal.fire({
        title: 'Simpan Data?',
        text: 'Data stock opname akan disimpan',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Simpan!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $('#stockOpnameModal').modal('hide');
            Swal.fire({
                title: 'Fitur Dalam Pengembangan',
                text: 'Fitur penyimpanan data stock opname sedang dalam tahap pengembangan',
                icon: 'info'
            });
        }
    });
}

// Initialize page
$(document).ready(function() {
    // Auto calculate selisih when stock fisik is entered
    $('#stock-fisik').on('input', function() {
        const stockSystem = parseFloat($('#stock-system').val()) || 0;
        const stockFisik = parseFloat($(this).val()) || 0;
        const selisih = stockFisik - stockSystem;
        
        // You can add visual indicator for selisih here
        if (selisih !== 0) {
            $(this).addClass(selisih > 0 ? 'border-success' : 'border-danger');
        } else {
            $(this).removeClass('border-success border-danger');
        }
    });
});
</script>
@endpush