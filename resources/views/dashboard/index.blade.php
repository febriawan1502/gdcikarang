@extends('layouts.app')

@section('title', 'Dashboard - Pojok IMS')
@section('breadcrumb', 'Dashboard')

@section('content')
<style>
    /* Dashboard Title */
    .dashboard-title {
        font-size: 24px;
        font-weight: 700;
        color: #2D3748;
        margin-bottom: 24px;
    }

    /* Stats Cards Row */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 24px;
    }

    @media (max-width: 1200px) {
        .stats-row { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 768px) {
        .stats-row { grid-template-columns: 1fr; }
    }

    /* Colored Stats Card */
    .stat-card {
        background: linear-gradient(126.97deg, #60A5FA 28.26%, #3B82F6 91.2%);
        border-radius: 15px;
        padding: 20px;
        color: white;
        display: flex;
        align-items: center;
        gap: 16px;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .stat-card.teal { background: linear-gradient(126.97deg, #4FD1C5 28.26%, #38B2AC 91.2%); }
    .stat-card.blue { background: linear-gradient(126.97deg, #60A5FA 28.26%, #3B82F6 91.2%); }
    .stat-card.orange { background: linear-gradient(126.97deg, #F6AD55 28.26%, #ED8936 91.2%); }
    .stat-card.yellow { background: linear-gradient(126.97deg, #F6E05E 28.26%, #ECC94B 91.2%); color: #744210; }

    .stat-card-icon {
        width: 48px;
        height: 48px;
        background: white;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .stat-card-icon i {
        font-size: 20px;
        color: #4FD1C5;
    }

    .stat-card.blue .stat-card-icon i { color: #3B82F6; }
    .stat-card.orange .stat-card-icon i { color: #ED8936; }
    .stat-card.yellow .stat-card-icon i { color: #D69E2E; }

    .stat-card-content {
        flex: 1;
    }

    .stat-card-value {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 2px;
    }

    .stat-card-label {
        font-size: 13px;
        opacity: 0.9;
    }

    /* Large Value Cards */
    .value-cards-row {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-bottom: 24px;
    }

    @media (max-width: 768px) {
        .value-cards-row { grid-template-columns: 1fr; }
    }

    .value-card {
        background: white;
        border-radius: 15px;
        padding: 24px;
        box-shadow: 0 3.5px 5.5px rgba(0, 0, 0, 0.05);
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .value-card-icon {
        width: 56px;
        height: 56px;
        background: linear-gradient(126.97deg, #4FD1C5 28.26%, #38B2AC 91.2%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 24px;
    }

    .value-card-content {
        flex: 1;
    }

    .value-card-value {
        font-size: 28px;
        font-weight: 700;
        color: #2D3748;
        margin-bottom: 4px;
    }

    .value-card-label {
        font-size: 13px;
        color: #718096;
    }

    /* Dashboard Grid */
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-bottom: 24px;
    }

    @media (max-width: 1024px) {
        .dashboard-grid { grid-template-columns: 1fr; }
    }

    /* Panel Cards */
    .panel-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 3.5px 5.5px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .panel-header {
        padding: 16px 20px;
        border-bottom: 1px solid #E2E8F0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .panel-header i {
        color: #4FD1C5;
    }

    .panel-header h3 {
        font-size: 14px;
        font-weight: 600;
        color: #2D3748;
        margin: 0;
    }

    .panel-body {
        padding: 20px;
    }

    /* Data Tables */
    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table thead th {
        text-align: left;
        padding: 10px 12px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        color: #A0AEC0;
        border-bottom: 1px solid #E2E8F0;
    }

    .data-table tbody td {
        padding: 12px;
        font-size: 13px;
        color: #4A5568;
        border-bottom: 1px solid #EDF2F7;
        vertical-align: middle;
    }

    .data-table tbody tr:last-child td {
        border-bottom: none;
    }

    .data-table tbody tr:hover {
        background: #F7FAFC;
    }

    /* Stock Badge */
    .stock-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .stock-badge.success { background: #C6F6D5; color: #22543D; }
    .stock-badge.warning { background: #FEFCBF; color: #744210; }
    .stock-badge.danger { background: #FED7D7; color: #742A2A; }
    .stock-badge.info { background: #BEE3F8; color: #2A4365; }

    .stock-bar {
        width: 60px;
        height: 6px;
        background: #E2E8F0;
        border-radius: 3px;
        overflow: hidden;
    }

    .stock-bar-fill {
        height: 100%;
        border-radius: 3px;
    }

    .stock-bar-fill.success { background: #48BB78; }
    .stock-bar-fill.warning { background: #ECC94B; }
    .stock-bar-fill.danger { background: #F56565; }
    .stock-bar-fill.info { background: #4299E1; }

    /* Material Saving Card */
    .saving-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
    }

    .saving-item {
        padding: 12px;
        background: #F7FAFC;
        border-radius: 10px;
    }

    .saving-item-label {
        font-size: 12px;
        color: #718096;
        margin-bottom: 4px;
    }

    .saving-item-value {
        font-size: 16px;
        font-weight: 600;
        color: #2D3748;
    }

    .saving-total {
        margin-top: 16px;
        padding: 16px;
        background: linear-gradient(126.97deg, #E6FFFA 28.26%, #B2F5EA 91.2%);
        border-radius: 10px;
    }

    .saving-total-label {
        font-size: 12px;
        color: #234E52;
        margin-bottom: 4px;
    }

    .saving-total-value {
        font-size: 20px;
        font-weight: 700;
        color: #234E52;
    }

    .saving-chart {
        height: 80px;
        display: flex;
        align-items: flex-end;
        gap: 8px;
        margin-top: 16px;
    }

    .chart-bar {
        flex: 1;
        background: #4FD1C5;
        border-radius: 4px 4px 0 0;
        transition: height 0.3s;
    }
</style>

<!-- Dashboard Title -->
<h1 class="dashboard-title">Dashboard - Pojok IMS</h1>

<!-- Stats Cards -->
<div class="stats-row">
    <div class="stat-card teal">
        <div class="stat-card-icon">
            <i class="fas fa-plug"></i>
        </div>
        <div class="stat-card-content">
            <div class="stat-card-value">{{ number_format($stats['total_materials']) }}</div>
            <div class="stat-card-label">Total Material</div>
        </div>
    </div>

    <div class="stat-card blue">
        <div class="stat-card-icon">
            <i class="fas fa-cubes"></i>
        </div>
        <div class="stat-card-content">
            <div class="stat-card-value">{{ number_format($stats['total_stock']) }}</div>
            <div class="stat-card-label">Total Stock</div>
        </div>
    </div>

    <div class="stat-card orange">
        <a href="{{ route('material-masuk.index') }}" wire:navigate style="color: inherit; text-decoration: none; display: flex; align-items: center; gap: 16px; width: 100%;">
            <div class="stat-card-icon">
                <i class="fas fa-arrow-down"></i>
            </div>
            <div class="stat-card-content">
                <div class="stat-card-value">{{ number_format($stats['total_material_masuk']) }}</div>
                <div class="stat-card-label">Total Material Masuk</div>
            </div>
        </a>
    </div>

    <div class="stat-card yellow">
        <a href="{{ route('surat-jalan.index') }}" wire:navigate style="color: inherit; text-decoration: none; display: flex; align-items: center; gap: 16px; width: 100%;">
            <div class="stat-card-icon">
                <i class="fas fa-truck"></i>
            </div>
            <div class="stat-card-content">
                <div class="stat-card-value">{{ number_format($stats['total_surat_jalan']) }}</div>
                <div class="stat-card-label">Total Surat Jalan</div>
            </div>
        </a>
    </div>
</div>

<!-- Value Cards -->
<div class="value-cards-row">
    <div class="value-card">
        <div class="value-card-icon">
            <i class="fas fa-money-bill-wave"></i>
        </div>
        <div class="value-card-content">
            <div class="value-card-value">Rp {{ number_format($stats['total_saldo'], 0, ',', '.') }}</div>
            <div class="value-card-label">Total Saldo Gudang</div>
        </div>
    </div>

    <div class="value-card">
        <div class="value-card-icon" style="background: linear-gradient(126.97deg, #F6AD55 28.26%, #ED8936 91.2%);">
            <i class="fas fa-search"></i>
        </div>
        <div class="value-card-content">
            <div class="value-card-value">Rp {{ number_format($material_saving_config->total_inspeksi, 0, ',', '.') }}</div>
            <div class="value-card-label">Total Inspeksi</div>
        </div>
    </div>
</div>

<!-- Row 1: Fast Moving + Material Saving -->
<div class="dashboard-grid">
    <!-- 10 Material Fast Moving -->
    <div class="panel-card">
        <div class="panel-header">
            <i class="fas fa-fire"></i>
            <h3>10 Material Fast Moving</h3>
        </div>
        <div class="panel-body">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Normalisasi</th>
                        <th>Nama Material</th>
                        <th>Stok</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($fast_moving_materials as $mat)
                    <tr>
                        <td>{{ $mat->material_code }}</td>
                        <td>{{ Str::limit($mat->material_description, 35) }}</td>
                        <td>
                            <span class="stock-badge info">{{ number_format($mat->unrestricted_use_stock, 0, ',', '.') }}</span>
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <div class="stock-bar">
                                    <div class="stock-bar-fill {{ $mat->stock_badge }}" style="width: {{ min(100, ($mat->unrestricted_use_stock / max(1, $mat->unrestricted_use_stock + 100)) * 100) }}%;"></div>
                                </div>
                                <span class="stock-badge {{ $mat->stock_badge }}">{{ strtoupper($mat->stock_status) }}</span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" style="text-align: center; color: #A0AEC0;">Belum ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Klasifikasi Material Saving -->
    <div class="panel-card" x-data>
        <div class="panel-header">
            <i class="fas fa-chart-pie"></i>
            <h3>Klarifikasi Material Saving</h3>
            <button x-on:click="$dispatch('open-modal', 'materialSavingConfigModal')" style="margin-left: auto; background: none; border: none; cursor: pointer; color: #718096;">
                <i class="fas fa-cog"></i>
            </button>
        </div>
        <div class="panel-body">
            <div class="saving-grid">
                <div class="saving-item">
                    <div class="saving-item-label">Standby:</div>
                    <div class="saving-item-value">Rp {{ number_format($material_saving_config->standby, 0, ',', '.') }}</div>
                </div>
                <div class="saving-item">
                    <div class="saving-item-label">Perbaikan:</div>
                    <div class="saving-item-value">Rp {{ number_format($material_saving_config->perbaikan, 0, ',', '.') }}</div>
                </div>
                <div class="saving-item">
                    <div class="saving-item-label">Garansi:</div>
                    <div class="saving-item-value">Rp {{ number_format($material_saving_config->garansi, 0, ',', '.') }}</div>
                </div>
                <div class="saving-item">
                    <div class="saving-item-label">Usul Hapus:</div>
                    <div class="saving-item-value">Rp {{ number_format($material_saving_config->usul_hapus, 0, ',', '.') }}</div>
                </div>
            </div>
            <div class="saving-total">
                <div class="saving-total-label">Total Inspeksi</div>
                <div class="saving-total-value">Rp {{ number_format($material_saving_config->total_inspeksi, 0, ',', '.') }}</div>
                <small style="color: #4A5568; font-size: 11px;">Standby + Garansi + Perbaikan + Usul Hapus</small>
            </div>
            <div class="saving-chart">
                @php
                    $total = max(1, $material_saving_config->total_inspeksi);
                    $heights = [
                        min(80, ($material_saving_config->standby / $total) * 100),
                        min(80, ($material_saving_config->perbaikan / $total) * 100),
                        min(80, ($material_saving_config->garansi / $total) * 100),
                        min(80, ($material_saving_config->usul_hapus / $total) * 100),
                    ];
                @endphp
                <div class="chart-bar" style="height: {{ $heights[0] }}%; background: #4FD1C5;"></div>
                <div class="chart-bar" style="height: {{ $heights[1] }}%; background: #63B3ED;"></div>
                <div class="chart-bar" style="height: {{ $heights[2] }}%; background: #F6AD55;"></div>
                <div class="chart-bar" style="height: {{ $heights[3] }}%; background: #FC8181;"></div>
                <div class="chart-bar" style="height: 60%; background: #68D391;"></div>
                <div class="chart-bar" style="height: 40%; background: #9F7AEA;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Row 2: Nilai Material + Stock Material -->
<div class="dashboard-grid">
    <!-- 10 Nilai Material Terbesar -->
    <div class="panel-card">
        <div class="panel-header">
            <i class="fas fa-dollar-sign"></i>
            <h3>10 Nilai Material Terbesar</h3>
        </div>
        <div class="panel-body">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Deskripsi</th>
                        <th style="text-align: right;">Total Nilai</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($top_value_materials as $mat)
                    <tr>
                        <td>{{ $mat->material_code }}</td>
                        <td>{{ Str::limit($mat->material_description, 28) }}</td>
                        <td style="text-align: right; font-weight: 600;">Rp {{ number_format($mat->calculated_total_value, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" style="text-align: center; color: #A0AEC0;">Belum ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- 10 Stock Material Terbesar -->
    <div class="panel-card">
        <div class="panel-header">
            <i class="fas fa-boxes"></i>
            <h3>10 Stock Material Terbesar</h3>
        </div>
        <div class="panel-body">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Deskripsi</th>
                        <th style="text-align: right;">Stock</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($top_stock_materials as $mat)
                    <tr>
                        <td>{{ $mat->material_code }}</td>
                        <td>{{ Str::limit($mat->material_description, 28) }}</td>
                        <td style="text-align: right;">
                            <div style="display: flex; align-items: center; justify-content: flex-end; gap: 8px;">
                                <div class="stock-bar" style="width: 40px;">
                                    <div class="stock-bar-fill info" style="width: 70%;"></div>
                                </div>
                                <span class="stock-badge info">{{ number_format($mat->unrestricted_use_stock, 0, ',', '.') }}</span>
                                <small style="color: #718096;">{{ $mat->base_unit_of_measure }}</small>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" style="text-align: center; color: #A0AEC0;">Belum ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Row 3: Surat Jalan + Material Masuk -->
<div class="dashboard-grid">
    <!-- 10 Surat Jalan Terakhir -->
    <div class="panel-card">
        <div class="panel-header">
            <i class="fas fa-truck"></i>
            <h3>10 Surat Jalan Terakhir</h3>
        </div>
        <div class="panel-body">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No Surat</th>
                        <th>Tanggal</th>
                        <th>Penerima</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($latest_surat_jalan as $sj)
                    <tr>
                        <td>{{ $sj->nomor_surat }}</td>
                        <td>{{ $sj->tanggal ? $sj->tanggal->format('d/m/Y') : '-' }}</td>
                        <td>{{ $sj->kepada }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" style="text-align: center; color: #A0AEC0;">Belum ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- 10 Material Masuk Terakhir -->
    <div class="panel-card">
        <div class="panel-header">
            <i class="fas fa-arrow-down"></i>
            <h3>10 Material Masuk Terakhir</h3>
        </div>
        <div class="panel-body">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No PO</th>
                        <th>Tanggal</th>
                        <th>Pabrikan/Pengirim</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($latest_material_masuk as $mm)
                    <tr>
                        <td>{{ $mm->nomor_po ?? '-' }}</td>
                        <td>{{ $mm->tanggal_masuk ? $mm->tanggal_masuk->format('d/m/Y') : '-' }}</td>
                        <td>{{ $mm->pabrikan }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" style="text-align: center; color: #A0AEC0;">Belum ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Material Saving Configuration Modal (Component) -->
<x-modal name="materialSavingConfigModal" title="Konfigurasi Klasifikasi Material Saving">
    <x-slot:icon>
        <i class="fas fa-cog" style="color: #4FD1C5;"></i>
    </x-slot:icon>

    <form id="materialSavingConfigForm">
        @csrf
        
        <div class="alert-purity info" style="margin-bottom: 20px;">
            <i class="fas fa-info-circle"></i>
            Total Inspeksi akan dihitung otomatis dari penjumlahan keempat field di bawah ini.
        </div>
        
        <div style="display: grid; gap: 16px;">
            <div class="form-group" style="margin: 0;">
                <label class="form-label">Standby (Rp)</label>
                <input type="number" class="form-input" id="standby" name="standby" 
                       value="{{ $material_saving_config->standby }}" step="0.01" min="0" required>
            </div>
            <div class="form-group" style="margin: 0;">
                <label class="form-label">Garansi (Rp)</label>
                <input type="number" class="form-input" id="garansi" name="garansi" 
                       value="{{ $material_saving_config->garansi }}" step="0.01" min="0" required>
            </div>
            <div class="form-group" style="margin: 0;">
                <label class="form-label">Perbaikan (Rp)</label>
                <input type="number" class="form-input" id="perbaikan" name="perbaikan" 
                       value="{{ $material_saving_config->perbaikan }}" step="0.01" min="0" required>
            </div>
            <div class="form-group" style="margin: 0;">
                <label class="form-label">Usul Hapus (Rp)</label>
                <input type="number" class="form-input" id="usul_hapus" name="usul_hapus" 
                       value="{{ $material_saving_config->usul_hapus }}" step="0.01" min="0" required>
            </div>
        </div>

        <div style="margin-top: 24px; text-align: right;">
            <button type="button" class="btn-action default" x-on:click="show = false">Batal</button>
            <button type="submit" class="btn-action primary">Simpan</button>
        </div>
    </form>
</x-modal>
@endsection

@push('scripts')
<script>
// Ajax Form Submit
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('materialSavingConfigForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('{{ route('dashboard.material-saving-config.update') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Close modal using Alpine event
                    window.dispatchEvent(new CustomEvent('close-modal', { detail: 'materialSavingConfigModal' }));
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Konfigurasi berhasil disimpan!',
                        confirmButtonColor: '#4FD1C5'
                    }).then(() => {
                        location.reload(); // Reload to update dashboard numbers
                    });
                } else {
                    throw new Error(data.message || 'Gagal menyimpan konfigurasi');
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: error.message,
                    confirmButtonColor: '#F56565'
                });
            });
        });
    }
});
</script>
@endpush
