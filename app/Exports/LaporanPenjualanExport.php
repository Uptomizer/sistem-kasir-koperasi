<?php

namespace App\Exports;

use App\Models\Penjualan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanPenjualanExport implements FromCollection, WithHeadings
{
    protected $tanggal;

    public function __construct($tanggal = null)
    {
        $this->tanggal = $tanggal;
    }

    public function collection(): Collection
{
    $query = Penjualan::with(['detail.barang'])
        ->orderBy('tanggal');

    if ($this->tanggal) {
        $query->whereDate('tanggal', $this->tanggal);
    }

    $rows = [];
    $totalKeuntungan = 0;

    foreach ($query->get() as $trx) {
        foreach ($trx->detail as $item) {
            $keuntunganItem =
                ($item->harga - $item->barang->harga_beli)
                * $item->jumlah;

            $totalKeuntungan += $keuntunganItem;

            $rows[] = [
                'tanggal'    => $trx->tanggal,
                'barang'     => $item->barang->nama_barang,
                'qty'        => $item->jumlah,
                'harga'      => $item->harga,
                'subtotal'   => $item->subtotal,
                'total_trx'  => $trx->total,
                'keuntungan' => $keuntunganItem,
            ];
        }
    }

    // Baris total keuntungan (footer)
    $rows[] = [
        'tanggal'    => '',
        'barang'     => 'TOTAL KEUNTUNGAN',
        'qty'        => '',
        'harga'      => '',
        'subtotal'   => '',
        'total_trx'  => '',
        'keuntungan' => $totalKeuntungan,
    ];

    return collect($rows);
}


    public function headings(): array
{
    return [
        'Tanggal Transaksi',
        'Nama Barang',
        'Quantity',
        'Harga Satuan',
        'Subtotal',
        'Total Transaksi',
        'Keuntungan',
    ];
}

}
