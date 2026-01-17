<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Barang;

class KasirDashboardController extends Controller
{
    public function index()
    {
        $barang = Barang::orderBy('nama_barang')->get();

        return view('kasir.dashboard', compact('barang'));
    }
}
