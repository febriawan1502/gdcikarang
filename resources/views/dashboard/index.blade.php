@extends('layouts.app')

@section('title', 'Dashboard - Pojok IMS')
@section('breadcrumb', 'Dashboard')

@section('content')
<style>
    /* Stats Cards Row */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
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

    /* Dashboard Grid (2 columns) */
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-bottom: 24px;
    }

    @media (max-width: 1024px) {
        .dashboard-grid { grid-template-columns: 1fr; }
    }

    /* Panel Card */
    .panel-card {
        background: white;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 3.5px 5.5px rgba(0, 0, 0, 0.05);
    }

    .panel-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 1px solid #E2E8F0;
    }

    .panel-header i {
        font-size: 18px;
        color: #4FD1C5;
    }

    .panel-header h3 {
        font-size: 14px;
        font-weight: 700;
        color: #2D3748;
        margin: 0;
    }

    .panel-body {
        overflow-x: auto;
    }

    /* Data Table */
    .data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
    }

    .data-table thead th {
        text-align: left;
        font-size: 11px;
        font-weight: 700;
        color: #A0AEC0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 8px 12px;
        border-bottom: 1px solid #E2E8F0;
    }

    .data-table tbody tr {
        border-bottom: 1px solid #F7FAFC;
    }

    .data-table tbody tr:hover {
        background: #F7FAFC;
    }

    .data-table tbody td {
        padding: 12px;
        color: #4A5568;
    }

    /* Stock Badge */
    .stock-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
    }

    .stock-badge.success {
        background: #C6F6D5;
        color: #22543D;
    }

    .stock-badge.warning {
        background: #FEFCBF;
        color: #744210;
    }

    .stock-badge.danger {
        background: #FED7D7;
        color: #742A2A;
    }

    .stock-badge.info {
        background: #BEE3F8;
        color: #2A4365;
    }

    /* Stock Bar */
    .stock-bar {
        width: 60px;
        height: 6px;
        background: #E2E8F0;
        border-radius: 3px;
        overflow: hidden;
    }

    .stock-bar-fill {
        height: 100%;
        transition: width 0.3s ease;
    }

    .stock-bar-fill.success { background: #48BB78; }
    .stock-bar-fill.warning { background: #ECC94B; }
    .stock-bar-fill.danger { background: #F56565; }
    .stock-bar-fill.info { background: #4299E1; }

    /* Material Saving Grid */
    .saving-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
        margin-bottom: 16px;
    }

    .saving-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .saving-item-label {
        font-size: 12px;
        color: #718096;
    }

    .saving-item-value {
        font-size: 13px;
        font-weight: 700;
        color: #2D3748;
    }

    .saving-total {
        padding-top: 16px;
        border-top: 1px solid #E2E8F0;
        margin-bottom: 16px;
        text-align: center;
    }

    .saving-total-label {
        font-size: 11px;
        color: #A0AEC0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }

    .saving-total-value {
        font-size: 20px;
        font-weight: 700;
        color: #4FD1C5;
    }

    .saving-chart {
        display: flex;
        align-items: flex-end;
        justify-content: space-around;
        height: 100px;
        gap: 8px;
        padding: 0 20px;
    }

    .chart-bar {
        flex: 1;
        border-radius: 4px 4px 0 0;
        transition: height 0.3s ease;
    }
</style>

<h2 style="font-size: 24px; font-weight: 700; color: #2D3748; margin-bottom: 24px;">Dashboard</h2>

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
        <div class="stat-card-icon">
            <i class="fas fa-chart-line"></i>
        </div>
        <div class="stat-card-content">
            <div class="stat-card-value">Rp {{ number_format($stats['pemakaian_kumulatif'], 0, ',', '.') }}</div>
            <div class="stat-card-label">Pemakaian Kumulatif Tahun Ini</div>
        </div>
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

    <div class="value-card" x-data @click="$dispatch('open-modal', 'saldoAwalConfigModal')" style="cursor: pointer;">
        <div class="value-card-icon" style="background: linear-gradient(126.97deg, #F6AD55 28.26%, #ED8936 91.2%);">
            <i class="fas fa-sync-alt"></i>
        </div>
        <div class="value-card-content">
            <div class="value-card-value">{{ number_format($stats['ito'], 2, ',', '.') }} Kali</div>
            <div class="value-card-label">ITO (Inventory Turnover)</div>
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
            <button x-data @click="$dispatch('open-modal', 'fastMovingConfigModal')" style="margin-left: auto; background: none; border: none; cursor: pointer; color: #718096;">
                <i class="fas fa-cog"></i>
            </button>
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
            <h3>Klasifikasi Material Retur (MRWI)</h3>
            <button x-on:click="$dispatch('open-modal', 'materialSavingConfigModal')" style="margin-left: auto; background: none; border: none; cursor: pointer; color: #718096;">
                <i class="fas fa-cog"></i>
            </button>
        </div>
        <div class="panel-body">
            <!-- Pie Chart Container -->
            <div style="max-width: 300px; margin: 0 auto 30px;">
                <canvas id="mrwiPieChart"></canvas>
            </div>
            
            <!-- Values Grid -->
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
<!-- Saldo Awal Configuration Modal -->
<x-modal name="saldoAwalConfigModal" title="Konfigurasi Saldo Awal Tahun">
    <x-slot:icon>
        <i class="fas fa-sync-alt" style="color: #F6AD55;"></i>
    </x-slot:icon>

    <form id="saldoAwalConfigForm">
        @csrf
        <div style="display: grid; gap: 16px;">
            <div class="form-group" style="margin: 0;">
                <label class="form-label">Saldo Awal Tahun (Rp)</label>
                <input type="number" class="form-input" id="saldo_awal_tahun" name="saldo_awal_tahun" 
                       value="{{ $material_saving_config->saldo_awal_tahun }}" step="0.01" min="0" required>
                <small style="color: #718096; font-size: 11px;">Diperlukan untuk perhitungan ITO</small>
                <div style="margin-top: 4px; font-style: italic; color: #E53E3E; font-size: 11px;">
                    "Pemakaian total merupakan pengeluaran (TP ke ULP & STO dianggap pemakaian), Saldo yang tercapture hanya saldo Gudang UP3. Pasti hasilnya tidak akurat. Aplikasi masih dalam proses pengembangan"
                </div>
            </div>
        </div>
        <div style="margin-top: 24px; text-align: right;">
            <button type="button" class="btn-action default" x-on:click="show = false">Batal</button>
            <button type="submit" class="btn-action primary">Simpan</button>
        </div>
    </form>
</x-modal>

<!-- Material Saving Configuration Modal (Component) -->
<x-modal name="materialSavingConfigModal" title="Konfigurasi Klasifikasi Material Retur">
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
                       value="{{ (int) $material_saving_config->standby }}" step="1" min="0" required
                       oninput="this.value = Math.round(this.value)">
            </div>
            <div class="form-group" style="margin: 0;">
                <label class="form-label">Garansi (Rp)</label>
                <input type="number" class="form-input" id="garansi" name="garansi" 
                       value="{{ (int) $material_saving_config->garansi }}" step="1" min="0" required
                       oninput="this.value = Math.round(this.value)">
            </div>
            <div class="form-group" style="margin: 0;">
                <label class="form-label">Perbaikan (Rp)</label>
                <input type="number" class="form-input" id="perbaikan" name="perbaikan" 
                       value="{{ (int) $material_saving_config->perbaikan }}" step="1" min="0" required
                       oninput="this.value = Math.round(this.value)">
            </div>
            <div class="form-group" style="margin: 0;">
                <label class="form-label">Usul Hapus (Rp)</label>
                <input type="number" class="form-input" id="usul_hapus" name="usul_hapus" 
                       value="{{ (int) $material_saving_config->usul_hapus }}" step="1" min="0" required
                       oninput="this.value = Math.round(this.value)">
            </div>
        </div>

        <div style="margin-top: 24px; text-align: right;">
            <button type="button" class="btn-action default" x-on:click="show = false">Batal</button>
            <button type="submit" class="btn-action primary">Simpan</button>
        </div>
    </form>
</x-modal>
@endsection

<!-- Fast Moving Material Configuration Modal -->
<x-modal name="fastMovingConfigModal" title="Konfigurasi Stok Minimum">
    <x-slot:icon>
        <i class="fas fa-fire" style="color: #4FD1C5;"></i>
    </x-slot:icon>

    <form id="fastMovingConfigForm">
        @csrf
        <div style="display: grid; gap: 12px; max-height: 400px; overflow-y: auto; padding-right: 8px;">
            @foreach($fast_moving_materials as $index => $mat)
                <div class="form-group" style="display: grid; grid-template-columns: 1fr 80px 80px; gap: 10px; align-items: end; border-bottom: 1px solid #E2E8F0; padding-bottom: 12px;">
                    <div>
                        @if($mat->id)
                            <input type="hidden" name="configs[{{ $index }}][id]" value="{{ $mat->id }}">
                        @endif
                        <label class="form-label" style="font-size: 11px; margin-bottom: 2px;">{{ Str::limit($mat->material_description, 40) }}</label>
                        <div style="font-size: 10px; color: #718096; font-family: monospace;">{{ $mat->material_code }}</div>
                    </div>
                
                    <div>
                        <label class="form-label" style="font-size: 10px;">Stok Saat Ini</label>
                        <div style="padding: 8px; background: #EDF2F7; border-radius: 6px; font-size: 12px; text-align: center; font-weight: 600;">
                            {{ number_format($mat->unrestricted_use_stock, 0, ',', '.') }}
                        </div>
                    </div>

                    <div>
                        <label class="form-label" style="font-size: 10px;">Min Stock</label>
                        @if($mat->id)
                            <input type="number" class="form-input" name="configs[{{ $index }}][min_stock]" 
                                   value="{{ $mat->stok_minimum }}" min="0" step="1" required style="padding: 6px; text-align: right;">
                        @else
                            <div style="font-size: 10px; color: #E53E3E; padding: 6px; text-align: right;">No DB Data</div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div style="margin-top: 24px; text-align: right;">
            <button type="button" class="btn-action default" x-on:click="show = false">Batal</button>
            <button type="submit" class="btn-action primary">Simpan</button>
        </div>
    </form>
</x-modal>

@push('scripts')
<script>
// Ajax Form Submit
// Ajax Form Submit for Material Saving Config
document.addEventListener('DOMContentLoaded', function() {
    // Handler for MRWI Config Form
    const mrwiForm = document.getElementById('materialSavingConfigForm');
    if (mrwiForm) {
        mrwiForm.addEventListener('submit', function(e) {
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
                    window.dispatchEvent(new CustomEvent('close-modal', { detail: 'materialSavingConfigModal' }));
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Konfigurasi MRWI berhasil disimpan!',
                        confirmButtonColor: '#4FD1C5'
                    }).then(() => {
                        location.reload();
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

    // Handler for Saldo Awal Config Form
    const saldoForm = document.getElementById('saldoAwalConfigForm');
    if (saldoForm) {
        saldoForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('{{ route('dashboard.saldo-awal.update') }}', {
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
                    window.dispatchEvent(new CustomEvent('close-modal', { detail: 'saldoAwalConfigModal' }));
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Saldo awal berhasil disimpan!',
                        confirmButtonColor: '#F6AD55'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    throw new Error(data.message || 'Gagal menyimpan saldo awal');
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

    // Handler for Fast Moving Config Form
    const fastMovingForm = document.getElementById('fastMovingConfigForm');
    if (fastMovingForm) {
        fastMovingForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('{{ route('dashboard.fast-moving-config.update') }}', {
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
                    window.dispatchEvent(new CustomEvent('close-modal', { detail: 'fastMovingConfigModal' }));
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Konfigurasi stok minimum berhasil disimpan!',
                        confirmButtonColor: '#4FD1C5'
                    }).then(() => {
                        location.reload();
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

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
// MRWI Pie Chart
// MRWI Pie Chart
let mrwiChart = null;

function initDashboardChart() {
    const ctx = document.getElementById('mrwiPieChart');
    if (ctx) {
        // Destroy existing chart if it exists
        if (mrwiChart) {
            mrwiChart.destroy();
            mrwiChart = null;
        }

        const standby = {{ $material_saving_config->standby }};
        const garansi = {{ $material_saving_config->garansi }};
        const perbaikan = {{ $material_saving_config->perbaikan }};
        const usulHapus = {{ $material_saving_config->usul_hapus }};
        const total = standby + garansi + perbaikan + usulHapus;
        
        mrwiChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Standby', 'Garansi', 'Perbaikan', 'Usul Hapus'],
                datasets: [{
                    data: [standby, garansi, perbaikan, usulHapus],
                    backgroundColor: [
                        '#4FD1C5',
                        '#F6AD55',
                        '#63B3ED',
                        '#FC8181'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            font: {
                                size: 12,
                                family: 'Plus Jakarta Sans'
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const value = context.parsed;
                                const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                const formattedValue = 'Rp ' + value.toLocaleString('id-ID');
                                return context.label + ': ' + formattedValue + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    }
}

// Initialize on first load
document.addEventListener('DOMContentLoaded', initDashboardChart);

// Initialize on Livewire navigation
document.addEventListener('livewire:navigated', initDashboardChart);
</script>
@endpush
