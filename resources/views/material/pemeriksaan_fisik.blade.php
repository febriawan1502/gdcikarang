@extends('layouts.app')

@section('title', 'Pemeriksaan Fisik')

@section('content')

<div class="card p-4">

    <h3 class="mb-3">Pilih Bulan Pemeriksaan Fisik</h3>

    {{-- FORM PILIH BULAN --}}
    <form action="{{ route('material.pemeriksaanFisik') }}" method="GET" class="mb-4">
        <label class="form-label">Pilih Bulan</label>
        <input type="month" name="bulan" value="{{ $bulan ?? '' }}"
               class="form-control" style="max-width:300px" required>
        <button class="btn btn-primary mt-3">Lanjut</button>
    </form>

@if($materials)
<hr>

<div class="d-flex align-items-center justify-content-between mb-3">

    <div class="fw-bold fs-5">
        Pemeriksaan Fisik Material
        <span class="text-muted">({{ $bulan }})</span>
    </div>

    <div class="d-flex gap-2">
        <button class="btn btn-info btn-sm"
            data-toggle="modal"
            data-target="#importSapModal">
            <i class="fa fa-upload"></i> Import SAP
        </button>

        <button class="btn btn-warning btn-sm"
            data-toggle="modal"
            data-target="#importMimsModal">
            <i class="fa fa-upload"></i> Import SN MIMS
        </button>

        <a href="{{ route('material.pemeriksaanFisikPdf', ['bulan'=>$bulan]) }}"
           class="btn btn-danger btn-sm"
           target="_blank">
            <i class="fa fa-file-pdf-o"></i> Print PDF
        </a>
    </div>

</div>

    {{-- FORM SIMPAN --}}
    <form action="{{ route('material.pemeriksaanFisik.store') }}" method="POST">
        @csrf
        <input type="hidden" name="bulan" value="{{ $bulan }}">  
        <div class="table-responsive">
            <table class="table table-bordered table-sm" style="font-size:12px">
                <thead class="text-center" style="background:#f2f2f2;font-weight:bold">
                    <tr>
                        <th rowspan="2">NO</th>
                        <th rowspan="2">No Normalisasi / Part</th>
                        <th rowspan="2">Nama Barang</th>
                        <th rowspan="2">Satuan</th>
                        <th rowspan="2">Valuation</th>
                        <th colspan="3">SALDO / JUMLAH</th>
                        <th colspan="3">PERBEDAAN / SELISIH</th>
                        <th colspan="3">JUSTIFIKASI</th>
                    </tr>
                    <tr>
                        <th>SAP</th>
                        <th>Stok Fisik</th>
                        <th>SN MIMS</th>

                        <th>Selisih SAP–Fisik</th>
                        <th>Selisih SAP–SN</th>
                        <th>Selisih Fisik–SN</th>

                        <th>Justifikasi SAP–Fisik</th>
                        <th>Justifikasi SAP–SN</th>
                        <th>Justifikasi Fisik–SN</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($materials as $i => $m)
                    <tr>
                        <td class="text-center">{{ $i+1 }}</td>
                        <td>{{ $m->material_code }}</td>
                        <td>{{ $m->material_description }}</td>
                        <td class="text-center">{{ $m->base_unit_of_measure }}</td>
                        <td class="text-center">{{ $m->valuation_type }}</td>

                        <input type="hidden" name="data[{{ $i }}][material_id]" value="{{ $m->id }}">

                        {{-- SAP --}}
                        <td>
                            <input type="number"
                                   name="data[{{ $i }}][sap]"
                                   id="sap_{{ $i }}"
                                   value="{{ $m->sap }}"
                                   class="form-control form-control-sm"
                                   oninput="hitung({{ $i }})">
                        </td>

                                                {{-- FISIK --}}
                        <td class="text-center">
                            {{ $m->fisik ?? $m->unrestricted_use_stock ?? 0 }}
                            <input type="hidden"
                                   name="data[{{ $i }}][fisik]"
                                   id="fisik_val_{{ $i }}"
                                   value="{{ $m->fisik ?? $m->unrestricted_use_stock ?? 0 }}">
                        </td>


                        {{-- SN --}}
                        <td>
                            <input type="number"
                                   name="data[{{ $i }}][sn]"
                                   id="sn_{{ $i }}"
                                   value="{{ $m->sn_mims }}"
                                   class="form-control form-control-sm"
                                   oninput="hitung({{ $i }})">
                        </td>

                        {{-- SELISIH --}}
                        <td><input class="form-control form-control-sm" id="sf_{{ $i }}" name="data[{{ $i }}][selisih_sf]" value="{{ $m->selisih_sf }}" readonly></td>
                        <td><input class="form-control form-control-sm" id="ss_{{ $i }}" name="data[{{ $i }}][selisih_ss]" value="{{ $m->selisih_ss }}" readonly></td>
                        <td><input class="form-control form-control-sm" id="fs_{{ $i }}" name="data[{{ $i }}][selisih_fs]" value="{{ $m->selisih_fs }}" readonly></td>

                        {{-- JUSTIFIKASI --}}
                        <td><input class="form-control form-control-sm" name="data[{{ $i }}][justifikasi_sf]" value="{{ $m->justifikasi_sf }}"></td>
                        <td><input class="form-control form-control-sm" name="data[{{ $i }}][justifikasi_ss]" value="{{ $m->justifikasi_ss }}"></td>
                        <td><input class="form-control form-control-sm" name="data[{{ $i }}][justifikasi_fs]" value="{{ $m->justifikasi_fs }}"></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
</div>
         <div class="d-flex justify-content-end mt-3">
            <button class="btn btn-success btn-sm">
                <i class="fa fa-save"></i> Simpan
            </button>
        </div>
    </form>
    @endif
</div>

<div class="modal fade" id="importSapModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <form action="{{ route('material.importSap') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="bulan" value="{{ $bulan }}">

        <div class="modal-header">
          <h5 class="modal-title">Import SAP</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <div class="modal-body">
          <input type="file" name="file" class="form-control" required>
          <small class="text-muted">Format: no_part | sap</small>
        </div>

        <div class="modal-footer">
          <button class="btn btn-success btn-sm">Import</button>
        </div>
      </form>

    </div>
  </div>
</div>

<div class="modal fade" id="importMimsModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <form action="{{ route('material.importMims') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="bulan" value="{{ $bulan }}">

        <div class="modal-header">
          <h5 class="modal-title">Import SN MIMS</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <div class="modal-body">
          <input type="file" name="file" class="form-control" required>
          <small class="text-muted">Format: no_part | sn_mims</small>
        </div>

        <div class="modal-footer">
          <button class="btn btn-success btn-sm">Import</button>
        </div>
      </form>

    </div>
  </div>
</div>

@endsection
@push('scripts')
<script>
function hitung(i) {
    const sapVal   = document.getElementById(`sap_${i}`).value;
    const fisikVal = document.getElementById(`fisik_val_${i}`).value;
    const snVal    = document.getElementById(`sn_${i}`).value;

    const sap   = sapVal !== '' ? parseFloat(sapVal) : null;
    const fisik = fisikVal !== '' ? parseFloat(fisikVal) : null;
    const sn    = snVal !== '' ? parseFloat(snVal) : null;

    document.getElementById(`sf_${i}`).value =
        sap !== null && fisik !== null ? sap - fisik : '';

    document.getElementById(`ss_${i}`).value =
        sap !== null && sn !== null ? sap - sn : '';

    document.getElementById(`fs_${i}`).value =
        fisik !== null && sn !== null ? fisik - sn : '';
}

document.addEventListener('DOMContentLoaded', function () {
    @if($materials)
        const totalRows = {{ $materials->count() }};
        for (let i = 0; i < totalRows; i++) {
            hitung(i);
        }
    @endif
});
</script>
