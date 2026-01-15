@php
    $isSecurity   = auth()->user()->role === 'security';
    $hasUnchecked = $suratJalan->details->where('is_checked', false)->count() > 0;
@endphp

{{-- ================= DETAIL SURAT JALAN ================= --}}
<div class="row">
    <div class="col-md-6">
        <table class="table table-borderless table-sm">
            <tr><td><strong>Nomor Surat Jalan:</strong></td><td>{{ $suratJalan->nomor_surat ?? '-' }}</td></tr>
            <tr><td><strong>Tanggal Surat Jalan:</strong></td><td>{{ optional($suratJalan->tanggal)->format('d/m/Y') ?? '-' }}</td></tr>
            <tr><td><strong>Jenis Surat Jalan:</strong></td><td>{{ $suratJalan->jenis_surat_jalan ?? '-' }}</td></tr>
            <tr><td><strong>Kepada:</strong></td><td>{{ $suratJalan->kepada ?? '-' }}</td></tr>
            <tr><td><strong>Berdasarkan:</strong></td><td>{{ $suratJalan->berdasarkan ?? '-' }}</td></tr>
            <tr><td><strong>Status:</strong></td><td>{{ $suratJalan->status ?? '-' }}</td></tr>
        </table>
    </div>

    <div class="col-md-6">
        <table class="table table-borderless table-sm">
            <tr><td><strong>Kendaraan:</strong></td><td>{{ $suratJalan->kendaraan ?? '-' }}</td></tr>
            <tr><td><strong>No Polisi:</strong></td><td>{{ $suratJalan->no_polisi ?? '-' }}</td></tr>
            <tr><td><strong>Pengemudi:</strong></td><td>{{ $suratJalan->pengemudi ?? '-' }}</td></tr>
            <tr><td><strong>Dibuat Oleh:</strong></td><td>{{ $suratJalan->creator->nama ?? '-' }}</td></tr>
            <tr><td><strong>Disetujui Oleh:</strong></td><td>{{ $suratJalan->approver->nama ?? '-' }}</td></tr>
            <tr><td><strong>Keterangan:</strong></td><td>{{ $suratJalan->keterangan ?? '-' }}</td></tr>
        </table>
    </div>
</div>

<hr>

{{-- ================= FORM CHECKING ================= --}}
@if($isSecurity && $hasUnchecked)
<form id="formCheckedMaterial"
      action="{{ route('surat-jalan.submit-checked') }}"
      method="POST">
    @csrf
    <input type="hidden" name="surat_jalan_id" value="{{ $suratJalan->id }}">
@endif

<div class="table-responsive">
    <table class="table table-bordered table-sm">
        <thead class="thead-light">
            <tr>
                <th>Kode Material</th>
                <th>Deskripsi Material</th>
                <th>Quantity</th>
                <th>Satuan</th>
                <th>Keterangan</th>
            </tr>
        </thead>

        <tbody>
        @foreach($suratJalan->details as $detail)
            <tr>
                @if($isSecurity && $hasUnchecked)
                    <td class="text-center">
                        @if(!$detail->is_checked)
                            <input type="checkbox"
                                   name="detail_ids[]"
                                   value="{{ $detail->id }}"
                                   class="check-item">
                        @else
                            <i class="fa fa-check-circle text-success"></i>
                        @endif
                    </td>
                @endif

                @if($detail->is_manual)
                    <td>-</td>
                    <td>{{ $detail->nama_barang_manual }}</td>
                    <td>{{ $detail->quantity }}</td>
                    <td>{{ $detail->satuan_manual }}</td>
                    <td>{{ $detail->keterangan }}</td>
                @else
                    <td>{{ $detail->material->material_code }}</td>
                    <td>{{ $detail->material->material_description }}</td>
                    <td>{{ $detail->quantity }}</td>
                    <td>{{ $detail->satuan }}</td>
                    <td>{{ $detail->keterangan }}</td>
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

@if($isSecurity && $hasUnchecked)
<div class="text-right mt-3">
    <button type="button" id="btnChecked" class="btn btn-success">
        <i class="fa fa-check"></i> Checked
    </button>
</div>
</form>
@endif

{{-- ================= VALIDASI CHECKBOX ================= --}}
<script>
$(document).off('click', '#btnChecked').on('click', '#btnChecked', function () {

    const totalCheckbox = $('.check-item').length;
    const checkedCheckbox = $('.check-item:checked').length;

    if (checkedCheckbox < totalCheckbox) {
        Swal.fire({
            title: 'Belum Lengkap',
            text: 'Semua material harus dicentang sebelum melakukan Checked.',
            icon: 'warning',
            confirmButtonText: 'Mengerti'
        });
        return;
    }

    $('#formCheckedMaterial').submit();
});

// CHECK ALL
$(document).off('change', '#checkAll').on('change', '#checkAll', function () {
    $('.check-item').prop('checked', this.checked);
});
</script>
