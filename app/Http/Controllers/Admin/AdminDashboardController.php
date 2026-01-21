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

        $startOfWeek = now()->startOfWeek(\Carbon\Carbon::SUNDAY);
        $endOfWeek   = now()->endOfWeek(\Carbon\Carbon::SATURDAY);

        $totalTransaksiMingguIni = Penjualan::whereBetween('tanggal', [$startOfWeek, $endOfWeek])->count();
        $pendapatanMingguIni     = Penjualan::whereBetween('tanggal', [$startOfWeek, $endOfWeek])->sum('total');

        $keuntunganMingguIni = DB::table('detail_penjualan')
            ->join('penjualan', 'detail_penjualan.id_penjualan', '=', 'penjualan.id_penjualan')
            ->join('barang', 'detail_penjualan.id_barang', '=', 'barang.id_barang')
            ->whereBetween('penjualan.tanggal', [$startOfWeek, $endOfWeek])
            ->sum(DB::raw('(detail_penjualan.harga - barang.harga_beli) * detail_penjualan.jumlah'));

        // Transaksi Hari Ini (Recent) - Keep this as distinct "Recent Activity" or update to "Recent This Week"? 
        // User asked to change "Pendapatan Hari Ini" (stat card) -> "Pendapatan Minggu Ini".
        // I will keep the recent transaction table as "Recent" (latest 10) regardless of time, but the query below fetches TODAY's recent. 
        // Let's broaden recent transactions to be general recent (latest 10 of all time or this week). 
        // Generally "Recent Transactions" implies just the latest events. Let's make it latest 10 overall for better context if today is empty.
        $recentTransactions = Penjualan::with('user')
            ->whereDate('tanggal', today())
            ->orderBy('created_at', 'desc')
            ->limit(10)
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
            'totalTransaksiMingguIni',
            'pendapatanMingguIni',
            'keuntunganMingguIni',
            'recentTransactions',
            'months',
            'salesData',
            'profitData'
        ));
    }

    public function getChartData(\Illuminate\Http\Request $request)
    {
        $filter = $request->get('filter', 'monthly'); // default monthly

        $labels = [];
        $salesData = [];
        $profitData = [];

        if ($filter === 'monthly') {
            // Existing Logic for Monthly (This Year)
            $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
            $salesData = array_fill(0, 12, 0);
            $profitData = array_fill(0, 12, 0);

            $sales = DB::table('detail_penjualan')
                ->join('penjualan', 'detail_penjualan.id_penjualan', '=', 'penjualan.id_penjualan')
                ->select(
                    DB::raw('EXTRACT(MONTH FROM penjualan.tanggal) as month'),
                    DB::raw('SUM(detail_penjualan.jumlah) as total_items')
                )
                ->whereYear('penjualan.tanggal', date('Y'))
                ->groupBy('month')
                ->pluck('total_items', 'month');

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

            foreach ($sales as $month => $total) {
                $salesData[$month - 1] = $total;
            }
            foreach ($profits as $month => $total) {
                $profitData[$month - 1] = $total;
            }

        } elseif ($filter === 'weekly') {
            // This Week (Sun - Sat)
            $startOfWeek = now()->startOfWeek(\Carbon\Carbon::SUNDAY);
            
            for ($i = 0; $i < 7; $i++) {
                $date = $startOfWeek->copy()->addDays($i);
                $labels[] = $date->format('D, d M'); // e.g., Sun, 21 Jan
                $dateString = $date->toDateString();

                // Sales
                $salesData[] = DB::table('detail_penjualan')
                    ->join('penjualan', 'detail_penjualan.id_penjualan', '=', 'penjualan.id_penjualan')
                    ->whereDate('penjualan.tanggal', $dateString)
                    ->sum('detail_penjualan.jumlah') ?? 0;

                // Profit
                $profitData[] = DB::table('detail_penjualan')
                    ->join('penjualan', 'detail_penjualan.id_penjualan', '=', 'penjualan.id_penjualan')
                    ->join('barang', 'detail_penjualan.id_barang', '=', 'barang.id_barang')
                    ->whereDate('penjualan.tanggal', $dateString)
                    ->sum(DB::raw('(detail_penjualan.harga - barang.harga_beli) * detail_penjualan.jumlah')) ?? 0;
            }
        } elseif ($filter === 'daily') {
            // Today's Hourly Logic (00:00 to 23:59)
            // Simplified: Just 6-hour blocks or every 3 hours to not overcrowd, 
            // BUT user usually expects hours. Let's do 07:00 to 21:00 (Shop Hours) or just all 24h if needed.
            // Let's do standard 24h for completeness but labels every 3h
            
            for ($i = 0; $i <= 23; $i++) {
                $labels[] = str_pad($i, 2, '0', STR_PAD_LEFT) . ':00';
                
                // This is heavier query if done in loop, but okay for small scale. 
                // Optimized way is one query group by hour.
            }
            
            // Optimized Query for Hourly Sales Today
            $sales = DB::table('detail_penjualan')
                ->join('penjualan', 'detail_penjualan.id_penjualan', '=', 'penjualan.id_penjualan')
                ->select(
                    DB::raw('EXTRACT(HOUR FROM penjualan.created_at) as hour'),
                    DB::raw('SUM(detail_penjualan.jumlah) as total_items')
                )
                ->whereDate('penjualan.tanggal', today())
                ->groupBy('hour')
                ->pluck('total_items', 'hour');

            $profits = DB::table('detail_penjualan')
                ->join('penjualan', 'detail_penjualan.id_penjualan', '=', 'penjualan.id_penjualan')
                ->join('barang', 'detail_penjualan.id_barang', '=', 'barang.id_barang')
                ->select(
                    DB::raw('EXTRACT(HOUR FROM penjualan.created_at) as hour'),
                    DB::raw('SUM((detail_penjualan.harga - barang.harga_beli) * detail_penjualan.jumlah) as total_profit')
                )
                ->whereDate('penjualan.tanggal', today())
                ->groupBy('hour')
                ->pluck('total_profit', 'hour');

            // Fill Data
            $salesData = array_fill(0, 24, 0);
            $profitData = array_fill(0, 24, 0);

            foreach ($sales as $hour => $total) {
                // Hour comes as float/int from DB
                $salesData[(int)$hour] = $total;
            }
            foreach ($profits as $hour => $total) {
                $profitData[(int)$hour] = $total;
            }
        }

        return response()->json([
            'labels' => $labels,
            'sales' => $salesData,
            'profit' => $profitData
        ]);
    }

    public function getHistory(\Illuminate\Http\Request $request)
    {
        $filter = $request->get('filter', 'daily');

        $query = Penjualan::with(['user', 'detail.barang'])
            ->orderBy('created_at', 'desc');

        if ($filter === 'daily') {
            $query->whereDate('tanggal', today());
        } elseif ($filter === 'weekly') {
            $query->whereBetween('tanggal', [now()->startOfWeek(\Carbon\Carbon::SUNDAY), now()->endOfWeek(\Carbon\Carbon::SATURDAY)]);
        } elseif ($filter === 'monthly') {
            $query->whereMonth('tanggal', now()->month)
                  ->whereYear('tanggal', now()->year);
        }

        $history = $query->get();

        // reuse the partial view for consistent design
        // We will repurpose the 'admin.laporan.partials.list' for this modal content
        // Note: The partial expects $penjualan variable.
        $html = view('admin.laporan.partials.list', ['penjualan' => $history])->render();

        return response()->json(['html' => $html]);
    }

    public function getTransactionDetail($id)
    {
        $transaction = Penjualan::with(['user', 'detail.barang'])->findOrFail($id);

        return response()->json([
            'id' => $transaction->id_penjualan,
            'tanggal' => $transaction->tanggal, // or formatted
            'kasir' => $transaction->user->nama_user ?? 'Umum',
            'total' => $transaction->total,
            'bayar' => $transaction->bayar,
            'kembali' => $transaction->kembali,
            'items' => $transaction->detail->map(function($detail) {
                return [
                    'nama_barang' => $detail->barang->nama_barang ?? 'Barang Dihapus',
                    'qty' => $detail->jumlah,
                    'harga' => $detail->harga,
                    'subtotal' => $detail->subtotal
                ];
            })
        ]);
    }
    public function getStats()
    {
        $totalBarang   = Barang::count();
        $totalKategori = Kategori::count();

        $startOfWeek = now()->startOfWeek(\Carbon\Carbon::SUNDAY);
        $endOfWeek   = now()->endOfWeek(\Carbon\Carbon::SATURDAY);

        $totalTransaksiMingguIni = Penjualan::whereBetween('tanggal', [$startOfWeek, $endOfWeek])->count();
        $pendapatanMingguIni     = Penjualan::whereBetween('tanggal', [$startOfWeek, $endOfWeek])->sum('total');

        $keuntunganMingguIni = DB::table('detail_penjualan')
            ->join('penjualan', 'detail_penjualan.id_penjualan', '=', 'penjualan.id_penjualan')
            ->join('barang', 'detail_penjualan.id_barang', '=', 'barang.id_barang')
            ->whereBetween('penjualan.tanggal', [$startOfWeek, $endOfWeek])
            ->sum(DB::raw('(detail_penjualan.harga - barang.harga_beli) * detail_penjualan.jumlah'));

        // Recent Transactions (Limit 10, same logic as index)
        $recentTransactions = Penjualan::with('user')
            ->whereDate('tanggal', today())
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $recentHtml = view('admin.partials.recent_transactions_rows', compact('recentTransactions'))->render();
        
        return response()->json([
            'total_barang' => number_format($totalBarang),
            'total_kategori' => number_format($totalKategori),
            'pendapatan_minggu_ini' => 'Rp ' . number_format($pendapatanMingguIni, 0, ',', '.'),
            'keuntungan_minggu_ini' => 'Rp ' . number_format($keuntunganMingguIni, 0, ',', '.'),
            'transaksi_minggu_ini' => $totalTransaksiMingguIni . ' Transaksi Berhasil',
            'recent_html' => $recentHtml
        ]);
    }
}

