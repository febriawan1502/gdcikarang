@extends('layouts.app')

@section('title', 'Daftar Material MRWI')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Daftar Material MRWI</h2>
                <p class="text-gray-500 text-sm mt-1">Daftar material MRWI per nomor seri.</p>
            </div>
        </div>

        <div class="card p-6 border border-gray-100 shadow-xl shadow-gray-200/50">
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 flex items-center gap-2">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 flex items-center gap-2">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <div class="mb-6 bg-gray-50 rounded-xl p-4 border border-gray-100">
                <div class="flex flex-col lg:flex-row items-end gap-4">
                    <div class="w-full lg:flex-1">
                        <label for="customSearch"
                            class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 block">Pencarian</label>
                        <div class="relative">
                            <input type="text" id="customSearch" class="form-input has-icon-left w-full"
                                placeholder="Cari no seri, material, merk, ID pel...">
                            <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                                <i class="fas fa-search"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="overflow-hidden rounded-xl border border-gray-100">
                <table id="mrwiItemsTable" class="table-purity w-full">
                    <thead>
                        <tr class="bg-gray-50 text-left">
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">No Seri PLN</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">No Material</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Material</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Merk</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Tahun</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">ID Pel</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Status Inspeksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        <!-- Data via AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var table = $('#mrwiItemsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('material-mrwi.items.data') }}',
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'serial_number', name: 'serial_number' },
                    { data: 'no_material', name: 'no_material' },
                    { data: 'nama_material', name: 'nama_material' },
                    { data: 'nama_pabrikan', name: 'nama_pabrikan' },
                    { data: 'tahun_buat', name: 'tahun_buat' },
                    { data: 'id_pelanggan', name: 'id_pelanggan' },
                    { data: 'status_inspeksi', name: 'status_inspeksi', orderable: false, searchable: false },
                ],
                order: [[1, 'asc']],
                responsive: true,
                dom: '<"hidden"f>rt<"flex flex-col md:flex-row justify-between items-center mt-4 gap-4"<"text-sm text-gray-500"l><"flex items-center gap-1"p>>',
                language: {
                    lengthMenu: "Tampilkan _MENU_ data",
                    zeroRecords: "Tidak ada data",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 data",
                    infoFiltered: "(difilter dari _MAX_ total data)",
                    paginate: {
                        previous: "<",
                        next: ">"
                    }
                }
            });

            $('#customSearch').on('keyup', function() {
                table.search(this.value).draw();
            });
        });
    </script>
@endpush
