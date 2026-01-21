@extends('layouts.app')

@section('title', 'Cek Kesesuaian SAP')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">📊 Cek Kesesuaian SAP vs Fisik</h2>
            <p class="text-gray-500 text-sm mt-1">Bandingkan data persediaan SAP dengan stok fisik di sistem.</p>
        </div>
    </div>

    <!-- Upload Card -->
    <div class="card p-6 border border-gray-100 shadow-xl shadow-gray-200/50">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-teal-400 to-teal-500 flex items-center justify-center text-white">
                <i class="fas fa-cloud-upload-alt"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-800">Upload Data SAP</h3>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 flex items-center gap-2">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 flex items-center gap-2">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <p class="text-gray-600 mb-4">Silahkan upload data persediaan fisik SAP terbaru kemudian upload.</p>
        
        <form action="{{ route('sap-check.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label for="file_sap" class="form-label">File Excel SAP</label>
                <input type="file" name="file_sap" id="file_sap" class="form-input" required accept=".xlsx, .xls, .csv">
                @error('file_sap')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn-teal px-6 py-3 shadow-lg shadow-teal-500/20 hover:shadow-teal-500/40 transition-all duration-300 flex items-center gap-2">
                <i class="fas fa-upload"></i>
                <span>Upload</span>
            </button>
        </form>
    </div>

    <!-- Results Card -->
    @if(isset($results))
    <div class="card p-6 border border-gray-100 shadow-xl shadow-gray-200/50">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-400 to-purple-500 flex items-center justify-center text-white">
                <i class="fas fa-chart-bar"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-800">Hasil Perbandingan</h3>
        </div>

        <div class="overflow-x-auto rounded-xl border border-gray-100">
            <table class="table-purity w-full" id="resultTable">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Normalisasi</th>
                        <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Material</th>
                        <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Stock Fisik (DB)</th>
                        <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Stock SAP (Excel)</th>
                        <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Selisih</th>
                        <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @foreach($results as $index => $row)
                    <tr class="hover:bg-gray-50 transition-colors {{ $row['selisih'] != 0 ? 'bg-red-50/50' : '' }}">
                        <td class="px-4 py-3 text-gray-600">{{ $index + 1 }}</td>
                        <td class="px-4 py-3 text-gray-800 font-medium">{{ $row['normalisasi'] }}</td>
                        <td class="px-4 py-3 text-gray-800">{{ $row['nama_material'] }}</td>
                        <td class="px-4 py-3 text-right text-gray-600">{{ number_format($row['stock_fisik'], 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right text-gray-600">{{ number_format($row['stock_sap'], 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right font-bold {{ $row['selisih'] < 0 ? 'text-red-600' : ($row['selisih'] > 0 ? 'text-green-600' : 'text-gray-600') }}">
                            {{ number_format($row['selisih'], 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3">
                            @if($row['selisih'] == 0)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Sesuai
                                </span>
                            @elseif($row['selisih'] > 0)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Fisik Lebih
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    SAP Lebih
                                </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        if ($('#resultTable').length) {
            $('#resultTable').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                language: {
                    search: "🔍 Cari:",
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    paginate: { previous: "Sebelumnya", next: "Berikutnya" },
                    zeroRecords: "Tidak ada data ditemukan"
                }
            });
        }
    });
</script>
@endpush
