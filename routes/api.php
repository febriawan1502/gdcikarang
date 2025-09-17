<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MaterialController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Material API Routes
Route::prefix('materials')->name('api.materials.')->group(function () {
    Route::get('/', [MaterialController::class, 'apiList'])->name('list');
    Route::get('/by-kode/{kode}', [MaterialController::class, 'getByKode'])->name('by-kode');
});