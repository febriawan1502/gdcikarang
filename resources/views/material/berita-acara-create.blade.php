@extends('layouts.app')

@section('title', 'Buat Berita Acara')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Buat Berita Acara</h2>
        <small class="text-muted">Isi data Berita Acara secara lengkap dan benar</small>
    </div>

    <a href="{{ route('berita-acara.index') }}" class="btn btn-outline-secondary">
        <i class="fa fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-primary text-white py-3">
        <h5 class="mb-0 fw-semibold">
            <i class="fa fa-file-text-o me-2"></i> Form Berita Acara
        </h5>
    </div>

    <div class="card-body p-4">

        <form action="{{ route('berita-acara.store') }}" method="POST">
            @csrf

            {{-- ================== SECTION: TANGGAL ================== --}}
            <div class="mb-4">
                <h6 class="fw-bold text-primary mb-3">
                    <i class="fa fa-calendar me-1"></i> Informasi Tanggal
                </h6>

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

            <hr>

            {{-- ================== SECTION: MENGETAHUI ================== --}}
            <div class="mb-4">
                <h6 class="fw-bold text-primary mb-3">
                    <i class="fa fa-user-circle me-1"></i> Pejabat Mengetahui
                </h6>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nama</label>
                        <input type="text" name="mengetahui" class="form-control"
                               placeholder="ARYTA WULANDARI" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Jabatan</label>
                        <input type="text" name="jabatan_mengetahui" class="form-control"
                               placeholder="Manager UP3 Cimahi" required>
                    </div>
                </div>
            </div>

            <hr>

            {{-- ================== SECTION: PEMBUAT ================== --}}
            <div class="mb-4">
                <h6 class="fw-bold text-primary mb-3">
                    <i class="fa fa-user me-1"></i> Pejabat Pembuat
                </h6>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nama</label>
                        <input type="text" name="pembuat" class="form-control"
                               placeholder="DENI PURNAMA" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Jabatan</label>
                        <input type="text" name="jabatan_pembuat" class="form-control"
                               placeholder="Asman Konstruksi UP3 Cimahi" required>
                    </div>
                </div>
            </div>

            <hr>

            {{-- ================== ACTION ================== --}}
            <div class="d-flex justify-content-end gap-2">
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
