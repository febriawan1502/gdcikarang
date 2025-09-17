@extends('layouts.app')

@section('title', 'Stock Opname - ASI System')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="fa fa-clipboard-list"></i>
                    Stock Opname Material
                </h3>
            </div>
            
            <div class="panel-body" style="padding-left: 40px; padding-right: 40px;">
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

                <div class="alert alert-info" role="alert">
                    <i class="fa fa-info-circle"></i>
                    <strong>Informasi:</strong> Stock opname digunakan untuk mengupdate quantity material berdasarkan hasil perhitungan fisik. Hanya admin yang dapat melakukan stock opname.
                </div>
                
                <!-- Action Button -->
                <div class="row" style="margin-bottom: 20px;">
                    <div class="col-12">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#stockOpnameModal">
                            <i class="fa fa-plus"></i>
                            Tambah Stock Opname
                        </button>
                    </div>
                </div>
                
                <!-- DataTable -->
                <div class="table-responsive">
                    <table id="stockOpnameTable" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Nomor KR</th>
                                <th>Material Description</th>
                                <th>Stock System</th>
                                <th>Stock Fisik</th>
                                <th>Selisih</th>
                                <th>Keterangan</th>
                                <th>Created By</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data akan dimuat via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stock Opname Modal -->
<div class="modal fade" id="stockOpnameModal" tabindex="-1" role="dialog" aria-labelledby="stockOpnameModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="stockOpnameModalLabel">
                    <i class="fa fa-clipboard-check"></i>
                    Input Stock Opname
                </h4>
            </div>
            
            <form id="stockOpnameForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="material_search" class="control-label">
                                    Cari Material <span class="text-danger">*</span>
                                </label>
                                <select class="form-control" id="material_search" name="material_id" required>
                                    <option value="">Pilih Material...</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="stock_system" class="control-label">
                                    Stock System
                                </label>
                                <input type="text" class="form-control" id="stock_system" readonly>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="stock_fisik" class="control-label">
                                    Stock Fisik <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control" id="stock_fisik" name="stock_fisik" min="0" step="0.01" required>
                            </div>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="keterangan" class="control-label">
                                    Keterangan
                                </label>
                                <textarea class="form-control" id="keterangan" name="keterangan" rows="3" placeholder="Catatan tambahan (opsional)"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        <i class="fa fa-times"></i>
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save"></i>
                        Simpan Stock Opname
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#stockOpnameTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("material.stock-opname.data") }}',
            type: 'GET'
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'created_at', name: 'created_at' },
            { data: 'material.nomor_kr', name: 'material.nomor_kr' },
            { data: 'material_description', name: 'material_description' },
            { data: 'stock_system', name: 'stock_system' },
            { data: 'stock_fisik', name: 'stock_fisik' },
            { data: 'selisih', name: 'selisih' },
            { data: 'keterangan', name: 'keterangan' },
            { data: 'created_by.name', name: 'created_by.name' }
        ],
        order: [[1, 'desc']],
        pageLength: 25,
        responsive: true,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json'
        }
    });
    
    // Initialize Select2 for material search
    $('#material_search').select2({
        placeholder: 'Ketik minimal 3 karakter untuk mencari material...',
        minimumInputLength: 3,
        ajax: {
            url: '{{ route("material.autocomplete") }}',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        },
        templateResult: function(material) {
            if (material.loading) {
                return material.text;
            }
            return $('<div>' + material.text + '</div>');
        },
        templateSelection: function(material) {
            return material.text || material.nomor_kr;
        }
    });
    
    // Handle material selection
    $('#material_search').on('select2:select', function (e) {
        var data = e.params.data;
        $('#stock_system').val(data.stock_system);
    });
    
    // Handle form submission
    $('#stockOpnameForm').on('submit', function(e) {
        e.preventDefault();
        
        var formData = {
            material_id: $('#material_search').val(),
            stock_fisik: $('#stock_fisik').val(),
            keterangan: $('#keterangan').val(),
            _token: $('input[name="_token"]').val()
        };
        
        $.ajax({
            url: '{{ route("material.stock-opname.store") }}',
            method: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    $('#stockOpnameModal').modal('hide');
                    table.ajax.reload();
                    
                    // Show success message
                    $('<div class="alert alert-success alert-dismissible fade in" role="alert">' +
                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                        '<span aria-hidden="true">&times;</span></button>' +
                        '<i class="fa fa-check-circle"></i> ' + response.message +
                        '</div>').prependTo('.panel-body').delay(5000).fadeOut();
                    
                    // Reset form
                    resetForm();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr) {
                var errors = xhr.responseJSON.errors;
                var errorMessage = 'Terjadi kesalahan:\n';
                
                if (errors) {
                    $.each(errors, function(key, value) {
                        errorMessage += '- ' + value[0] + '\n';
                    });
                } else {
                    errorMessage += xhr.responseJSON.message || 'Terjadi kesalahan tidak dikenal';
                }
                
                alert(errorMessage);
            }
        });
    });
    
    // Reset form when modal is hidden
    $('#stockOpnameModal').on('hidden.bs.modal', function () {
        resetForm();
    });
    
    function resetForm() {
        $('#stockOpnameForm')[0].reset();
        $('#material_search').val(null).trigger('change');
        $('#stock_system').val('');
    }
});
</script>
@endpush