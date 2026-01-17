<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use App\Exports\LaporanPenjualanExport;
use Maatwebsite\Excel\Facades\Excel;


class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = Penjualan::with(['detail.barang', 'user']) // Added user eager load
            ->orderByDesc('tanggal');

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        // Clone query untuk menghitung total profit dari SEMUA data (sesuai filter)
        $allTransactions = $query->get();
        $totalKeuntunganHarian = 0;

        foreach ($allTransactions as $trx) {
            foreach ($trx->detail as $item) {
                // Pastikan relationship barang ada
                if ($item->barang) {
                    $keuntunganItem = ($item->harga - $item->barang->harga_beli) * $item->jumlah;
                    $totalKeuntunganHarian += $keuntunganItem;
                }
            }
        }

        // Pagination 5 transaksi per halaman
        $penjualan = $query->paginate(5)->withQueryString();

        if ($request->ajax()) {
            return view('admin.laporan.partials.list', compact('penjualan'))->render();
        }

        return view('admin.laporan.index', compact(
            'penjualan',
            'totalKeuntunganHarian'
        ));
    }

    public function export(Request $request)
{
    return Excel::download(
        new LaporanPenjualanExport($request->tanggal),
        'laporan-penjualan.xlsx'
    );
}

}
