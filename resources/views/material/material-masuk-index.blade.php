@extends('layouts.app')

@section('title', 'Material Masuk')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Material Masuk</h3>
                    <div class="card-tools">
                        <a href="{{ route('material-masuk.create') }}" class="btn btn-primary">
                        <i class="fa fa-plus"></i> Tambah Material Masuk
                    </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Alert Messages -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade in" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <i class="fa fa-check-circle"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade in" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <i class="fa fa-exclamation-circle"></i>
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table id="materialMasukTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Masuk</th>
                                    <th>Nomor KR</th>
                                    <th>Pabrikan</th>
                                    <th>Material</th>
                                    <th>Total Qty</th>
                                    <th>Dibuat Oleh</th>
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

<!-- Modal Detail Material Masuk -->
<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel">Detail Material Masuk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="detailModalBody">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
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
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'tanggal_masuk_formatted', name: 'tanggal_masuk' },
            { data: 'nomor_kr', name: 'nomor_kr' },
            { data: 'pabrikan', name: 'pabrikan' },
            { data: 'material_info', name: 'material_info', orderable: false, searchable: false },
            { data: 'total_quantity', name: 'total_quantity', orderable: false },
            { data: 'creator_name', name: 'creator.nama' },
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
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Nomor KR:</strong></td>
                                    <td>${data.nomor_kr || '-'}</td>
                                </tr>
                                <tr>
                                    <td><strong>Pabrikan:</strong></td>
                                    <td>${data.pabrikan || '-'}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal Masuk:</strong></td>
                                    <td>${data.tanggal_masuk}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
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
                $('#detailModal').modal('show');
            } else {
                Swal.fire('Error!', 'Gagal memuat detail data.', 'error');
            }
        },
        error: function() {
            Swal.fire('Error!', 'Terjadi kesalahan saat memuat detail data.', 'error');
        }
    });
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