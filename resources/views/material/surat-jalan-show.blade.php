@extends('layouts.app')

@section('title', 'Detail Surat Jalan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Surat Jalan</h3>
                    <div class="card-tools">
                        <a href="{{ route('surat-jalan.index') }}" class="btn btn-secondary">
                            <i class="fa fa-arrow-left"></i> Kembali
                        </a>
                        @if($suratJalan->status == 'APPROVED')
                            <a href="{{ route('surat-jalan.export', $suratJalan->id) }}" class="btn btn-success">
                                <i class="fa fa-download"></i> Export Excel
                            </a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Nomor Surat:</strong></td>
                                    <td>{{ $suratJalan->nomor_surat }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Jenis Surat Jalan:</strong></td>
                                    <td>
                                        <span class="badge badge-info">{{ $suratJalan->jenis_surat_jalan ?? 'Normal' }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal:</strong></td>
                                    <td>{{ $suratJalan->tanggal->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Kepada:</strong></td>
                                    <td>{{ $suratJalan->kepada }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Nama Penerima:</strong></td>
                                    <td>{{ $suratJalan->nama_penerima ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Alamat:</strong></td>
                                    <td>{{ $suratJalan->alamat }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Berdasarkan:</strong></td>
                                    <td>{{ $suratJalan->berdasarkan }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Security:</strong></td>
                                    <td>{{ $suratJalan->security ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        @if($suratJalan->status == 'APPROVED')
                                            <span class="badge badge-success">{{ $suratJalan->status }}</span>
                                        @else
                                            <span class="badge badge-warning">{{ $suratJalan->status }}</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Dibuat Oleh:</strong></td>
                                    <td>{{ $suratJalan->creator->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal Dibuat:</strong></td>
                                    <td>{{ $suratJalan->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                @if($suratJalan->status == 'APPROVED')
                                <tr>
                                    <td><strong>Disetujui Oleh:</strong></td>
                                    <td>{{ $suratJalan->approver->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal Disetujui:</strong></td>
                                    <td>{{ $suratJalan->approved_at ? $suratJalan->approved_at->format('d/m/Y H:i') : '-' }}</td>
                                </tr>
                                @endif
                                @if($suratJalan->keterangan)
                                <tr>
                                    <td><strong>Keterangan:</strong></td>
                                    <td>{{ $suratJalan->keterangan }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    @if($suratJalan->kendaraan || $suratJalan->no_polisi || $suratJalan->pengemudi)
                    <hr>
                    <h5>Informasi Kendaraan</h5>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-borderless">
                                @if($suratJalan->kendaraan)
                                <tr>
                                    <td width="150"><strong>Kendaraan:</strong></td>
                                    <td>{{ $suratJalan->kendaraan }}</td>
                                </tr>
                                @endif
                                @if($suratJalan->no_polisi)
                                <tr>
                                    <td><strong>No. Polisi:</strong></td>
                                    <td>{{ $suratJalan->no_polisi }}</td>
                                </tr>
                                @endif
                                @if($suratJalan->pengemudi)
                                <tr>
                                    <td><strong>Pengemudi:</strong></td>
                                    <td>{{ $suratJalan->pengemudi }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                    @endif

                    <hr>

                    <h5>Detail Material</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nomor KR</th>
                                    <th>Deskripsi Material</th>
                                    <th>Quantity</th>
                                    <th>Satuan</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($suratJalan->details as $index => $detail)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $detail->material->nomor_kr }}</td>
                                    <td>{{ $detail->material->material_description }}</td>
                                    <td>{{ number_format($detail->quantity) }}</td>
                                    <td>{{ $detail->satuan }}</td>
                                    <td>{{ $detail->keterangan ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
