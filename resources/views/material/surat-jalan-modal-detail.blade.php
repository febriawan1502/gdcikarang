<div class="row" style="padding-left: 20px; padding-right: 20px;">
    <div class="col-md-6">
        <table class="table table-borderless">
            <tr>
                <td><strong>Nomor Surat:</strong></td>
                <td>{{ $suratJalan->nomor_surat }}</td>
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
<div class="row mt-3" style="padding-left: 30px; padding-right: 30px;">
    <div class="col-12">
        <h5>Informasi Kendaraan</h5>
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

<div class="row mt-3" style="padding-left: 30px; padding-right: 30px;">
    <div class="col-12">
        <h5>Detail Material</h5>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Deskripsi Material</th>
                        <th>Jumlah</th>
                        <th>Satuan</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suratJalan->details as $index => $detail)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $detail->material->material_description ?? '-' }}</td>
                        <td>{{ number_format($detail->quantity, 0, ',', '.') }}</td>
                        <td>{{ $detail->satuan ?? '-' }}</td>
                        <td>{{ $detail->keterangan ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada detail material</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>