@extends('layouts.app')

@section('title', 'Dashboard - ASI System')

@section('content')
<div class="dashboard-container">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card stat-primary">
                <div class="card-body">
                    <div class="stat-content">
                        <div class="stat-icon">
                            <i class="fa fa-boxes"></i>
                        </div>
                        <div class="stat-info">
                            <h3 class="stat-number" id="totalMaterials">{{ $stats['total_materials'] ?? 0 }}</h3>
                            <p class="stat-label">Total Material</p>
                        </div>
                    </div>
                    <div class="stat-footer">
                        <small class="text-muted">
                            <i class="fa fa-info-circle"></i>
                            Semua material terdaftar
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card stat-success">
                <div class="card-body">
                    <div class="stat-content">
                        <div class="stat-icon">
                            <i class="fa fa-check-circle"></i>
                        </div>
                        <div class="stat-info">
                            <h3 class="stat-number" id="activeMaterials">{{ $stats['active_materials'] ?? 0 }}</h3>
                            <p class="stat-label">Material Aktif</p>
                        </div>
                    </div>
                    <div class="stat-footer">
                        <small class="text-muted">
                            <i class="fa fa-arrow-up text-success"></i>
                            Status aktif
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card stat-warning">
                <div class="card-body">
                    <div class="stat-content">
                        <div class="stat-icon">
                            <i class="fa fa-exclamation-triangle"></i>
                        </div>
                        <div class="stat-info">
                            <h3 class="stat-number" id="lowStockMaterials">{{ $stats['low_stock_materials'] ?? 0 }}</h3>
                            <p class="stat-label">Stok Rendah</p>
                        </div>
                    </div>
                    <div class="stat-footer">
                        <small class="text-muted">
                            <i class="fa fa-arrow-down text-warning"></i>
                            Perlu perhatian
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card stat-info">
                <div class="card-body">
                    <div class="stat-content">
                        <div class="stat-icon">
                            <i class="fa fa-dollar-sign"></i>
                        </div>
                        <div class="stat-info">
                            <h3 class="stat-number" id="totalValue">Rp {{ number_format($stats['total_value'] ?? 0, 0, ',', '.') }}</h3>
                            <p class="stat-label">Total Nilai</p>
                        </div>
                    </div>
                    <div class="stat-footer">
                        <small class="text-muted">
                            <i class="fa fa-calculator"></i>
                            Nilai inventori
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Materials DataTable Removed -->

    <!-- Recent Activities -->
    <div class="row" style="margin-top: 20px;">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <i class="fa fa-clock-o"></i>
                        Aktivitas Terbaru
                    </h4>
                </div>
                <div class="panel-body">
                    <div class="activity-list" id="recentActivities">
                        <!-- Activities will be loaded via AJAX -->
                        <div class="text-center" style="padding: 15px 0;">
                            <i class="fa fa-spinner fa-spin fa-2x text-primary"></i>
                            <p style="margin-top: 10px;">Loading...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="panel panel-warning">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <i class="fa fa-exclamation-triangle"></i>
                        Material Stok Rendah
                    </h4>
                </div>
                <div class="panel-body">
                    <div class="low-stock-list" id="lowStockList">
                        <!-- Low stock items will be loaded via AJAX -->
                        <div class="text-center" style="padding: 15px 0;">
                            <i class="fa fa-spinner fa-spin fa-2x text-warning"></i>
                            <p style="margin-top: 10px;">Loading...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Material Detail Modal -->
<div class="modal fade" id="materialDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fa fa-info-circle"></i>
                    Detail Material
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="materialDetailContent">
                <!-- Content will be loaded via AJAX -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Dashboard Styles */
.dashboard-container {
    padding: 20px 0;
}

.dashboard-header {
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 2px solid #e9ecef;
}

.dashboard-title {
    font-size: 2rem;
    font-weight: 700;
    color: #495057;
    margin-bottom: 5px;
}

.dashboard-subtitle {
    color: #6c757d;
    margin-bottom: 0;
}

.dashboard-actions {
    display: flex;
    gap: 10px;
    justify-content: flex-end;
}

/* Statistics Cards */
.stat-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    overflow: hidden;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
}

.stat-card.stat-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.stat-card.stat-success {
    background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
    color: white;
}

.stat-card.stat-warning {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
}

.stat-card.stat-info {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    color: white;
}

.stat-content {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
}

.stat-icon {
    font-size: 2.5rem;
    margin-right: 20px;
    opacity: 0.8;
}

.stat-info h3 {
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 5px;
}

.stat-info p {
    font-size: 0.9rem;
    margin-bottom: 0;
    opacity: 0.9;
}

.stat-footer {
    border-top: 1px solid rgba(255, 255, 255, 0.2);
    padding-top: 10px;
}

/* Card Styles */
.card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.card-header {
    background: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
    border-radius: 15px 15px 0 0 !important;
    padding: 20px;
}

.card-title {
    font-weight: 600;
    color: #495057;
}

.card-actions {
    display: flex;
    gap: 10px;
}

/* DataTable Styles */
.table {
    margin-bottom: 0;
}

.table thead th {
    border-top: none;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
}

.table tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}

/* Activity List */
.activity-list {
    max-height: 400px;
    overflow-y: auto;
}

.activity-item {
    display: flex;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #e9ecef;
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    font-size: 0.9rem;
}

.activity-content {
    flex: 1;
}

.activity-title {
    font-weight: 600;
    margin-bottom: 2px;
    font-size: 0.9rem;
}

.activity-time {
    font-size: 0.8rem;
    color: #6c757d;
}

/* Low Stock List */
.low-stock-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #e9ecef;
}

.low-stock-item:last-child {
    border-bottom: none;
}

.low-stock-info {
    flex: 1;
}

.low-stock-name {
    font-weight: 600;
    margin-bottom: 2px;
    font-size: 0.9rem;
}

.low-stock-code {
    font-size: 0.8rem;
    color: #6c757d;
}

.low-stock-quantity {
    font-weight: 600;
    color: #dc3545;
}

/* Responsive */
@media (max-width: 768px) {
    .dashboard-header .row {
        text-align: center;
    }
    
    .dashboard-actions {
        justify-content: center;
        margin-top: 15px;
    }
    
    .card-actions {
        justify-content: center;
    }
    
    .stat-content {
        flex-direction: column;
        text-align: center;
    }
    
    .stat-icon {
        margin-right: 0;
        margin-bottom: 10px;
    }
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // DataTable removed


    // Load recent activities
    loadRecentActivities();

    // Load low stock materials
    loadLowStockMaterials();

    // Material detail modal
    $(document).on('click', '.btn-detail', function() {
        const materialId = $(this).data('id');
        loadMaterialDetail(materialId);
    });

    function loadRecentActivities() {
        $.get('{{ route("dashboard.activities") }}')
            .done(function(data) {
                $('#recentActivities').html(data);
            })
            .fail(function() {
                $('#recentActivities').html('<p class="text-muted text-center">Gagal memuat aktivitas terbaru</p>');
            });
    }

    function loadLowStockMaterials() {
        $.get('{{ route("dashboard.low-stock") }}')
            .done(function(data) {
                $('#lowStockList').html(data);
            })
            .fail(function() {
                $('#lowStockList').html('<p class="text-muted text-center">Gagal memuat data stok rendah</p>');
            });
    }

    function loadMaterialDetail(materialId) {
        $('#materialDetailContent').html('<div class="text-center py-3"><div class="spinner-border" role="status"></div></div>');
        $('#materialDetailModal').modal('show');

        $.get('{{ route("materials.show", ":id") }}'.replace(':id', materialId))
            .done(function(data) {
                $('#materialDetailContent').html(data);
            })
            .fail(function() {
                $('#materialDetailContent').html('<p class="text-danger text-center">Gagal memuat detail material</p>');
            });
    }

    // Auto refresh statistics every 5 minutes
    setInterval(function() {
        $.get('{{ route("dashboard.stats") }}')
            .done(function(data) {
                $('#totalMaterials').text(data.total_materials);
                $('#activeMaterials').text(data.active_materials);
                $('#lowStockMaterials').text(data.low_stock_materials);
                $('#totalValue').text('Rp ' + new Intl.NumberFormat('id-ID').format(data.total_value));
            });
    }, 300000); // 5 minutes
});
</script>
@endpush