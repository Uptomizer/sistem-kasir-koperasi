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

    $penjualan = DB::transaction(function () use ($items, $request) {

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

            $stokAwal = $barang->stok;
            $barang->decrement('stok', $item['qty']);
            $stokAkhir = $barang->stok - $item['qty']; // Or $barang->fresh()->stok but simple math is faster here since we just decremented

            // Record History
            \App\Models\RiwayatStok::create([
                'id_barang' => $barang->id_barang,
                'id_user' => auth()->id(),
                'jenis' => 'keluar',
                'jumlah' => $item['qty'],
                'stok_awal' => $stokAwal,
                'stok_akhir' => $stokAkhir,
                'referensi' => 'TRX#' . $penjualan->id_penjualan,
                'keterangan' => 'Penjualan Kasir'
            ]);

            DetailPenjualan::create([
                'id_penjualan' => $penjualan->id_penjualan,
                'id_barang' => $barang->id_barang,
                'jumlah' => $item['qty'],
                'harga' => $barang->harga_jual,
                'subtotal' => $subtotal,
            ]);

            $total += $subtotal;
        }

        // Calculate Payment & Change
        $diskon = $request->input('diskon', 0);
        
        // Ensure discount is not greater than total
        if ($diskon > $total) $diskon = $total;
        if ($diskon < 0) $diskon = 0;

        $totalAkhir = $total - $diskon;
        $bayar = $request->input('bayar', 0);
        $kembali = $bayar - $totalAkhir;

        // Ensure we don't save negative change if logic bypassed (optional safety)
        if ($kembali < 0) $kembali = 0; 

        $penjualan->update([
            'total'   => $total, // Original Total
            'diskon'  => $diskon,
            'bayar'   => $bayar,
            'kembali' => $kembali
        ]);
        
        return $penjualan;
    });

    return redirect()
        ->route('kasir.dashboard')
        ->with('transaction_id', $penjualan->id_penjualan);
}

}
