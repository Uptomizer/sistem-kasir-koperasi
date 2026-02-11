<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RiwayatStok;

class RiwayatStokController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\RiwayatStok::with(['barang', 'user'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }
        
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $query->whereHas('barang', function($q) use ($search) {
                $q->whereRaw('LOWER(nama_barang) LIKE ?', ["%{$search}%"])
                  ->orWhere('kode_barang', 'like', "%{$search}%");
            });
        }

        $riwayat = $query->paginate(20)->withQueryString();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('supervisor.riwayat.partials.rows', compact('riwayat'))->render(),
                'pagination' => (string) $riwayat->links()
            ]);
        }

        return view('supervisor.riwayat.index', compact('riwayat'));
    }
}
