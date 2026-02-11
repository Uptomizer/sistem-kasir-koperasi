<?php

namespace App\Imports;

use App\Models\Barang;
use App\Models\Kategori;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class BarangImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Cari ID Kategori berdasarkan nama (case insensitive)
        $kategoriNama = $row['kategori'] ?? 'Umum';
        $kategori = Kategori::where('nama_kategori', 'LIKE', $kategoriNama)->first();

        // Jika tidak ada, buat baru (Opsional) atau pakai default. Kita pakai first().
        // Kalau null, maybe default to ID 1 or create. Let's create if not exists safely.
        if (!$kategori) {
            $kategori = Kategori::create(['nama_kategori' => ucfirst($kategoriNama)]);
        }

        return new Barang([
            'nama_barang' => $row['nama_barang'],
            'kode_barang' => $row['kode_barang'], // Ensure Excel has this column
            'id_kategori' => $kategori->id_kategori,
            'harga_beli'  => $row['harga_beli'],
            'harga_jual'  => $row['harga_jual'],
            'stok'        => $row['stok'],
        ]);
    }

    public function rules(): array
    {
        return [
            'nama_barang' => 'required',
            'kode_barang' => 'required|unique:barang,kode_barang', 
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'stok' => 'required|integer',
        ];
    }
}
