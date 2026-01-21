@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">⏱ Masa {{ ucfirst($jenis) }}</h2>
            <p class="text-gray-500 text-sm mt-1">Kelola masa pengeluaran dan pengembalian barang.</p>
        </div>
        
        <!-- Filter -->
        <div class="flex items-center gap-3">
            <label for="filterJenis" class="text-sm font-medium text-gray-600">Filter Jenis:</label>
            <select id="filterJenis" class="form-input py-2 min-w-[200px]">
                <option value="">Semua Jenis</option>
                <option value="Garansi">Garansi</option>
                <option value="Peminjaman">Peminjaman</option>
                <option value="Perbaikan">Perbaikan</option>
            </select>
        </div>
    </div>

    <!-- Content Card -->
    <div class="card p-6 border border-gray-100 shadow-xl shadow-gray-200/50">
        <div class="overflow-x-auto rounded-xl border border-gray-100">
            <table id="masaTable" class="table-purity w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Nomor Surat</th>
                        <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Jenis</th>
                        <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal Keluar</th>
                        <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Diberikan Kepada</th>
                        <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Material</th>
                        <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Keluar</th>
                        <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Kembali</th>
                        <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Sisa</th>
                        <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Durasi</th>
                        <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Progres</th>
                        <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @foreach ($suratJalans as $i => $surat)
                        @foreach ($surat->details as $detail)
                            @php
                                $keluar = \Carbon\Carbon::parse($surat->tanggal);
                                $kembali = $detail->tanggal_kembali ? \Carbon\Carbon::parse($detail->tanggal_kembali) : now();
                                $hari = $keluar->diffInDays($kembali);
                                $masa = "{$hari} hari";
                                $jumlahKeluar = $detail->quantity;
                                $jumlahKembali = $detail->jumlah_kembali ?? 0;
                                $sisa = $jumlahKeluar - $jumlahKembali;
                                $persen = round(($jumlahKembali / max($jumlahKeluar, 1)) * 100);
                                $namaMaterial = $detail->material->material_description ?? $detail->nama_barang_manual;
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 text-gray-600">{{ $i + 1 }}</td>
                                <td class="px-4 py-3 text-gray-800 font-medium">{{ $surat->nomor_surat }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ ucfirst($surat->jenis_surat_jalan) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-gray-600">{{ $surat->tanggal->format('d/m/Y') }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $surat->kepada }}</td>
                                <td class="px-4 py-3 text-gray-800">{{ $namaMaterial }}</td>
                                <td class="px-4 py-3 font-semibold text-blue-600">{{ $jumlahKeluar }}</td>
                                <td class="px-4 py-3 font-semibold text-green-600">{{ $jumlahKembali }}</td>
                                <td class="px-4 py-3 font-semibold text-red-600">{{ $sisa }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $masa }}</td>
                                <td class="px-4 py-3">
                                    <div class="w-full bg-gray-200 rounded-full h-2 mb-1">
                                        <div class="h-2 rounded-full {{ $persen == 100 ? 'bg-green-500' : ($persen >= 50 ? 'bg-yellow-500' : 'bg-gray-400') }}" 
                                             style="width: {{ $persen }}%"></div>
                                    </div>
                                    <span class="text-xs text-gray-500 font-medium">{{ $persen }}%</span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex justify-end items-center gap-2">
                                        <!-- Tombol Detail -->
                                        <button class="p-2 rounded-lg text-teal-600 hover:bg-teal-50 transition-colors"
                                                title="Lihat Detail"
                                                onclick="viewHistory({{ $detail->id }})">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        <!-- Tombol Pengembalian -->
                                        @if ($persen < 100)
                                            <a href="{{ route('surat-jalan.kembalikan.form', ['suratId' => $surat->id, 'detailId' => $detail->id]) }}"
                                               class="px-3 py-1.5 rounded-lg bg-blue-500 text-white hover:bg-blue-600 transition-colors text-sm flex items-center gap-1"
                                               title="Pengembalian">
                                                <i class="fas fa-undo"></i>
                                                <span>Kembali</span>
                                            </a>
                                        @else
                                            <!-- Tombol Hapus -->
                                            <form id="delete-detail-{{ $detail->id }}" 
                                                action="{{ route('surat.masa.hapus-detail', ['surat' => $surat->id, 'detail' => $detail->id]) }}" 
                                                method="POST" 
                                                style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>

                                            <button class="p-2 rounded-lg text-red-500 hover:bg-red-50 transition-colors" 
                                                    title="Hapus Detail"
                                                    onclick="confirmDeleteDetail({{ $detail->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        const table = $('#masaTable').DataTable({
            language: {
                search: "🔍 Cari:",
                lengthMenu: "Tampilkan _MENU_ data per halaman",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                paginate: { previous: "Sebelumnya", next: "Berikutnya" },
                zeroRecords: "Tidak ada data ditemukan"
            },
            pageLength: 10,
            ordering: true,
            responsive: true
        });

        $('#filterJenis').on('change', function() {
            const value = $(this).val();
            table.column(2).search(value ? '^' + value + '$' : '', true, false).draw();
        });
    });

    function viewHistory(detailId) {
        Swal.fire({
            title: '⏳ Memuat data...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        fetch(`/surat-jalan/${detailId}/history`)
            .then(res => res.json())
            .then(res => {
                if (!res.success) throw new Error('Gagal ambil data');

                const d = res.detail;
                const historyRows = res.history.length
                    ? res.history.map((h, i) => `
                        <tr>
                            <td>${i + 1}</td>
                            <td>${h.nomor_surat_masuk}</td>
                            <td>${h.tanggal_masuk}</td>
                            <td>${h.jumlah_kembali}</td>
                            <td>${h.keterangan}</td>
                        </tr>
                    `).join('')
                    : `<tr><td colspan="5" class="text-center text-muted">Belum ada pengembalian</td></tr>`;

                Swal.fire({
                    title: `<strong>📦 Detail Pengembalian</strong>`,
                    width: '800px',
                    html: `
                        <div class="text-start mb-3">
                            <table class="table table-sm table-bordered">
                                <tr><th>Nomor Surat</th><td>${d.nomor_surat}</td></tr>
                                <tr><th>Tanggal Keluar</th><td>${d.tanggal_keluar}</td></tr>
                                <tr><th>Material</th><td>${d.material}</td></tr>
                                <tr><th>Keluar</th><td>${d.keluar}</td></tr>
                                <tr><th>Kembali</th><td>${d.kembali}</td></tr>
                                <tr><th>Sisa</th><td>${d.sisa}</td></tr>
                            </table>
                        </div>
                        <div>
                            <h6 class="fw-semibold mb-2">Riwayat Pengembalian</h6>
                            <table class="table table-sm table-bordered text-center">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>No Surat Masuk</th>
                                        <th>Tanggal Masuk</th>
                                        <th>Jumlah Kembali</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${historyRows}
                                </tbody>
                            </table>
                        </div>
                    `,
                    confirmButtonText: 'Tutup'
                });
            })
            .catch(err => {
                Swal.fire('Gagal', 'Tidak dapat memuat data.', 'error');
                console.error(err);
            });
    }

    function confirmDeleteDetail(id) {
        Swal.fire({
            title: 'Hapus Data?',
            text: 'Apakah kamu yakin ingin menghapus detail ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal',
            customClass: { popup: 'rounded-4' }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-detail-' + id).submit();
            }
        });
    }
</script>
@endpush
@endsection