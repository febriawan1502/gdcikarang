@extends('layouts.app')

@section('title', 'Dashboard - Pojok IMS')

@section('content')
<style>
    body {
        background: #f6f6f6;
        font-family: Calibri, Arial, sans-serif;
    }

    .monitoring-section {
    margin-top: 20px;
    margin-bottom: 40px; /* ✅ jarak bawah ditambah */
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
}

    .monitoring-title {
        background: #0b73a6;
        color: #fff;
        font-weight: 700;
        padding: 8px 10px;
        font-size: 15px;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
        text-align: center;
    }

    .table-wrapper {
        overflow-x: auto;
        padding: 8px;
    }

    .excel-table {
        border-collapse: collapse;
        width: 100%;
        min-width: 1300px; /* ↓ dari 1800px ke 1300px */
        table-layout: fixed;
        font-size: 12px; /* ↓ sedikit biar lebih kompak */
    }

    .excel-table th,
    .excel-table td {
        border: 1px solid #c7c7c7;
        padding: 6px 4px; /* ↓ padding biar padat */
        vertical-align: middle;
        text-align: center;
        word-break: break-word;
    }

    .excel-table thead th {
        background: #dfeaf6;
        font-weight: 700;
        color: #133b4a;
    }

    .excel-table thead tr.subhead th {
        background: #eef6fb;
        font-weight: 600;
    }

    .excel-table tbody tr:nth-child(even) {
        background: #fbfdff;
    }

    .excel-table tbody tr:hover {
        background: #eef7ff;
    }

    .excel-table td.left {
        text-align: left;
        padding-left: 6px;
    }

    .highlight-yellow {
        background: #fff4cc;
    }

    .highlight-pink {
        background: #ffd6d6;
    }

    .highlight-green {
        background: #e6f7e6;
    }

    .highlight-blue {
        background: #d6e9ff;
    }

    /* Responsive tweak biar tetap enak dibaca di layar kecil */
    @media (max-width: 1400px) {
        .excel-table {
            font-size: 11px;
            min-width: 1100px;
        }
        .excel-table th, .excel-table td {
            padding: 5px 3px;
        }
    }

    @media (max-width: 1000px) {
        .excel-table {
            font-size: 10px;
            min-width: 900px;
        }
    }
</style>

<!-- <div class="monitoring-section">
    <div class="monitoring-title">MONITORING PENCAPAIAN LM 1 KUMULATIF - UP3 CIMAHI</div>

    <div class="table-wrapper">
        <table class="excel-table">
            <thead>
                <tr>
                    <th rowspan="3" style="width:40px">NO</th>
                    <th rowspan="3" style="min-width:100px">UNIT</th>
                    <th rowspan="3" style="min-width:80px">TARGET<br>HARIAN</th>
                    <th colspan="3">LEAD MEASURES (LOGISTIK)</th>
                    <th rowspan="3">JML<br>REALISASI<br>HARIAN</th>
                    <th rowspan="3">PENCAPAIAN<br>HARIAN</th>
                    <th colspan="4">NILAI PERSEDIAAN</th>
                    <th colspan="4">PENCAPAIAN LM 1 KUMULATIF</th>
                    <th colspan="3">RASIO PERPUTARAN MATERIAL</th>
                </tr>
                <tr class="subhead">
                    <th>LM1<br><small>B1-EFF</small></th>
                    <th>LM2<br><small>B1-DAL</small></th>
                    <th>LM3<br><small>B2-SAR</small></th>
                    <th>PENERIMAAN<br><small>MATERIAL</small></th>
                    <th>TARGET</th>
                    <th>REALISASI</th>
                    <th>SALDO<br><small>SEBELUMNYA</small></th>
                    <th>TARGET</th>
                    <th>REALISASI</th>
                    <th>% PEMAKAIAN</th>
                    <th>% PENCAPAIAN</th>
                    <th>TARGET<br><small>JAN–SEPT</small></th>
                    <th>REALISASI<br><small>JAN–SEPT</small></th>
                    <th>% ITO</th>
                </tr>
                <tr>
                    <th class="highlight-yellow">(Rp)</th>
                    <th class="highlight-pink">(Rp)</th>
                    <th class="highlight-pink">(Rp)</th>
                    <th class="highlight-green">(Rp)</th>
                    <th class="highlight-yellow">(Rp)</th>
                    <th class="highlight-pink">(Rp)</th>
                    <th class="highlight-pink">(Rp)</th>
                    <th class="highlight-yellow">(Rp)</th>
                    <th class="highlight-pink">(Rp)</th>
                    <th class="highlight-blue">%</th>
                    <th class="highlight-blue">%</th>
                    <th class="highlight-blue">x</th>
                    <th class="highlight-blue">x</th>
                    <th class="highlight-pink">%</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td>1</td>
                    <td class="left">UP3 CIMAHI</td>
                    <td class="highlight-yellow">133,453.266</td>
                    <td class="highlight-pink">0</td>
                    <td class="highlight-pink">0</td>
                    <td class="highlight-pink">0</td>
                    <td>0</td>
                    <td class="highlight-pink">#DIV/0!</td>
                    <td class="highlight-green">8,540,587,691</td>
                    <td class="highlight-yellow">0</td>
                    <td class="highlight-pink">#DIV/0!</td>
                    <td class="highlight-pink">79,009,276,251</td>
                    <td class="highlight-yellow">0</td>
                    <td class="highlight-pink">0%</td>
                    <td class="highlight-blue">9.71</td>
                    <td class="highlight-blue">6.23</td>
                    <td class="highlight-pink">64%</td>
                    <td class="highlight-pink">-</td>
                    <td class="highlight-pink">-</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td class="left">ULP CIMAHI KOTA</td>
                    <td class="highlight-yellow">0</td>
                    <td class="highlight-pink">0</td>
                    <td class="highlight-pink">0</td>
                    <td class="highlight-pink">0</td>
                    <td>0</td>
                    <td class="highlight-pink">#DIV/0!</td>
                    <td class="highlight-green">8,540,587,691</td>
                    <td class="highlight-yellow">0</td>
                    <td class="highlight-pink">#DIV/0!</td>
                    <td class="highlight-pink">79,009,276,251</td>
                    <td class="highlight-yellow">0</td>
                    <td class="highlight-pink">0%</td>
                    <td class="highlight-blue">9.71</td>
                    <td class="highlight-blue">6.23</td>
                    <td class="highlight-pink">64%</td>
                    <td class="highlight-pink">-</td>
                    <td class="highlight-pink">-</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td class="left">ULP PADALARANG</td>
                    <td class="highlight-yellow">50,000.000</td>
                    <td class="highlight-pink">0</td>
                    <td class="highlight-pink">0</td>
                    <td class="highlight-pink">0</td>
                    <td>0</td>
                    <td class="highlight-pink">#DIV/0!</td>
                    <td class="highlight-green">2,000,000,000</td>
                    <td class="highlight-yellow">0</td>
                    <td class="highlight-pink">#DIV/0!</td>
                    <td class="highlight-pink">10,000,000,000</td>
                    <td class="highlight-yellow">0</td>
                    <td class="highlight-pink">0%</td>
                    <td class="highlight-blue">9.71</td>
                    <td class="highlight-blue">6.23</td>
                    <td class="highlight-pink">64%</td>
                    <td class="highlight-pink">-</td>
                    <td class="highlight-pink">-</td>
                </tr>

                <tr>
                    <td>4</td>
                    <td class="left">ULP LEMBANG</td>
                    <td class="highlight-yellow">40,500.000</td>
                    <td class="highlight-pink">0</td>
                    <td class="highlight-pink">0</td>
                    <td class="highlight-pink">0</td>
                    <td>0</td>
                    <td class="highlight-pink">#DIV/0!</td>
                    <td class="highlight-green">1,800,000,000</td>
                    <td class="highlight-yellow">0</td>
                    <td class="highlight-pink">#DIV/0!</td>
                    <td class="highlight-pink">9,500,000,000</td>
                    <td class="highlight-yellow">0</td>
                    <td class="highlight-pink">0%</td>
                    <td class="highlight-blue">9.71</td>
                    <td class="highlight-blue">6.23</td>
                    <td class="highlight-pink">64%</td>
                    <td class="highlight-pink">-</td>
                    <td class="highlight-pink">-</td>
                </tr>

                <tr>
                    <td>5</td>
                    <td class="left">ULP CILILIN</td>
                    <td class="highlight-yellow">30,250.300</td>
                    <td class="highlight-pink">0</td>
                    <td class="highlight-pink">0</td>
                    <td class="highlight-pink">0</td>
                    <td>0</td>
                    <td class="highlight-pink">#DIV/0!</td>
                    <td class="highlight-green">1,200,000,000</td>
                    <td class="highlight-yellow">0</td>
                    <td class="highlight-pink">#DIV/0!</td>
                    <td class="highlight-pink">8,000,000,000</td>
                    <td class="highlight-yellow">0</td>
                    <td class="highlight-pink">0%</td>
                    <td class="highlight-blue">9.71</td>
                    <td class="highlight-blue">6.23</td>
                    <td class="highlight-pink">64%</td>
                    <td class="highlight-pink">-</td>
                    <td class="highlight-pink">-</td>
                </tr>

                <tr>
                    <td>6</td>
                    <td class="left">ULP RAZAMANDALA</td>
                    <td class="highlight-yellow">25,000.000</td>
                    <td class="highlight-pink">0</td>
                    <td class="highlight-pink">0</td>
                    <td class="highlight-pink">0</td>
                    <td>0</td>
                    <td class="highlight-pink">#DIV/0!</td>
                    <td class="highlight-green">900,000,000</td>
                    <td class="highlight-yellow">0</td>
                    <td class="highlight-pink">#DIV/0!</td>
                    <td class="highlight-pink">6,500,000,000</td>
                    <td class="highlight-yellow">0</td>
                    <td class="highlight-pink">0%</td>
                    <td class="highlight-blue">9.71</td>
                    <td class="highlight-blue">6.23</td>
                    <td class="highlight-pink">64%</td>
                    <td class="highlight-pink">-</td>
                    <td class="highlight-pink">-</td>
                </tr>

                <tr>
                    <td>7</td>
                    <td class="left">ULP CIMAHI SELATAN</td>
                    <td class="highlight-yellow">60,000.000</td>
                    <td class="highlight-pink">0</td>
                    <td class="highlight-pink">0</td>
                    <td class="highlight-pink">0</td>
                    <td>0</td>
                    <td class="highlight-pink">#DIV/0!</td>
                    <td class="highlight-green">2,500,000,000</td>
                    <td class="highlight-yellow">0</td>
                    <td class="highlight-pink">#DIV/0!</td>
                    <td class="highlight-pink">11,000,000,000</td>
                    <td class="highlight-yellow">0</td>
                    <td class="highlight-pink">0%</td>
                    <td class="highlight-blue">9.71</td>
                    <td class="highlight-blue">6.23</td>
                    <td class="highlight-pink">64%</td>
                    <td class="highlight-pink">-</td>
                    <td class="highlight-pink">-</td>
                </tr>
            </tbody>
        </table>
    </div>
</div> -->
    <!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="card bg-blue" style="cursor: default;">
            <div class="card-body">
                <div class="row">
                    <div class="col-xs-4">
                        <i class="fa fa-plug fa-4x"></i>
                    </div>
                    <div class="col-xs-8">
                        <p class="text-elg text-strong mb-0">{{ number_format($stats['total_materials']) }}</p>
                        <span>Total Material</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="card bg-greensea" style="cursor: default;">
            <div class="card-body">
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
        </div>
    </div>
    <div class="col-lg-6 col-md-12 col-sm-12">
        <a href="{{ route('material-masuk.index') }}" class="text-decoration-none">
            <div class="card bg-orange" style="cursor: pointer; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xs-4">
                            <i class="fa fa-arrow-down fa-4x"></i>
                        </div>
                        <div class="col-xs-8">
                            <p class="text-elg text-strong mb-0">Rp {{ number_format($stats['total_pemakaian'], 0, ',', '.') }}</p>
                            <span>Pemakaian Komulatif</span>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

<div class="row mb-4" style="margin-top: 30px; margin-bottom: 30px;">
    <div class="col-lg-6 col-md-6 col-sm-12">
        <div class="card bg-cyan" style="cursor: default;">
            <div class="card-body">
                <div class="row">
                    <div class="col-xs-4">
                        <i class="fa fa-money fa-4x"></i>
                    </div>
                    <div class="col-xs-8">
                        <p class="text-elg text-strong mb-0">Rp {{ number_format($stats['total_saldo'], 0, ',', '.') }}</p>
                        <span>Total Saldo Gudang</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12">
        <div class="card bg-cyan" style="cursor: pointer;" onclick="openSaldoAwalModal()">
            <div class="card-body">
                <div class="row">
                    <div class="col-xs-4">
                        <i class="fa fa-refresh fa-4x"></i>
                    </div>
                    <div class="col-xs-8">
                        <p class="text-elg text-strong mb-0">{{ number_format($stats['ito'], 2, ',', '.') }} Kali</p>
                        <span>ITO</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Row 1: 10 Material Fast Moving + Klasifikasi Material Saving -->
<div class="row">
    <!-- 10 Material Fast Moving (Left Half) -->
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-fire"></i> 10 Material Fast Moving</h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-sm">
                        <thead>
                            <tr>
                                <th width="20%">Normalisasi</th>
                                <th width="40%">Nama Material</th>
                                <th class="text-center" width="15%">Stok</th>
                                <th class="text-center" width="25%">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($fast_moving_materials as $mat)
                            <tr>
                                <td>{{ $mat->material_code }}</td>
                                <td>{{ Str::limit($mat->material_description, 50) }}</td>
                                <td class="text-center">
                                    <span class="badge badge-info">{{ number_format($mat->unrestricted_use_stock, 0, ',', '.') }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-{{ $mat->stock_badge }}" style="font-size: 12px; padding: 6px 12px;">
                                        {{ strtoupper($mat->stock_status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center">Belum ada data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Klasifikasi Material Saving (Right Half) -->
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="fa fa-pie-chart"></i> Klasifikasi Material Saving
                    <button class="btn btn-xs btn-default pull-right" onclick="openMaterialSavingConfigModal()" title="Konfigurasi">
                        <i class="fa fa-cog"></i>
                    </button>
                </h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Standby:</strong></p>
                        <p class="mb-2">Rp {{ number_format($material_saving_config->standby, 0, ',', '.') }}</p>
                        
                        <p class="mb-1"><strong>Garansi:</strong></p>
                        <p class="mb-2">Rp {{ number_format($material_saving_config->garansi, 0, ',', '.') }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Perbaikan:</strong></p>
                        <p class="mb-2">Rp {{ number_format($material_saving_config->perbaikan, 0, ',', '.') }}</p>
                        
                        <p class="mb-1"><strong>Usul Hapus:</strong></p>
                        <p class="mb-2">Rp {{ number_format($material_saving_config->usul_hapus, 0, ',', '.') }}</p>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <p class="mb-1"><strong>Total Inspeksi:</strong></p>
                        <p class="mb-0 text-primary"><strong style="font-size: 18px;">Rp {{ number_format($material_saving_config->total_inspeksi, 0, ',', '.') }}</strong></p>
                        <small class="text-muted">Standby + Garansi + Perbaikan + Usul Hapus</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Row 2: 10 Nilai Material Terbesar + 10 Stock Material Terbesar -->
<div class="row">
    <!-- 10 Nilai Material Terbesar -->
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-dollar"></i> 10 Nilai Material Terbesar</h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-sm">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Deskripsi</th>
                                <th class="text-right">Total Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($top_value_materials as $mat)
                            <tr>
                                <td>{{ $mat->material_code }}</td>
                                <td>{{ Str::limit($mat->material_description, 30) }}</td>
                                <td class="text-right">Rp {{ number_format($mat->calculated_total_value, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center">Belum ada data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- 10 Material Stock Terbesar -->
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-cubes"></i> 10 Stock Material Terbesar</h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-sm">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Deskripsi</th>
                                <th class="text-center">Stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($top_stock_materials as $mat)
                            <tr>
                                <td>{{ $mat->material_code }}</td>
                                <td>{{ Str::limit($mat->material_description, 30) }}</td>
                                <td class="text-center">
                                    <span class="badge badge-info">{{ number_format($mat->unrestricted_use_stock, 0, ',', '.') }}</span>
                                    <small>{{ $mat->base_unit_of_measure }}</small>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center">Belum ada data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Row 3: 10 Surat Jalan Terakhir + 10 Material Masuk Terakhir -->
<div class="row">
    <!-- 10 Surat Jalan Terakhir -->
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-truck"></i> 10 Surat Jalan Terakhir</h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-sm">
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
                            <tr><td colspan="3" class="text-center">Belum ada data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- 10 Material Masuk Terakhir -->
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-arrow-down"></i> 10 Material Masuk Terakhir</h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-sm">
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
                            <tr><td colspan="3" class="text-center">Belum ada data</td></tr>
                            @endforelse
                        </tbody>
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
                    <i class="fa fa-info-circle me-2"></i>
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

<!-- Material Saving Configuration Modal -->
<div class="modal fade" id="materialSavingConfigModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fa fa-cog me-2"></i>
                    Konfigurasi Klasifikasi Material Saving
                </h5>
                <button type="button" class="btn-close" data-dismiss="modal"></button>
            </div>
            <form id="materialSavingConfigForm">
                @csrf
                <div class="modal-body">
                    <p class="text-muted"><small><i class="fa fa-info-circle"></i> Total Inspeksi akan dihitung otomatis dari penjumlahan keempat field di bawah ini.</small></p>
                    <div class="form-group">
                        <label for="standby">Standby (Rp)</label>
                        <input type="number" class="form-control" id="standby" name="standby" 
                               value="{{ $material_saving_config->standby }}" step="0.01" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="garansi">Garansi (Rp)</label>
                        <input type="number" class="form-control" id="garansi" name="garansi" 
                               value="{{ $material_saving_config->garansi }}" step="0.01" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="perbaikan">Perbaikan (Rp)</label>
                        <input type="number" class="form-control" id="perbaikan" name="perbaikan" 
                               value="{{ $material_saving_config->perbaikan }}" step="0.01" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="usul_hapus">Usul Hapus (Rp)</label>
                        <input type="number" class="form-control" id="usul_hapus" name="usul_hapus" 
                               value="{{ $material_saving_config->usul_hapus }}" step="0.01" min="0" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>

<!-- Saldo Awal Configuration Modal -->
<div class="modal fade" id="saldoAwalModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fa fa-cog me-2"></i>
                    Konfigurasi Saldo Awal
                </h5>
                <button type="button" class="btn-close" data-dismiss="modal"></button>
            </div>
            <form id="saldoAwalForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="saldo_awal_tahun_modal">Saldo 1 Januari (Tahun Berjalan) (Rp)</label>
                        <input type="number" class="form-control" id="saldo_awal_tahun_modal" name="saldo_awal_tahun" 
                               value="{{ $material_saving_config->saldo_awal_tahun }}" step="0.01" min="0" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
@endsection

@push('scripts')
<script>
function openMaterialSavingConfigModal() {
    $('#materialSavingConfigModal').modal('show');
}

function openSaldoAwalModal() {
    $('#saldoAwalModal').modal('show');
}

$(document).ready(function() {
    // Saldo Awal Form Submit
    $('#saldoAwalForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '{{ route('dashboard.saldo-awal.update') }}',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    $('#saldoAwalModal').modal('hide');
                    alert('Saldo awal berhasil disimpan!');
                    location.reload(); // Reload page to show updated values
                } else {
                    alert('Gagal menyimpan saldo awal: ' + response.message);
                }
            },
            error: function(xhr) {
                let errorMessage = 'Gagal menyimpan saldo awal';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                alert(errorMessage);
            }
        });
    });

    // Material Saving Config Form Submit
    $('#materialSavingConfigForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '{{ route('dashboard.material-saving-config.update') }}',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    $('#materialSavingConfigModal').modal('hide');
                    alert('Konfigurasi berhasil disimpan!');
                    location.reload(); // Reload page to show updated values
                } else {
                    alert('Gagal menyimpan konfigurasi: ' + response.message);
                }
            },
            error: function(xhr) {
                let errorMessage = 'Gagal menyimpan konfigurasi';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                alert(errorMessage);
            }
        });
    });
});
</script>
@endpush




