<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $kategori = Kategori::orderBy('nama_kategori')->get();
        return view('admin.kategori.index', compact('kategori'));
    }

    public function create()
    {
        return view('admin.kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|unique:kategori,nama_kategori'
        ]);

        Kategori::create($request->only('nama_kategori'));

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Kategori berhasil ditambahkan']);
        }

        return redirect()
            ->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan');
    }

    public function edit(Kategori $kategori)
    {
        return view('admin.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, Kategori $kategori)
    {
        $request->validate([
            'nama_kategori' => 'required|unique:kategori,nama_kategori,' . $kategori->id_kategori . ',id_kategori'
        ]);

        $kategori->update($request->only('nama_kategori'));

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Kategori berhasil diperbarui']);
        }

        return redirect()
            ->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil diperbarui');
    }

    public function destroy(Kategori $kategori)
    {
        // Delete all associated barang items first (Manual Cascade)
        $kategori->barang()->delete();
        
        $kategori->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Kategori berhasil dihapus']);
        }

        return redirect()
            ->route('admin.kategori.index')
            ->with('success', 'Kategori dan semua barang di dalamnya berhasil dihapus');
    }

    public function getList(Request $request)
    {
        $kategori = Kategori::orderBy('nama_kategori')->get();
        return view('admin.kategori.partials.list', compact('kategori'));
    }
}

