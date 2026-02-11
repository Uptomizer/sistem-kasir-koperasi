<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Penjualan;
use Illuminate\Support\Facades\DB;

class SupervisorDashboardController extends AdminDashboardController
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

        $recentTransactions = Penjualan::with('user')
            ->whereDate('tanggal', today())
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Initial Chart Data (Empty/Zero arrays to start, or query it?)
        // The dashboard view uses JavaScript to populate/update charts usually via AJAX endpoint.
        // But let's pre-fill the initial state like AdminDashboardController does.
        
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
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

        // 5. Hourly Sales (Busy Hours) - Today
        $hourlySales = DB::table('penjualan')
            ->select(DB::raw('EXTRACT(HOUR FROM created_at) as hour'), DB::raw('COUNT(*) as total'))
            ->whereDate('tanggal', today())
            ->groupBy('hour')
            ->orderBy('hour')
            ->pluck('total', 'hour');

        $busyHoursLabels = [];
        $busyHoursData = [];
        for ($i = 0; $i < 24; $i++) {
            $busyHoursLabels[] = str_pad($i, 2, '0', STR_PAD_LEFT) . ':00';
            $busyHoursData[] = $hourlySales[$i] ?? 0;
        }

        // 6. Top Products (All Time or Monthly?) - Let's do Monthly for relevance
        $topProducts = DB::table('detail_penjualan')
            ->join('penjualan', 'detail_penjualan.id_penjualan', '=', 'penjualan.id_penjualan')
            ->join('barang', 'detail_penjualan.id_barang', '=', 'barang.id_barang')
            ->select('barang.nama_barang', DB::raw('SUM(detail_penjualan.jumlah) as total_qty'))
            ->whereMonth('penjualan.tanggal', date('m'))
            ->groupBy('barang.nama_barang')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        // 7. Recent Logs & Mutations
        $logs = \App\Models\ActivityLog::with('user')->latest()->limit(5)->get();
        $recentMutations = \App\Models\RiwayatStok::with(['user', 'barang'])->latest()->limit(5)->get();
        $activeDiscounts = \App\Models\Diskon::where('status', 'active')->count();

        // Use 'supervisor.dashboard' view
        return view('supervisor.dashboard', compact(
            'totalBarang',
            'totalKategori',
            'totalTransaksiMingguIni',
            'pendapatanMingguIni',
            'keuntunganMingguIni',
            'recentTransactions',
            'months',
            'salesData',
            'profitData',
            'busyHoursLabels',
            'busyHoursData',
            'topProducts',
            'logs',
            'recentMutations',
            'activeDiscounts'
        ));
    }
    public function getChartData(\Illuminate\Http\Request $request)
    {
        // Reused Logic from Admin (now Supervisor)
        $filter = $request->get('filter', 'monthly'); 
        $labels = [];
        $salesData = [];
        $profitData = [];

        if ($filter === 'monthly') {
            $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
            $salesData = array_fill(0, 12, 0);
            $profitData = array_fill(0, 12, 0);

            $sales = DB::table('detail_penjualan')
                ->join('penjualan', 'detail_penjualan.id_penjualan', '=', 'penjualan.id_penjualan')
                ->select(DB::raw('EXTRACT(MONTH FROM penjualan.tanggal) as month'), DB::raw('SUM(detail_penjualan.jumlah) as total_items'))
                ->whereYear('penjualan.tanggal', date('Y'))
                ->groupBy('month')->pluck('total_items', 'month');

            $profits = DB::table('detail_penjualan')
                ->join('penjualan', 'detail_penjualan.id_penjualan', '=', 'penjualan.id_penjualan')
                ->join('barang', 'detail_penjualan.id_barang', '=', 'barang.id_barang')
                ->select(DB::raw('EXTRACT(MONTH FROM penjualan.tanggal) as month'), DB::raw('SUM((detail_penjualan.harga - barang.harga_beli) * detail_penjualan.jumlah) as total_profit'))
                ->whereYear('penjualan.tanggal', date('Y'))
                ->groupBy('month')->pluck('total_profit', 'month');

            foreach ($sales as $month => $total) $salesData[$month - 1] = $total;
            foreach ($profits as $month => $total) $profitData[$month - 1] = $total;

        } elseif ($filter === 'weekly') {
            $startOfWeek = now()->startOfWeek(\Carbon\Carbon::SUNDAY);
            for ($i = 0; $i < 7; $i++) {
                $date = $startOfWeek->copy()->addDays($i);
                $labels[] = $date->format('D, d M');
                $dateString = $date->toDateString();
                $salesData[] = DB::table('detail_penjualan')->join('penjualan', 'detail_penjualan.id_penjualan', '=', 'penjualan.id_penjualan')->whereDate('penjualan.tanggal', $dateString)->sum('detail_penjualan.jumlah') ?? 0;
                $profitData[] = DB::table('detail_penjualan')->join('penjualan', 'detail_penjualan.id_penjualan', '=', 'penjualan.id_penjualan')->join('barang', 'detail_penjualan.id_barang', '=', 'barang.id_barang')->whereDate('penjualan.tanggal', $dateString)->sum(DB::raw('(detail_penjualan.harga - barang.harga_beli) * detail_penjualan.jumlah')) ?? 0;
            }
        } elseif ($filter === 'daily') {
             // Busy Hours logic could go here or separate. Let's stick to Sales/Profit hourly.
            for ($i = 0; $i <= 23; $i++) $labels[] = str_pad($i, 2, '0', STR_PAD_LEFT) . ':00';
            
            $sales = DB::table('detail_penjualan')->join('penjualan', 'detail_penjualan.id_penjualan', '=', 'penjualan.id_penjualan')
                ->select(DB::raw('EXTRACT(HOUR FROM penjualan.created_at) as hour'), DB::raw('SUM(detail_penjualan.jumlah) as total_items'))
                ->whereDate('penjualan.tanggal', today())->groupBy('hour')->pluck('total_items', 'hour');

            $profits = DB::table('detail_penjualan')->join('penjualan', 'detail_penjualan.id_penjualan', '=', 'penjualan.id_penjualan')->join('barang', 'detail_penjualan.id_barang', '=', 'barang.id_barang')
                ->select(DB::raw('EXTRACT(HOUR FROM penjualan.created_at) as hour'), DB::raw('SUM((detail_penjualan.harga - barang.harga_beli) * detail_penjualan.jumlah) as total_profit'))
                ->whereDate('penjualan.tanggal', today())->groupBy('hour')->pluck('total_profit', 'hour');

            $salesData = array_fill(0, 24, 0);
            $profitData = array_fill(0, 24, 0);
            foreach ($sales as $hour => $total) $salesData[(int)$hour] = $total;
            foreach ($profits as $hour => $total) $profitData[(int)$hour] = $total;
        }

        return response()->json(['labels' => $labels, 'sales' => $salesData, 'profit' => $profitData]);
    }

    public function getHistory(\Illuminate\Http\Request $request)
    {
        // Reuse view but moved to Supervisor context if needed, or share the admin partial.
        // We will need to create 'supervisor.partials.list' or reuse.
        // For now, let's reuse 'admin.laporan.partials.list' as it is generic enough or copy it.
        $filter = $request->get('filter', 'daily');
        $query = Penjualan::with(['user', 'detail.barang'])->orderBy('created_at', 'desc');

        if ($filter === 'daily') $query->whereDate('tanggal', today());
        elseif ($filter === 'weekly') $query->whereBetween('tanggal', [now()->startOfWeek(\Carbon\Carbon::SUNDAY), now()->endOfWeek(\Carbon\Carbon::SATURDAY)]);
        elseif ($filter === 'monthly') $query->whereMonth('tanggal', now()->month)->whereYear('tanggal', now()->year);

        $history = $query->get();
        // Just return simple HTML list
        $html = view('supervisor.laporan.partials.list', ['penjualan' => $history])->render();
        return response()->json(['html' => $html]);
    }

    public function getTransactionDetail($id)
    {
        $transaction = Penjualan::with(['user', 'detail.barang'])->findOrFail($id);
        return response()->json([
            'id' => $transaction->id_penjualan,
            'tanggal' => $transaction->tanggal,
            'kasir' => $transaction->user->nama_user ?? 'Umum',
            'total' => $transaction->total,
            'diskon' => $transaction->diskon,
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
    
    public function audit(\Illuminate\Http\Request $request) {
        $logs = \App\Models\ActivityLog::with('user')->latest()->paginate(20);
        if ($request->ajax()) {
            return view('supervisor.partials.audit_rows', compact('logs'));
        }
        return view('supervisor.audit', compact('logs'));
    }

    public function backup() {
        return view('supervisor.backup');
    }

    public function downloadBackup()
    {
        $tables = [
            'users' => \App\Models\User::all(),
            'barang' => \App\Models\Barang::withTrashed()->get(), 
            'kategori' => \App\Models\Kategori::all(),
            'penjualan' => \App\Models\Penjualan::all(),
            'detail_penjualan' => \App\Models\DetailPenjualan::all(),
            'stok_opname' => \App\Models\StokOpname::with('details')->get(),
            'riwayat_stok' => \App\Models\RiwayatStok::all(),
            'activity_logs' => \App\Models\ActivityLog::all(),
            'discounts' => \App\Models\Diskon::all()
        ];
        
        $filename = 'backup-database-' . date('Y-m-d_H-i-s') . '.json';
        
        $json = json_encode($tables, JSON_PRETTY_PRINT);
        
        // Log Backup
        \App\Models\ActivityLog::create([
            'id_user' => \Illuminate\Support\Facades\Auth::id(),
            'action' => 'backup',
            'target' => 'System',
            'details' => 'Mengunduh backup database (JSON)'
        ]);

        return response()->streamDownload(function () use ($json) {
            echo $json;
        }, $filename);
    }
    public function getDashboardData(\Illuminate\Http\Request $request)
    {
        $section = $request->get('section');

        if ($section === 'stats') {
            $startOfWeek = now()->startOfWeek(\Carbon\Carbon::SUNDAY);
            $endOfWeek   = now()->endOfWeek(\Carbon\Carbon::SATURDAY);

            $pendapatan = Penjualan::whereBetween('tanggal', [$startOfWeek, $endOfWeek])->sum('total');
            $transaksi  = Penjualan::whereBetween('tanggal', [$startOfWeek, $endOfWeek])->count();
            
            $keuntungan = DB::table('detail_penjualan')
                ->join('penjualan', 'detail_penjualan.id_penjualan', '=', 'penjualan.id_penjualan')
                ->join('barang', 'detail_penjualan.id_barang', '=', 'barang.id_barang')
                ->whereBetween('penjualan.tanggal', [$startOfWeek, $endOfWeek])
                ->sum(DB::raw('(detail_penjualan.harga - barang.harga_beli) * detail_penjualan.jumlah'));

            $activeDiscounts = \App\Models\Diskon::where('status', 'active')->count();
            $recentMutationsCount = \App\Models\RiwayatStok::latest()->limit(5)->count(); // Just count or show number? Dashboard shows count.

            return response()->json([
                'omzet' => number_format($pendapatan, 0, ',', '.'),
                'transaksi' => number_format($transaksi),
                'profit' => number_format($keuntungan, 0, ',', '.'),
                'discounts' => $activeDiscounts,
                'mutations' => $recentMutationsCount
            ]);
        }

        if ($section === 'top_products') {
            $topProducts = DB::table('detail_penjualan')
                ->join('penjualan', 'detail_penjualan.id_penjualan', '=', 'penjualan.id_penjualan')
                ->join('barang', 'detail_penjualan.id_barang', '=', 'barang.id_barang')
                ->select('barang.nama_barang', DB::raw('SUM(detail_penjualan.jumlah) as total_qty'))
                ->whereMonth('penjualan.tanggal', date('m'))
                ->groupBy('barang.nama_barang')
                ->orderByDesc('total_qty')
                ->limit(5)
                ->get();

            $html = '';
            foreach($topProducts as $index => $product) {
                $html .= '<div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="w-6 h-6 rounded-full bg-slate-100 text-slate-500 text-xs flex items-center justify-center font-bold">'.($index + 1).'</span>
                        <div class="text-sm font-medium text-slate-700 truncate max-w-[120px]" title="'.$product->nama_barang.'">'.$product->nama_barang.'</div>
                    </div>
                    <div class="text-sm font-bold text-slate-600">'.number_format($product->total_qty).' <span class="text-xs font-normal text-slate-400">Sold</span></div>
                </div>';
            }
            if($topProducts->isEmpty()) $html = '<div class="text-center text-slate-400 py-10 text-sm">Belum ada data penjualan</div>';
            
            return response()->json(['html' => $html]);
        }

        if ($section === 'audit') {
            $logs = \App\Models\ActivityLog::with('user')->latest()->limit(5)->get();
            $html = '';
            foreach($logs as $log) {
                $user = $log->user ? $log->user->nama_user : 'System/Deleted';
                $html .= '<tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-4 py-3 font-medium text-slate-700">'.$user.'</td>
                    <td class="px-4 py-3"><span class="px-2 py-1 rounded text-xs font-bold bg-slate-100 text-slate-600">'.ucfirst($log->action).'</span></td>
                    <td class="px-4 py-3 text-slate-600">'.$log->details.'</td>
                    <td class="px-4 py-3 text-right text-slate-500 text-xs">'.$log->created_at->diffForHumans().'</td>
                </tr>';
            }
            if($logs->isEmpty()) $html = '<tr><td colspan="4" class="text-center py-4 text-slate-400">Belum ada aktivitas</td></tr>';
            return response()->json(['html' => $html]);
        }

        if ($section === 'mutations') {
            $recentMutations = \App\Models\RiwayatStok::with(['user', 'barang'])->latest()->limit(5)->get();
            $html = '';
            foreach($recentMutations as $mut) {
                $user = $mut->user->nama_user ?? 'System';
                $barang = $mut->barang->nama_barang ?? 'Deleted';
                $color = $mut->jenis == 'masuk' ? 'emerald' : ($mut->jenis == 'keluar' ? 'red' : 'blue');
                $sign = $mut->jenis == 'keluar' ? '-' : '+';
                
                $html .= '<tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-4 py-3 flex flex-col">
                        <span class="font-bold text-slate-700">'.$barang.'</span>
                        <span class="text-[10px] text-slate-400">'.$mut->created_at->format('H:i').'</span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="font-bold text-'.$color.'-600 bg-'.$color.'-50 px-2 py-1 rounded text-xs">'.$sign.$mut->jumlah.'</span>
                    </td>
                    <td class="px-4 py-3 text-xs text-slate-500">'.$mut->keterangan.'</td>
                    <td class="px-4 py-3 text-right text-xs text-slate-400">'.$user.'</td>
                </tr>';
            }
            if($recentMutations->isEmpty()) $html = '<tr><td colspan="4" class="text-center py-4 text-slate-400">Belum ada mutasi</td></tr>';
            return response()->json(['html' => $html]);
        }

        return response()->json(['error' => 'Invalid section'], 400);
    }
}
