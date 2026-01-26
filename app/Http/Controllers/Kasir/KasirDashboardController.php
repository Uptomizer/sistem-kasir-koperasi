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
            $search = strtolower(request('search'));
            $query->where(function($q) use ($search) {
                $q->whereRaw('LOWER(nama_barang) LIKE ?', [$search . '%'])
                  ->orWhere('kode_barang', 'LIKE', $search . '%');
            });
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
            $search = strtolower(request('search'));
            $query->where(function($q) use ($search) {
                $q->whereRaw('LOWER(nama_barang) LIKE ?', [$search . '%'])
                  ->orWhere('kode_barang', 'LIKE', $search . '%');
            });
        }

        $barang = $query->get();

        return view('kasir.partials.items_list', compact('barang'));
    }

    public function scan()
    {
        $barcode = request('barcode');
        
        // 1. Cari berdasarkan kode_barang (Exact match)
        $barang = Barang::where('kode_barang', $barcode)->first();

        // 2. Jika tidak ketemu, cari berdasarkan ID (untuk barcode generik dari sistem)
        // Barcode generik biasanya format angka (misal: 00000012)
        if (!$barang && is_numeric($barcode)) {
            $id = intval($barcode); // Ubah "00000012" jadi 12
            $barang = Barang::find($id);
        }

        if ($barang) {
            return response()->json([
                'success' => true,
                'item' => $barang
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Barang tidak ditemukan'
        ]);
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
