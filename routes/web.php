<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\BarangController;
use App\Http\Controllers\Kasir\KasirDashboardController;
use App\Http\Controllers\Kasir\KasirTransaksiController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Supervisor\SupervisorDashboardController;
use App\Http\Controllers\Supervisor\DiskonController;



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
Route::middleware(['auth', 'role:admin,supervisor'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

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
            ->except(['create', 'edit', 'show'])
            ->middleware('role:admin');

        Route::get('/barang/items', [BarangController::class, 'getItems'])
            ->name('barang.items');

        Route::get('/barang/export', [BarangController::class, 'export'])
            ->name('barang.export');
        
        Route::post('/barang/import', [BarangController::class, 'import'])
            ->name('barang.import');

        Route::get('/barang/{barang}/stok', [BarangController::class, 'stok'])
            ->name('barang.stok');

        Route::put('/barang/{barang}/stok', [BarangController::class, 'updateStok'])
            ->name('barang.updateStok');

        Route::get('/barang/{barang}/barcode', [BarangController::class, 'printBarcode'])
            ->name('barang.printBarcode');
        
        // Stok Opname Routes
        Route::resource('/stok-opname', \App\Http\Controllers\Admin\StokOpnameController::class);



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

        Route::get('/discounts', [KasirDashboardController::class, 'getDiscounts'])
            ->name('discounts');
    });

/*
|--------------------------------------------------------------------------
| Supervisor Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:supervisor'])
    ->prefix('supervisor')
    ->name('supervisor.')
    ->group(function () {
        Route::get('/dashboard', [SupervisorDashboardController::class, 'index'])
            ->name('dashboard');
        
        // Analytics & Reports (Moved from Admin)
        Route::get('/laporan', [LaporanController::class, 'index'])
            ->name('laporan.index');
        Route::get('/laporan/export', [LaporanController::class, 'export'])
            ->name('laporan.export');
        Route::get('/laporan/export-pdf', [LaporanController::class, 'exportPdf'])
            ->name('laporan.export_pdf');
            
        // Dashboard Ajax
        Route::get('/dashboard/data', [SupervisorDashboardController::class, 'getDashboardData'])
            ->name('dashboard.data');
        Route::get('/chart-data', [SupervisorDashboardController::class, 'getChartData'])
            ->name('chart.data');
        Route::get('/history', [SupervisorDashboardController::class, 'getHistory'])
            ->name('history.data');
        Route::get('/transaksi/{id}', [SupervisorDashboardController::class, 'getTransactionDetail'])
            ->name('transaksi.detail');

        // New Features
        Route::get('/backup', [SupervisorDashboardController::class, 'backup'])->name('backup');
        Route::post('/backup/download', [SupervisorDashboardController::class, 'downloadBackup'])->name('backup.download');
        Route::resource('/diskon', DiskonController::class);
        Route::get('/audit', [SupervisorDashboardController::class, 'audit'])->name('audit');
        
        // Moved from Admin
        Route::get('/riwayat-stok', [\App\Http\Controllers\Supervisor\RiwayatStokController::class, 'index'])
            ->name('riwayat-stok.index');

        // Stok Opname Approval
        Route::get('/stok-opname', [\App\Http\Controllers\Supervisor\StokOpnameController::class, 'index'])
            ->name('stok-opname.index');
        Route::get('/stok-opname/{id}', [\App\Http\Controllers\Supervisor\StokOpnameController::class, 'show'])
            ->name('stok-opname.show');
        Route::post('/stok-opname/{id}/verify', [\App\Http\Controllers\Supervisor\StokOpnameController::class, 'verify'])
            ->name('stok-opname.verify');
        Route::get('/stok-opname/{id}/export-pdf', [\App\Http\Controllers\Supervisor\StokOpnameController::class, 'exportPdf'])
            ->name('stok-opname.export-pdf');
    });

