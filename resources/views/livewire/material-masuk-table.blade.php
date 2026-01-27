<div>
    <!-- Filter Section -->
    <div class="mb-6 bg-gray-50 rounded-xl p-4 border border-gray-100">
        <div class="flex flex-col lg:flex-row items-end gap-4">
            <!-- Status Filter -->
            <div class="w-full lg:w-56">
                <label for="filterStatus"
                    class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 block">Status</label>
                <div class="relative">
                    <select id="filterStatus" wire:model.live="status"
                        class="form-input w-full appearance-none bg-white cursor-pointer hover:border-teal-400 transition-colors pr-10">
                        <option value="">Semua Status</option>
                        <option value="BELUM_SAP">Belum SAP</option>
                        <option value="SELESAI_SAP">Selesai SAP</option>
                    </select>
                    <div class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                        <i class="fas fa-chevron-down text-xs"></i>
                    </div>
                </div>
            </div>

            <!-- Date Range -->
            <div class="w-full sm:w-48">
                <label for="filterStartDate"
                    class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 block">Tanggal Awal</label>
                <input type="date" id="filterStartDate" wire:model.live="startDate" class="form-input w-full">
            </div>
            <div class="w-full sm:w-48">
                <label for="filterEndDate"
                    class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 block">Tanggal Akhir</label>
                <input type="date" id="filterEndDate" wire:model.live="endDate" class="form-input w-full">
            </div>

            <!-- Search -->
            <div class="w-full lg:flex-1">
                <label for="customSearch"
                    class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 block">Pencarian</label>
                <div class="relative">
                    <input type="text" id="customSearch" wire:model.live.debounce.300ms="search"
                        class="form-input has-icon-left w-full"
                        placeholder="Cari nomor KR, pabrikan, material...">
                    <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                        <i class="fas fa-search"></i>
                    </div>
                    @if ($search)
                        <button type="button" wire:click="$set('search', '')"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    @endif
                </div>
            </div>

            <!-- Per Page -->
            <div class="w-full sm:w-40">
                <label for="perPage"
                    class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 block">Tampilkan</label>
                <select id="perPage" wire:model.live="perPage" class="form-input w-full bg-white">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Loading Indicator -->
    <div wire:loading.flex class="loading-overlay">
        <div class="loading-spinner">
            <i class="fas fa-spinner fa-spin"></i>
            <span>Memuat data...</span>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-hidden rounded-xl border border-gray-100">
        <table class="table-purity w-full">
            <thead>
                <tr class="bg-gray-50 text-left">
                    <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal Masuk</th>
                    <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Nomor KR</th>
                    <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Pabrikan</th>
                    <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Material</th>
                    <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Total Qty</th>
                    <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Dibuat Oleh</th>
                    <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Status SAP</th>
                    <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white" wire:loading.class="opacity-50">
                @forelse ($materialMasuk as $index => $row)
                    <tr>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            {{ $materialMasuk->firstItem() + $index }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-700">
                            {{ $row->tanggal_masuk ? \Carbon\Carbon::parse($row->tanggal_masuk)->format('d/m/Y') : '-' }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-700 font-mono">
                            {{ $row->nomor_kr ?? '-' }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-700">
                            {{ $row->pabrikan ?? '-' }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-700">
                            @php
                                $details = $row->details ?? collect();
                                $materials = $details->map(function ($detail) {
                                    $desc = $detail->material->material_description ?? 'Material tidak diketahui';
                                    return $desc . ' (' . $detail->quantity . ' ' . $detail->satuan . ')';
                                });
                                $preview = $materials->take(2);
                            @endphp
                            {!! $preview->implode('<br>') !!}
                            @if ($materials->count() > 2)
                                <br>
                                <small class="text-gray-400">+{{ $materials->count() - 2 }} material lainnya</small>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-700 font-semibold">
                            {{ $details->sum('quantity') }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-700">
                            {{ $row->creator->nama ?? '-' }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-700">
                            @if (strtolower(trim($row->status_sap ?? '')) === 'selesai sap')
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                    Selesai SAP
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                    Belum SAP
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-700 text-right">
                            <div class="action-buttons">
                                <button type="button" class="action-btn view" onclick="showDetail({{ $row->id }})"
                                    title="Detail">
                                    <i class="fa fa-eye"></i>
                                </button>
                                @if (auth()->user()->role !== 'guest' &&
                                        strtolower(trim($row->status_sap ?? '')) !== 'selesai sap')
                                    <a href="{{ route('material-masuk.edit', $row->id) }}" class="action-btn edit"
                                        title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <button type="button" class="action-btn delete"
                                        onclick="deleteMaterialMasuk({{ $row->id }})" title="Hapus">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-6 py-10 text-center text-sm text-gray-500">
                            Tidak ada data material masuk.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3 text-sm text-gray-500">
        <div>
            @if ($materialMasuk->total())
                Menampilkan {{ $materialMasuk->firstItem() }} - {{ $materialMasuk->lastItem() }} dari
                {{ $materialMasuk->total() }} data
            @else
                Menampilkan 0 data
            @endif
        </div>
        <div>
            {{ $materialMasuk->links() }}
        </div>
    </div>
</div>
