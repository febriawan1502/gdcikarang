@extends('layouts.app')

@section('title', 'Edit Material Masuk')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600">
                Edit Material Masuk
            </h2>
            <p class="text-gray-500 text-sm mt-1">Perbarui data penerimaan material: <span class="font-mono font-bold text-blue-600">{{ $materialMasuk->nomor_kr ?? '-' }}</span></p>
        </div>
        
        <a href="{{ route('material-masuk.index') }}" 
           class="group px-4 py-2 rounded-xl bg-white border border-gray-200 text-gray-600 hover:text-blue-600 hover:border-blue-200 hover:shadow-md transition-all duration-300 flex items-center gap-2 text-sm font-medium">
            <div class="w-6 h-6 rounded-full bg-gray-50 group-hover:bg-blue-50 flex items-center justify-center transition-colors">
                <i class="fas fa-arrow-left text-xs"></i>
            </div>
            <span>Kembali</span>
        </a>
    </div>

    <!-- Main Card -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-xl shadow-gray-200/50 overflow-hidden relative">
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-400 via-indigo-500 to-purple-500"></div>

        <div class="p-8">
            @if ($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-100 flex items-start gap-3">
                    <i class="fas fa-exclamation-circle text-red-500 mt-0.5"></i>
                    <div>
                        <h4 class="text-sm font-bold text-red-800 mb-1">Terdapat Kesalahan Input</h4>
                        <ul class="list-disc list-inside text-sm text-red-600">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form action="{{ route('material-masuk.update', $materialMasuk->id) }}" method="POST" id="materialMasukForm">
                @csrf
                @method('PUT')

                <!-- Section 1: Identitas Dokumen -->
                <div class="mb-10">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center shadow-sm">
                            <i class="fas fa-file-invoice text-indigo-500"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Identitas Dokumen</h3>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Nomor KR -->
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700">Nomor KR</label>
                            <input type="text" name="nomor_kr" value="{{ old('nomor_kr', $materialMasuk->nomor_kr) }}" 
                                   class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all font-mono placeholder-gray-400"
                                   placeholder="Masukkan Nomor KR">
                        </div>

                        <!-- Pabrikan -->
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700">Pabrikan</label>
                            <input type="text" name="pabrikan" value="{{ old('pabrikan', $materialMasuk->pabrikan) }}" 
                                   class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all placeholder-gray-400"
                                   placeholder="Contoh: PT. Sumber Makmur">
                        </div>

                         <!-- Tanggal Masuk -->
                         <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700">Tanggal Masuk <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_masuk" value="{{ old('tanggal_masuk', \Carbon\Carbon::parse($materialMasuk->tanggal_masuk)->format('Y-m-d')) }}" required
                                   class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all">
                        </div>

                         <!-- Jenis -->
                         <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700">Jenis</label>
                            <div class="relative">
                                <select name="jenis" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all appearance-none bg-white">
                                    <option value="">-- Pilih Jenis --</option>
                                    <option value="B1" {{ old('jenis', $materialMasuk->jenis) == 'B1' ? 'selected' : '' }}>B1</option>
                                    <option value="B2" {{ old('jenis', $materialMasuk->jenis) == 'B2' ? 'selected' : '' }}>B2</option>
                                    <option value="AO" {{ old('jenis', $materialMasuk->jenis) == 'AO' ? 'selected' : '' }}>AO</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-gray-500">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Nomor PO -->
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700">Nomor PO</label>
                            <input type="text" name="nomor_po" value="{{ old('nomor_po', $materialMasuk->nomor_po) }}" 
                                   class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all font-mono placeholder-gray-400"
                                   placeholder="Masukkan Nomor PO">
                        </div>

                         <!-- Nomor DOC -->
                         <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700">Nomor DOC</label>
                            <input type="text" name="nomor_doc" value="{{ old('nomor_doc', $materialMasuk->nomor_doc) }}" 
                                   class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all placeholder-gray-400"
                                   placeholder="Masukkan Nomor DOC">
                        </div>

                        <!-- Tug 4 -->
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700">Tug 4</label>
                            <input type="text" name="tugas_4" value="{{ old('tugas_4', $materialMasuk->tugas_4) }}" 
                                   class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all placeholder-gray-400"
                                   placeholder="Masukkan Tug 4">
                        </div>

                        <!-- Keterangan -->
                        <div class="space-y-2 md:col-span-2 lg:col-span-3">
                            <label class="text-sm font-medium text-gray-700">Keterangan</label>
                            <textarea name="keterangan" rows="2" 
                                      class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all placeholder-gray-400"
                                      placeholder="Tambahan catatan (opsional)">{{ old('keterangan', $materialMasuk->keterangan) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="w-full h-px bg-gray-100 my-8"></div>

                <!-- Section 2: Detail Material -->
                <div class="mb-8">
                     <div class="flex items-center gap-4 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-orange-50 border border-orange-100 flex items-center justify-center shadow-sm">
                            <i class="fas fa-boxes text-orange-500"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Detail Material</h3>
                        </div>
                    </div>

                    <div class="overflow-hidden rounded-xl border border-gray-200">
                        <table class="w-full text-left border-collapse" id="materialTable">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-200 text-xs uppercase text-gray-500 font-semibold tracking-wider">
                                    <th class="px-4 py-3 text-center w-12">No</th>
                                    <th class="px-4 py-3">Material</th>
                                    <th class="px-4 py-3 w-48">Normalisasi</th>
                                    <th class="px-4 py-3 w-32">Qty</th>
                                    <th class="px-4 py-3 w-32">Satuan</th>
                                    <th class="px-4 py-3 w-20 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="materialTableBody" class="divide-y divide-gray-100 bg-white">
                                @foreach($materialMasuk->details as $index => $detail)
                                <tr class="hover:bg-gray-50/50 transition-colors group">
                                    <td class="px-4 py-3 text-center text-gray-500 font-mono text-sm">{{ $index + 1 }}</td>
                                    
                                    <td class="px-4 py-3">
                                        <input type="hidden" name="materials[{{ $index }}][detail_id]" value="{{ $detail->id }}">
                                        <input type="hidden" name="materials[{{ $index }}][material_id]" value="{{ $detail->material_id }}">
                                        <div class="relative">
                                             <input type="text" 
                                                   name="materials[{{ $index }}][material_name]" 
                                                   value="{{ $detail->material->material_description ?? '' }}" 
                                                   class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-100 outline-none text-sm material-input transition-all" 
                                                   placeholder="Cari material..." 
                                                   autocomplete="off">
                                            <div class="absolute right-3 top-2.5 text-gray-400 pointer-events-none">
                                                <i class="fas fa-search text-xs"></i>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-4 py-3">
                                        <input type="text" name="materials[{{ $index }}][normalisasi]" 
                                               value="{{ $detail->normalisasi ?? $detail->material->material_code ?? '' }}" 
                                               class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:border-orange-500 outline-none text-sm font-mono bg-gray-50 focus:bg-white transition-colors">
                                    </td>

                                    <td class="px-4 py-3">
                                        <input type="number" name="materials[{{ $index }}][quantity]" 
                                               value="{{ $detail->quantity }}" min="1" 
                                               class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:border-orange-500 outline-none text-sm text-center font-semibold" required>
                                    </td>

                                    <td class="px-4 py-3">
                                        <input type="text" name="materials[{{ $index }}][satuan]" 
                                               value="{{ $detail->satuan }}" 
                                               class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:border-orange-500 outline-none text-sm text-center bg-gray-50 focus:bg-white transition-colors" required>
                                    </td>

                                    <td class="px-4 py-3 text-center">
                                        <button type="button" class="w-8 h-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-100 hover:text-red-700 transition-colors flex items-center justify-center mx-auto remove-row" onclick="removeRow(this)">
                                            <i class="fas fa-trash-alt text-xs"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col-reverse md:flex-row justify-end gap-4 pt-6 border-t border-gray-100">
                    <a href="{{ route('material-masuk.index') }}" class="px-6 py-2.5 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 transition-all font-medium flex items-center justify-center gap-2">
                        <i class="fas fa-times"></i>
                        <span>Batal</span>
                    </a>

                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 text-white font-bold hover:bg-blue-700 shadow-lg shadow-blue-200 hover:shadow-blue-300 transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-save"></i>
                        <span>Update Data</span>
                    </button>

                    <button type="submit" 
                            formaction="{{ route('material-masuk.updateDanSelesaiSAP', $materialMasuk->id) }}" 
                            class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-emerald-500 to-green-600 text-white font-bold hover:from-emerald-600 hover:to-green-700 shadow-lg shadow-green-200 hover:shadow-green-300 transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-check-double"></i>
                        <span>Update & Selesai SAP</span>
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- jQuery UI Autocomplete --}}
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<style>
    /* Custom CSS for jQuery UI Autocomplete to match Tailwind */
    .ui-autocomplete {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 0.75rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        padding: 0.5rem;
        z-index: 9999;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    .ui-menu-item {
        padding: 0;
        margin-bottom: 2px;
    }
    .ui-menu-item-wrapper {
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        color: #374151;
    }
    .ui-menu-item-wrapper.ui-state-active {
        background: #eff6ff;
        color: #2563eb;
        border: none;
        font-weight: 500;
    }
</style>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<script>
function removeRow(btn) {
    if(confirm('Apakah Anda yakin ingin menghapus baris ini?')) {
        $(btn).closest('tr').remove();
        // Re-number rows? Optional but looks better
        $('#materialTableBody tr').each(function(index) {
            $(this).find('td:first').text(index + 1);
        });
    }
}

$(document).ready(function() {
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    // Autocomplete untuk kolom Material
    $(document).on('focus', '.material-input', function() {
        const input = $(this);
        const row = input.closest('tr');

        input.autocomplete({
            appendTo: 'body',
            minLength: 2,
            delay: 200,
            source: function(request, response) {
                $.ajax({
                    url: '/material/autocomplete',
                    dataType: 'json',
                    data: { term: request.term },
                    success: function(data) {
                        response($.map(data, function(item) {
                            return {
                                label: item.material_code + ' - ' + item.material_description,
                                value: item.material_description,
                                id: item.id,
                                kode: item.material_code,
                                satuan: item.satuan
                            };
                        }));
                    },
                    error: function(xhr) {
                        console.error('Autocomplete error:', xhr.responseText);
                    }
                });
            },
            select: function(event, ui) {
                row.find('input[name*="[material_id]"]').val(ui.item.id);
                row.find('input[name*="[normalisasi]"]').val(ui.item.kode);
                row.find('input[name*="[satuan]"]').val(ui.item.satuan);
            }
        });
    });

    // Kalau user ngetik manual, kosongkan material_id
    $(document).on('input', '.material-input', function() {
        const row = $(this).closest('tr');
        row.find('input[name*="[material_id]"]').val('');
    });
});
</script>

@endpush
