<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Penjualan;

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

    public function getTransactionDetail($id)
    {
        $transaction = Penjualan::with(['user', 'detail.barang'])->findOrFail($id);

        return response()->json([
            'id' => $transaction->id_penjualan,
            'tanggal' => $transaction->tanggal,
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
}
