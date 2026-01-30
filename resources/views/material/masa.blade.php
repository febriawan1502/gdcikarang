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
                @if ($jenis === 'Garansi' || !$jenis)
                    <button onclick="openClaimModal()"
                        class="bg-teal-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-teal-700 transition shadow-lg shadow-teal-500/30 flex items-center gap-2">
                        <i class="fas fa-plus-circle"></i> Klaim Garansi
                    </button>
                @endif
                <label for="filterJenis" class="text-sm font-medium text-gray-600">Filter Jenis:</label>
                <select id="filterJenis" class="form-input py-2 min-w-[200px]">
                    <option value="">Semua Jenis</option>
                    <option value="Garansi">Garansi</option>
                    <option value="Peminjaman">Peminjaman</option>
                    <option value="Perbaikan">Perbaikan</option>
                </select>
            </div>
        </div>

        <!-- Active Claims Section -->
        @if (isset($warrantyClaims) && $warrantyClaims->isNotEmpty())
            <div class="card p-6 border border-yellow-100 shadow-lg shadow-yellow-100/50 bg-yellow-50/30">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-exclamation-circle text-yellow-500"></i> Klaim Garansi Aktif (Menunggu Penjemputan)
                </h3>
                <div class="overflow-x-auto rounded-xl border border-yellow-200">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-yellow-100 text-yellow-800 uppercase font-bold text-xs">
                            <tr>
                                <th class="px-4 py-3">Tiket</th>
                                <th class="px-4 py-3">Material</th>
                                <th class="px-4 py-3">Serial Number</th>
                                <th class="px-4 py-3">Diajukan</th>
                                <th class="px-4 py-3">Durasi</th>
                                <th class="px-4 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-yellow-100 bg-white">
                            @foreach ($warrantyClaims as $claim)
                                <tr class="hover:bg-yellow-50 transition">
                                    <td class="px-4 py-3 font-medium">{{ $claim->ticket_number }}</td>
                                    <td class="px-4 py-3">{{ $claim->material->material_description }}</td>
                                    <td class="px-4 py-3 font-mono text-gray-600">{{ $claim->serial_number }}</td>
                                    <td class="px-4 py-3">{{ $claim->submission_date->format('d/m/Y H:i') }}</td>
                                    <td class="px-4 py-3 font-bold text-yellow-700">
                                        @php
                                            $hours = $claim->submission_date->diffInHours(now());
                                            $hari = max(1, (int) ceil($hours / 24));
                                        @endphp
                                        {{ $hari }} hari
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="{{ route('surat-jalan.create', ['claim_id' => $claim->id]) }}"
                                            class="inline-flex items-center px-3 py-1.5 bg-teal-600 text-white rounded hover:bg-teal-700 transition text-xs font-bold shadow-md">
                                            <i class="fas fa-truck-loading mr-1"></i> Proses SJ
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Content Card -->
        <div class="card p-6 border border-gray-100 shadow-xl shadow-gray-200/50">
            <div class="rounded-xl border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table id="masaTable" class="table-purity w-full">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">No</th>
                                <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Nomor Surat
                                </th>
                                <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Jenis</th>
                                <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal
                                    Keluar</th>
                                <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Diberikan
                                    Kepada</th>
                                <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Material</th>
                                <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Keluar</th>
                                <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Kembali</th>
                                <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Sisa</th>
                                <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Durasi</th>
                                <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Progres</th>
                                <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach ($suratJalans as $i => $surat)
                                @foreach ($surat->details as $detail)
                                    @php
                                        $keluar = \Carbon\Carbon::parse($surat->tanggal);
                                        $kembali = $detail->tanggal_kembali
                                            ? \Carbon\Carbon::parse($detail->tanggal_kembali)
                                            : now();
                                        $hours = $keluar->diffInHours($kembali);
                                        $hari = max(1, (int) ceil($hours / 24));
                                        $masa = "{$hari} hari";
                                        $jumlahKeluar = $detail->quantity;
                                        $jumlahKembali = $detail->jumlah_kembali ?? 0;
                                        $sisa = $jumlahKeluar - $jumlahKembali;
                                        $persen = round(($jumlahKembali / max($jumlahKeluar, 1)) * 100);
                                        $namaMaterial =
                                            $detail->material->material_description ?? $detail->nama_barang_manual;
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-3 text-gray-600">{{ $i + 1 }}</td>
                                        <td class="px-4 py-3 text-gray-800 font-medium">{{ $surat->nomor_surat }}</td>
                                        <td class="px-4 py-3">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
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
                                                <button
                                                    class="p-2 rounded-lg text-teal-600 hover:bg-teal-50 transition-colors"
                                                    title="Lihat Detail" onclick="viewHistory({{ $detail->id }})">
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
                                                        method="POST" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>

                                                    <button
                                                        class="p-2 rounded-lg text-red-500 hover:bg-red-50 transition-colors"
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
                        paginate: {
                            previous: "Sebelumnya",
                            next: "Berikutnya"
                        },
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
                        const historyRows = res.history.length ?
                            res.history.map((h, i) => `
                        <tr>
                            <td>${i + 1}</td>
                            <td>${h.nomor_surat_masuk}</td>
                            <td>${h.tanggal_masuk}</td>
                            <td>${h.jumlah_kembali}</td>
                            <td>${h.keterangan}</td>
                        </tr>
                    `).join('') :
                            `<tr><td colspan="5" class="swal-masa-empty">Belum ada pengembalian</td></tr>`;

                        // ✅ Warranty Claims Section
                        let claimHtml = '';
                        if (res.claims && res.claims.length > 0) {
                            const claimRows = res.claims.map(c => `
                                <tr>
                                    <td>${c.ticket}</td>
                                    <td>${c.serial}</td>
                                    <td>${c.submitted}</td>
                                    <td>${c.pickup}</td>
                                    <td>${c.returned}</td>
                                    <td>
                                        <span class="px-2 py-0.5 rounded-full text-xs font-bold ${
                                            c.status === 'COMPLETED' ? 'bg-green-100 text-green-800' : 
                                            (c.status === 'PROCESSED' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800')
                                        }">
                                            ${c.status}
                                        </span>
                                    </td>
                                </tr>
                            `).join('');

                            claimHtml = `
                            <div class="swal-masa-history mt-4">
                                <div class="swal-masa-subtitle text-teal-600"><i class="fas fa-history mr-1"></i> Riwayat Klaim Garansi</div>
                                <table class="swal-masa-table">
                                    <thead>
                                        <tr>
                                            <th>Tiket</th>
                                            <th>SN</th>
                                            <th>Diajukan</th>
                                            <th>Diambil</th>
                                            <th>Dikembalikan</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>${claimRows}</tbody>
                                </table>
                            </div>`;
                        }

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

                        ${claimHtml}

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
                    customClass: {
                        popup: 'rounded-4'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('delete-detail-' + id).submit();
                    }
                });
            }

            function openClaimModal() {
                $('#claimModal').removeClass('hidden');
                setTimeout(() => {
                    document.getElementById('claimSn').focus();
                }, 100);
            }

            function closeClaimModal() {
                $('#claimModal').addClass('hidden');
                // Reset form
                document.getElementById('claimSn').value = '';
                document.getElementById('items-table-body').classList.add('hidden');
                document.getElementById('btnSubmitClaim').disabled = true;
                document.getElementById('detailMaterialId').value = '';
                document.getElementById('detailMaterial').textContent = '-';
                document.getElementById('detailMerk').textContent = '-';
                document.getElementById('detailTahun').textContent = '-';
                document.getElementById('detailIdPel').textContent = '-';
                document.getElementById('hiddenIdPel').value = '';
                document.getElementById('detailKerusakan').textContent = '-';
            }

            // Handle Enter key on SN input
            document.getElementById('claimSn').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    searchBySn();
                }
            });

            function searchBySn() {
                const sn = document.getElementById('claimSn').value.trim();
                if (!sn) {
                    Swal.fire('Error', 'Silakan isi Serial Number terlebih dahulu', 'error');
                    return;
                }

                const btn = document.querySelector('button[onclick="searchBySn()"]');
                const originalIcon = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

                fetch(`/api/mrwi/search-by-sn?sn=${encodeURIComponent(sn)}`)
                    .then(response => response.json())
                    .then(res => {
                        if (res.success) {
                            const data = res.data;

                            // Show details
                            document.getElementById('items-table-body').classList.remove('hidden');

                            // Populate fields
                            document.getElementById('detailMaterial').textContent =
                                `${data.material_code} - ${data.material_description}`;
                            document.getElementById('detailMerk').textContent = data.merk;
                            document.getElementById('detailTahun').textContent = data.tahun;
                            document.getElementById('detailIdPel').textContent = data.id_pelanggan;
                            document.getElementById('detailKerusakan').textContent = data.jenis_kerusakan;

                            // Set hidden inputs
                            document.getElementById('detailMaterialId').value = data.material_id;
                            document.getElementById('hiddenIdPel').value = data.id_pelanggan;

                            // Enable submit
                            document.getElementById('btnSubmitClaim').disabled = false;

                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true
                            });
                            Toast.fire({
                                icon: 'success',
                                title: 'Data Ditemukan'
                            });

                        } else {
                            document.getElementById('items-table-body').classList.add('hidden');
                            document.getElementById('btnSubmitClaim').disabled = true;
                            Swal.fire('Tidak Ditemukan', res.error || 'Data material tidak ditemukan', 'warning');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        Swal.fire('Error', 'Gagal mencari data', 'error');
                    })
                    .finally(() => {
                        btn.disabled = false;
                        btn.innerHTML = originalIcon;
                    });
            }
        </script>

        <!-- Modal -->
        <div id="claimModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog"
            aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity backdrop-blur-sm" aria-hidden="true"
                    onclick="closeClaimModal()"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div
                    class="relative z-10 inline-block align-bottom bg-white rounded-lg text-left shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form action="{{ route('surat-jalan.warranty-claim.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">
                                <i class="fas fa-shield-alt text-teal-600 mr-2"></i> Pengajuan Klaim Garansi
                            </h3>
                            <div class="space-y-4">
                                <div class="space-y-4">
                                    <!-- SN Search -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Serial Number</label>
                                        <div class="flex gap-2">
                                            <input type="text" id="claimSn" name="serial_number"
                                                class="flex-1 form-input rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200 focus:ring-opacity-50"
                                                placeholder="Masukkan SN lalu Enter..." required>
                                            <button type="button" onclick="searchBySn()"
                                                class="bg-teal-600 text-white px-3 py-2 rounded-md hover:bg-teal-700 transition">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                        <p class="text-xs text-gray-400 mt-1">*Tekan Enter atau Klik tombol cari untuk
                                            mendapatkan data</p>
                                    </div>
                                    <div id="items-table-body" class="mb-4 bg-gray-50 p-4 rounded-lg hidden">
                                        <h4 class="text-sm font-bold text-gray-800 mb-3 border-b pb-2">Detail Material</h4>
                                        <div class="grid grid-cols-2 gap-y-3 gap-x-4 text-sm">
                                            <div>
                                                <span class="block text-xs text-gray-500">Material</span>
                                                <span class="font-medium text-gray-900" id="detailMaterial">-</span>
                                                <input type="hidden" name="material_id" id="detailMaterialId">
                                            </div>
                                            <div>
                                                <span class="block text-xs text-gray-500">Merk / Pabrikan</span>
                                                <span class="font-medium text-gray-900" id="detailMerk">-</span>
                                            </div>
                                            <div>
                                                <span class="block text-xs text-gray-500">Tahun Buat</span>
                                                <span class="font-medium text-gray-900" id="detailTahun">-</span>
                                            </div>
                                            <div>
                                                <span class="block text-xs text-gray-500">ID Pelanggan</span>
                                                <span class="font-medium text-gray-900" id="detailIdPel">-</span>
                                                <input type="hidden" name="id_pelanggan" id="hiddenIdPel">
                                            </div>
                                            <div class="col-span-2">
                                                <span class="block text-xs text-gray-500">Jenis Kerusakan</span>
                                                <span
                                                    class="inline-block px-2 py-0.5 rounded text-xs font-semibold bg-red-100 text-red-800"
                                                    id="detailKerusakan">-</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-6">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Bukti / Surat Klaim
                                            (PDF/Img)</label>
                                        <input type="file" name="evidence"
                                            class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100"
                                            accept=".pdf,.jpg,.jpeg,.png" required>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse rounded-b-lg">
                                <button type="submit" id="btnSubmitClaim" disabled
                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-teal-600 text-base font-medium text-white hover:bg-teal-700 focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed sm:ml-3 sm:w-auto sm:text-sm">
                                    Simpan & Mulai Timer
                                </button>
                                <button type="button" onclick="closeClaimModal()"
                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                    Batal
                                </button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
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

        /* Helper for Select2 in Modal */
        .select2-container {
            z-index: 99999 !important;
        }

        .select2-search__field {
            color: #444 !important;
        }

        .select2-results__option {
            color: #333 !important;
            background-color: #fff !important;
        }

        .select2-results__option--highlighted {
            background-color: #319795 !important;
            color: #fff !important;
        }
    </style>
@endpush
