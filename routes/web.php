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

        Route::get('/chart-data', [AdminDashboardController::class, 'getChartData'])
            ->name('chart.data');

        Route::get('/history', [AdminDashboardController::class, 'getHistory'])
            ->name('history.data');

        Route::get('/transaksi/{id}', [AdminDashboardController::class, 'getTransactionDetail'])
            ->name('transaksi.detail');

        Route::get('/dashboard/stats', [AdminDashboardController::class, 'getStats'])
            ->name('dashboard.stats');
    
        // Resource Routes
        Route::get('/kategori/list', [KategoriController::class, 'getList'])
            ->name('kategori.list');

        Route::resource('/kategori', KategoriController::class)
            ->except(['show']);

        Route::resource('/barang', BarangController::class)
            ->except(['show']);

        Route::resource('/kasir', \App\Http\Controllers\Admin\AdminKasirController::class)
            ->except(['create', 'edit', 'show']);

        Route::get('/barang/items', [BarangController::class, 'getItems'])
            ->name('barang.items');

        Route::get('/barang/{barang}/stok', [BarangController::class, 'stok'])
            ->name('barang.stok');

        Route::put('/barang/{barang}/stok', [BarangController::class, 'updateStok'])
            ->name('barang.updateStok');

        Route::get('/barang/{barang}/barcode', [BarangController::class, 'printBarcode'])
            ->name('barang.printBarcode');

        Route::get('/laporan', [LaporanController::class, 'index'])
            ->name('laporan.index');
        Route::get('/laporan/export', [LaporanController::class, 'export'])
            ->name('laporan.export');
        Route::get('/laporan/export-pdf', [LaporanController::class, 'exportPdf'])
            ->name('laporan.export_pdf');

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

        Route::get('/scan', [KasirDashboardController::class, 'scan'])
            ->name('scan');

        Route::post('/transaksi', [KasirTransaksiController::class, 'store'])
            ->name('transaksi.store');

        Route::get('/transaksi/{id}', [KasirDashboardController::class, 'getTransactionDetail'])
            ->name('transaksi.detail');
    });

