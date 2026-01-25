@extends('layouts.app')

@section('title', 'Data Material')
@section('breadcrumb', 'Data Material')

@push('styles')
<style>
    /* Page Header */
    .material-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }

    .material-header-title h1 {
        font-size: 24px;
        font-weight: 700;
        color: #2D3748;
        margin: 0 0 4px 0;
    }

    .material-header-title p {
        font-size: 13px;
        color: #718096;
        margin: 0;
    }

    .material-header-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    /* Action Buttons */
    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 10px 16px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 500;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
    }

    .btn-action:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .btn-action.default { background: #EDF2F7; color: #4A5568; }
    .btn-action.default:hover { background: #E2E8F0; color: #2D3748; }
    .btn-action.primary { background: linear-gradient(126.97deg, #4FD1C5 28.26%, #38B2AC 91.2%); color: white; }
    .btn-action.success { background: linear-gradient(126.97deg, #68D391 28.26%, #48BB78 91.2%); color: white; }
    .btn-action.warning { background: linear-gradient(126.97deg, #F6AD55 28.26%, #ED8936 91.2%); color: white; }
    .btn-action.info { background: linear-gradient(126.97deg, #63B3ED 28.26%, #4299E1 91.2%); color: white; }
    .btn-action.danger { background: linear-gradient(126.97deg, #FC8181 28.26%, #F56565 91.2%); color: white; }

    /* Panel Card */
    .panel-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 3.5px 5.5px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .panel-header {
        padding: 16px 20px;
        border-bottom: 1px solid #E2E8F0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
    }

    .panel-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
        font-weight: 600;
        color: #2D3748;
        margin: 0;
    }

    .panel-title i { color: #4FD1C5; }

    .panel-controls {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    /* Search Input */
    .search-box {
        display: flex;
        align-items: center;
        background: #F7FAFC;
        border: 1px solid #E2E8F0;
        border-radius: 10px;
        padding: 0 12px;
        transition: all 0.2s;
    }

    .search-box:focus-within {
        border-color: #4FD1C5;
        box-shadow: 0 0 0 3px rgba(79, 209, 197, 0.1);
    }

    .search-box input {
        border: none;
        background: transparent;
        padding: 10px 8px;
        font-size: 13px;
        width: 200px;
        outline: none;
    }

    .search-box button {
        background: none;
        border: none;
        color: #4FD1C5;
        cursor: pointer;
        padding: 4px;
    }

    /* Per Page Select */
    .per-page-select {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: #718096;
    }

    .per-page-select select {
        padding: 8px 12px;
        border: 1px solid #E2E8F0;
        border-radius: 8px;
        font-size: 13px;
        background: white;
        cursor: pointer;
    }

    /* Table */
    .panel-body { padding: 0; }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table thead th {
        background: #F7FAFC;
        padding: 14px 16px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        color: #718096;
        text-align: left;
        border-bottom: 1px solid #E2E8F0;
    }

    .data-table thead th:hover { color: #4FD1C5; }

    .data-table tbody td {
        padding: 14px 16px;
        font-size: 13px;
        color: #4A5568;
        border-bottom: 1px solid #EDF2F7;
        vertical-align: middle;
    }

    .data-table tbody tr:hover { background: #F7FAFC; }
    .data-table tbody tr:last-child td { border-bottom: none; }

    /* Stock Badge */
    .stock-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        background: #BEE3F8;
        color: #2A4365;
    }

    /* Action Buttons in Table */
    .action-buttons {
        display: flex;
        gap: 6px;
        justify-content: center;
    }

    .action-btn {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-size: 12px;
        transition: all 0.2s;
    }

    .action-btn:hover { transform: translateY(-1px); }
    .action-btn.view { background: #BEE3F8; color: #2B6CB0; }
    .action-btn.edit { background: #FEFCBF; color: #744210; }
    .action-btn.barcode { background: #C6F6D5; color: #22543D; }
    .action-btn.delete { background: #FED7D7; color: #742A2A; }

    /* Pagination */
    .pagination-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 20px;
        border-top: 1px solid #E2E8F0;
        flex-wrap: wrap;
        gap: 12px;
    }

    .pagination-info { font-size: 13px; color: #718096; }

    .pagination {
        display: flex;
        gap: 4px;
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .pagination li { margin: 0; }

    .pagination li a,
    .pagination li span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 32px;
        height: 32px;
        padding: 0 10px;
        border-radius: 8px;
        font-size: 13px;
        text-decoration: none;
        background: #EDF2F7;
        color: #4A5568;
        border: none;
    }

    .pagination li a:hover { background: #4FD1C5; color: white; }
    .pagination li.active span { background: #4FD1C5; color: white; }
    .pagination li.disabled span { opacity: 0.5; }

    /* Loading Overlay */
    .loading-overlay {
        position: absolute;
        inset: 0;
        background: rgba(255, 255, 255, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10;
    }

    .loading-spinner {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #4FD1C5;
        font-size: 14px;
    }

    .opacity-50 { opacity: 0.5; }

    /* Modal Overlay */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }

    .modal-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    .modal-container {
        background: white;
        border-radius: 16px;
        width: 90%;
        max-width: 480px;
        max-height: 90vh;
        overflow: hidden;
        transform: scale(0.9) translateY(-20px);
        transition: all 0.3s ease;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }

    .modal-overlay.active .modal-container {
        transform: scale(1) translateY(0);
    }

    .modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px 24px;
        border-bottom: 1px solid #E2E8F0;
    }

    .modal-header h3 {
        font-size: 16px;
        font-weight: 600;
        color: #2D3748;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .modal-close {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        border: none;
        background: #EDF2F7;
        color: #718096;
        cursor: pointer;
        transition: all 0.2s;
    }

    .modal-close:hover { background: #E2E8F0; color: #4A5568; }

    .modal-body { padding: 24px; }

    .modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        padding: 16px 24px;
        border-top: 1px solid #E2E8F0;
    }

    .form-group { margin-bottom: 16px; }

    .form-label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #4A5568;
        margin-bottom: 8px;
    }

    .form-input {
        width: 100%;
        padding: 12px 16px;
        border: 2px dashed #E2E8F0;
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.2s;
        cursor: pointer;
    }

    .form-input:focus {
        outline: none;
        border-color: #4FD1C5;
        border-style: solid;
    }

    .form-help {
        font-size: 12px;
        color: #718096;
        margin-top: 8px;
    }

    .alert-purity {
        padding: 16px 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .alert-purity.success { background: #C6F6D5; color: #22543D; }
    .alert-purity.danger { background: #FED7D7; color: #742A2A; }
    .alert-purity.info { background: #BEE3F8; color: #2A4365; }
</style>
@endpush

@section('content')
<!-- Alerts -->
@if(session('success'))
<div class="alert-purity success">
    <i class="fas fa-check-circle"></i>
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="alert-purity danger">
    <i class="fas fa-exclamation-circle"></i>
    {{ session('error') }}
</div>
@endif

<!-- Livewire Material Table Component -->
@livewire('material-table')

<!-- Modal Hapus (Tailwind Component) -->
<x-modal name="deleteModal" title="Konfirmasi Hapus" maxWidth="md">
    <x-slot:icon>
        <i class="fas fa-exclamation-triangle" style="color: #F56565;"></i>
    </x-slot:icon>

    <div style="margin-bottom: 24px;">
        <p style="margin: 0 0 12px 0;">Apakah Anda yakin ingin menghapus material <strong id="materialName"></strong>?</p>
        <p style="color: #F56565; font-size: 13px; margin: 0;">
            <i class="fas fa-info-circle"></i> Data yang sudah dihapus tidak dapat dikembalikan.
        </p>
    </div>

    <x-slot:footer>
        <button type="button" class="btn-action default" x-on:click="show = false">Batal</button>
        <form id="deleteForm" method="POST" style="margin: 0;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-action danger">
                <i class="fas fa-trash"></i> Hapus
            </button>
        </form>
    </x-slot:footer>
</x-modal>

<!-- Modal Import Excel (Tailwind Component) -->
<x-modal name="importModal" title="Import Data Material via Excel">
    <x-slot:icon>
        <i class="fas fa-upload" style="color: #4FD1C5;"></i>
    </x-slot:icon>

    <form id="importMaterialForm" action="{{ route('material.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label class="form-label">Pilih File Excel (.xlsx, .xls, .csv)</label>
            <input type="file" name="file" id="file" class="form-input" required accept=".xlsx,.xls,.csv">
            <p class="form-help">Pastikan format header sesuai: material_code, material_description, dll.</p>
        </div>

        <div style="margin-top: 24px; text-align: right;">
            <button type="button" class="btn-action default" x-on:click="show = false">Batal</button>
            <button type="submit" class="btn-action primary">
                <i class="fas fa-upload"></i> Upload & Import
            </button>
        </div>
    </form>
</x-modal>
@endsection

@push('scripts')
<script>
function confirmDelete(materialId, materialName) {
    document.getElementById('materialName').textContent = materialName;
    document.getElementById('deleteForm').action = '/material/' + materialId;
    window.dispatchEvent(new CustomEvent('open-modal', { detail: 'deleteModal' }));
}

document.getElementById('importMaterialForm')?.addEventListener('submit', function (event) {
    event.preventDefault();
    const form = event.currentTarget;
    const formData = new FormData(form);

    Swal.fire({
        title: 'Mengimpor data...',
        text: 'Mohon tunggu, proses sedang berjalan.',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(async (response) => {
        const data = await response.json().catch(() => ({}));
        if (!response.ok) {
            const message = data.message || 'Gagal mengimpor data.';
            throw new Error(message);
        }
        return data;
    })
    .then((data) => {
        const details = data.details || {};
        const successCount = details.success_count ?? 0;
        const errorCount = details.error_count ?? 0;
        const errors = Array.isArray(details.errors) ? details.errors : [];

        const errorPreview = errors.slice(0, 6).map((err) => {
            const code = err.material_code || '-';
            const desc = err.material_description || '-';
            const msg = err.error || 'Gagal diimpor';
            return `<li><strong>${code}</strong> — ${desc}<br><span style="color:#9B2C2C;">${msg}</span></li>`;
        }).join('');

        const html = `
            <div style="text-align:left;">
                <p><strong>${successCount}</strong> data berhasil diimpor.</p>
                <p><strong>${errorCount}</strong> data gagal diimpor.</p>
                ${errorCount > 0 ? `
                    <details style="margin-top:12px;">
                        <summary style="cursor:pointer;">Lihat sebagian error</summary>
                        <ul style="margin:10px 0 0 18px; padding:0; list-style:disc;">
                            ${errorPreview}
                        </ul>
                        ${errors.length > 6 ? '<div style="margin-top:8px;color:#718096;">...dan error lainnya</div>' : ''}
                    </details>
                ` : ''}
            </div>
        `;

        Swal.fire({
            icon: errorCount > 0 ? 'warning' : 'success',
            title: errorCount > 0 ? 'Import selesai dengan catatan' : 'Import berhasil',
            html,
            confirmButtonText: 'OK'
        });

        window.dispatchEvent(new CustomEvent('close-modal', { detail: 'importModal' }));
        form.reset();
    })
    .catch((error) => {
        Swal.fire({
            icon: 'error',
            title: 'Import gagal',
            text: error.message || 'Terjadi kesalahan saat impor.',
            confirmButtonText: 'OK'
        });
    });
});
</script>
@endpush
