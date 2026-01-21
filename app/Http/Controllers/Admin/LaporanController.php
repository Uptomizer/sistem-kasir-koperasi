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

        // Logic Filter Waktu
        $date = $request->filled('tanggal') ? \Carbon\Carbon::parse($request->tanggal) : now();
        $filterMode = $request->get('filter_profit', 'harian');
        $subtitle = '';

        if ($filterMode === 'harian') {
            // Jika request tanggal spesifik atau default hari ini
            if ($request->filled('tanggal') || !$request->filled('filter_profit')) {
                $query->whereDate('tanggal', $date);
            } else {
                // If filter is explicitly 'harian' but no strict date, default to today
                $query->whereDate('tanggal', today());
            }
            $subtitle = $date->translatedFormat('d F Y');

        } elseif ($filterMode === 'mingguan') {
            $startOfWeek = $date->copy()->startOfWeek();
            $endOfWeek   = $date->copy()->endOfWeek();
            $query->whereBetween('tanggal', [$startOfWeek, $endOfWeek]);
            $subtitle = $startOfWeek->translatedFormat('d M') . ' - ' . $endOfWeek->translatedFormat('d M Y');

        } elseif ($filterMode === 'bulanan') {
            $query->whereMonth('tanggal', $date->month)
                  ->whereYear('tanggal', $date->year);
            $subtitle = $date->translatedFormat('F Y');

        } elseif ($filterMode === 'tahunan') {
            $query->whereYear('tanggal', $date->year);
            $subtitle = 'Tahun ' . $date->year;
        }

        // Clone query untuk menghitung total profit dari SEMUA data (sesuai filter)
        // Note: clone() is important because get() executes the query
        $allTransactions = $query->get();
        $totalKeuntungan = 0;

        foreach ($allTransactions as $trx) {
            foreach ($trx->detail as $item) {
                // Pastikan relationship barang ada
                if ($item->barang) {
                    $keuntunganItem = ($item->harga - $item->barang->harga_beli) * $item->jumlah;
                    $totalKeuntungan += $keuntunganItem;
                }
            }
        }

        // Pagination 10 transaksi per halaman (increased from 5 for better overview)
        $penjualan = $query->paginate(10)->withQueryString();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.laporan.partials.list', compact('penjualan'))->render(),
                'totalProfit' => 'Rp ' . number_format($totalKeuntungan),
                'profitSubtitle' => $subtitle
            ]);
        }

        return view('admin.laporan.index', [
            'penjualan' => $penjualan,
            'totalKeuntunganHarian' => $totalKeuntungan, // Variable name kept for view compatibility, but logic is dynamic
            'profitSubtitle' => $subtitle
        ]);
    }

    public function export(Request $request)
    {
        // TODO: Update Export to respect filters if needed, currently just daily
        return Excel::download(
            new LaporanPenjualanExport($request->tanggal),
            'laporan-penjualan.xlsx'
        );
    }

}
