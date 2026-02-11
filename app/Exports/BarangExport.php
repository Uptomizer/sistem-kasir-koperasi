<?php

namespace App\Exports;

use App\Models\Barang;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BarangExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Barang::with('kategori')->get();
    }

    public function headings(): array
    {
        return [
            'Nama Barang',
            'Kode Barang',
            'Kategori',
            'Harga Beli',
            'Harga Jual',
            'Stok',
        ];
    }

    public function map($barang): array
    {
        return [
            $barang->nama_barang,
            $barang->kode_barang,
            $barang->kategori->nama_kategori ?? '-',
            $barang->harga_beli,
            $barang->harga_jual,
            $barang->stok,
        ];
    }
}
