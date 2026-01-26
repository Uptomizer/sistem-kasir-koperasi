<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index()
    {
        $barang = Barang::with('kategori')->orderBy('nama_barang')->get();
        $kategori = Kategori::orderBy('nama_kategori')->get();
        return view('admin.barang.index', compact('barang', 'kategori'));
    }

    public function create()
    {
        $kategori = Kategori::orderBy('nama_kategori')->get();
        return view('admin.barang.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required',
            'kode_barang' => 'nullable|unique:barang,kode_barang',
            'id_kategori' => 'required',
            'harga_beli'  => 'required|numeric',
            'harga_jual'  => 'required|numeric',
            'stok'        => 'required|numeric|min:0',
        ]);

        Barang::create($request->all());

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Barang berhasil ditambahkan']);
        }

        return redirect()
            ->route('admin.barang.index')
            ->with('success', 'Barang berhasil ditambahkan');
    }

    public function edit(Barang $barang)
    {
        $kategori = Kategori::orderBy('nama_kategori')->get();
        return view('admin.barang.edit', compact('barang', 'kategori'));
    }

    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'nama_barang' => 'required',
            'kode_barang' => 'nullable|unique:barang,kode_barang,' . $barang->id_barang . ',id_barang',
            'id_kategori' => 'required',
            'harga_beli'  => 'required|numeric',
            'harga_jual'  => 'required|numeric',
        ]);

        $barang->update($request->all());

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Barang berhasil diperbarui']);
        }

        return redirect()
            ->route('admin.barang.index')
            ->with('success', 'Barang berhasil diperbarui');
    }

    public function destroy(Barang $barang)
    {
        $barang->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Barang berhasil dihapus']);
        }

        return redirect()
            ->route('admin.barang.index')
            ->with('success', 'Barang berhasil dihapus');
    }

    /* ======================
       KHUSUS KELOLA STOK
    ====================== */

    public function stok(Barang $barang)
    {
        return view('admin.barang.stok', compact('barang'));
    }

    public function updateStok(Request $request, Barang $barang)
    {
        $request->validate([
            'stok' => 'required|numeric|min:0',
        ]);

        $barang->update([
            'stok' => $request->stok
        ]);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Stok berhasil diperbarui']);
        }

        return redirect()
            ->route('admin.barang.index')
            ->with('success', 'Stok berhasil diperbarui');
    }

    public function printBarcode(Barang $barang)
    {
        return view('admin.barang.print_barcode', compact('barang'));
    }

    public function getItems(Request $request)
    {
        $query = Barang::with('kategori');

        // Filter Param
        if ($request->kategori) {
            $query->where('id_kategori', $request->kategori);
        }

        if ($request->search) {
             $query->whereRaw('LOWER(nama_barang) LIKE ?', [strtolower($request->search) . '%']);
        }

        // Sort Param
        switch ($request->sort) {
            case 'nama_desc':
                $query->orderBy('nama_barang', 'desc');
                break;
            case 'kategori_asc':
                $query->join('kategori', 'barang.id_kategori', '=', 'kategori.id_kategori')
                      ->orderBy('kategori.nama_kategori', 'asc')
                      ->select('barang.*'); // Avoid column collision
                break;
            case 'kategori_desc':
                $query->join('kategori', 'barang.id_kategori', '=', 'kategori.id_kategori')
                      ->orderBy('kategori.nama_kategori', 'desc')
                      ->select('barang.*');
                break;
            case 'harga_asc':
                $query->orderBy('harga_jual', 'asc');
                break;
            case 'harga_desc':
                $query->orderBy('harga_jual', 'desc');
                break;
            case 'stok_asc':
                $query->orderBy('stok', 'asc');
                break;
            case 'stok_desc':
                $query->orderBy('stok', 'desc');
                break;
            case 'nama_asc':
            default:
                $query->orderBy('nama_barang', 'asc');
                break;
        }

        $barang = $query->get();

        return view('admin.barang.partials.list', compact('barang'));
    }
}
