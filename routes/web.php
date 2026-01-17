<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\BarangController;
use App\Http\Controllers\Kasir\KasirDashboardController;
use App\Http\Controllers\Kasir\KasirTransaksiController;
use App\Http\Controllers\Admin\LaporanController;



/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('landing');
})->name('landing');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::post('/login', [AuthController::class, 'login'])
    ->name('login.process');

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource('/kategori', KategoriController::class)
            ->except(['show']);

        Route::resource('/barang', BarangController::class)
            ->except(['show']);

        Route::get('/barang/{barang}/stok', [BarangController::class, 'stok'])
            ->name('barang.stok');

        Route::put('/barang/{barang}/stok', [BarangController::class, 'updateStok'])
            ->name('barang.updateStok');

        Route::get('/laporan', [LaporanController::class, 'index'])
            ->name('laporan.index');
        Route::get('/laporan/export', [LaporanController::class, 'export'])
            ->name('laporan.export');

    });

/*
|--------------------------------------------------------------------------
| Kasir Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:kasir'])
    ->prefix('kasir')
    ->name('kasir.')
    ->group(function () {

        Route::get('/dashboard', [KasirDashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/items', [KasirDashboardController::class, 'getItems'])
            ->name('items.search');

        Route::post('/transaksi', [KasirTransaksiController::class, 'store'])
            ->name('transaksi.store');
    });

