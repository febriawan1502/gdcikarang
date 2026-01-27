@extends('layouts.app')

@section('title', 'Material Masuk')

@section('content')
    @push('styles')
        <style>
            /* Action Buttons in Table */
            .action-buttons {
                display: flex;
                gap: 6px;
                justify-content: center;
                /* Center action buttons */
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
                /* Ensure links don't have underline */
            }

            .action-btn:hover {
                transform: translateY(-1px);
            }

            .action-btn.view {
                background: #BEE3F8;
                color: #2B6CB0;
            }

            .action-btn.edit {
                background: #FEFCBF;
                color: #744210;
            }

            .action-btn.barcode {
                background: #C6F6D5;
                color: #22543D;
            }

            /* Reuse for Print/Success if needed */
            .action-btn.delete {
                background: #FED7D7;
                color: #742A2A;
            }

            .action-btn.info {
                background: #EBF8FF;
                color: #3182CE;
            }

            /* Explicit Info style */
        </style>
    @endpush
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Daftar Material Masuk</h2>
                <p class="text-gray-500 text-sm mt-1">Kelola data material yang masuk ke gudang.</p>
            </div>

            <div class="flex flex-wrap gap-2">
                @if (auth()->user()->role !== 'guest')
                    <a href="{{ route('material-masuk.create') }}"
                        class="btn-teal flex items-center gap-2 shadow-lg shadow-teal-500/20 hover:shadow-teal-500/40 transition-all duration-300">
                        <i class="fas fa-plus"></i>
                        <span>Tambah Material</span>
                    </a>
                @endif
                <a href="{{ route('material-masuk.print') }}" target="_blank"
                    class="px-4 py-2 rounded-xl bg-green-500 text-white hover:bg-green-600 transition-colors flex items-center gap-2">
                    <i class="fas fa-print"></i>
                    <span>Print PDF</span>
                </a>
                <a href="{{ route('material-masuk.export-excel') }}"
                    class="px-4 py-2 rounded-xl bg-red-500 text-white hover:bg-red-600 transition-colors flex items-center gap-2">
                    <i class="fas fa-file-excel"></i>
                    <span>Export Excel</span>
                </a>
            </div>
        </div>

        <!-- Content Card -->
        <div class="card p-6 border border-gray-100 shadow-xl shadow-gray-200/50">
            <!-- Alert Messages -->
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

            <!-- Livewire Table -->
            @livewire('material-masuk-table')
        </div>
    </div>

    <!-- Modal Detail Material Masuk (Tailwind) -->
    <div id="detailModal" class="fixed inset-0 z-9999 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity"
                style="background-color: rgba(17, 24, 39, 0.6); backdrop-filter: blur(4px);" aria-hidden="true"
                onclick="closeDetailModal()"></div>

            <!-- Modal panel -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="relative z-10 inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full border border-gray-100">
                <div class="bg-linear-to-r from-teal-500 to-teal-600 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                            <i class="fas fa-box-open"></i>
                            Detail Material Masuk
                        </h3>
                        <button type="button" class="text-white hover:text-gray-200 transition-colors"
                            onclick="closeDetailModal()">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>
                <div class="p-6 max-h-[80vh] overflow-y-auto" id="detailModalBody">
                    <!-- Content will be loaded here -->
                </div>
                <div class="bg-gray-50 px-6 py-4 flex justify-end">
                    <button type="button"
                        class="px-6 py-2 rounded-xl border border-gray-300 text-gray-600 hover:bg-gray-100 transition-colors"
                        onclick="closeDetailModal()">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function showDetail(id) {
            $.ajax({
                url: '{{ route('material-masuk.index') }}/' + id,
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        let data = response.data;
                        let detailHtml = `
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <!-- Left Column -->
                        <div class="space-y-4">
                            <div class="bg-gray-50/50 rounded-xl p-5 border border-gray-100">
                                <h4 class="text-xs font-bold text-teal-600 uppercase tracking-wider mb-4 flex items-center gap-2">
                                    <i class="fas fa-file-alt"></i> Info Dokumen
                                </h4>
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-gray-500">Nomor KR</span>
                                        <span class="font-semibold text-gray-800 font-mono">${data.nomor_kr || '-'}</span>
                                    </div>
                                    <div class="flex justify-between items-center text-sm border-t border-dashed border-gray-200 pt-2">
                                        <span class="text-gray-500">Nomor PO</span>
                                        <span class="font-medium text-gray-800">${data.nomor_po || '-'}</span>
                                    </div>
                                    <div class="flex justify-between items-center text-sm border-t border-dashed border-gray-200 pt-2">
                                        <span class="text-gray-500">Nomor DOC</span>
                                        <span class="font-medium text-gray-800">${data.nomor_doc || '-'}</span>
                                    </div>
                                    <div class="flex justify-between items-center text-sm border-t border-dashed border-gray-200 pt-2">
                                        <span class="text-gray-500">Pabrikan</span>
                                        <span class="font-medium text-gray-800">${data.pabrikan || '-'}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-teal-50/50 rounded-xl p-5 border border-teal-100">
                                <h4 class="text-xs font-bold text-teal-600 uppercase tracking-wider mb-4 flex items-center gap-2">
                                    <i class="fas fa-tag"></i> Klasifikasi
                                </h4>
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-gray-500">Jenis Material</span>
                                        <span class="font-medium text-gray-800 bg-white px-2 py-1 rounded shadow-sm">${data.jenis || '-'}</span>
                                    </div>
                                    <div class="flex justify-between items-center text-sm border-t border-dashed border-teal-200 pt-2">
                                        <span class="text-gray-500">Status SAP</span>
                                        <span class="font-bold text-orange-500">
                                            ${data.status_sap && data.status_sap !== 'Selesai SAP' ? data.status_sap : '-'}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-4">
                             <div class="bg-gray-50/50 rounded-xl p-5 border border-gray-100 h-full">
                                <h4 class="text-xs font-bold text-teal-600 uppercase tracking-wider mb-4 flex items-center gap-2">
                                    <i class="fas fa-clock"></i> Riwayat & Logistik
                                </h4>
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-gray-500">Tanggal Masuk</span>
                                        <span class="font-medium text-gray-800">${data.tanggal_masuk}</span>
                                    </div>
                                     <div class="flex justify-between items-center text-sm border-t border-dashed border-gray-200 pt-2">
                                        <span class="text-gray-500">Tanggal Keluar</span>
                                        <span class="font-medium text-gray-800">${data.tanggal_keluar || '-'}</span>
                                    </div>
                                    <div class="flex justify-between items-center text-sm border-t border-dashed border-gray-200 pt-2">
                                        <span class="text-gray-500">Tug 4</span>
                                        <span class="font-medium text-gray-800">${data.tugas_4 || '-'}</span>
                                    </div>
                                    <div class="flex justify-between items-center text-sm border-t border-dashed border-gray-200 pt-2">
                                        <span class="text-gray-500">Dibuat Oleh</span>
                                        <div class="flex items-center gap-2">
                                            <div class="w-6 h-6 rounded-full bg-teal-100 flex items-center justify-center text-teal-600 text-xs font-bold">
                                                ${data.created_by.charAt(0)}
                                            </div>
                                            <span class="font-medium text-gray-800">${data.created_by}</span>
                                        </div>
                                    </div>
                                    <div class="flex justify-between items-center text-sm border-t border-dashed border-gray-200 pt-2">
                                        <span class="text-gray-500">Keterangan</span>
                                        <span class="font-medium text-gray-800 italic">${data.keterangan || '-'}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-6">
                        <h4 class="text-sm font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <div class="p-1.5 bg-teal-100 text-teal-600 rounded-lg">
                                <i class="fas fa-boxes"></i>
                            </div>
                            Detail Item Material
                        </h4>
                        <div class="overflow-hidden rounded-xl border border-gray-200 shadow-sm">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kode</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Deskripsi</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Qty</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Satuan</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Norm</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">`;

                        data.details.forEach(function(detail) {
                            detailHtml += `
                        <tr class="hover:bg-teal-50/30 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono font-medium text-teal-600 bg-gray-50/50">
                                ${detail.material_code}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                ${detail.material_description}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 text-center bg-gray-50/30">
                                ${detail.quantity}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                ${detail.satuan}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                ${detail.normalisasi || '-'}
                            </td>
                        </tr>`;
                        });

                        detailHtml += `
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4 flex justify-end">
                            <span class="text-xs text-gray-400">Total ${data.details.length} item material</span>
                        </div>
                    </div>`;

                        $('#detailModalBody').html(detailHtml);
                        $('#detailModal').removeClass('hidden'); // Show Tailwind modal
                    } else {
                        Swal.fire('Error!', 'Gagal memuat detail data.', 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error!', 'Terjadi kesalahan saat memuat detail data.', 'error');
                }
            });
        }

        function closeDetailModal() {
            $('#detailModal').addClass('hidden');
        }

        function deleteMaterialMasuk(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data material masuk akan dihapus dan stock akan dikurangi!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('material-masuk.index') }}/' + id,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire('Berhasil!', response.message, 'success');
                                if (window.Livewire) {
                                    window.Livewire.dispatch('material-masuk-refresh');
                                }
                            } else {
                                Swal.fire('Error!', response.message, 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('Error!', 'Terjadi kesalahan saat menghapus data.', 'error');
                        }
                    });
                }
            });
        }
    </script>
@endpush
