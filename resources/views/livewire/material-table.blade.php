<div>
    <!-- Header Actions -->
    <div class="material-header">
        <div class="material-header-title">
            <h1>Daftar Material</h1>
            <p>Kelola data material inventaris</p>
        </div>
        <div class="material-header-actions">
            <button type="button" class="btn-action default" wire:click="$refresh">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
            @if(auth()->user()->role !== 'guest')
            <button type="button" class="btn-action success" x-data x-on:click="$dispatch('open-modal', 'importModal')">
                <i class="fas fa-upload"></i> Import Excel
            </button>
            @endif
            <a href="{{ route('dashboard.export') }}" class="btn-action warning" target="_blank">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
            @if(auth()->user()->role !== 'guest')
            <a href="{{ route('material.bulk-print-barcode') }}" class="btn-action info" target="_blank">
                <i class="fas fa-qrcode"></i> Bulk Print Barcode
            </a>
            <a href="{{ route('material.create') }}" wire:navigate class="btn-action primary">
                <i class="fas fa-plus"></i> Tambah Material
            </a>
            @endif
        </div>
    </div>

    <!-- Data Table Panel -->
    <div class="panel-card">
        <div class="panel-header">
            <h3 class="panel-title">
                <i class="fas fa-list"></i>
                Data Material
            </h3>
            <div class="panel-controls">
                <!-- Live Search -->
                <div class="search-box">
                    <input type="text" 
                           wire:model.live.debounce.300ms="search" 
                           placeholder="Cari kode atau deskripsi...">
                    <button type="button">
                        <i class="fas fa-search"></i>
                    </button>
                    @if($search)
                    <button type="button" wire:click="$set('search', '')" style="color: #A0AEC0; padding: 4px;">
                        <i class="fas fa-times"></i>
                    </button>
                    @endif
                </div>
                
                <!-- Per Page Select -->
                <div class="per-page-select">
                    <span>Tampilkan</span>
                    <select wire:model.live="perPage">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <span>entri</span>
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

        @if($materials->count() > 0)
        <div class="panel-body" wire:loading.class="opacity-50">
            <div style="overflow-x: auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th style="width: 12%; cursor: pointer;" wire:click="sort('material_code')">
                                <div style="display: flex; align-items: center; gap: 4px;">
                                    Kode Material
                                    @if($sortBy === 'material_code')
                                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @else
                                        <i class="fas fa-sort" style="opacity: 0.3;"></i>
                                    @endif
                                </div>
                            </th>
                            <th style="width: 28%; cursor: pointer;" wire:click="sort('material_description')">
                                <div style="display: flex; align-items: center; gap: 4px;">
                                    Deskripsi Material
                                    @if($sortBy === 'material_description')
                                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @else
                                        <i class="fas fa-sort" style="opacity: 0.3;"></i>
                                    @endif
                                </div>
                            </th>
                            <th style="width: 8%; text-align: center; cursor: pointer;" wire:click="sort('unrestricted_use_stock')">
                                <div style="display: flex; align-items: center; justify-content: center; gap: 4px;">
                                    Stock
                                    @if($sortBy === 'unrestricted_use_stock')
                                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @else
                                        <i class="fas fa-sort" style="opacity: 0.3;"></i>
                                    @endif
                                </div>
                            </th>
                            <th style="width: 8%; text-align: center;">Satuan</th>
                            <th style="width: 10%; text-align: center;">Rak</th>
                            <th style="width: 15%; text-align: right; cursor: pointer;" wire:click="sort('total_nilai')">
                                <div style="display: flex; align-items: center; justify-content: flex-end; gap: 4px;">
                                    Total Nilai
                                    @if($sortBy === 'total_nilai')
                                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @else
                                        <i class="fas fa-sort" style="opacity: 0.3;"></i>
                                    @endif
                                </div>
                            </th>
                            @if(auth()->user()->role !== 'guest')
                            <th style="width: 12%; text-align: center;">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($materials as $index => $material)
                        <tr>
                            <td>{{ $materials->firstItem() + $index }}</td>
                            <td><strong>{{ $material->material_code }}</strong></td>
                            <td>{{ Str::limit($material->material_description, 50) }}</td>
                            <td style="text-align: center;">
                                <span class="stock-badge">{{ number_format($material->unrestricted_use_stock, 0, ',', '.') }}</span>
                            </td>
                            <td style="text-align: center;">{{ $material->base_unit_of_measure }}</td>
                            <td style="text-align: center;">{{ $material->rak ?? '-' }}</td>
                            <td style="text-align: right;">
                                @php
                                    $totalNilai = $material->harga_satuan * $material->unrestricted_use_stock;
                                @endphp
                                @if($material->harga_satuan)
                                    {{ $material->currency ?? 'IDR' }} {{ number_format($totalNilai, 0, ',', '.') }}
                                @else
                                    -
                                @endif
                            </td>
                            @if(auth()->user()->role !== 'guest')
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('material.show', $material) }}" wire:navigate class="action-btn view" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('material.edit', $material) }}" wire:navigate class="action-btn edit" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('material.generate-barcode', $material) }}" class="action-btn barcode" title="Generate Barcode">
                                        <i class="fas fa-qrcode"></i>
                                    </a>
                                    <button type="button" class="action-btn delete" title="Hapus" 
                                            onclick="confirmDelete('{{ $material->id }}', '{{ $material->material_code }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="pagination-wrapper">
            <div class="pagination-info">
                Menampilkan {{ $materials->firstItem() }} sampai {{ $materials->lastItem() }} dari {{ $materials->total() }} data
            </div>
            <div class="pagination-links">
                {{ $materials->links() }}
            </div>
        </div>
        @else
        <div class="panel-body" style="padding: 40px;">
            <div class="alert-purity info">
                <i class="fas fa-info-circle"></i>
                @if($search)
                    Tidak ada material yang cocok dengan pencarian "{{ $search }}".
                    <button wire:click="$set('search', '')" style="background: none; border: none; font-weight: 600; cursor: pointer; color: inherit; text-decoration: underline;">Reset pencarian</button>
                @else
                    Belum ada data material. <a href="{{ route('material.create') }}" wire:navigate style="color: inherit; font-weight: 600;">Tambah material pertama</a>.
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
