<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Barang;

class KasirDashboardController extends Controller
{
    public function index()
    {
        $kategori = \App\Models\Kategori::all();
        
        $query = Barang::with('kategori')->orderBy('nama_barang');

        if (request('kategori')) {
            $query->where('id_kategori', request('kategori'));
        }

        if (request('search')) {
            $query->whereRaw('LOWER(nama_barang) LIKE ?', [strtolower(request('search')) . '%']);
        }

        $barang = $query->get();

        return view('kasir.dashboard', compact('barang', 'kategori'));
    }

    public function getItems() 
    {
        $query = Barang::with('kategori')->orderBy('nama_barang');

        if (request('kategori')) {
            $query->where('id_kategori', request('kategori'));
        }

        if (request('search')) {
            $query->whereRaw('LOWER(nama_barang) LIKE ?', [strtolower(request('search')) . '%']);
        }

        $barang = $query->get();

        return view('kasir.partials.items_list', compact('barang'));
    }
}
