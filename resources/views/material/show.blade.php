@extends('layouts.app')

@section('title', 'Detail Material - POJOK IMS')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-blue-400">
                Detail Material
            </h2>
            <p class="text-gray-500 text-sm mt-1">Informasi lengkap material: <span class="font-mono font-bold text-blue-600">{{ $material->material_code }}</span></p>
        </div>
        
        <div class="flex items-center gap-3">
            <a href="{{ route('material.index') }}" 
               class="group px-4 py-2 rounded-xl bg-white border border-gray-200 text-gray-600 hover:text-blue-600 hover:border-blue-200 hover:shadow-md transition-all duration-300 flex items-center gap-2 text-sm font-medium">
                <i class="fas fa-arrow-left text-xs"></i>
                <span>Kembali</span>
            </a>
            
            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'petugas')
            <a href="{{ route('material.edit', $material->id) }}" 
               class="group px-4 py-2 rounded-xl bg-gradient-to-r from-amber-500 to-orange-500 text-white shadow-lg shadow-orange-500/30 hover:shadow-orange-500/50 hover:scale-[1.02] transition-all duration-300 flex items-center gap-2 text-sm font-bold">
                <i class="fas fa-edit"></i>
                <span>Edit Material</span>
            </a>
            @endif
        </div>
    </div>

    <!-- Main Card -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-xl shadow-gray-200/50 overflow-hidden relative">
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-400 via-indigo-500 to-purple-500"></div>

        <div class="p-8 space-y-10">
            <!-- Section 1: Info Dasar -->
            <div>
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 border border-blue-100 flex items-center justify-center shadow-sm">
                        <i class="fas fa-cube text-blue-500 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Informasi Dasar</h3>
                        <p class="text-xs text-gray-400">Identitas utama material</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <label class="text-xs font-medium text-gray-400 mb-1 block">Nomor Normalisasi (Material Code)</label>
                        <div class="font-mono text-lg font-bold text-gray-800 tracking-wide">
                            {{ $material->material_code }}
                        </div>
                    </div>

                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 md:col-span-2">
                        <label class="text-xs font-medium text-gray-400 mb-1 block">Deskripsi Material</label>
                        <div class="text-base font-semibold text-gray-800 leading-relaxed">
                            {{ $material->material_description }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-full h-px bg-gray-100"></div>

            <!-- Section 2: Info Perusahaan -->
            <div>
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center shadow-sm">
                        <i class="fas fa-building text-indigo-500 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Lokasi & Perusahaan</h3>
                        <p class="text-xs text-gray-400">Detail penyimpanan asset</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Company -->
                    <div class="space-y-1">
                        <label class="text-xs font-medium text-gray-400">Company</label>
                        <div class="bg-indigo-50/50 p-3 rounded-lg border border-indigo-100 flex items-center gap-3">
                            <span class="font-mono font-bold text-indigo-700 bg-indigo-100 px-2 py-0.5 rounded text-sm">{{ $material->company_code }}</span>
                            <span class="text-sm font-semibold text-gray-700">{{ $material->company_code_description }}</span>
                        </div>
                    </div>

                    <!-- Plant -->
                    <div class="space-y-1">
                        <label class="text-xs font-medium text-gray-400">Plant</label>
                        <div class="bg-indigo-50/50 p-3 rounded-lg border border-indigo-100 flex items-center gap-3">
                            <span class="font-mono font-bold text-indigo-700 bg-indigo-100 px-2 py-0.5 rounded text-sm">{{ $material->plant }}</span>
                            <span class="text-sm font-semibold text-gray-700">{{ $material->plant_description }}</span>
                        </div>
                    </div>

                    <!-- Storage Location -->
                    <div class="space-y-1">
                        <label class="text-xs font-medium text-gray-400">Storage Location</label>
                        <div class="bg-indigo-50/50 p-3 rounded-lg border border-indigo-100 flex items-center gap-3">
                            <span class="font-mono font-bold text-indigo-700 bg-indigo-100 px-2 py-0.5 rounded text-sm">{{ $material->storage_location }}</span>
                            <span class="text-sm font-semibold text-gray-700">{{ $material->storage_location_description }}</span>
                        </div>
                    </div>
                    
                    <!-- Rak -->
                    <div class="space-y-1">
                        <label class="text-xs font-medium text-gray-400">Posisi Rak</label>
                        <div class="p-3 rounded-lg border border-gray-200 flex items-center gap-3 bg-white">
                            <i class="fas fa-th text-gray-400"></i>
                            <span class="font-semibold text-gray-700">{{ $material->rak ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-full h-px bg-gray-100"></div>

            <!-- Section 3: Stock & Harga -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <!-- Kiri: Tipe Material -->
                <div>
                     <div class="flex items-center gap-4 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-purple-50 border border-purple-100 flex items-center justify-center shadow-sm">
                            <i class="fas fa-tags text-purple-500 text-lg"></i>
                        </div>
                        <h3 class="text-base font-bold text-gray-800">Tipe & Kategori</h3>
                    </div>

                    <div class="space-y-4">
                        <div class="space-y-1">
                            <label class="text-xs font-medium text-gray-400">Material Type</label>
                            <div class="flex items-center gap-2">
                                <span class="font-mono text-sm font-bold text-purple-600 bg-purple-50 px-2 py-1 rounded border border-purple-100">{{ $material->material_type }}</span>
                                <span class="text-sm text-gray-600">{{ $material->material_type_description }}</span>
                            </div>
                        </div>
                         <div class="space-y-1">
                            <label class="text-xs font-medium text-gray-400">Material Group</label>
                            <div class="font-mono text-sm font-semibold text-gray-700">{{ $material->material_group }}</div>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-medium text-gray-400">Satuan (Base UoM)</label>
                            <div class="font-bold text-gray-800 flex items-center gap-2">
                                <span class="w-8 h-8 rounded-full bg-gray-100 text-gray-500 text-xs flex items-center justify-center border border-gray-200">
                                    {{ $material->base_unit_of_measure }}
                                </span>
                                <span>{{ $material->base_unit_of_measure }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kanan: Stock & Harga Card -->
                <div class="p-6 bg-gradient-to-br from-orange-50 to-white rounded-2xl border border-orange-100 shadow-sm relative overflow-hidden">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-orange-100/50 rounded-full blur-2xl"></div>
                    
                     <div class="flex items-center gap-3 mb-6 relative z-10">
                        <div class="w-10 h-10 rounded-xl bg-white border border-orange-100 flex items-center justify-center shadow-sm">
                            <i class="fas fa-coins text-orange-500 text-lg"></i>
                        </div>
                        <h3 class="text-base font-bold text-gray-800">Stock & Valuasi</h3>
                    </div>

                    <div class="grid grid-cols-2 gap-4 relative z-10">
                        <div class="col-span-2 p-4 bg-white rounded-xl border border-orange-100 shadow-sm">
                            <label class="text-xs font-medium text-gray-400 mb-1 block">Unrestricted Use Stock</label>
                            <div class="text-2xl font-bold text-orange-600 font-mono">
                                {{ number_format($material->unrestricted_use_stock, 0, ',', '.') }}
                                <span class="text-sm text-gray-400 font-sans font-normal">{{ $material->base_unit_of_measure }}</span>
                            </div>
                        </div>

                         <div class="p-3 bg-white/60 rounded-xl border border-orange-50">
                             <label class="text-xs font-medium text-gray-400 mb-1 block">Harga Satuan</label>
                             <div class="font-bold text-gray-700">
                                <span class="text-xs text-gray-400 mr-1">Rp</span>{{ number_format($material->harga_satuan, 0, ',', '.') }}
                             </div>
                        </div>

                        <div class="p-3 bg-white/60 rounded-xl border border-orange-50">
                             <label class="text-xs font-medium text-gray-400 mb-1 block">Total Nilai</label>
                             <div class="font-bold text-gray-700">
                                <span class="text-xs text-gray-400 mr-1">Rp</span>{{ number_format($material->total_harga, 0, ',', '.') }}
                             </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer: System Info -->
            <div class="mt-8 pt-6 border-t border-gray-100 flex flex-col md:flex-row justify-between gap-4 text-xs text-gray-400 bg-gray-50/50 p-4 rounded-xl">
                 <div class="flex items-center gap-2">
                    <i class="far fa-clock"></i>
                    <span>Dibuat: <strong>{{ $material->created_at->format('d M Y H:i') }}</strong></span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-pen-fancy"></i>
                    <span>Terakhir diupdate: <strong>{{ $material->updated_at->format('d M Y H:i') }}</strong></span>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection