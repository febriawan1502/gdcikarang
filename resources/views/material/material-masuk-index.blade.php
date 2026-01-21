@extends('layouts.app')

@section('title', 'Material Masuk')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Daftar Material Masuk</h2>
            <p class="text-gray-500 text-sm mt-1">Kelola data material yang masuk ke gudang.</p>
        </div>
        
        <div class="flex flex-wrap gap-2">
            @if(auth()->user()->role !== 'guest')
            <a href="{{ route('material-masuk.create') }}" class="btn-teal flex items-center gap-2 shadow-lg shadow-teal-500/20 hover:shadow-teal-500/40 transition-all duration-300">
                <i class="fas fa-plus"></i>
                <span>Tambah Material</span>
            </a>
            @endif
            <a href="{{ route('material-masuk.print') }}" target="_blank" class="px-4 py-2 rounded-xl bg-green-500 text-white hover:bg-green-600 transition-colors flex items-center gap-2">
                <i class="fas fa-print"></i>
                <span>Print PDF</span>
            </a>
            <a href="{{ route('material-masuk.export-excel') }}" class="px-4 py-2 rounded-xl bg-red-500 text-white hover:bg-red-600 transition-colors flex items-center gap-2">
                <i class="fas fa-file-excel"></i>
                <span>Export Excel</span>
            </a>
        </div>
    </div>

    <!-- Content Card -->
    <div class="card p-6 border border-gray-100 shadow-xl shadow-gray-200/50">
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

        <!-- Table -->
        <div class="overflow-hidden rounded-xl border border-gray-100">
            <table id="materialMasukTable" class="table-purity w-full">
                <thead>
                    <tr class="bg-gray-50 text-left">
                        <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal Masuk</th>
                        <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Nomor KR</th>
                        <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Pabrikan</th>
                        <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Material</th>
                        <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Total Qty</th>
                        <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Dibuat Oleh</th>
                        <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Status SAP</th>
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

<!-- Modal Detail Material Masuk (Tailwind) -->
<div id="detailModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeDetailModal()"></div>

        <!-- Modal panel -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
            <div class="bg-gradient-to-r from-teal-500 to-teal-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                        <i class="fas fa-box-open"></i>
                        Detail Material Masuk
                    </h3>
                    <button type="button" class="text-white hover:text-gray-200 transition-colors" onclick="closeDetailModal()">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            <div class="p-6" id="detailModalBody">
                <!-- Content will be loaded here -->
            </div>
            <div class="bg-gray-50 px-6 py-4 flex justify-end">
                <button type="button" class="px-6 py-2 rounded-xl border border-gray-300 text-gray-600 hover:bg-gray-100 transition-colors" onclick="closeDetailModal()">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#materialMasukTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("material-masuk.data") }}',
        // columns: [
        //     { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
        //     { data: 'tanggal_masuk_formatted', name: 'tanggal_masuk' },
        //     { data: 'nomor_kr', name: 'nomor_kr' },
        //     { data: 'pabrikan', name: 'pabrikan' },
        //     { data: 'material_info', name: 'material_info', orderable: false, searchable: false },
        //     { data: 'total_quantity', name: 'total_quantity', orderable: false },
        //     { data: 'creator_name', name: 'creator.nama' },
        //     { data: 'action', name: 'action', orderable: false, searchable: false }
        // ],
        columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'tanggal_masuk_formatted', name: 'tanggal_masuk' },
                // { data: 'tanggal_keluar_formatted', name: 'tanggal_keluar' },
                // { data: 'jenis', name: 'jenis' },
                { data: 'nomor_kr', name: 'nomor_kr' },
                // { data: 'nomor_po', name: 'nomor_po' },
                // { data: 'nomor_doc', name: 'nomor_doc' },
                // { data: 'tugas_4', name: 'tugas_4' },
                { data: 'pabrikan', name: 'pabrikan' },
                { data: 'material_info', name: 'material_info', orderable: false, searchable: true },
                { data: 'total_quantity', name: 'total_quantity', orderable: false },
                { data: 'creator_name', name: 'creator.nama' },
                { data: 'status_sap', name: 'status_sap' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],

        responsive: true,
        order: [[1, 'desc']],
        pageLength: 25,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json'
        }
    });
});

// function showDetail(id) {
//     $.ajax({
//         url: '{{ route("material-masuk.index") }}/' + id,
//         type: 'GET',
//         success: function(response) {
//             if (response.success) {
//                 let data = response.data;
//                 let detailHtml = `
//                     <div class="row">
//                         <div class="col-md-6">
//                             <table class="table table-borderless">
//                                 <tr>
//                                     <td><strong>Nomor KR:</strong></td>
//                                     <td>${data.nomor_kr || '-'}</td>
//                                 </tr>
//                                 <tr>
//                                     <td><strong>Pabrikan:</strong></td>
//                                     <td>${data.pabrikan || '-'}</td>
//                                 </tr>
//                                 <tr>
//                                     <td><strong>Tanggal Masuk:</strong></td>
//                                     <td>${data.tanggal_masuk}</td>
//                                 </tr>
//                             </table>
//                         </div>
//                         <div class="col-md-6">
//                             <table class="table table-borderless">
//                                 <tr>
//                                     <td><strong>Dibuat Oleh:</strong></td>
//                                     <td>${data.created_by}</td>
//                                 </tr>
//                                 <tr>
//                                     <td><strong>Tanggal Dibuat:</strong></td>
//                                     <td>${data.created_at}</td>
//                                 </tr>
//                                 <tr>
//                                     <td><strong>Keterangan:</strong></td>
//                                     <td>${data.keterangan || '-'}</td>
//                                 </tr>
//                             </table>
//                         </div>
//                     </div>
//                     <hr>
//                     <h6><strong>Detail Material:</strong></h6>
//                     <div class="table-responsive">
//                         <table class="table table-bordered table-sm">
//                             <thead class="thead-light">
//                                 <tr>
//                                     <th>Kode Material</th>
//                                     <th>Deskripsi Material</th>
//                                     <th>Quantity</th>
//                                     <th>Satuan</th>
//                                     <th>Normalisasi</th>
//                                 </tr>
//                             </thead>
//                             <tbody>`;
                
//                 data.details.forEach(function(detail) {
//                     detailHtml += `
//                         <tr>
//                             <td>${detail.material_code}</td>
//                             <td>${detail.material_description}</td>
//                             <td>${detail.quantity}</td>
//                             <td>${detail.satuan}</td>
//                             <td>${detail.normalisasi || '-'}</td>
//                         </tr>`;
//                 });
                
//                 detailHtml += `
//                             </tbody>
//                         </table>
//                     </div>`;
                
//                 $('#detailModalBody').html(detailHtml);
//                 $('#detailModal').modal('show');
//             } else {
//                 Swal.fire('Error!', 'Gagal memuat detail data.', 'error');
//             }
//         },
//         error: function() {
//             Swal.fire('Error!', 'Terjadi kesalahan saat memuat detail data.', 'error');
//         }
//     });
// }
function showDetail(id) {
    $.ajax({
        url: '{{ route("material-masuk.index") }}/' + id,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                let data = response.data;
                let detailHtml = `
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td><strong>Nomor KR:</strong></td>
                                    <td>${data.nomor_kr || '-'}</td>
                                </tr>
                                <tr>
                                    <td><strong>Nomor PO:</strong></td>
                                    <td>${data.nomor_po || '-'}</td>
                                </tr>
                                <tr>
                                    <td><strong>Nomor DOC:</strong></td>
                                    <td>${data.nomor_doc || '-'}</td>
                                </tr>
                                <tr>
                                    <td><strong>Pabrikan:</strong></td>
                                    <td>${data.pabrikan || '-'}</td>
                                </tr>
                                <tr>
                                    <td><strong>Jenis:</strong></td>
                                    <td>${data.jenis || '-'}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status SAP:</strong></td>
                                    <td>${data.status_sap || '-'}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td><strong>Tanggal Masuk:</strong></td>
                                    <td>${data.tanggal_masuk}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal Keluar:</strong></td>
                                    <td>${data.tanggal_keluar || '-'}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tug 4:</strong></td>
                                    <td>${data.tugas_4 || '-'}</td>
                                </tr>
                                <tr>
                                    <td><strong>Dibuat Oleh:</strong></td>
                                    <td>${data.created_by}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal Dibuat:</strong></td>
                                    <td>${data.created_at}</td>
                                </tr>
                                <tr>
                                    <td><strong>Keterangan:</strong></td>
                                    <td>${data.keterangan || '-'}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <hr>
                    <h6><strong>Detail Material:</strong></h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="thead-light">
                                <tr>
                                    <th>Kode Material</th>
                                    <th>Deskripsi Material</th>
                                    <th>Quantity</th>
                                    <th>Satuan</th>
                                    <th>Normalisasi</th>
                                </tr>
                            </thead>
                            <tbody>`;
                
                data.details.forEach(function(detail) {
                    detailHtml += `
                        <tr>
                            <td>${detail.material_code}</td>
                            <td>${detail.material_description}</td>
                            <td>${detail.quantity}</td>
                            <td>${detail.satuan}</td>
                            <td>${detail.normalisasi || '-'}</td>
                        </tr>`;
                });
                
                detailHtml += `
                            </tbody>
                        </table>
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
                url: '{{ route("material-masuk.index") }}/' + id,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Berhasil!', response.message, 'success');
                        $('#materialMasukTable').DataTable().ajax.reload();
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