@extends('layouts.app')

@section('title', 'Berita Acara')

@section('content')

<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">📄 Berita Acara</h2>
            <p class="text-gray-500 text-sm mt-1">Kelola dokumen berita acara.</p>
        </div>
        
        @if(auth()->user()->role !== 'guest')
        <a href="{{ route('berita-acara.create') }}" class="btn-teal flex items-center gap-2 shadow-lg shadow-teal-500/20 hover:shadow-teal-500/40 transition-all duration-300">
            <i class="fas fa-plus"></i>
            <span>Buat Berita Acara</span>
        </a>
        @endif
    </div>

    <!-- Content Card -->
    <div class="card p-6 border border-gray-100 shadow-xl shadow-gray-200/50">
        <div class="overflow-x-auto rounded-xl border border-gray-100">
            <table class="table-purity w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider w-12">No</th>
                        <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Hari</th>
                        <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Mengetahui</th>
                        <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Pembuat</th>
                        <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($beritaAcaras as $ba)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 text-center text-gray-600">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3 text-gray-800 font-medium">{{ $ba->hari }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ \Carbon\Carbon::parse($ba->tanggal)->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $ba->mengetahui }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $ba->pembuat }}</td>

                            <td class="px-4 py-3">
                                <div class="flex justify-end items-center gap-2">
                                    <!-- Lihat (SEMUA ROLE BOLEH) -->
                                    <a href="{{ route('berita-acara.show', $ba->id) }}"
                                       class="p-2 rounded-lg text-teal-600 hover:bg-teal-50 transition-colors"
                                       title="Lihat">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                
                                    <!-- AKSI EDIT / DELETE / UPLOAD HANYA BUKAN GUEST -->
                                    @if(auth()->user()->role !== 'guest')
                                
                                        <!-- Edit -->
                                        <a href="{{ route('berita-acara.edit', $ba->id) }}"
                                           class="p-2 rounded-lg text-yellow-600 hover:bg-yellow-50 transition-colors"
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                
                                        <!-- Delete -->
                                        <form action="{{ route('berita-acara.destroy', $ba->id) }}"
                                              method="POST"
                                              class="inline"
                                              onsubmit="return confirm('Hapus Berita Acara ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="p-2 rounded-lg text-red-500 hover:bg-red-50 transition-colors"
                                                    title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                
                                        <!-- PDF -->
                                        @if ($ba->file_pdf)
                                            <a href="{{ route('berita-acara.pdf-upload', $ba->id) }}"
                                               target="_blank"
                                               class="p-2 rounded-lg text-green-600 hover:bg-green-50 transition-colors"
                                               title="Lihat PDF">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                        @else
                                            <form action="{{ route('berita-acara.upload-pdf', $ba->id) }}"
                                                  method="POST"
                                                  enctype="multipart/form-data"
                                                  class="inline">
                                                @csrf
                                                <input type="file"
                                                       id="baUpload{{ $ba->id }}"
                                                       name="file_pdf"
                                                       accept="application/pdf"
                                                       class="hidden"
                                                       onchange="this.form.submit()">
                                
                                                <button type="button"
                                                        class="p-2 rounded-lg text-indigo-600 hover:bg-indigo-50 transition-colors"
                                                        title="Upload PDF"
                                                        onclick="document.getElementById('baUpload{{ $ba->id }}').click()">
                                                    <i class="fas fa-upload"></i>
                                                </button>
                                            </form>
                                        @endif
                                
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-400">
                                <i class="fas fa-inbox text-4xl mb-2"></i>
                                <p>Belum ada Berita Acara</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
