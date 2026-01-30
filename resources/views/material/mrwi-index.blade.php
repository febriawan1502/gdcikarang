@extends('layouts.app')

@section('title', 'Material MRWI')

@section('content')
    @push('styles')
        <style>
            .action-buttons {
                display: flex;
                gap: 6px;
                justify-content: center;
            }

            .action-btn {
                width: 32px;
                height: 32px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 8px;
                border: none;
                cursor: pointer;
                font-size: 12px;
                transition: all 0.2s;
                text-decoration: none;
            }

            .action-btn:hover {
                transform: translateY(-1px);
            }

            .action-btn.view {
                background: #BEE3F8;
                color: #2B6CB0;
            }
        </style>
    @endpush
    <div class="space-y-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Daftar Material MRWI</h2>
                <p class="text-gray-500 text-sm mt-1">Kelola data material retur (bekas).</p>
            </div>

            <div class="flex flex-wrap gap-2">
                @if (auth()->user()->role !== 'guest')
                    <a href="{{ route('material-mrwi.create') }}"
                        class="btn-teal flex items-center gap-2 shadow-lg shadow-teal-500/20 hover:shadow-teal-500/40 transition-all duration-300">
                        <i class="fas fa-plus"></i>
                        <span>Input MRWI</span>
                    </a>
                @endif
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
                    <div class="w-full lg:w-56">
                        <label for="filterStatus"
                            class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 block">Status</label>
                        <div class="relative">
                            <select id="filterStatus"
                                class="form-input w-full appearance-none bg-white cursor-pointer hover:border-teal-400 transition-colors pr-10">
                                <option value="">Semua Status</option>
                                <option value="DRAFT">Draft</option>
                                <option value="MENUNGGU_KLASIFIKASI">Menunggu Klasifikasi</option>
                                <option value="SELESAI">Selesai</option>
                            </select>
                            <div class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                    </div>

                    <div class="w-full sm:w-48">
                        <label for="filterStartDate"
                            class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 block">Tanggal Awal</label>
                        <input type="date" id="filterStartDate" class="form-input w-full">
                    </div>
                    <div class="w-full sm:w-48">
                        <label for="filterEndDate"
                            class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 block">Tanggal Akhir</label>
                        <input type="date" id="filterEndDate" class="form-input w-full">
                    </div>

                    <div class="w-full lg:flex-1">
                        <label for="customSearch"
                            class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 block">Pencarian</label>
                        <div class="relative">
                            <input type="text" id="customSearch" class="form-input has-icon-left w-full"
                                placeholder="Cari nomor MRWI, ULP...">
                            <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                                <i class="fas fa-search"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="overflow-hidden rounded-xl border border-gray-100">
                <table id="mrwiTable" class="table-purity w-full">
                    <thead>
                        <tr class="bg-gray-50 text-left">
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Nomor MRWI</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">ULP Pengirim</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Total Qty</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Dibuat Oleh</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Aksi</th>
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
            var table = $('#mrwiTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('material-mrwi.data') }}',
                    data: function(d) {
                        d.status = $('#filterStatus').val();
                        d.start_date = $('#filterStartDate').val();
                        d.end_date = $('#filterEndDate').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'tanggal_masuk',
                        name: 'tanggal_masuk'
                    },
                    {
                        data: 'nomor_mrwi',
                        name: 'nomor_mrwi'
                    },
                    {
                        data: 'ulp_pengirim',
                        name: 'ulp_pengirim'
                    },
                    {
                        data: 'total_qty',
                        name: 'total_qty',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'status_badge',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'creator_name',
                        name: 'creator_name',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [
                    [1, 'desc']
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

            $('#customSearch').on('keyup', function() {
                table.search(this.value).draw();
            });

            $('#filterStatus, #filterStartDate, #filterEndDate').on('change', function() {
                table.ajax.reload();
            });
        });
    </script>
@endpush
