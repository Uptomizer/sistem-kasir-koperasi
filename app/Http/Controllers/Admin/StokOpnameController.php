<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\StokOpname;
use App\Models\DetailStokOpname;
use App\Models\Barang;

class StokOpnameController extends Controller
{
    public function index(Request $request)
    {
        $opnames = StokOpname::with('user')->orderBy('tanggal', 'desc')->get();
        if ($request->ajax()) {
            return view('admin.stok_opname.partials.rows', compact('opnames'));
        }
        return view('admin.stok_opname.index', compact('opnames'));
    }

    public function create()
    {
        $barangs = Barang::with('kategori')->orderBy('nama_barang')->get();
        // Generate kode opname: SO-YYYYMMDD-XXX
        $today = date('Ymd');
        $lastOpname = StokOpname::where('kode_opname', 'like', 'SO-' . $today . '%')
            ->orderBy('kode_opname', 'desc')
            ->first();
        
        $number = 1;
        if ($lastOpname) {
            $lastNumber = intval(substr($lastOpname->kode_opname, -3));
            $number = $lastNumber + 1;
        }
        
        $kode_opname = 'SO-' . $today . '-' . str_pad($number, 3, '0', STR_PAD_LEFT);

        return view('admin.stok_opname.create', compact('barangs', 'kode_opname'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_opname' => 'required|unique:stok_opname,kode_opname',
            'tanggal' => 'required|date',
            'fisik' => 'array',
            'catatan' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            $opname = StokOpname::create([
                'kode_opname' => $request->kode_opname,
                'tanggal' => $request->tanggal,
                'id_user' => Auth::id(), // Petugas
                'status' => 'pending', // Menunggu persetujuan Supervisor
                'catatan' => $request->catatan
            ]);

            // Save details
            if ($request->has('fisik')) {
                foreach ($request->fisik as $id_barang => $stok_fisik) {
                    $barang = Barang::find($id_barang);
                    if ($barang && !is_null($stok_fisik)) {
                        $stok_sistem = $barang->stok;
                        $selisih = $stok_fisik - $stok_sistem;
                        
                        DetailStokOpname::create([
                            'id_stok_opname' => $opname->id_stok_opname,
                            'id_barang' => $id_barang,
                            'stok_sistem' => $stok_sistem,
                            'stok_fisik' => $stok_fisik,
                            'selisih' => $selisih,
                        ]);
                    }
                }
            }
            
            DB::commit();
            return redirect()->route('admin.stok-opname.index')->with('success', 'Stok Opname berhasil diajukan, menunggu persetujuan Supervisor.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $opname = StokOpname::with(['user', 'detail.barang'])->findOrFail($id);
        return view('admin.stok_opname.show', compact('opname'));
    }
}
