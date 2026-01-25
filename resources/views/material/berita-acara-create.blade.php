@extends('layouts.app')

@push('styles')
<style>
    .ba-header {
        background: linear-gradient(135deg, #0ea5a6 0%, #2dd4bf 100%);
        color: #ffffff;
        border-radius: 16px;
        padding: 18px 22px;
        box-shadow: 0 10px 25px rgba(13, 148, 136, 0.2);
    }
    .ba-card {
        border-radius: 16px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.08);
        overflow: hidden;
    }
    .ba-section {
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        padding: 16px;
        background: #ffffff;
    }
    .ba-section + .ba-section {
        margin-top: 16px;
    }
    .ba-section-title {
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 0.5px;
        color: #0f172a;
        text-transform: uppercase;
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 14px;
    }
    .ba-help {
        background: #f8fafc;
        border: 1px dashed #cbd5f5;
        border-radius: 14px;
        padding: 16px;
    }
    .ba-help h6 {
        font-weight: 700;
        margin-bottom: 10px;
    }
    .ba-help ul {
        margin: 0;
        padding-left: 18px;
        color: #475569;
    }
</style>
@endpush

@section('content')
@php
    $unitName = auth()->user()->unit->name ?? 'UP3';
@endphp

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <div class="ba-header">
            <h2 class="fw-bold mb-1">Buat Berita Acara</h2>
            <small>Lengkapi data dengan format yang konsisten untuk dokumen resmi.</small>
        </div>
    </div>

    <a href="{{ route('berita-acara.index') }}" class="btn btn-outline-secondary">
        <i class="fa fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="row g-4">
    <div class="col-12">
        <div class="card ba-card">
            <div class="card-header bg-white py-3 border-0">
                <h5 class="mb-0 fw-semibold text-dark">
                    <i class="fa fa-file-text-o me-2 text-teal-600"></i> Form Berita Acara
                </h5>
            </div>

            <div class="card-body p-4">
                <form action="{{ route('berita-acara.store') }}" method="POST">
                    @csrf

                    {{-- ================== SECTION: TANGGAL ================== --}}
                    <div class="ba-section">
                        <div class="ba-section-title">
                            <i class="fa fa-calendar text-teal-600"></i> Informasi Tanggal
                        </div>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Hari</label>
                                <input type="text" name="hari" class="form-control"
                                       placeholder="Selasa" required>
                            </div>

                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Tanggal (Teks)</label>
                                <input type="text" name="tanggal_teks" class="form-control"
                                       placeholder="Empat Bulan November Tahun Dua Ribu Dua Puluh Lima" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tanggal</label>
                                <input type="date" name="tanggal" class="form-control" required>
                                <small class="text-muted">
                                    Digunakan untuk format tanggal surat & lokasi
                                </small>
                            </div>
                        </div>
                    </div>

                    {{-- ================== SECTION: MENGETAHUI ================== --}}
                    <div class="ba-section">
                        <div class="ba-section-title">
                            <i class="fa fa-user-circle text-teal-600"></i> Pejabat Mengetahui
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nama</label>
                                <input type="text" name="mengetahui" class="form-control"
                                       placeholder="Nama Manager UP3" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Jabatan</label>
                                <input type="text" name="jabatan_mengetahui" class="form-control"
                                       placeholder="Manager {{ $unitName }}" required>
                            </div>
                        </div>
                    </div>

                    {{-- ================== SECTION: PEMBUAT ================== --}}
                    <div class="ba-section">
                        <div class="ba-section-title">
                            <i class="fa fa-user text-teal-600"></i> Pejabat Pembuat
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nama</label>
                                <input type="text" name="pembuat" class="form-control"
                                       placeholder="Nama Asman Kons" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Jabatan</label>
                                <input type="text" name="jabatan_pembuat" class="form-control"
                                       placeholder="Asman Konstruksi {{ $unitName }}" required>
                            </div>
                        </div>
                    </div>

                    {{-- ================== ACTION ================== --}}
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('berita-acara.index') }}" class="btn btn-light px-4">
                            Batal
                        </a>

                        <button class="btn btn-primary px-4 fw-semibold">
                            <i class="fa fa-save me-1"></i> Simpan Berita Acara
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="ba-help">
            <h6>Catatan Pengisian</h6>
            <ul>
                <li>Tanggal otomatis mengisi hari dan teks.</li>
                <li>Pastikan nama pejabat sesuai SK terbaru.</li>
                <li>Gunakan format jabatan resmi unit.</li>
            </ul>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
const tanggalInput = document.querySelector('input[name="tanggal"]');
const hariInput = document.querySelector('input[name="hari"]');
const tanggalTeksInput = document.querySelector('input[name="tanggal_teks"]');

const namaHari = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
const namaBulan = [
    'Januari','Februari','Maret','April','Mei','Juni',
    'Juli','Agustus','September','Oktober','November','Desember'
];

function angkaKeKata(n) {
    const angkaKata = ['Nol','Satu','Dua','Tiga','Empat','Lima','Enam','Tujuh','Delapan','Sembilan','Sepuluh',
        'Sebelas','Dua Belas','Tiga Belas','Empat Belas','Lima Belas','Enam Belas','Tujuh Belas','Delapan Belas','Sembilan Belas',
        'Dua Puluh','Dua Puluh Satu','Dua Puluh Dua','Dua Puluh Tiga','Dua Puluh Empat','Dua Puluh Lima','Dua Puluh Enam','Dua Puluh Tujuh','Dua Puluh Delapan','Dua Puluh Sembilan','Tiga Puluh','Tiga Puluh Satu'];
    return angkaKata[n] || n;
}

function tahunKeKata(tahun) {
    let t = String(tahun);
    const angkaKata = ['Nol','Satu','Dua','Tiga','Empat','Lima','Enam','Tujuh','Delapan','Sembilan'];
    return 'Dua Ribu ' + angkaKata[parseInt(t[2])] + ' ' + angkaKata[parseInt(t[3])];
}

function updateTanggalTeks() {
    if(tanggalInput.value){
        const date = new Date(tanggalInput.value);
        const hari = namaHari[date.getDay()];
        const tanggal = angkaKeKata(date.getDate());
        const bulan = namaBulan[date.getMonth()];
        const tahun = tahunKeKata(date.getFullYear());

        // isi kolom hari
        hariInput.value = hari;

        // isi kolom tanggal_teks
        tanggalTeksInput.value = `${tanggal} Bulan ${bulan} Tahun ${tahun}`;
    }
}

// Jalankan saat tanggal diubah dan saat halaman load
tanggalInput.addEventListener('change', updateTanggalTeks);
window.addEventListener('DOMContentLoaded', updateTanggalTeks);
</script>
@endpush
