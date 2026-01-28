@extends('layouts.app')

@section('title', 'Material MRWI Stock')

@section('content')
    @push('styles')
        <style>
            .mrwi-tab {
                padding: 8px 14px;
                border-radius: 9999px;
                font-weight: 600;
                transition: all 0.2s;
            }

            .mrwi-tab.active {
                background: #14b8a6;
                color: #fff;
                box-shadow: 0 6px 20px rgba(20, 184, 166, 0.25);
            }
        </style>
    @endpush
    <div class="space-y-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Material MRWI</h2>
                <p class="text-gray-500 text-sm mt-1">Stok material bekas berdasarkan kategori.</p>
            </div>
        </div>

        <livewire:material-mrwi-stock-table :category="$category" />
    </div>
@endsection
