@extends('layouts.app')

@section('title', 'Tambah Material')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header with Breadcrumb-like feel -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-teal-600 to-teal-400">
                Tambah Material Baru
            </h2>
            <p class="text-gray-500 text-sm mt-1">Lengkapi formulir di bawah untuk mendaftarkan material baru ke dalam sistem.</p>
        </div>
        
        <a href="{{ route('material.index') }}" 
           class="group px-4 py-2 rounded-xl bg-white border border-gray-200 text-gray-600 hover:text-teal-600 hover:border-teal-200 hover:shadow-md transition-all duration-300 flex items-center gap-2 text-sm font-medium">
            <div class="w-6 h-6 rounded-full bg-gray-50 group-hover:bg-teal-50 flex items-center justify-center transition-colors">
                <i class="fas fa-arrow-left text-xs"></i>
            </div>
            <span>Kembali ke Daftar</span>
        </a>
    </div>

    <!-- Main Form Card -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-xl shadow-gray-200/50 overflow-hidden relative">
        <!-- Decorative Top Line -->
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-teal-400 via-blue-500 to-purple-500"></div>

        <form method="POST" action="{{ route('material.store') }}" class="p-8">
            @csrf

            <!-- Section 1: Informasi Dasar -->
            <div class="mb-10 relative">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-2xl bg-teal-50 border border-teal-100 flex items-center justify-center shadow-sm">
                        <i class="fas fa-cube text-teal-500 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Informasi Dasar</h3>
                        <p class="text-xs text-gray-400">Identitas utama material</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Material Code -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700">Nomor Normalisasi <span class="text-red-500">*</span></label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-barcode text-gray-400 group-focus-within:text-teal-500 transition-colors"></i>
                            </div>
                            <input type="text" name="material_code" value="{{ old('material_code') }}" required
                                   class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition-all outline-none bg-gray-50/50 hover:bg-white"
                                   placeholder="Contoh: 1000293">
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="space-y-2 md:col-span-2">
                        <label class="text-sm font-medium text-gray-700">Deskripsi Material <span class="text-red-500">*</span></label>
                        <div class="relative group">
                            <div class="absolute top-3 left-3 pointer-events-none">
                                <i class="fas fa-align-left text-gray-400 group-focus-within:text-teal-500 transition-colors"></i>
                            </div>
                            <textarea name="material_description" rows="2" required
                                      class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition-all outline-none bg-gray-50/50 hover:bg-white"
                                      placeholder="Contoh : MTR;kWH E-PR;;1P;230V;5-60A;1;;2W">{{ old('material_description') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Divider -->
            <div class="w-full h-px bg-gray-100 my-8"></div>

            <!-- Section 2: Informasi Perusahaan -->
            <div class="mb-10">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 border border-blue-100 flex items-center justify-center shadow-sm">
                        <i class="fas fa-building text-blue-500 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Informasi Perusahaan</h3>
                        <p class="text-xs text-gray-400">Detail lokasi dan organisasi</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50/50 p-6 rounded-2xl border border-gray-100 border-dashed">
                    <!-- Editable Field: Company -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700">Company Code & Name</label>
                        <div class="grid grid-cols-3 gap-2">
                            <input type="text" name="company_code" value="{{ old('company_code', '5300') }}" required
                                   class="col-span-1 px-3 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all outline-none bg-white font-mono text-sm font-bold text-center"
                                   placeholder="Code">
                            <input type="text" name="company_code_description" value="{{ old('company_code_description', 'UID Jawa Barat') }}" required
                                   class="col-span-2 px-3 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all outline-none bg-white text-sm"
                                   placeholder="Description">
                        </div>
                    </div>

                    <!-- Editable Field: Plant -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700">Plant</label>
                        <div class="grid grid-cols-3 gap-2">
                            <input type="text" name="plant" value="{{ old('plant', '5319') }}" required
                                   class="col-span-1 px-3 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all outline-none bg-white font-mono text-sm font-bold text-center"
                                   placeholder="Code">
                            <input type="text" name="plant_description" value="{{ old('plant_description', 'PLN UP3 Cimahi') }}" required
                                   class="col-span-2 px-3 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all outline-none bg-white text-sm"
                                   placeholder="Description">
                        </div>
                    </div>

                    <!-- Editable Field: Storage Loc -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700">Storage Location</label>
                        <div class="grid grid-cols-3 gap-2">
                            <input type="text" name="storage_location" value="{{ old('storage_location', '2080') }}" required
                                   class="col-span-1 px-3 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all outline-none bg-white font-mono text-sm font-bold text-center"
                                   placeholder="Code">
                            <input type="text" name="storage_location_description" value="{{ old('storage_location_description', 'APJ Cimahi') }}" required
                                   class="col-span-2 px-3 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all outline-none bg-white text-sm"
                                   placeholder="Description">
                        </div>
                    </div>

                    <!-- Editable Field: Rak -->
                    <div class="space-y-2 md:col-start-2 overflow-visible">
                        <label class="text-sm font-medium text-gray-700">Posisi Rak</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-th text-gray-400 group-focus-within:text-blue-500 transition-colors"></i>
                            </div>
                            <input type="text" name="rak" value="{{ old('rak') }}" 
                                   class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all outline-none bg-white"
                                   placeholder="Contoh: A-01-02">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Divider -->
            <div class="w-full h-px bg-gray-100 my-8"></div>

            <!-- Section 3 & 4: Type & Harga -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <!-- Material Type -->
                <div class="space-y-6">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-purple-50 border border-purple-100 flex items-center justify-center shadow-sm">
                            <i class="fas fa-tags text-purple-500 text-lg"></i>
                        </div>
                        <h3 class="text-base font-bold text-gray-800">Tipe & Kategori</h3>
                    </div>

                    <div class="space-y-4">
                         <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700">Material Type</label>
                            <div class="flex gap-2">
                                <input type="text" name="material_type" value="ZST1" readonly
                                       class="w-20 px-3 py-2.5 bg-gray-50 text-center font-mono text-sm font-bold rounded-lg border border-gray-200 text-gray-500 cursor-not-allowed">
                                <input type="text" value="PLN Stock Materials" readonly
                                       class="flex-1 px-3 py-2.5 bg-gray-50 text-sm rounded-lg border border-gray-200 text-gray-500 cursor-not-allowed">
                                <input type="hidden" name="material_type_description" value="PLN Stock Materials">
                            </div>
                        </div>

                         <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700">Material Group <span class="text-red-500">*</span></label>
                            <input type="text" name="material_group" value="{{ old('material_group', 'ZM0106') }}" required
                                   class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-100 transition-all outline-none bg-white font-mono text-sm">
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700">Satuan (UoM) <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select name="base_unit_of_measure" required
                                        class="w-full pl-4 pr-10 py-2.5 rounded-xl border border-gray-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-100 transition-all outline-none bg-white appearance-none">
                                    <option value="">Pilih...</option>
                                    @foreach(['BH', 'SET', 'M', 'KG', 'BTG', 'U'] as $uom)
                                    <option value="{{ $uom }}" {{ old('base_unit_of_measure') == $uom ? 'selected' : '' }}>{{ $uom }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-gray-500">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Harga -->
                <div class="space-y-6">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-orange-50 border border-orange-100 flex items-center justify-center shadow-sm">
                            <i class="fas fa-coins text-orange-500 text-lg"></i>
                        </div>
                        <h3 class="text-base font-bold text-gray-800">Valuasi Harga</h3>
                    </div>

                    <div class="p-5 bg-gradient-to-br from-orange-50 to-white rounded-2xl border border-orange-100">
                         <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700">Harga Satuan (Rp) <span class="text-red-500">*</span></label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-400 font-bold group-focus-within:text-orange-500 transition-colors">Rp</span>
                                </div>
                                <input type="number" name="harga_satuan" value="{{ old('harga_satuan') }}" required
                                       class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition-all outline-none bg-white font-mono text-lg font-semibold text-gray-800"
                                       placeholder="0">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Harga per unit sebelum PPN</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hidden Fields -->
            <input type="hidden" name="valuation_type" value="NORMAL">
            <input type="hidden" name="quality_inspection_stock" value="0">
            <input type="hidden" name="blocked_stock" value="0">
            <input type="hidden" name="in_transit_stock" value="0">
            <input type="hidden" name="project_stock" value="0">
            <input type="hidden" name="wbs_element" value="">
            <input type="hidden" name="valuation_class" value="1000">
            <input type="hidden" name="valuation_description" value="HAR-Material">

            <!-- Footer Action -->
            <div class="mt-10 pt-6 border-t border-gray-100 flex flex-col-reverse md:flex-row justify-between gap-4">
                <button type="reset" class="px-6 py-3 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 hover:text-gray-800 transition-all font-medium flex items-center justify-center gap-2">
                    <i class="fas fa-undo text-sm"></i>
                    <span>Reset Data</span>
                </button>

                <button type="submit" class="group relative px-8 py-3 bg-gradient-to-r from-teal-400 to-teal-500 rounded-xl text-white font-bold shadow-lg shadow-teal-500/30 hover:shadow-teal-500/50 hover:scale-[1.02] transition-all duration-300 overflow-hidden">
                    <div class="absolute inset-0 bg-white/20 group-hover:translate-x-full transition-transform duration-500 ease-out -translate-x-full transform skew-x-12"></div>
                    <span class="relative flex items-center gap-2">
                        <i class="fas fa-save"></i>
                        Simpan Material
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
