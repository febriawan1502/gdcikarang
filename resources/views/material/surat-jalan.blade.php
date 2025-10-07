@extends('layouts.app')

@section('title', 'Surat Jalan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Surat Jalan</h3>
                    <div class="card-tools">
                        <a href="{{ route('surat-jalan.create') }}" class="btn btn-primary">
                            <i class="fa fa-plus"></i> Buat Surat Jalan
                        </a>
                        @if(auth()->user()->isAdmin())
                        <a href="{{ route('surat-jalan.approval') }}" class="btn btn-success">
                            <i class="fa fa-check"></i> Approval
                        </a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="suratJalanTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nomor Surat</th>
                                    <th>Tanggal</th>
                                    <th>Diberikan Kepada</th>
                                    <th>Berdasarkan</th>
                                    <th>Untuk Pekerjaan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#suratJalanTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("surat-jalan.data") }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'nomor_surat', name: 'nomor_surat' },
            { data: 'tanggal', name: 'tanggal' },
            { data: 'kepada', name: 'kepada' },
            { data: 'berdasarkan', name: 'berdasarkan' },
            { data: 'keterangan', name: 'keterangan' },
            { data: 'status', name: 'status' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        responsive: true
    });
});

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
                data: {
                    _token: '{{ csrf_token() }}'
                },
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

// Handle SweetAlert for session messages
@if(session('swal_error'))
    Swal.fire({
        icon: 'error',
        title: 'Akses Ditolak!',
        text: '{{ session("swal_error") }}',
        confirmButtonText: 'OK'
    });
@endif
</script>
@endpush