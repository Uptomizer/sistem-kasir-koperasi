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
        if (auth()->user()->role === 'supervisor') {
            return redirect()->route('supervisor.dashboard');
        }

        $totalBarang   = Barang::count();
        $totalKategori = Kategori::count();
        $totalUser     = \App\Models\User::count();
        $totalStokOpname = \App\Models\StokOpname::where('status', 'pending')->count();

        // Admin Dashboard only shows operation status, no financial analytics.
        return view('admin.dashboard', compact(
            'totalBarang',
            'totalKategori',
            'totalUser',
            'totalStokOpname'
        ));
    }

    public function getStats()
    {
        $totalBarang   = Barang::count();
        $totalKategori = Kategori::count();
        $totalUser     = \App\Models\User::count();
        $totalStokOpname = \App\Models\StokOpname::where('status', 'pending')->count();
        
        return response()->json([
            'total_barang' => number_format($totalBarang),
            'total_kategori' => number_format($totalKategori),
            'total_user' => number_format($totalUser),
            'total_stok_opname' => number_format($totalStokOpname)
        ]);
    }
}

