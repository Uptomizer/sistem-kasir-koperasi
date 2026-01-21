<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KasirTransaksiController extends Controller
{
    public function store(Request $request)
{
    $items = json_decode($request->items, true);

    if (!is_array($items) || count($items) === 0) {
        return back()->withErrors('Keranjang kosong atau data tidak valid');
    }

    DB::transaction(function () use ($items, $request) {

        $penjualan = Penjualan::create([
            'tanggal' => now(),
            'id_user' => auth()->id(),
            'total' => 0,
        ]);

        $total = 0;

        foreach ($items as $id_barang => $item) {
            $barang = Barang::findOrFail($id_barang);

            if ($barang->stok < $item['qty']) {
                abort(400, 'Stok tidak cukup');
            }

            $subtotal = $barang->harga_jual * $item['qty'];

            DetailPenjualan::create([
                'id_penjualan' => $penjualan->id_penjualan,
                'id_barang' => $barang->id_barang,
                'jumlah' => $item['qty'],
                'harga' => $barang->harga_jual,
                'subtotal' => $subtotal,
            ]);

            $barang->decrement('stok', $item['qty']);
            $total += $subtotal;
        }

        // Calculate Payment & Change
        $bayar = $request->input('bayar', 0);
        $kembali = $bayar - $total;

        // Ensure we don't save negative change if logic bypassed (optional safety)
        if ($kembali < 0) $kembali = 0; 

        $penjualan->update([
            'total'   => $total,
            'bayar'   => $bayar,
            'kembali' => $kembali
        ]);
    });

    return redirect()
        ->route('kasir.dashboard')
        ->with('success', 'Transaksi berhasil');
}

}
