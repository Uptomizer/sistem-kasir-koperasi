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

        $today = now();
        $discounts = \App\Models\Diskon::where('status', 'active')
            ->where(function($q) use ($today) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', $today);
            })
            ->where(function($q) use ($today) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $today);
            })
            ->get();

        return view('kasir.dashboard', compact('barang', 'kategori', 'discounts'));
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

        if (request()->wantsJson()) {
            return response()->json([
                'html' => view('kasir.partials.items_list', compact('barang'))->render(),
                'total_items' => number_format(\App\Models\Barang::count()),
                'total_categories' => number_format(\App\Models\Kategori::count()),
                'filtered_count' => number_format($barang->count())
            ]);
        }

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
    public function getDiscounts()
    {
        $today = now();
        $discounts = \App\Models\Diskon::where('status', 'active')
            ->where(function($q) use ($today) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', $today);
            })
            ->where(function($q) use ($today) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $today);
            })
            ->get(['id', 'name', 'type', 'value']);

        return response()->json($discounts);
    }
}
