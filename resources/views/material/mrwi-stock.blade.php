@extends('layouts.app')

@section('title', 'Material MRWI Stock')

@section('content')
    @push('styles')
        <style>
            .mrwi-tab {
                padding: 8px 14px;
                border-radius: 9999px;
                font-weight: 600;
                transition: all 0.2s;
            }

            .mrwi-tab.active {
                background: #14b8a6;
                color: #fff;
                box-shadow: 0 6px 20px rgba(20, 184, 166, 0.25);
            }
        </style>
    @endpush
    <div class="space-y-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Material MRWI</h2>
                <p class="text-gray-500 text-sm mt-1">Stok material bekas berdasarkan kategori.</p>
            </div>
        </div>

        <div class="card p-6 border border-gray-100 shadow-xl shadow-gray-200/50">
            <div class="flex flex-wrap gap-2 mb-6">
                <a href="{{ route('material-mrwi.stock', ['category' => 'standby']) }}"
                    class="mrwi-tab {{ $category === 'standby' ? 'active' : 'bg-gray-100 text-gray-600' }}">
                    Standby
                </a>
                <a href="{{ route('material-mrwi.stock', ['category' => 'garansi']) }}"
                    class="mrwi-tab {{ $category === 'garansi' ? 'active' : 'bg-gray-100 text-gray-600' }}">
                    Garansi
                </a>
                <a href="{{ route('material-mrwi.stock', ['category' => 'perbaikan']) }}"
                    class="mrwi-tab {{ $category === 'perbaikan' ? 'active' : 'bg-gray-100 text-gray-600' }}">
                    Perbaikan
                </a>
                <a href="{{ route('material-mrwi.stock', ['category' => 'rusak']) }}"
                    class="mrwi-tab {{ $category === 'rusak' ? 'active' : 'bg-gray-100 text-gray-600' }}">
                    Rusak
                </a>
            </div>

            <div class="overflow-hidden rounded-xl border border-gray-100">
                <table id="mrwiStockTable" class="table-purity w-full">
                    <thead>
                        <tr class="bg-gray-50 text-left">
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Kode Material</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Material</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Stock</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Satuan</th>
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
            $('#mrwiStockTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('material-mrwi.stock.data', ['category' => $category]) }}',
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'material_code',
                        name: 'material_code'
                    },
                    {
                        data: 'material_description',
                        name: 'material_description'
                    },
                    {
                        data: 'stock_qty',
                        name: 'stock_qty',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'base_unit_of_measure',
                        name: 'base_unit_of_measure',
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [
                    [1, 'asc']
                ],
                responsive: true,
                dom: '<"hidden"f>rt<"flex flex-col md:flex-row justify-between items-center mt-4 gap-4"<"text-sm text-gray-500"l><"flex items-center gap-1"p>>',
                language: {
                    lengthMenu: 'Tampilkan _MENU_ data',
                    info: 'Menampilkan _START_ - _END_ dari _TOTAL_ data',
                    infoEmpty: 'Tidak ada data',
                    infoFiltered: '(difilter dari _MAX_ total data)',
                    search: '',
                    searchPlaceholder: 'Cari...',
                    zeroRecords: 'Data tidak ditemukan',
                    paginate: {
                        first: '<i class="fas fa-angle-double-left"></i>',
                        last: '<i class="fas fa-angle-double-right"></i>',
                        next: '<i class="fas fa-chevron-right"></i> Berikutnya',
                        previous: 'Sebelumnya <i class="fas fa-chevron-left"></i>'
                    }
                }
            });
        });
    </script>
@endpush
