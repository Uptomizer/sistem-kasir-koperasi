<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StokOpname;
use App\Models\DetailStokOpname;
use App\Models\Barang;
use App\Models\RiwayatStok;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StokOpnameController extends Controller
{
    public function index()
    {
        $opnames = StokOpname::with('user')
            ->orderByRaw("CASE status WHEN 'pending' THEN 1 WHEN 'selesai' THEN 2 WHEN 'batal' THEN 3 ELSE 4 END")
            ->orderByDesc('tanggal')
            ->paginate(10);
            
        return view('supervisor.stok_opname.index', compact('opnames'));
    }

    public function show($id)
    {
        $opname = StokOpname::with(['user', 'detail.barang'])->findOrFail($id);
        return view('supervisor.stok_opname.show', compact('opname'));
    }

    public function verify(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'catatan_supervisor' => 'nullable|string'
        ]);

        $opname = StokOpname::with('detail')->findOrFail($id);
        
        if ($opname->status !== 'pending') {
            return back()->with('error', 'Stok Opname ini sudah diproses.');
        }

        DB::beginTransaction();
        try {
            if ($request->action === 'approve') {
                $opname->status = 'selesai';
                
                // Process Stock Updates
                foreach ($opname->detail as $detail) {
                    $barang = Barang::find($detail->id_barang);
                    if ($barang) {
                        // Only update if there is a difference or if we strictly apply physical stock
                        // The detail has 'selesai' calculated from (fisik - sistem).
                        // If we want to set stock to 'stok_fisik', we do:
                        
                        $oldStok = $barang->stok;
                        $newStok = $detail->stok_fisik;
                        $diff = $newStok - $oldStok; // This might be different from Detail's 'selisih' if standard stock changed in between.
                        
                        // NOTE: Usually Stok Opname freezes stock or we assume stock hasn't moved. 
                        // Or we trust the 'selisih' recorded at opname time and apply it?
                        // Better to set stock to the counted physical stock. 
                        // But wait, if transactions happened between opname creation and approval?
                        // Option A: Apply the 'selisih' (delta).
                        // Option B: Force set to 'stok_fisik'.
                        
                        // Given the context of simple app, forcing set to stock_fisik is safer representation of "Actual Count".
                        // However, if sales happened, those sales might be valid.
                        // Let's stick to the logic from Admin controller: update(['stok' => $stok_fisik]).
                        
                        // But wait, if sales occurred while pending?
                        // Example: Sys: 10, Fisik: 8 (Selisih -2).
                        // While pending, 1 item sold. Sys: 9.
                        // If we set to 8, we "lose" the sold item record effectively or we double count the loss?
                        // If we set to 8, we say "At time X it was 8".
                        // Logic moved from Admin controller was: $barang->update(['stok' => $stok_fisik]);
                        // Let's keep that for consistency.
                        
                        if ($diff != 0) {
                            $barang->update(['stok' => $newStok]);
                            
                            RiwayatStok::create([
                                'id_barang' => $detail->id_barang,
                                'id_user' => Auth::id(), // Supervisor who approved
                                'jenis' => 'penyesuaian',
                                'jumlah' => abs($diff),
                                'stok_awal' => $oldStok,
                                'stok_akhir' => $newStok,
                                'referensi' => $opname->kode_opname,
                                'keterangan' => 'Stok Opname (Approved)'
                            ]);
                        }
                    }
                }
                
                $message = 'Stok Opname disetujui. Stok telah diperbarui.';
                
            } else {
                $opname->status = 'batal';
                $message = 'Stok Opname ditolak/dibatalkan.';
            }

            // Save Supervisor Note if needed, maybe append to catatan?
            if ($request->filled('catatan_supervisor')) {
                $opname->catatan .= "\n[Supervisor]: " . $request->catatan_supervisor;
            }
            
            $opname->save();
            
            DB::commit();
            return redirect()->route('supervisor.stok-opname.index')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function exportPdf($id)
    {
        $opname = StokOpname::with(['user', 'detail.barang'])->findOrFail($id);
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('supervisor.stok_opname.pdf', compact('opname'));
        
        return $pdf->stream('laporan-stok-opname-' . $opname->kode_opname . '.pdf');
    }
}
