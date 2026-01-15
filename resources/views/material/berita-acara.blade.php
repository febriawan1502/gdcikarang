@extends('layouts.app')

@section('title', 'Berita Acara')

@section('content')

<style>
    /* ===== STYLE KHUSUS HALAMAN BERITA ACARA (LOKAL SAJA) ===== */
    .ba-action-group {
        display: inline-flex;
        gap: 6px;
        flex-wrap: nowrap;
        justify-content: center;
        align-items: center;
    }

    .ba-action-group .btn {
        width: 34px;
        height: 34px;
        padding: 0;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .ba-action-group .btn i {
        font-size: 14px;
    }

    /* 🟣 UPLOAD PDF – UNGU (BEDA DENGAN EDIT) */
    .ba-btn-upload {
        background-color: #6366f1; /* indigo */
        border-color: #6366f1;
        color: #fff;
    }

    .ba-btn-upload:hover {
        background-color: #4f46e5;
        border-color: #4f46e5;
        color: #fff;
    }

    .ba-upload-input {
        display: none !important;
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Berita Acara</h2>

    @if(auth()->user()->role !== 'guest')
    <a href="{{ route('berita-acara.create') }}" class="btn btn-primary">
        <i class="fa fa-plus"></i> Buat Berita Acara
    </a>
    @endif

</div>

<div class="card">
    <div class="card-body">

        <table class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                    <th width="5%">No</th>
                    <th>Hari</th>
                    <th>Tanggal</th>
                    <th>Mengetahui</th>
                    <th>Pembuat</th>
                    <th width="20%" class="text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($beritaAcaras as $ba)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $ba->hari }}</td>
                        <td>{{ \Carbon\Carbon::parse($ba->tanggal)->format('d/m/Y') }}</td>
                        <td>{{ $ba->mengetahui }}</td>
                        <td>{{ $ba->pembuat }}</td>

                        <td class="text-center">
                            <div class="ba-action-group">
                        {{-- Lihat (SEMUA ROLE BOLEH) --}}
                        <a href="{{ route('berita-acara.show', $ba->id) }}"
                           class="btn btn-info btn-sm"
                           title="Lihat">
                            <i class="fa fa-eye"></i>
                        </a>
                    
                        {{-- AKSI EDIT / DELETE / UPLOAD HANYA BUKAN GUEST --}}
                        @if(auth()->user()->role !== 'guest')
                    
                            {{-- Edit --}}
                            <a href="{{ route('berita-acara.edit', $ba->id) }}"
                               class="btn btn-warning btn-sm"
                               title="Edit">
                                <i class="fa fa-pencil"></i>
                            </a>
                    
                            {{-- Delete --}}
                            <form action="{{ route('berita-acara.destroy', $ba->id) }}"
                                  method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm"
                                        onclick="return confirm('Hapus Berita Acara ini?')"
                                        title="Hapus">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                    
                            {{-- PDF --}}
                            @if ($ba->file_pdf)
                                <a href="{{ route('berita-acara.pdf-upload', $ba->id) }}"
                                   target="_blank"
                                   class="btn btn-success btn-sm"
                                   title="Lihat PDF">
                                    <i class="fa fa-file-pdf-o"></i>
                                </a>
                            @else
                                <form action="{{ route('berita-acara.upload-pdf', $ba->id) }}"
                                      method="POST"
                                      enctype="multipart/form-data">
                                    @csrf
                                    <input type="file"
                                           id="baUpload{{ $ba->id }}"
                                           name="file_pdf"
                                           accept="application/pdf"
                                           class="ba-upload-input"
                                           onchange="this.form.submit()">
                    
                                    <button type="button"
                                            class="btn btn-sm ba-btn-upload"
                                            title="Upload PDF"
                                            onclick="document.getElementById('baUpload{{ $ba->id }}').click()">
                                        <i class="fa fa-upload"></i>
                                    </button>
                                </form>
                            @endif
                    
                        @endif
                    </div>

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">
                            Belum ada Berita Acara
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>
</div>

@endsection
