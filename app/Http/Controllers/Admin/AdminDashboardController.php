<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Penjualan;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalBarang   = Barang::count();
        $totalKategori = Kategori::count();

        $totalTransaksiHariIni = Penjualan::whereDate('tanggal', today())->count();

        // laporan penjualan (ringkas)
        $laporan = DB::table('detail_penjualan')
            ->join('barang', 'detail_penjualan.id_barang', '=', 'barang.id_barang')
            ->join('penjualan', 'detail_penjualan.id_penjualan', '=', 'penjualan.id_penjualan')
            ->select(
                'barang.nama_barang',
                DB::raw('SUM(detail_penjualan.jumlah) as total_jumlah'),
                DB::raw('SUM(detail_penjualan.subtotal) as total_penjualan')
            )
            ->groupBy('barang.nama_barang')
            ->orderByDesc('total_penjualan')
            ->limit(5)
            ->get();

        // --- CHART DATA ---
        
        // 1. Initialize 12 months data
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
        $salesData = array_fill(0, 12, 0);
        $profitData = array_fill(0, 12, 0);

        // 2. Query Monthly Sales
        // 2. Query Monthly Sales (Quantity)
        $sales = DB::table('detail_penjualan')
            ->join('penjualan', 'detail_penjualan.id_penjualan', '=', 'penjualan.id_penjualan')
            ->select(
                DB::raw('EXTRACT(MONTH FROM penjualan.tanggal) as month'),
                DB::raw('SUM(detail_penjualan.jumlah) as total_items')
            )
            ->whereYear('penjualan.tanggal', date('Y'))
            ->groupBy('month')
            ->pluck('total_items', 'month');

        // 3. Query Monthly Profit
        $profits = DB::table('detail_penjualan')
            ->join('penjualan', 'detail_penjualan.id_penjualan', '=', 'penjualan.id_penjualan')
            ->join('barang', 'detail_penjualan.id_barang', '=', 'barang.id_barang')
            ->select(
                DB::raw('EXTRACT(MONTH FROM penjualan.tanggal) as month'),
                DB::raw('SUM((detail_penjualan.harga - barang.harga_beli) * detail_penjualan.jumlah) as total_profit')
            )
            ->whereYear('penjualan.tanggal', date('Y'))
            ->groupBy('month')
            ->pluck('total_profit', 'month');

        // 4. Populate arrays
        foreach ($sales as $month => $total) {
            $salesData[$month - 1] = $total;
        }

        foreach ($profits as $month => $total) {
            $profitData[$month - 1] = $total;
        }

        return view('admin.dashboard', compact(
            'totalBarang',
            'totalKategori',
            'totalTransaksiHariIni',
            'laporan',
            'months',
            'salesData',
            'profitData'
        ));
    }
}

