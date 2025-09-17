@extends('layouts.app')

@section('title', 'Detail Material')

@section('content')
<div class="page-content">
    <div class="page-header">
        <div class="page-title">
            <h1>Detail Material</h1>
            <h4>Informasi lengkap material</h4>
        </div>
        <div class="page-stats">
            <ul>
                <li>
                    <a href="{{ route('material.index') }}" class="btn btn-default">
                        <i class="fa fa-arrow-left"></i> Kembali
                    </a>
                </li>
                <li>
                    <a href="{{ route('material.edit', $material) }}" class="btn btn-primary">
                        <i class="fa fa-edit"></i> Edit
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="portlet">
                <div class="portlet-header">
                    <h3>
                        <i class="fa fa-info-circle"></i>
                        Informasi Material
                    </h3>
                </div>
                <div class="portlet-content">
                    <div class="row">
                        <div class="col-md-6">

                            
                            <div class="form-group">
                                <label class="control-label">Deskripsi Material:</label>
                                <p class="form-control-static">{{ $material->material_description }}</p>
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label">Lokasi Penyimpanan:</label>
                                <p class="form-control-static">{{ $material->storage_location ?? '-' }}</p>
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label">Rak:</label>
                                <p class="form-control-static">{{ $material->rak ?? '-' }}</p>
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label">Tipe Material:</label>
                                <p class="form-control-static">{{ $material->material_type ?? '-' }}</p>
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label">Quantity:</label>
                                <p class="form-control-static">
                                    <span class="badge badge-info">{{ number_format($material->qty, 0, ',', '.') }} {{ $material->base_unit_of_measure }}</span>
                                </p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Stok Tidak Terbatas:</label>
                                <p class="form-control-static">
                                    @if($material->unrestricted_stock)
                                        <span class="badge badge-success">Ya</span>
                                    @else
                                        <span class="badge badge-default">Tidak</span>
                                    @endif
                                </p>
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label">Tanggal Terima:</label>
                                <p class="form-control-static">{{ $material->receipt_date ? \Carbon\Carbon::parse($material->receipt_date)->format('d/m/Y') : '-' }}</p>
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label">Harga Satuan:</label>
                                <p class="form-control-static">
                                    @if($material->unit_price)
                                        {{ $material->currency ?? 'IDR' }} {{ number_format($material->unit_price, 0, ',', '.') }}
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label">Total Harga:</label>
                                <p class="form-control-static">
                                    @if($material->total_harga)
                                        {{ $material->currency ?? 'IDR' }} {{ number_format($material->total_harga, 0, ',', '.') }}
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label">Status:</label>
                                <p class="form-control-static">
                                    @if($material->status === 'aktif')
                                        <span class="badge badge-success">Aktif</span>
                                    @else
                                        <span class="badge badge-danger">Tidak Aktif</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    @if($material->keterangan)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">Keterangan:</label>
                                <div class="well well-sm">
                                    {{ $material->keterangan }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <div class="row">
                        <div class="col-md-12">
                            <hr>
                            <div class="form-group">
                                <label class="control-label">Informasi Sistem:</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <small class="text-muted">
                                            <strong>Dibuat:</strong> {{ $material->created_at->format('d/m/Y H:i') }}<br>
                                            @if($material->created_by)
                                                <strong>Oleh:</strong> {{ $material->creator->name ?? 'System' }}
                                            @endif
                                        </small>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted">
                                            <strong>Diupdate:</strong> {{ $material->updated_at->format('d/m/Y H:i') }}<br>
                                            @if($material->updated_by)
                                                <strong>Oleh:</strong> {{ $material->updater->name ?? 'System' }}
                                            @endif
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection