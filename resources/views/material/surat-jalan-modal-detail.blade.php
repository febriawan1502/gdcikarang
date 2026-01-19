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
            <tr><td><strong>Nomor Slip SAP:</strong></td><td>{{ $suratJalan->nomor_slip ?? '-' }}</td></tr>
            <tr><td><strong>Status:</strong></td><td>{{ $suratJalan->status ?? '-' }}</td></tr>
        </table>
    </div>

    <div class="col-md-6">
        @if($isSecurity)
        {{-- Form untuk edit kendaraan oleh Security --}}
        <form id="formUpdateKendaraan" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="surat_jalan_id" value="{{ $suratJalan->id }}">
            <table class="table table-borderless table-sm">
                <tr>
                    <td width="120"><strong>Kendaraan:</strong></td>
                    <td>
                        <input type="text" name="kendaraan" class="form-control form-control-sm" 
                               value="{{ $suratJalan->kendaraan }}" placeholder="Jenis kendaraan">
                    </td>
                </tr>
                <tr>
                    <td><strong>No Polisi:</strong></td>
                    <td>
                        <input type="text" name="no_polisi" class="form-control form-control-sm" 
                               value="{{ $suratJalan->no_polisi }}" placeholder="Nomor polisi">
                    </td>
                </tr>
                <tr>
                    <td><strong>Pengemudi:</strong></td>
                    <td>
                        <input type="text" name="pengemudi" class="form-control form-control-sm" 
                               value="{{ $suratJalan->pengemudi }}" placeholder="Nama pengemudi">
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="text-right pt-2">
                        <button type="button" id="btnSaveKendaraan" class="btn btn-sm btn-primary">
                            <i class="fa fa-save"></i> Simpan Info Kendaraan
                        </button>
                    </td>
                </tr>
            </table>
        </form>
        <table class="table table-borderless table-sm">
            <tr><td><strong>Dibuat Oleh:</strong></td><td>{{ $suratJalan->creator->nama ?? '-' }}</td></tr>
            <tr><td><strong>Disetujui Oleh:</strong></td><td>{{ $suratJalan->approver->nama ?? '-' }}</td></tr>
            <tr><td><strong>Keterangan:</strong></td><td>{{ $suratJalan->keterangan ?? '-' }}</td></tr>
        </table>
        @else
        {{-- Tampilan biasa untuk non-security --}}
        <table class="table table-borderless table-sm">
            <tr><td><strong>Kendaraan:</strong></td><td>{{ $suratJalan->kendaraan ?? '-' }}</td></tr>
            <tr><td><strong>No Polisi:</strong></td><td>{{ $suratJalan->no_polisi ?? '-' }}</td></tr>
            <tr><td><strong>Pengemudi:</strong></td><td>{{ $suratJalan->pengemudi ?? '-' }}</td></tr>
            <tr><td><strong>Dibuat Oleh:</strong></td><td>{{ $suratJalan->creator->nama ?? '-' }}</td></tr>
            <tr><td><strong>Disetujui Oleh:</strong></td><td>{{ $suratJalan->approver->nama ?? '-' }}</td></tr>
            <tr><td><strong>Keterangan:</strong></td><td>{{ $suratJalan->keterangan ?? '-' }}</td></tr>
        </table>
        @endif
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

// SAVE KENDARAAN INFO (Security Only)
$(document).off('click', '#btnSaveKendaraan').on('click', '#btnSaveKendaraan', function () {
    const form = $('#formUpdateKendaraan');
    const suratJalanId = form.find('input[name="surat_jalan_id"]').val();
    const kendaraan = form.find('input[name="kendaraan"]').val();
    const noPolisi = form.find('input[name="no_polisi"]').val();
    const pengemudi = form.find('input[name="pengemudi"]').val();
    
    $.ajax({
        url: '/surat-jalan/' + suratJalanId + '/update-kendaraan',
        method: 'PUT',
        data: {
            _token: '{{ csrf_token() }}',
            kendaraan: kendaraan,
            no_polisi: noPolisi,
            pengemudi: pengemudi
        },
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Informasi kendaraan berhasil diupdate.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            } else {
                Swal.fire({
                    title: 'Gagal!',
                    text: response.message || 'Terjadi kesalahan.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        },
        error: function(xhr) {
            Swal.fire({
                title: 'Error!',
                text: 'Gagal menyimpan data: ' + (xhr.responseJSON?.message || 'Unknown error'),
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    });
});
</script>
