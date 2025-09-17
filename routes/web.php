<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\MaterialMasukController;

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

// Test route tanpa auth untuk debugging
Route::get('/debug-material', function() {
    try {
        $materials = \App\Models\Material::latest()->take(5)->get();
        return response()->json([
            'success' => true,
            'total_materials' => \App\Models\Material::count(),
            'latest_materials' => $materials->map(function($material) {
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
    Route::get('/dashboard/{id}', [DashboardController::class, 'show'])->name('dashboard.show');
    Route::delete('/dashboard/{id}', [DashboardController::class, 'destroy'])->name('dashboard.destroy');
    
    // Test route untuk verifikasi material
    Route::get('/test-material', function() {
        $materials = \App\Models\Material::latest()->take(5)->get();
        return response()->json([
            'success' => true,
            'total_materials' => \App\Models\Material::count(),
            'latest_materials' => $materials->map(function($material) {
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
        Route::get('/', [MaterialController::class, 'index'])->name('index');
        Route::get('/create', [MaterialController::class, 'create'])->name('create');
        Route::post('/', [MaterialController::class, 'store'])->name('store');
        
        // DataTables AJAX endpoint
        Route::get('/data/ajax', [MaterialController::class, 'getDataForDataTables'])->name('data.ajax');
        
        // Autocomplete dan Search - Available for all authenticated users
        Route::get('/autocomplete', [MaterialController::class, 'autocomplete'])->name('autocomplete');
        Route::get('/search', [MaterialController::class, 'search'])->name('search');
        
        // Stock Opname (Admin only) - HARUS SEBELUM ROUTE RESOURCE
        Route::middleware('role:admin')->group(function () {
            Route::get('/stock-opname', [MaterialController::class, 'stockOpname'])->name('stock-opname');
            Route::get('/stock-opname/data', [MaterialController::class, 'getStockOpnameData'])->name('stock-opname.data');
            Route::post('/stock-opname', [MaterialController::class, 'processStockOpname'])->name('stock-opname.process');
            Route::post('/stock-opname/store', [MaterialController::class, 'storeStockOpname'])->name('stock-opname.store');
        });
        
        // Input Material Masuk
        Route::get('/input-masuk', [MaterialController::class, 'inputMaterialMasuk'])->name('input-masuk');
        
        // Surat Jalan
        Route::get('/surat-jalan', [MaterialController::class, 'suratJalan'])->name('surat-jalan');
        Route::get('/surat-jalan/data', [MaterialController::class, 'getSuratJalanData'])->name('surat-jalan.data');
        Route::get('/surat-jalan/create', [MaterialController::class, 'createSuratJalan'])->name('surat-jalan.create');
        
        // Approval Surat Jalan - HARUS SEBELUM ROUTE DENGAN PARAMETER
        Route::get('/surat-jalan/approval', [MaterialController::class, 'approvalSuratJalan'])->name('surat-jalan.approval');
        Route::get('/surat-jalan/approval-data', [MaterialController::class, 'getApprovalData'])->name('surat-jalan.approval-data');
        
        Route::post('/surat-jalan', [MaterialController::class, 'storeSuratJalan'])->name('surat-jalan.store');
        Route::get('/surat-jalan/{suratJalan}', [MaterialController::class, 'showSuratJalan'])->name('surat-jalan.show');
        Route::get('/surat-jalan/{suratJalan}/modal-detail', [MaterialController::class, 'getModalDetail'])->name('surat-jalan.modal-detail');
        Route::get('/surat-jalan/{suratJalan}/edit', [MaterialController::class, 'editSuratJalan'])->name('surat-jalan.edit');
        Route::put('/surat-jalan/{suratJalan}', [MaterialController::class, 'updateSuratJalan'])->name('surat-jalan.update');
        Route::delete('/surat-jalan/{suratJalan}', [MaterialController::class, 'destroySuratJalan'])->name('surat-jalan.destroy');
        Route::post('/surat-jalan/{suratJalan}/approve', [MaterialController::class, 'approveSuratJalan'])->name('surat-jalan.approve');
        Route::get('/surat-jalan/{suratJalan}/export', [MaterialController::class, 'exportSuratJalan'])->name('surat-jalan.export');
        Route::get('/surat-jalan/{suratJalan}/export-excel', [MaterialController::class, 'exportSuratJalanExcel'])->name('surat-jalan.export-excel');
        
        // Resource routes - HARUS DI AKHIR
        Route::get('/{material}', [MaterialController::class, 'show'])->name('show');
        Route::get('/{material}/edit', [MaterialController::class, 'edit'])->name('edit');
        Route::put('/{material}', [MaterialController::class, 'update'])->name('update');
        Route::delete('/{material}', [MaterialController::class, 'destroy'])->name('destroy');
    });
    
    // Material Masuk Routes
    Route::prefix('material-masuk')->name('material-masuk.')->group(function () {
        Route::get('/', [MaterialMasukController::class, 'index'])->name('index');
        Route::get('/data', [MaterialMasukController::class, 'getData'])->name('data');
        Route::get('/create', [MaterialMasukController::class, 'create'])->name('create');
        Route::post('/', [MaterialMasukController::class, 'store'])->name('store');
        Route::get('/{materialMasuk}/edit', [MaterialMasukController::class, 'edit'])->name('edit');
        Route::put('/{materialMasuk}', [MaterialMasukController::class, 'update'])->name('update');
        Route::delete('/{materialMasuk}', [MaterialMasukController::class, 'destroy'])->name('destroy');
        
        // Autocomplete routes
        Route::get('/autocomplete/material', [MaterialMasukController::class, 'autocompleteMaterial'])->name('autocomplete.material');
        Route::get('/autocomplete/normalisasi', [MaterialMasukController::class, 'autocompleteNormalisasi'])->name('autocomplete.normalisasi');
    });
    
    // Stock Opname Routes
    Route::prefix('stock-opname')->name('stock-opname.')->group(function () {
        Route::get('/', [MaterialController::class, 'stockOpname'])->name('index');
        Route::get('/data', [MaterialController::class, 'getStockOpnameData'])->name('data');
        Route::post('/', [MaterialController::class, 'processStockOpname'])->name('process');
        Route::post('/store', [MaterialController::class, 'storeStockOpname'])->name('store');
    });
    
    // Surat Jalan Routes (Template Kosong) - Controller belum dibuat
    // Route::prefix('surat-jalan')->name('surat-jalan.')->group(function () {
    //     Route::get('/', [SuratJalanController::class, 'index'])->name('index');
    //     Route::get('/create', [SuratJalanController::class, 'create'])->name('create');
    //     Route::post('/', [SuratJalanController::class, 'store'])->name('store');
    //     Route::get('/{suratJalan}', [SuratJalanController::class, 'show'])->name('show');
        //     Route::get('/{suratJalan}/edit', [SuratJalanController::class, 'edit'])->name('edit');
     //     Route::put('/{suratJalan}', [SuratJalanController::class, 'update'])->name('update');
     //     Route::delete('/{suratJalan}', [SuratJalanController::class, 'destroy'])->name('destroy');
     // });
    
    // Admin Only Routes
    Route::middleware(['role:admin'])->group(function () {
        // User Management (akan dikembangkan kemudian)
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', function() { return view('users.index'); })->name('index');
        });
        
        // Settings (akan dikembangkan kemudian)
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', function() { return view('settings.index'); })->name('index');
        });
    });
});

// API Routes untuk AJAX calls
Route::prefix('api')->middleware(['auth'])->group(function () {
    Route::get('/materials/search', [MaterialController::class, 'search'])->name('api.materials.search');
    Route::get('/dashboard/stats', [DashboardController::class, 'getStats'])->name('api.dashboard.stats');
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