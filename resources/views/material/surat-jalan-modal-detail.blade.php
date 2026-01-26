@php
    $isSecurity   = auth()->user()->role === 'security';
    $hasUnchecked = $suratJalan->details->where('is_checked', false)->count() > 0;
    $isSapDone    = !empty($suratJalan->nomor_slip);
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <!-- Kolom Kiri: Info Utama -->
    <div class="bg-white rounded-xl">
        <table class="w-full text-sm">
            <tbody class="divide-y divide-gray-100">
                <tr>
                    <td class="py-2 text-gray-500 font-medium w-32">No. Surat</td>
                    <td class="py-2 font-semibold text-gray-900">: {{ $suratJalan->nomor_surat ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="py-2 text-gray-500 font-medium">Tanggal</td>
                    <td class="py-2 text-gray-900">: {{ optional($suratJalan->tanggal)->format('d/m/Y') ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="py-2 text-gray-500 font-medium">Jenis</td>
                    <td class="py-2 text-gray-900">: {{ $suratJalan->jenis_surat_jalan ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="py-2 text-gray-500 font-medium">Kepada</td>
                    <td class="py-2 text-gray-900">: {{ $suratJalan->kepada ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="py-2 text-gray-500 font-medium">Berdasarkan</td>
                    <td class="py-2 text-gray-900">: {{ $suratJalan->berdasarkan ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="py-2 text-gray-500 font-medium">No Slip SAP</td>
                    <td class="py-2 text-gray-900">: {{ $suratJalan->nomor_slip ?? '-' }}</td>
                </tr>
                @if(!$isSapDone)
                <tr>
                    <td class="py-2 text-gray-500 font-medium">Status SAP</td>
                    <td class="py-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                            : Belum Selesai SAP
                        </span>
                    </td>
                </tr>
                @endif
                <tr>
                    <td class="py-2 text-gray-500 font-medium">Status</td>
                    <td class="py-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            {{ $suratJalan->status == 'APPROVED' ? 'bg-green-100 text-green-800' : 
                               ($suratJalan->status == 'SELESAI' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
                            : {{ $suratJalan->status ?? '-' }}
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Kolom Kanan: Info Kendaraan -->
    <div class="bg-white rounded-xl">
        @if($isSecurity)
            <form id="formUpdateKendaraan" class="bg-gray-50 p-4 rounded-xl border border-gray-200 shadow-sm">
                <input type="hidden" name="surat_jalan_id" value="{{ $suratJalan->id }}">
                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3 border-b border-gray-200 pb-2">
                    <i class="fas fa-truck mr-1"></i> Update Info Kendaraan
                </h4>
                
                <div class="space-y-3">
                    <div class="grid grid-cols-1 gap-1">
                        <label class="text-xs font-medium text-gray-600">Kendaraan</label>
                        <input type="text" name="kendaraan" class="form-input text-sm w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" 
                               value="{{ $suratJalan->kendaraan }}" placeholder="Jenis kendaraan">
                    </div>
                    <div class="grid grid-cols-1 gap-1">
                        <label class="text-xs font-medium text-gray-600">No Polisi</label>
                        <input type="text" name="no_polisi" class="form-input text-sm w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" 
                               value="{{ $suratJalan->no_polisi }}" placeholder="Nomor polisi">
                    </div>
                    <div class="grid grid-cols-1 gap-1">
                        <label class="text-xs font-medium text-gray-600">Pengemudi</label>
                        <input type="text" name="pengemudi" class="form-input text-sm w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" 
                               value="{{ $suratJalan->pengemudi }}" placeholder="Nama pengemudi">
                    </div>
                    <button type="button" id="btnSaveKendaraan" class="w-full mt-2 bg-blue-600 text-white text-xs font-bold py-2 rounded-lg hover:bg-blue-700 transition shadow-md">
                        SIMPAN
                    </button>
                </div>
            </form>
            
            <div class="mt-4 text-xs text-gray-500 space-y-1 pl-1">
                <p>Dibuat: <span class="font-medium text-gray-700">{{ $suratJalan->creator->nama ?? '-' }}</span></p>
                <p>Approved: <span class="font-medium text-gray-700">{{ $suratJalan->approver->nama ?? '-' }}</span></p>
            </div>
        @else
            <table class="w-full text-sm">
                <tbody class="divide-y divide-gray-100">
                    <tr><td class="py-2 text-gray-500 font-medium w-32">Kendaraan</td><td class="py-2 text-gray-900">: {{ $suratJalan->kendaraan ?? '-' }}</td></tr>
                    <tr><td class="py-2 text-gray-500 font-medium">No Polisi</td><td class="py-2 text-gray-900">: {{ $suratJalan->no_polisi ?? '-' }}</td></tr>
                    <tr><td class="py-2 text-gray-500 font-medium">Pengemudi</td><td class="py-2 text-gray-900">: {{ $suratJalan->pengemudi ?? '-' }}</td></tr>
                    <tr><td class="py-2 text-gray-500 font-medium">Dibuat Oleh</td><td class="py-2 text-gray-900">: {{ $suratJalan->creator->nama ?? '-' }}</td></tr>
                    <tr><td class="py-2 text-gray-500 font-medium">Disetujui Oleh</td><td class="py-2 text-gray-900">: {{ $suratJalan->approver->nama ?? '-' }}</td></tr>
                    <tr><td class="py-2 text-gray-500 font-medium">Keterangan</td><td class="py-2 text-gray-900">: {{ $suratJalan->keterangan ?? '-' }}</td></tr>
                </tbody>
            </table>
        @endif
    </div>
</div>

@if($suratJalan->foto_penerima)
<div class="mb-6 bg-gray-50 rounded-xl p-4 border border-gray-100">
    <h6 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3 flex items-center gap-2">
        <i class="fas fa-camera text-blue-500"></i> Dokumentasi Penerimaan
    </h6>
    <div class="flex flex-col items-center">
        <a href="{{ asset('storage/' . $suratJalan->foto_penerima) }}" target="_blank" class="block overflow-hidden rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition">
            <img src="{{ asset('storage/' . $suratJalan->foto_penerima) }}" 
                 alt="Foto Penerima" 
                 class="max-w-xs object-cover hover:scale-105 transition duration-300"
                 style="max-height: 200px;">
        </a>
        <p class="text-xs text-gray-400 mt-2">Klik foto untuk memperbesar</p>
    </div>
</div>
@endif

<div class="flex items-center justify-between mb-3">
    <h4 class="text-sm font-bold text-gray-800">Detail Material</h4>
    @if($isSecurity && $hasUnchecked)
        <div class="text-xs text-orange-600 bg-orange-50 px-2 py-1 rounded border border-orange-200">
            <i class="fas fa-info-circle mr-1"></i> Silakan cek fisik barang
        </div>
    @endif
</div>

@if($isSecurity && $hasUnchecked)
<form id="formCheckedMaterial" action="{{ route('surat-jalan.submit-checked') }}" method="POST">
    @csrf
    <input type="hidden" name="surat_jalan_id" value="{{ $suratJalan->id }}">
@endif

<div class="border rounded-xl overflow-hidden shadow-sm">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                @if($isSecurity && $hasUnchecked)
                <th class="px-4 py-3 text-center w-12 text-xs font-bold text-gray-500 uppercase">
                    <input type="checkbox" id="checkAll" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 w-4 h-4 cursor-pointer">
                </th>
                @endif
                <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Kode</th>
                <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Deskripsi</th>
                <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Qty</th>
                <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Satuan</th>
                <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Ket</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 bg-white">
            @foreach($suratJalan->details as $detail)
            <tr class="hover:bg-gray-50 transition-colors">
                @if($isSecurity && $hasUnchecked)
                    <td class="px-4 py-3 text-center">
                         @if(!$detail->is_checked)
                            <input type="checkbox" name="detail_ids[]" value="{{ $detail->id }}" class="check-item w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500 cursor-pointer">
                        @else
                            <i class="fas fa-check-circle text-green-500 text-lg"></i>
                        @endif
                    </td>
                @endif

                @if($detail->is_manual)
                    <td class="px-4 py-3 text-gray-400 italic text-xs">MANUAL</td>
                    <td class="px-4 py-3 font-medium text-gray-900">{{ $detail->nama_barang_manual }}</td>
                    <td class="px-4 py-3 text-gray-900 font-bold">{{ $detail->quantity }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $detail->satuan_manual }}</td>
                @else
                    <td class="px-4 py-3 font-mono text-xs text-blue-600">{{ $detail->material->material_code ?? '-' }}</td>
                    <td class="px-4 py-3 font-medium text-gray-900">{{ $detail->material->material_description ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-900 font-bold">{{ $detail->quantity }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $detail->satuan }}</td>
                @endif
                <td class="px-4 py-3 text-gray-500 italic text-xs">{{ $detail->keterangan ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@if($isSecurity && $hasUnchecked)
    <div class="mt-4 flex justify-end">
        <button type="button" id="btnChecked" class="btn-primary flex items-center gap-2 shadow-lg shadow-blue-500/30 px-6 py-2 rounded-xl">
            <i class="fas fa-check-double"></i>
            <span>Konfirmasi Checked</span>
        </button>
    </div>
</form>
@endif

<!-- Script Handler -->
<script>
// Validasi Checkbox sebelum submit
$(document).off('click', '#btnChecked').on('click', '#btnChecked', function () {
    const totalCheckbox = $('.check-item').length;
    const checkedCheckbox = $('.check-item:checked').length;

    if (checkedCheckbox < totalCheckbox) {
        Swal.fire({
            title: 'Belum Lengkap',
            text: 'Semua material harus dicentang sebelum melakukan konfirmasi checked.',
            icon: 'warning',
            confirmButtonText: 'Mengerti',
            confirmButtonColor: '#F59E0B'
        });
        return;
    }

    Swal.fire({
        title: 'Konfirmasi',
        text: "Apakah Anda yakin semua material fisik sudah sesuai?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#10B981',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Konfirmasi!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $('#formCheckedMaterial').submit();
        }
    });
});

// Check All
$(document).off('change', '#checkAll').on('change', '#checkAll', function () {
    $('.check-item').prop('checked', this.checked);
});

// Save Kendaraan
$(document).off('click', '#btnSaveKendaraan').on('click', '#btnSaveKendaraan', function () {
    const form = $('#formUpdateKendaraan');
    const btn = $(this);
    const originalText = btn.html();
    
    // Loading state
    btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

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
            btn.prop('disabled', false).html(originalText);
            if (response.success) {
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Informasi kendaraan berhasil diupdate.',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                Swal.fire('Gagal!', response.message || 'Terjadi kesalahan.', 'error');
            }
        },
        error: function(xhr) {
            btn.prop('disabled', false).html(originalText);
            Swal.fire('Error!', 'Gagal menyimpan data: ' + (xhr.responseJSON?.message || 'Unknown error'), 'error');
        }
    });
});
</script>
