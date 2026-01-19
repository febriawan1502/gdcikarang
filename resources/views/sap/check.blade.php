@extends('layouts.app')

@section('title', 'Cek Kesesuaian SAP')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>Cek Kesesuaian SAP vs Fisik</h4>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <p>Silahkan upload data persediaan fisik SAP terbaru kemudian upload.</p>
                
                <form action="{{ route('sap-check.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="file_sap">File Excel SAP</label>
                        <input type="file" name="file_sap" id="file_sap" class="form-control" required accept=".xlsx, .xls, .csv">
                        @error('file_sap')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-upload"></i> Upload
                    </button>
                </form>
            </div>
        </div>

        @if(isset($results))
        <div class="card mt-4">
            <div class="card-header">
                <h4>Hasil Perbandingan</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="resultTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Normalisasi</th>
                                <th>Nama Material</th>
                                <th>Stock Fisik (DB)</th>
                                <th>Stock SAP (Excel)</th>
                                <th>Selisih</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($results as $index => $row)
                            <tr class="{{ $row['selisih'] != 0 ? 'danger' : '' }}">
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $row['normalisasi'] }}</td>
                                <td>{{ $row['nama_material'] }}</td>
                                <td class="text-right">{{ number_format($row['stock_fisik'], 0, ',', '.') }}</td>
                                <td class="text-right">{{ number_format($row['stock_sap'], 0, ',', '.') }}</td>
                                <td class="text-right font-weight-bold {{ $row['selisih'] < 0 ? 'text-danger' : ($row['selisih'] > 0 ? 'text-success' : '') }}">
                                    {{ number_format($row['selisih'], 0, ',', '.') }}
                                </td>
                                <td>
                                    @if($row['selisih'] == 0)
                                        <span class="badge badge-success">Sesuai</span>
                                    @elseif($row['selisih'] > 0)
                                        <span class="badge badge-warning">Fisik Lebih</span>
                                    @else
                                        <span class="badge badge-danger">SAP Lebih</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>
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
                ]
            });
        }
    });
</script>
@endpush
