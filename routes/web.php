<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\MaterialMasukController;
use App\Http\Controllers\MaterialMrwiController;
use App\Http\Controllers\MaterialMrwiHistoryController;
use App\Http\Controllers\MaterialMrwiStockController;
use App\Http\Controllers\SuratJalanController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\MaterialHistoryController;
use App\Http\Controllers\PemeriksaanFisikController;
use App\Http\Controllers\BeritaAcaraController;
use App\Http\Controllers\SapCheckController;
use App\Http\Controllers\SettingController;
use App\Exports\MaterialMasukExport;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Barcode Route (Public Access - No Auth Required)
Route::get('/barcode/{unitId}/{materialCode}', [App\Http\Controllers\BarcodeController::class, 'show'])
    ->name('barcode.show.unit');
Route::get('/barcode/{materialCode}', [App\Http\Controllers\BarcodeController::class, 'showLegacy'])
    ->name('barcode.show');

// Test route tanpa auth untuk debugging
Route::get('/debug-material', function () {
    try {
        $materials = \App\Models\Material::latest()->take(5)->get();
        return response()->json([
            'success' => true,
            'total_materials' => \App\Models\Material::count(),
            'latest_materials' => $materials->map(function ($material) {
                return [
                    'id' => $material->id,
                    'material_code' => $material->material_code,
                    'material_description' => $material->material_description,
                    'rak' => $material->rak ?? 'null',
                    'created_at' => $material->created_at->format('Y-m-d H:i:s')
                ];
            })
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Auth Routes (requires authentication)
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/change-password', [AuthController::class, 'showChangePasswordForm'])->name('auth.change-password');
    Route::post('/change-password', [AuthController::class, 'changePassword']);
});

// Protected Routes (Memerlukan Authentication)
Route::middleware(['auth'])->group(function () {

    // Dashboard routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/stock-opname', [DashboardController::class, 'stockOpname'])->name('dashboard.stock-opname');
    Route::get('/dashboard/data', [DashboardController::class, 'getData'])->name('dashboard.data');
    Route::get('/dashboard/export', [DashboardController::class, 'export'])->name('dashboard.export');
    Route::get('/dashboard/{id}', [DashboardController::class, 'show'])->name('dashboard.show');
    Route::delete('/dashboard/{id}', [DashboardController::class, 'destroy'])->name('dashboard.destroy');
    Route::post('/dashboard/update-material-saving-config', [DashboardController::class, 'updateMaterialSavingConfig'])->name('dashboard.material-saving-config.update');
    Route::post('/dashboard/update-fast-moving-config', [DashboardController::class, 'updateFastMovingConfig'])->name('dashboard.fast-moving-config.update');
    Route::post('/dashboard/saldo-awal/update', [DashboardController::class, 'updateSaldoAwal'])->name('dashboard.saldo-awal.update');

    // Test route untuk verifikasi material
    Route::get('/test-material', function () {
        $materials = \App\Models\Material::latest()->take(5)->get();
        return response()->json([
            'success' => true,
            'total_materials' => \App\Models\Material::count(),
            'latest_materials' => $materials->map(function ($material) {
                return [
                    'id' => $material->id,
                    'material_code' => $material->material_code,
                    'material_description' => $material->material_description,
                    'rak' => $material->rak,
                    'created_at' => $material->created_at->format('Y-m-d H:i:s')
                ];
            })
        ]);
    })->name('test.material');

    // Master Material Routes
    Route::prefix('material')->name('material.')->group(function () {
        // 📌 Export & History (pindah ke atas agar tidak tertangkap sebagai {material})
        Route::get('/history/export/{id?}', [MaterialHistoryController::class, 'export'])->name('history.export');
        Route::get('/history/export-pdf/{id}', [MaterialHistoryController::class, 'exportPdf'])->name('history.export-pdf');
        Route::get('/history/{id?}', [MaterialHistoryController::class, 'index'])->name('history');

        // ✅ Tambahkan route PDF kartu gantung jika perlu
        Route::get('/{id}/kartu-gantung/pdf', [MaterialHistoryController::class, 'exportKartuGantung'])->name('kartu-gantung.pdf');

        // 📦 Material CRUD & utility
        Route::get('/', [MaterialController::class, 'index'])->name('index');
        Route::get('/create', [MaterialController::class, 'create'])->name('create');
        Route::post('/', [MaterialController::class, 'store'])->name('store');
        Route::post('/import', [MaterialController::class, 'import'])->name('import');

        Route::get('/data/ajax', [MaterialController::class, 'getDataForDataTables'])->name('data.ajax');
        Route::get('/autocomplete', [MaterialController::class, 'autocomplete'])->name('autocomplete');
        Route::get('/search', [MaterialController::class, 'search'])->name('search');

        // 🛠️ Admin-only stock opname
        Route::middleware('role:admin')->group(function () {
            Route::get('/stock-opname', [MaterialController::class, 'stockOpname'])->name('stock-opname');
            Route::get('/stock-opname/data', [MaterialController::class, 'getStockOpnameData'])->name('stock-opname.data');
            Route::post('/stock-opname', [MaterialController::class, 'processStockOpname'])->name('stock-opname.process');
            Route::post('/stock-opname/store', [MaterialController::class, 'storeStockOpname'])->name('stock-opname.store');
        });

        // ➕ Input material masuk
        Route::get('/input-masuk', [MaterialController::class, 'inputMaterialMasuk'])->name('input-masuk');

        // 🏷️ Bulk Print All Barcodes
        Route::get('/bulk-print-barcode', [MaterialController::class, 'bulkPrintBarcode'])->name('bulk-print-barcode');

        // 🏷️ Generate Barcode
        Route::get('/{material}/generate-barcode', [MaterialController::class, 'generateBarcode'])
            ->where('material', '[0-9]+')
            ->name('generate-barcode');

        // 🚨 HARUS PALING BAWAH! Agar tidak menangkap /history/export dsb.
        Route::get('/{material}', [MaterialController::class, 'show'])
            ->where('material', '[0-9]+')
            ->name('show');

        Route::get('/{material}/edit', [MaterialController::class, 'edit'])
            ->where('material', '[0-9]+')
            ->name('edit');

        Route::put('/{material}', [MaterialController::class, 'update'])
            ->where('material', '[0-9]+')
            ->name('update');

        Route::delete('/{material}', [MaterialController::class, 'destroy'])
            ->where('material', '[0-9]+')
            ->name('destroy');
    });


    Route::prefix('material-masuk')->group(function () {

        // List & Data
        Route::get('/material-masuk/print', [MaterialMasukController::class, 'print'])
            ->name('material-masuk.print');
        Route::get('/', [MaterialMasukController::class, 'index'])->name('material-masuk.index');
        Route::get('/material-masuk/export-excel', function () {
            return Excel::download(
                new MaterialMasukExport,
                'material-masuk.xlsx'
            );
        })->name('material-masuk.export-excel');
        Route::get('/data', [MaterialMasukController::class, 'getData'])->name('material-masuk.data');
        Route::get('/daftar-sap', [MaterialMasukController::class, 'daftarSAP'])->name('material-masuk.daftar-sap');

        // Create & Store
        Route::get('/create', [MaterialMasukController::class, 'create'])->name('material-masuk.create');
        Route::post('/', [MaterialMasukController::class, 'store'])->name('material-masuk.store');

        // Edit & Update
        Route::get('/{id}/edit', [MaterialMasukController::class, 'edit'])->name('material-masuk.edit');
        Route::put('/{id}', [MaterialMasukController::class, 'update'])->name('material-masuk.update');

        // ✅ Tambahkan di sini (SEBELUM show)
        Route::put('/{id}/update-selesai', [MaterialMasukController::class, 'updateDanSelesaiSAP'])
            ->name('material-masuk.updateDanSelesaiSAP');

        // Selesai SAP (versi POST lama, bisa dipakai untuk API/manual trigger)
        // Route::post('/selesai-sap/{id}', [MaterialMasukController::class, 'selesaiSAP'])->name('material-masuk.selesai-sap');

        // Show & Delete (harus paling bawah)
        Route::get('/{id}', [MaterialMasukController::class, 'show'])->name('material-masuk.show');
        Route::delete('/{id}', [MaterialMasukController::class, 'destroy'])->name('material-masuk.destroy');

        // Autocomplete
        Route::get('/autocomplete/material', [MaterialMasukController::class, 'autocompleteMaterial'])->name('material-masuk.autocomplete.material');
        Route::get('/autocomplete/normalisasi', [MaterialMasukController::class, 'autocompleteNormalisasi'])->name('material-masuk.autocomplete.normalisasi');
    });

    Route::prefix('material-mrwi')->name('material-mrwi.')->group(function () {
        Route::get('/', [MaterialMrwiController::class, 'index'])->name('index');
        Route::get('/data', [MaterialMrwiController::class, 'getData'])->name('data');
        Route::get('/create', [MaterialMrwiController::class, 'create'])->name('create');
        Route::post('/preview', [MaterialMrwiController::class, 'preview'])->name('preview');
        Route::post('/', [MaterialMrwiController::class, 'store'])->name('store');
        Route::get('/history', [MaterialMrwiHistoryController::class, 'index'])->name('history');
        Route::get('/serials', [MaterialMrwiController::class, 'getAvailableSerials'])->name('serials');
        Route::get('/serial-lookup', [MaterialMrwiController::class, 'lookupSerial'])->name('serial-lookup');
        Route::get('/stock/{category?}', [MaterialMrwiStockController::class, 'index'])->name('stock');
        Route::get('/stock/{category}/export', [MaterialMrwiStockController::class, 'export'])->name('stock.export');
        Route::get('/stock/{category}/data', [MaterialMrwiStockController::class, 'getData'])->name('stock.data');
        Route::get('/{id}', [MaterialMrwiController::class, 'show'])->name('show');
    });


    // Stock Opname Routes
    Route::prefix('stock-opname')->name('stock-opname.')->group(function () {
        Route::get('/', [MaterialController::class, 'stockOpname'])->name('index');
        Route::get('/data', [MaterialController::class, 'getStockOpnameData'])->name('data');
        Route::post('/', [MaterialController::class, 'processStockOpname'])->name('process');
        Route::post('/store', [MaterialController::class, 'storeStockOpname'])->name('store');
    });

    // Surat Jalan Routes
    Route::get('/surat-jalan/data', [SuratJalanController::class, 'getData'])->name('surat-jalan.getData');
    Route::prefix('surat-jalan')->name('surat-jalan.')->group(function () {
        Route::post(
            '/submit-checked',
            [SuratJalanController::class, 'submitChecked']
        )->name('submit-checked');

        Route::get('/', [SuratJalanController::class, 'index'])->name('index');
        Route::get('/create', [SuratJalanController::class, 'create'])->name('create');
        Route::post('/', [SuratJalanController::class, 'store'])->name('surat-jalan.store');
        Route::post('/', [SuratJalanController::class, 'store'])->name('store');
        Route::get('/generate-nomor', [SuratJalanController::class, 'generateNomor'])->name('generate-nomor');
        Route::put('/{suratJalan}/selesai', [SuratJalanController::class, 'markAsSelesai'])->name('selesai');


        // ✅ Route statis approval HARUS di atas parameter dinamis
        Route::get('/approval', [SuratJalanController::class, 'approval'])->name('approval');
        Route::get('/approval-data', [SuratJalanController::class, 'getApprovalData'])->name('approval-data');
        // Route::patch('{suratJalan}/approve', [SuratJalanController::class, 'approve'])->name('surat-jalan.approval');


        // ✅ Edit, Update, Delete
        Route::get('/{suratJalan}/edit', [SuratJalanController::class, 'edit'])->name('edit');
        Route::put('/{suratJalan}', [SuratJalanController::class, 'update'])->name('update');
        Route::delete('/{suratJalan}', [SuratJalanController::class, 'destroy'])->name('destroy');

        // ✅ Modal detail DITARUH DI ATAS show agar tidak ditangkap show()
        Route::get('/{suratJalan}/modal-detail', [SuratJalanController::class, 'getModalDetail'])->name('modal-detail');

        // ✅ Show detail (halaman biasa)
        Route::get('/{suratJalan}', [SuratJalanController::class, 'show'])->name('show');

        // ✅ Approve & Export
        Route::post('/{suratJalan}/approve', [SuratJalanController::class, 'approve'])->name('approve');
        Route::get('/{suratJalan}/export', [SuratJalanController::class, 'export'])->name('export');
        Route::get('/{suratJalan}/export-excel', [SuratJalanController::class, 'exportExcel'])->name('export-excel');

        // ✅ Update Kendaraan (Security Only)
        Route::put('/{suratJalan}/update-kendaraan', [SuratJalanController::class, 'updateKendaraan'])->name('update-kendaraan');
    });


    // Admin Only Routes
    Route::middleware(['role:admin'])->group(function () {
        // User Management (akan dikembangkan kemudian)
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', function () {
                return view('users.index');
            })->name('index');
        });

        // Settings (akan dikembangkan kemudian)
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', function () {
                return view('settings.index');
            })->name('index');
        });

        // Cek Kesesuaian SAP
        Route::get('/sap-check', [SapCheckController::class, 'index'])->name('sap-check.index');
        Route::post('/sap-check', [SapCheckController::class, 'store'])->name('sap-check.store');
    });
});

// API Routes untuk AJAX calls
Route::prefix('api')->group(function () {
    Route::get('/materials/search', [MaterialController::class, 'search'])->name('api.materials.search');
    Route::get('/dashboard/stats', [DashboardController::class, 'getStats'])->middleware('auth')->name('api.dashboard.stats');
    Route::get('/material/{id}', [MaterialController::class, 'getMaterialById'])->middleware('auth')->name('api.material.detail');
});

// Health Check
Route::get('/health', function () {
    return response()->json([
        'status' => 'OK',
        'timestamp' => now(),
        'app' => config('app.name')
    ]);
})->name('health');

// Fallback route
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});

Route::get('/surat-jalan/{jenis}/masa', [SuratJalanController::class, 'masa'])->name('surat.masa');
Route::put('/surat-jalan/{surat}/kembalikan/{detail}', [SuratJalanController::class, 'kembalikan'])
    ->name('barang.kembalikan');
Route::delete('/surat-jalan/masa/{surat}/{detail}', [SuratJalanController::class, 'hapusDetailMasa'])
    ->name('masa.detail.hapus');

// Route::get('/surat-jalan/masa', [SuratJalanController::class, 'masa'])->name('surat.masa');
// Route::get('/materials/{id}/history', [MaterialController::class, 'history'])->name('material.history');
// Route::get('/materials/{id}/history/export', [MaterialHistoryController::class, 'export'])
//     ->name('material.history.export');
Route::put('/surat-jalan/{suratId}/kembalikan/{detailId}', [SuratJalanController::class, 'kembalikan'])->name('surat-jalan.kembalikan');
Route::get('/surat-jalan/{suratId}/detail/{detailId}/kembalikan', [SuratJalanController::class, 'showReturnForm'])
    ->name('surat-jalan.kembalikan.form');
Route::get('/surat-jalan/{detail}/history', [App\Http\Controllers\SuratJalanController::class, 'getHistory'])
    ->name('surat-jalan.history');
Route::delete('/surat-masa/{surat}/{detail}', [SuratJalanController::class, 'hapusDetailMasa'])
    ->name('surat.masa.hapus-detail');


// Route::get('/material/{id}/history', [MaterialHistoryController::class, 'index'])
//     ->name('material.history');
// Route::get('/materials/history', [MaterialHistoryController::class, 'all'])
//     ->name('materials.history');
// Route::get('/material/history/{id?}', [MaterialHistoryController::class, 'index'])->name('material.history');
// Route::get('/material/history/export/{id?}', [MaterialHistoryController::class, 'export'])->name('material.history.export');

// Route::get('/material/{id}/history', [MaterialHistoryController::class, 'index'])
//     ->name('material.history');
Route::get('/autocomplete-material', [MaterialMasukController::class, 'autocomplete'])->name('material.autocomplete');
Route::get('/materials/{id}/history/export-pdf', [MaterialHistoryController::class, 'exportPdf']);
Route::get('/material/{id}/kartu-gantung/pdf', [MaterialHistoryController::class, 'exportKartuGantung'])
    ->name('material.kartu-gantung.pdf');


Route::middleware(['auth'])->group(function () {
    Route::prefix('laporan')->group(function () {
        Route::get('/pemeriksaan-fisik', [PemeriksaanFisikController::class, 'index'])
            ->name('material.pemeriksaanFisik');
    });
});


Route::get('/berita-acara', [BeritaAcaraController::class, 'index'])
    ->name('berita-acara.index');
Route::post('/berita-acara', [BeritaAcaraController::class, 'store'])
    ->name('berita-acara.store');
Route::resource('berita-acara', BeritaAcaraController::class);
Route::get(
    '/berita-acara/{id}/pdf',
    [BeritaAcaraController::class, 'pdf']
)->name('berita-acara.pdf');
Route::post(
    '/berita-acara/{id}/upload-pdf',
    [BeritaAcaraController::class, 'uploadPdf']
)->name('berita-acara.upload-pdf');
Route::get(
    '/berita-acara/{id}/pdf-upload',
    [BeritaAcaraController::class, 'viewUploadedPdf']
)->name('berita-acara.pdf-upload');


Route::get('/material/pemeriksaan-fisik', [MaterialController::class, 'pemeriksaanFisik'])
    ->name('material.pemeriksaanFisik');
Route::post(
    '/material/pemeriksaan-fisik/store',
    [MaterialController::class, 'storePemeriksaanFisik']
)->name('material.pemeriksaanFisik.store');
Route::get(
    '/material/pemeriksaan-fisik/pdf',
    [MaterialController::class, 'pemeriksaanFisikPdf']
)->name('material.pemeriksaanFisikPdf');
Route::post(
    '/pemeriksaan-fisik/import-sap',
    [MaterialController::class, 'importSap']
)->name('material.importSap');

Route::post(
    '/pemeriksaan-fisik/import-mims',
    [MaterialController::class, 'importMims']
)->name('material.importMims');


// ⚙️ Settings Routes (Admin Only)
Route::middleware(['auth', 'role:admin'])->prefix('settings')->name('settings.')->group(function () {
    Route::get('/', [SettingController::class, 'index'])->name('index');
    Route::post('/company', [SettingController::class, 'updateCompany'])->name('company.update');

    // User Management
    Route::post('/users', [SettingController::class, 'storeUser'])->name('users.store');
    Route::put('/users/{id}', [SettingController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{id}', [SettingController::class, 'deleteUser'])->name('users.delete');
});
