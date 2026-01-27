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
        <div class="rounded-xl border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
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
                                $hours = $keluar->diffInHours($kembali);
                                $hari = max(1, (int) ceil($hours / 24));
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
                    : `<tr><td colspan="5" class="swal-masa-empty">Belum ada pengembalian</td></tr>`;

                Swal.fire({
                    title: 'Detail Pengembalian',
                    width: 860,
                    buttonsStyling: false,
                    customClass: {
                        popup: 'swal-masa-popup',
                        title: 'swal-masa-title',
                        htmlContainer: 'swal-masa-body',
                        actions: 'swal-masa-actions',
                        confirmButton: 'swal-masa-confirm'
                    },
                    html: `
                        <div class="swal-masa-summary">
                            <div class="swal-masa-grid">
                                <div class="swal-masa-row">
                                    <span class="swal-masa-label">Nomor Surat</span>
                                    <span class="swal-masa-value">${d.nomor_surat}</span>
                                </div>
                                <div class="swal-masa-row">
                                    <span class="swal-masa-label">Tanggal Keluar</span>
                                    <span class="swal-masa-value">${d.tanggal_keluar}</span>
                                </div>
                                <div class="swal-masa-row swal-masa-row-full">
                                    <span class="swal-masa-label">Material</span>
                                    <span class="swal-masa-value">${d.material}</span>
                                </div>
                            </div>
                            <div class="swal-masa-stats">
                                <div class="swal-masa-stat swal-masa-stat-out">
                                    <div class="swal-masa-stat-label">Keluar</div>
                                    <div class="swal-masa-stat-value">${d.keluar}</div>
                                </div>
                                <div class="swal-masa-stat swal-masa-stat-in">
                                    <div class="swal-masa-stat-label">Kembali</div>
                                    <div class="swal-masa-stat-value">${d.kembali}</div>
                                </div>
                                <div class="swal-masa-stat swal-masa-stat-left">
                                    <div class="swal-masa-stat-label">Sisa</div>
                                    <div class="swal-masa-stat-value">${d.sisa}</div>
                                </div>
                            </div>
                        </div>
                        <div class="swal-masa-history">
                            <div class="swal-masa-subtitle">Riwayat Pengembalian</div>
                            <table class="swal-masa-table">
                                <thead>
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

@push('styles')
<style>
    .swal-masa-popup {
        border-radius: 18px;
        overflow: hidden;
        padding: 0;
        max-width: 900px;
        width: 92vw;
    }

    .swal-masa-title {
        margin: 0;
        padding: 18px 24px;
        text-align: left;
        font-size: 18px;
        font-weight: 700;
        color: #ffffff;
        background: linear-gradient(90deg, #319795, #4FD1C5);
    }

    .swal-masa-body {
        text-align: left;
        padding: 18px 24px 36px;
        color: #2D3748;
    }

    .swal-masa-summary {
        background: #F8F9FA;
        border: 1px solid #E2E8F0;
        border-radius: 14px;
        padding: 16px 18px;
        margin-bottom: 16px;
    }

    .swal-masa-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 8px 16px;
    }

    .swal-masa-row {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        font-size: 13px;
        color: #4A5568;
    }

    .swal-masa-row-full {
        grid-column: 1 / -1;
    }

    .swal-masa-label {
        font-weight: 600;
        color: #718096;
    }

    .swal-masa-value {
        font-weight: 600;
        color: #1A202C;
        text-align: right;
    }

    .swal-masa-stats {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 10px;
        margin-top: 12px;
    }

    .swal-masa-stat {
        border-radius: 12px;
        padding: 10px 12px;
        text-align: center;
        border: 1px solid #E2E8F0;
        background: #ffffff;
    }

    .swal-masa-stat-label {
        font-size: 11px;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        color: #718096;
        margin-bottom: 4px;
    }

    .swal-masa-stat-value {
        font-size: 18px;
        font-weight: 700;
        color: #1A202C;
    }

    .swal-masa-stat-out .swal-masa-stat-value {
        color: #2B6CB0;
    }

    .swal-masa-stat-in .swal-masa-stat-value {
        color: #2F855A;
    }

    .swal-masa-stat-left .swal-masa-stat-value {
        color: #C53030;
    }

    .swal-masa-subtitle {
        font-size: 13px;
        font-weight: 700;
        color: #2D3748;
        margin: 6px 0 10px;
    }

    .swal-masa-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
        background: #ffffff;
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        overflow: hidden;
    }

    .swal-masa-table th,
    .swal-masa-table td {
        padding: 8px 10px;
        border-bottom: 1px solid #E2E8F0;
        text-align: center;
    }

    .swal-masa-table th {
        background: #F7FAFC;
        color: #4A5568;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .swal-masa-table tbody tr:last-child td {
        border-bottom: none;
    }

    .swal-masa-empty {
        text-align: center;
        color: #A0AEC0;
        font-weight: 600;
        padding: 14px 10px;
    }

    .swal-masa-confirm {
        background: #319795 !important;
        color: #ffffff !important;
        border-radius: 10px !important;
        padding: 10px 18px !important;
        font-weight: 600 !important;
        border: none !important;
        box-shadow: 0 6px 18px rgba(49, 151, 149, 0.25);
    }

    .swal-masa-confirm:hover {
        background: #2C7A7B !important;
    }

    .swal-masa-actions {
        margin-top: 0;
        padding: 0 24px 28px;
    }

    @media (max-width: 640px) {
        .swal-masa-grid {
            grid-template-columns: 1fr;
        }

        .swal-masa-stats {
            grid-template-columns: 1fr;
        }

        .swal-masa-value {
            text-align: left;
        }
    }
</style>
@endpush
