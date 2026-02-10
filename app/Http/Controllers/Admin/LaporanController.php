<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use App\Exports\LaporanPenjualanExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\DetailPenjualan;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // Jalankan pembersihan data lama otomatis (1x sehari)
        $this->cleanupOldSales();

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

    public function exportPdf(Request $request)
    {
        $query = Penjualan::with(['detail.barang', 'user'])
            ->orderByDesc('tanggal');

        // Logic Filter Waktu (Sama dengan index)
        $date = $request->filled('tanggal') ? \Carbon\Carbon::parse($request->tanggal) : now();
        $filterMode = $request->get('filter_profit', 'harian');
        $subtitle = '';

        if ($filterMode === 'harian') {
            if ($request->filled('tanggal') || !$request->filled('filter_profit')) {
                $query->whereDate('tanggal', $date);
            } else {
                $query->whereDate('tanggal', today());
            }
            $subtitle = 'Laporan Harian - ' . $date->translatedFormat('d F Y');

        } elseif ($filterMode === 'mingguan') {
            $startOfWeek = $date->copy()->startOfWeek();
            $endOfWeek   = $date->copy()->endOfWeek();
            $query->whereBetween('tanggal', [$startOfWeek, $endOfWeek]);
            $subtitle = 'Laporan Mingguan - ' . $startOfWeek->translatedFormat('d M') . ' s/d ' . $endOfWeek->translatedFormat('d M Y');

        } elseif ($filterMode === 'bulanan') {
            $query->whereMonth('tanggal', $date->month)
                  ->whereYear('tanggal', $date->year);
            $subtitle = 'Laporan Bulanan - ' . $date->translatedFormat('F Y');

        } elseif ($filterMode === 'tahunan') {
            $query->whereYear('tanggal', $date->year);
            $subtitle = 'Laporan Tahunan - ' . $date->year;
        }

        $penjualan = $query->get();
        $totalKeuntungan = 0;

        foreach ($penjualan as $trx) {
            foreach ($trx->detail as $item) {
                if ($item->barang) {
                    $keuntunganItem = ($item->harga - $item->barang->harga_beli) * $item->jumlah;
                    $totalKeuntungan += $keuntunganItem;
                }
            }
        }

        $pdf = Pdf::loadView('admin.laporan.pdf', compact('penjualan', 'totalKeuntungan', 'subtitle'));
        
        // Setup paper size
        $pdf->setPaper('a4', 'portrait');

        return $pdf->stream('laporan-penjualan-' . now()->format('Y-m-d_H-i') . '.pdf');
    }


    /**
     * Membersihkan data penjualan yang lebih lama dari 1 tahun.
     * Dijalankan otomatis, maksimal 1 kali per hari via Cache lock.
     */
    private function cleanupOldSales()
    {
        // Cek apakah cleanup sudah dijalankan hari ini
        if (Cache::has('last_sales_cleanup_date') && 
            Carbon::parse(Cache::get('last_sales_cleanup_date'))->isToday()) {
            return;
        }

        try {
            DB::transaction(function () {
                $cutoffDate = now()->subYear();
                
                // Ambil ID penjualan lama
                $oldSalesIds = Penjualan::where('tanggal', '<', $cutoffDate)->pluck('id_penjualan');

                if ($oldSalesIds->isNotEmpty()) {
                    // Hapus detail terlebih dahulu
                    DetailPenjualan::whereIn('id_penjualan', $oldSalesIds)->delete();
                    
                    // Hapus data penjualan
                    Penjualan::whereIn('id_penjualan', $oldSalesIds)->delete();
                }
            });

            // Set cache flag agar tidak dijalankan lagi hari ini
            Cache::put('last_sales_cleanup_date', now(), now()->endOfDay());

        } catch (\Exception $e) {
            // Log error jika diperlukan, tapi jangan hentikan flow aplikasi
            // \Log::error('Gagal membersihkan data lama: ' . $e->getMessage());
        }
    }
}
