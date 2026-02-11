<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DetailPenjualan;
use Illuminate\Support\Facades\DB;

class StatistikController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', date('m'));
        $year = $request->get('year', date('Y'));

        // Top 10 Terlaris (Qty)
        $topItems = DetailPenjualan::select(
                'id_barang', 
                DB::raw('SUM(jumlah) as total_qty'), 
                DB::raw('SUM(subtotal) as total_revenue')
            )
            ->whereHas('penjualan', function($q) use ($month, $year) {
                $q->whereMonth('tanggal', $month)
                  ->whereYear('tanggal', $year);
            })
            ->with(['barang' => function($q) {
                $q->withTrashed(); // Include deleted items if any, to avoid null errors
            }])
            ->groupBy('id_barang')
            ->orderByDesc('total_qty')
            ->limit(10)
            ->get();

        // Top 10 Omset (Revenue)
        $topRevenue = DetailPenjualan::select(
                'id_barang', 
                DB::raw('SUM(jumlah) as total_qty'), 
                DB::raw('SUM(subtotal) as total_revenue')
            )
            ->whereHas('penjualan', function($q) use ($month, $year) {
                $q->whereMonth('tanggal', $month)
                  ->whereYear('tanggal', $year);
            })
            ->with(['barang' => function($q) {
                $q->withTrashed();
            }])
            ->groupBy('id_barang')
            ->orderByDesc('total_revenue')
            ->limit(10)
            ->get();

        // Kategori Terlaris
        $topCategories = DetailPenjualan::select(
                'barang.id_kategori',
                DB::raw('SUM(detail_penjualan.jumlah) as total_qty'),
                DB::raw('SUM(detail_penjualan.subtotal) as total_revenue')
            )
            ->join('barang', 'detail_penjualan.id_barang', '=', 'barang.id_barang')
            ->join('penjualan', 'detail_penjualan.id_penjualan', '=', 'penjualan.id_penjualan')
            ->join('kategori', 'barang.id_kategori', '=', 'kategori.id_kategori') // Join ke tabel kategori untuk nama
            ->whereMonth('penjualan.tanggal', $month)
            ->whereYear('penjualan.tanggal', $year)
            ->groupBy('barang.id_kategori', 'kategori.nama_kategori')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->with('barang.kategori') // Eager load via barang relation slightly redundant due to join but okay
            // Better to just get category name from join or relation. 
            // Since we group by id_kategori, we can't easily use 'with' directly on result unless we map it.
            // Let's use the join approach fully for data.
            ->addSelect('kategori.nama_kategori')
            ->get();


        return view('admin.statistik.index', compact('topItems', 'topRevenue', 'topCategories', 'month', 'year'));
    }
}
