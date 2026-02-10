<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;


use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\DB;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule automatic cleanup for old sales records (older than 1 year)
Schedule::call(function () {
    try {
        DB::transaction(function () {
            $cutoffDate = now()->subYear();
            $oldSalesIds = Penjualan::where('tanggal', '<', $cutoffDate)->pluck('id_penjualan');

            if ($oldSalesIds->isNotEmpty()) {
                DetailPenjualan::whereIn('id_penjualan', $oldSalesIds)->delete();
                Penjualan::whereIn('id_penjualan', $oldSalesIds)->delete();
            }
        });
    } catch (\Exception $e) {
        // Log error silently
    }
})->daily()->name('sales:prune-old');
