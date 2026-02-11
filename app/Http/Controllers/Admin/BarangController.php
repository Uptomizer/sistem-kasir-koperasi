<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\RiwayatStok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Imports\BarangImport;
use App\Exports\BarangExport;
use Maatwebsite\Excel\Facades\Excel;

class BarangController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin')->only([
            'create', 'store', 'edit', 'update', 'destroy',
            'import', 'updateStok'
        ]);
    }

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
            'nama_barang' => 'required|string|max:30',
            // Barcode Unique
            'barcode'     => ['nullable', 'string', 'max:50', 'unique:barang,barcode'],
            // Kode Barang Unique (Restored)
            'kode_barang' => ['nullable', 'string', 'max:50', 'unique:barang,kode_barang'], 
            'id_kategori' => 'required',
            'harga_beli'  => 'required|numeric',
            'harga_jual'  => 'required|numeric',
            'stok'        => 'required|numeric|min:0',
        ]);

        $barang = Barang::create($request->all());

        // Record Initial Stock
        if ($barang->stok > 0) {
            RiwayatStok::create([
                'id_barang' => $barang->id_barang,
                'id_user' => Auth::id(),
                'jenis' => 'masuk',
                'jumlah' => $barang->stok,
                'stok_awal' => 0,
                'stok_akhir' => $barang->stok,
                'referensi' => 'INIT',
                'keterangan' => 'Stok Awal Barang Baru'
            ]);
        }

        // Activity Log
        \App\Models\ActivityLog::create([
            'id_user' => Auth::id(),
            'action' => 'create',
            'target' => $barang->nama_barang,
            'details' => 'Menambahkan barang baru dengan stok awal ' . $barang->stok
        ]);

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
            'nama_barang' => 'required|string|max:30',
            // Barcode Unique
            'barcode'     => ['nullable', 'string', 'max:50', 'unique:barang,barcode,' . $barang->id_barang . ',id_barang'],
            // Kode Barang Unique (Restored)
            'kode_barang' => ['nullable', 'string', 'max:50', 'unique:barang,kode_barang,' . $barang->id_barang . ',id_barang'],
            'id_kategori' => 'required',
            'harga_beli'  => 'required|numeric',
            'harga_jual'  => 'required|numeric',
        ]);

        $barang->update($request->all());

        // Activity Log
        \App\Models\ActivityLog::create([
            'id_user' => Auth::id(),
            'action' => 'update',
            'target' => $barang->nama_barang,
            'details' => 'Memperbarui data barang'
        ]);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Barang berhasil diperbarui']);
        }

        return redirect()
            ->route('admin.barang.index')
            ->with('success', 'Barang berhasil diperbarui');
    }

    public function destroy(Barang $barang)
    {
        $targetName = $barang->nama_barang; // Save name before delete
        $barang->delete();

        // Activity Log
        \App\Models\ActivityLog::create([
            'id_user' => Auth::id(),
            'action' => 'delete',
            'target' => $targetName,
            'details' => 'Menghapus barang dari sistem (Soft Delete)'
        ]);

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

        $stokAwal = $barang->stok;
        $stokBaru = $request->stok;
        $diff = $stokBaru - $stokAwal;

        if ($diff != 0) {
            $barang->update([
                'stok' => $stokBaru
            ]);

            RiwayatStok::create([
                'id_barang' => $barang->id_barang,
                'id_user' => Auth::id(),
                'jenis' => 'penyesuaian', // Manual adjustment is usually 'penyesuaian'
                'jumlah' => abs($diff),
                'stok_awal' => $stokAwal,
                'stok_akhir' => $stokBaru,
                'referensi' => 'MANUAL',
                'keterangan' => 'Update Stok Manual'
            ]);

            // Activity Log
            \App\Models\ActivityLog::create([
                'id_user' => Auth::id(),
                'action' => 'update',
                'target' => $barang->nama_barang,
                'details' => 'Update stok manual dari ' . $stokAwal . ' menjadi ' . $stokBaru
            ]);
        }

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
    public function export()
    {
        return Excel::download(new BarangExport, 'barang-' . date('Y-m-d') . '.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls'
        ]);

        try {
            Excel::import(new BarangImport, $request->file('file'));
            return redirect()->back()->with('success', 'Data barang berhasil diimpor!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['file' => 'Gagal impor: ' . $e->getMessage()]);
        }
    }
}
