@extends('layouts.app')

@section('title', 'Surat Jalan')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Daftar Surat Jalan</h2>
            <p class="text-gray-500 text-sm mt-1">Kelola data surat jalan dan pengiriman material.</p>
        </div>
        
        @if(!in_array(auth()->user()->role, ['security', 'guest']))
        <a href="{{ route('surat-jalan.create') }}" class="btn-teal flex items-center gap-2 shadow-lg shadow-teal-500/20 hover:shadow-teal-500/40 transition-all duration-300">
            <i class="fas fa-plus"></i>
            <span>Buat Surat Jalan</span>
        </a>
        @endif
    </div>

    <!-- Filters & Content -->
    <div class="card p-6 border border-gray-100 shadow-xl shadow-gray-200/50">
        <!-- Filter Section -->
        <div class="mb-6 bg-gray-50 rounded-xl p-4 border border-gray-100">
            <div class="flex flex-col md:flex-row items-end gap-4">
                <!-- Status Filter -->
                <div class="w-full md:w-64">
                    <label for="filterStatus" class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 block">Status Filter</label>
                    <div class="relative">
                        <select id="filterStatus" class="form-input w-full appearance-none bg-white cursor-pointer hover:border-teal-400 transition-colors pr-10">
                            <option value="">Semua Status</option>
                            <option value="BUTUH_PERSETUJUAN">Butuh Persetujuan</option>
                            <option value="APPROVED">Approved</option>
                            <option value="SELESAI">Selesai</option>
                        </select>
                        <div class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </div>
                <!-- Search -->
                <div class="w-full md:flex-1">
                    <label for="customSearch" class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 block">Pencarian</label>
                    <div class="relative">
                        <input type="text" id="customSearch" class="form-input has-icon-left w-full" placeholder="Cari nomor surat, penerima...">
                        <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                            <i class="fas fa-search"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-hidden rounded-xl border border-gray-100">
            <table id="suratJalanTable" class="table-purity w-full">
                <thead>
                    <tr class="bg-gray-50 text-left">
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Nomor Surat</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Diberikan Kepada</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Berdasarkan</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Keterangan</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    <!-- Data will be loaded via AJAX -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Detail Surat Jalan -->
<!-- Modal Detail Surat Jalan (Tailwind) -->
<div id="detailModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
  <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
    <!-- Background overlay -->
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeDetailModal()"></div>

    <!-- Modal panel -->
    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
    <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
                <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">Detail Surat Jalan</h3>
                    <div id="detailModalBody">
                        <!-- Content via AJAX -->
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button type="button" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm" onclick="closeDetailModal()">
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
    var table = $('#suratJalanTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("surat-jalan.getData") }}',
            data: function (d) {
                d.status = $('#filterStatus').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'nomor_surat', name: 'nomor_surat' },
            { data: 'tanggal', name: 'tanggal' },
            { data: 'kepada', name: 'kepada' },
            { data: 'berdasarkan', name: 'berdasarkan' },
            { data: 'keterangan', name: 'keterangan' },
            { data: 'status', name: 'status', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false }
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

    // Custom search input
    $('#customSearch').on('keyup', function () {
        table.search(this.value).draw();
    });

    // ⬇️ Tambahkan ini
    $('#filterStatus').on('change', function () {
        table.ajax.reload();
    });
    // ✅ SWEETALERT SUCCESS
    @if(session('swal_success'))
        Swal.fire({
            title: 'Berhasil',
            text: @json(session('swal_success')),
            icon: 'success',
            confirmButtonText: 'OK'
        });
    @endif

    // ❌ SWEETALERT ERROR
    @if(session('swal_error'))
        Swal.fire({
            title: 'Gagal',
            text: @json(session('swal_error')),
            icon: 'error',
            confirmButtonText: 'Mengerti'
        });
    @endif
});

// Popup detail surat jalan
function showDetailSuratJalan(id) {
    $.ajax({
        url: '/surat-jalan/' + id + '/modal-detail',
        type: 'GET',
        success: function(response) {
            if (response.success) {
                $('#detailModalBody').html(response.html);
                $('#detailModal').removeClass('hidden');
            } else {
                Swal.fire('Error!', response.message, 'error');
            }
        },
        error: function() {
            Swal.fire('Error!', 'Terjadi kesalahan saat memuat detail surat jalan.', 'error');
        }
    });
}

function closeDetailModal() {
    $('#detailModal').addClass('hidden');
}


function deleteSuratJalan(id) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data surat jalan akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/surat-jalan/' + id,
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Berhasil!', response.message, 'success');
                        $('#suratJalanTable').DataTable().ajax.reload();
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

function printSuratJalan(id) {
    window.open('{{ route("surat-jalan.export", ":id") }}'.replace(':id', id), '_blank');
}
</script>
@endpush

