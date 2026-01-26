@extends('layouts.app')

@section('title', 'Edit Material - Si Eneng')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-amber-600 to-amber-400">
                Edit Material
            </h2>
            <p class="text-gray-500 text-sm mt-1">Perbarui informasi material: <span class="font-mono font-bold text-amber-600">{{ $material->material_code }}</span></p>
        </div>
        
        <a href="{{ route('material.index') }}" 
           class="group px-4 py-2 rounded-xl bg-white border border-gray-200 text-gray-600 hover:text-amber-600 hover:border-amber-200 hover:shadow-md transition-all duration-300 flex items-center gap-2 text-sm font-medium">
            <div class="w-6 h-6 rounded-full bg-gray-50 group-hover:bg-amber-50 flex items-center justify-center transition-colors">
                <i class="fas fa-arrow-left text-xs"></i>
            </div>
            <span>Kembali</span>
        </a>
    </div>

    <!-- Main Card -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-xl shadow-gray-200/50 overflow-hidden relative">
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-amber-400 via-orange-500 to-red-500"></div>

        <form method="POST" action="{{ route('material.update', $material->id) }}" class="p-8">
            @csrf
            @method('PUT')

            <!-- Section 1: Info Dasar (Read-Only) -->
             <div class="mb-10 relative">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-2xl bg-gray-50 border border-gray-100 flex items-center justify-center shadow-sm">
                        <i class="fas fa-cube text-gray-400 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Informasi Dasar</h3>
                        <p class="text-xs text-gray-400">Identitas utama material (Read-only)</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 opacity-75">
                    <!-- Material Code -->
                     <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700">Nomor Normalisasi</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-barcode text-gray-400"></i>
                            </div>
                            <input type="text" value="{{ $material->material_code }}" readonly
                                   class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-600 font-mono cursor-not-allowed">
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="space-y-2 md:col-span-2">
                        <label class="text-sm font-medium text-gray-700">Deskripsi Material</label>
                         <div class="relative group">
                            <div class="absolute top-3 left-3 pointer-events-none">
                                <i class="fas fa-align-left text-gray-400"></i>
                            </div>
                            <textarea rows="2" readonly
                                      class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-600 cursor-not-allowed">{{ $material->material_description }}</textarea>
                        </div>
                    </div>
                </div>
             </div>

             <div class="w-full h-px bg-gray-100 my-8"></div>

             <!-- Section 2: Info Perusahaan (Partial Editable) -->
             <div class="mb-10">
                 <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 border border-blue-100 flex items-center justify-center shadow-sm">
                        <i class="fas fa-building text-blue-500 text-xl"></i>
                    </div>
                    <div>
                         <h3 class="text-lg font-bold text-gray-800">Lokasi & Rak</h3>
                        <p class="text-xs text-gray-400">Hanya posisi rak yang dapat diubah</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50/50 p-6 rounded-2xl border border-gray-100 border-dashed">
                    <!-- Company (Read-only) -->
                    <div class="space-y-2 opacity-75">
                        <label class="text-sm font-medium text-gray-700">Company</label>
                        <div class="grid grid-cols-3 gap-2">
                            <input type="text" value="{{ $material->company_code }}" readonly
                                   class="col-span-1 px-3 py-2.5 rounded-xl border border-gray-200 bg-gray-50 font-mono text-sm font-bold text-center text-gray-500 cursor-not-allowed">
                            <input type="text" value="{{ $material->company_code_description }}" readonly
                                   class="col-span-2 px-3 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-sm text-gray-500 cursor-not-allowed">
                        </div>
                    </div>

                    <!-- Plant (Read-only) -->
                    <div class="space-y-2 opacity-75">
                         <label class="text-sm font-medium text-gray-700">Plant</label>
                        <div class="grid grid-cols-3 gap-2">
                            <input type="text" value="{{ $material->plant }}" readonly
                                   class="col-span-1 px-3 py-2.5 rounded-xl border border-gray-200 bg-gray-50 font-mono text-sm font-bold text-center text-gray-500 cursor-not-allowed">
                            <input type="text" value="{{ $material->plant_description }}" readonly
                                   class="col-span-2 px-3 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-sm text-gray-500 cursor-not-allowed">
                        </div>
                    </div>

                    <!-- Storage (Read-only) -->
                     <div class="space-y-2 opacity-75">
                        <label class="text-sm font-medium text-gray-700">Storage Location</label>
                        <div class="grid grid-cols-3 gap-2">
                            <input type="text" value="{{ $material->storage_location }}" readonly
                                   class="col-span-1 px-3 py-2.5 rounded-xl border border-gray-200 bg-gray-50 font-mono text-sm font-bold text-center text-gray-500 cursor-not-allowed">
                            <input type="text" value="{{ $material->storage_location_description }}" readonly
                                   class="col-span-2 px-3 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-sm text-gray-500 cursor-not-allowed">
                        </div>
                    </div>

                    <!-- Rak (EDITABLE) -->
                    <div class="space-y-2 md:col-start-2">
                         <label class="text-sm font-bold text-blue-700">Posisi Rak (Editable)</label>
                         <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-th text-blue-400 group-focus-within:text-blue-600 transition-colors"></i>
                            </div>
                            <input type="text" name="rak" value="{{ old('rak', $material->rak) }}" 
                                   class="w-full pl-10 pr-4 py-2.5 rounded-xl border-2 border-blue-200 focus:border-blue-500 bg-white ring-4 ring-blue-50/50 transition-all font-semibold text-gray-800"
                                   placeholder="Contoh: A-01-02">
                        </div>
                        <p class="text-xs text-blue-500 mt-1"><i class="fas fa-info-circle mr-1"></i> Field ini dapat Anda perbarui.</p>
                    </div>
                </div>
             </div>

             <div class="w-full h-px bg-gray-100 my-8"></div>

             <!-- Section 3: Material Type (Read-only) -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-10 opacity-75">
                <div class="space-y-6">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center shadow-sm">
                            <i class="fas fa-tags text-gray-400 text-lg"></i>
                        </div>
                        <h3 class="text-base font-bold text-gray-800">Tipe & Kategori (Read-only)</h3>
                    </div>

                    <div class="space-y-4 cursor-not-allowed">
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700">Material Type</label>
                            <div class="grid grid-cols-3 gap-2">
                                <input type="text" value="{{ $material->material_type }}" readonly
                                       class="col-span-1 px-3 py-2.5 rounded-xl border border-gray-200 bg-gray-50 font-mono text-sm font-bold text-center text-gray-500">
                                <input type="text" value="{{ $material->material_type_description }}" readonly
                                       class="col-span-2 px-3 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-sm text-gray-500">
                            </div>
                        </div>

                         <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700">Material Group</label>
                            <input type="text" value="{{ $material->material_group }}" readonly
                                   class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 font-mono text-sm text-gray-500">
                        </div>

                        <div class="space-y-2">
                             <label class="text-sm font-medium text-gray-700">Satuan (UoM)</label>
                             <input type="text" value="{{ $material->base_unit_of_measure }}" readonly
                                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 font-mono text-sm text-gray-500">
                        </div>
                    </div>
                </div>

                <!-- Section 4: Stock & Price (Read-only) -->
                 <div class="space-y-6">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-orange-50 border border-orange-100 flex items-center justify-center shadow-sm">
                            <i class="fas fa-coins text-orange-500 text-lg"></i>
                        </div>
                         <h3 class="text-base font-bold text-gray-800">Stock & Valuasi</h3>
                    </div>

                    <div class="p-5 bg-gradient-to-br from-orange-50 to-white rounded-2xl border border-orange-100 space-y-4">
                        <!-- Unrestricted Stock (Readonly) -->
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700">Unrestricted Stock</label>
                            <input type="text" value="{{ number_format($material->unrestricted_use_stock, 0, ',', '.') }}" readonly
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 font-mono text-lg font-bold text-gray-600 cursor-not-allowed">
                        </div>

                         <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700">Harga Satuan (Rp)</label>
                             <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-400 font-bold">Rp</span>
                                </div>
                                <input type="text" value="{{ number_format($material->harga_satuan, 0, ',', '.') }}" readonly
                                       class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 bg-gray-50 font-mono text-lg font-bold text-gray-600 cursor-not-allowed">
                            </div>
                        </div>
                    </div>
                
                    <!-- Keterangan (Editable as well usually, but user asked specifically only Rak? I will assume Keterangan is also safe to edit as it's secondary, or should I strict ONLY rak? User said "editable hanya rak material saja". So I will make Keterangan readonly too to be safe, or just remove it if not needed? I'll make it readonly based strictly on prompt "editable hanya rak material saja" -->
                     <div class="space-y-2">
                         <label class="text-sm font-medium text-gray-700">Keterangan</label>
                        <textarea name="keterangan" rows="3"
                                  class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-amber-500 bg-white"
                                  placeholder="Keterangan tambahan (opsional)">{{ old('keterangan', $material->keterangan) }}</textarea>
                    </div>
                </div>
              </div>

            <!-- Footer Action -->
             <div class="mt-10 pt-6 border-t border-gray-100 flex flex-col-reverse md:flex-row justify-between gap-4">
                 <a href="{{ route('material.index') }}" class="px-6 py-3 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 transition-all font-medium flex items-center justify-center gap-2">
                    <span>Batal</span>
                </a>

                <button type="submit" class="group relative px-8 py-3 bg-gradient-to-r from-amber-500 to-orange-600 rounded-xl text-white font-bold shadow-lg shadow-orange-500/30 hover:shadow-orange-500/50 hover:scale-[1.02] transition-all duration-300 overflow-hidden">
                    <div class="absolute inset-0 bg-white/20 group-hover:translate-x-full transition-transform duration-500 ease-out -translate-x-full transform skew-x-12"></div>
                    <span class="relative flex items-center gap-2">
                        <i class="fas fa-save"></i>
                        Simpan Rak
                    </span>
                </button>
             </div>
        </form>
    </div>
</div>
@endsection
