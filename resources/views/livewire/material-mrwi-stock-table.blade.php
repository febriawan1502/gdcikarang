<div class="card p-6 border border-gray-100 shadow-xl shadow-gray-200/50">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div class="flex flex-wrap gap-2">
            <button type="button" wire:click="setCategory('standby')"
                class="mrwi-tab {{ $category === 'standby' ? 'active' : 'bg-gray-100 text-gray-600' }}">
                Standby
            </button>
            <button type="button" wire:click="setCategory('garansi')"
                class="mrwi-tab {{ $category === 'garansi' ? 'active' : 'bg-gray-100 text-gray-600' }}">
                Garansi
            </button>
            <button type="button" wire:click="setCategory('perbaikan')"
                class="mrwi-tab {{ $category === 'perbaikan' ? 'active' : 'bg-gray-100 text-gray-600' }}">
                Perbaikan
            </button>
            <button type="button" wire:click="setCategory('rusak')"
                class="mrwi-tab {{ $category === 'rusak' ? 'active' : 'bg-gray-100 text-gray-600' }}">
                Rusak
            </button>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 sm:items-center">
            <a href="{{ route('material-mrwi.stock.export', $category) }}"
                class="px-4 py-2 text-sm rounded-lg bg-green-500 text-white hover:bg-green-600 shadow-md transition-all flex items-center gap-2">
                <i class="fas fa-file-excel"></i> Export
            </a>
            <div class="relative">
                <input type="text" wire:model.live.debounce.300ms="search" class="form-input pr-10"
                    placeholder="Cari kode atau material...">
                @if ($search)
                    <button type="button" wire:click="$set('search', '')"
                        class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                @endif
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <span>Tampilkan</span>
                <select wire:model.live="perPage" class="form-input py-1 px-2">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span>data</span>
            </div>
        </div>
    </div>

    <div class="relative overflow-hidden rounded-xl border border-gray-100">
        <div wire:loading.flex class="loading-overlay">
            <div class="loading-spinner">
                <i class="fas fa-spinner fa-spin"></i>
                <span>Memuat data...</span>
            </div>
        </div>

        <table class="table-purity w-full">
            <thead>
                <tr class="bg-gray-50 text-left">
                    <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider w-12">No</th>
                    <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider cursor-pointer"
                        wire:click="sort('material_code')">
                        <div class="flex items-center gap-1">
                            Kode Material
                            @if ($sortBy === 'material_code')
                                <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                            @else
                                <i class="fas fa-sort text-gray-300"></i>
                            @endif
                        </div>
                    </th>
                    <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider cursor-pointer"
                        wire:click="sort('material_description')">
                        <div class="flex items-center gap-1">
                            Nama Material
                            @if ($sortBy === 'material_description')
                                <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                            @else
                                <i class="fas fa-sort text-gray-300"></i>
                            @endif
                        </div>
                    </th>
                    <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider text-center cursor-pointer"
                        wire:click="sort('stock_qty')">
                        <div class="flex items-center justify-center gap-1">
                            Stock
                            @if ($sortBy === 'stock_qty')
                                <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                            @else
                                <i class="fas fa-sort text-gray-300"></i>
                            @endif
                        </div>
                    </th>
                    <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Satuan
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white" wire:loading.class="opacity-50">
                @forelse ($materials as $index => $material)
                    <tr>
                        <td class="px-4 py-3 text-gray-600">{{ $materials->firstItem() + $index }}</td>
                        <td class="px-4 py-3 text-gray-800 font-semibold">{{ $material->material_code }}</td>
                        <td class="px-4 py-3 text-gray-700">{{ $material->material_description }}</td>
                        <td class="px-4 py-3 text-center font-semibold text-gray-800">
                            {{ number_format($material->stock_qty, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-center text-gray-700">{{ $material->base_unit_of_measure }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                            Data tidak ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mt-4 text-sm text-gray-500">
        <div>
            @if ($materials->total() > 0)
                Menampilkan {{ $materials->firstItem() }} sampai {{ $materials->lastItem() }} dari
                {{ $materials->total() }} data
            @else
                Tidak ada data
            @endif
        </div>
        <div>
            {{ $materials->links() }}
        </div>
    </div>
</div>
