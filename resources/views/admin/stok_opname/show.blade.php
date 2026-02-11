@extends('layouts.admin')

@section('title', 'Detail Stok Opname')
@section('page-title', 'Detail Stok Opname')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    {{-- Summary Card --}}
    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 md:col-span-1">
        <h3 class="font-bold text-slate-800 mb-4 border-b border-slate-100 pb-2">Informasi</h3>
        <div class="space-y-3 text-sm">
            <div class="flex justify-between">
                <span class="text-slate-500">Kode Opname</span>
                <span class="font-bold text-slate-800 font-mono">{{ $opname->kode_opname }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Tanggal</span>
                <span class="font-bold text-slate-800">{{ date('d M Y', strtotime($opname->tanggal)) }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Petugas</span>
                <span class="font-bold text-slate-800">{{ $opname->user->nama_user ?? '-' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Status</span>
                <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded text-xs font-bold uppercase">{{ $opname->status }}</span>
            </div>
            @php
                $fullCatatan = $opname->catatan ?? '';
                $supervisorNotePrefix = '[Supervisor]:';
                $hasSupervisorNote = str_contains($fullCatatan, $supervisorNotePrefix);
                
                $userNote = $fullCatatan;
                $supervisorNote = null;

                if ($hasSupervisorNote) {
                    $parts = explode($supervisorNotePrefix, $fullCatatan);
                    $userNote = trim($parts[0]);
                    $supervisorNote = trim($parts[1] ?? '');
                }
            @endphp
            
            <div class="mt-4 pt-4 border-t border-slate-100">
                <span class="block text-slate-500 mb-1 font-semibold text-xs uppercase tracking-wider">Catatan Petugas</span>
                <p class="text-slate-700 italic text-sm bg-slate-50 p-2 rounded border border-slate-100 mb-3">
                    {{ $userNote ?: '-' }}
                </p>

                <span class="block text-slate-500 mb-1 font-semibold text-xs uppercase tracking-wider">Catatan Supervisor</span>
                @if($supervisorNote)
                    <div class="bg-blue-50 text-blue-800 p-2 rounded border border-blue-100 text-sm">
                        {{ $supervisorNote }}
                    </div>
                @elseif($opname->status == 'pending')
                     <p class="text-amber-600 text-sm bg-amber-50 p-2 rounded border border-amber-100 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Menunggu verifikasi supervisor.
                    </p>
                @else
                    <p class="text-slate-400 text-sm italic bg-slate-50 p-2 rounded border border-slate-100">
                        Tidak ada catatan tambahan.
                    </p>
                @endif
            </div>
        </div>
        
        <div class="mt-6">
             <a href="{{ route('admin.stok-opname.index') }}" class="block w-full text-center bg-slate-100 text-slate-700 py-2 rounded-lg font-medium hover:bg-slate-200 transition">
                Kembali
            </a>

        </div>
    </div>

    {{-- Details Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 md:col-span-2 overflow-hidden flex flex-col">
        <div class="p-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
            <h3 class="font-bold text-slate-800">Hasil Audit</h3>
            <span class="text-xs text-slate-500 bg-white px-2 py-1 rounded border border-slate-200">
                Total Item: {{ $opname->detail->count() }}
            </span>
        </div>
        
        <div class="overflow-x-auto flex-1">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100">
                    <tr>
                        <th class="px-4 py-3">Barang</th>
                        <th class="px-4 py-3 text-center">Stok Sistem</th>
                        <th class="px-4 py-3 text-center">Stok Fisik</th>
                        <th class="px-4 py-3 text-center">Selisih</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($opname->detail as $detail)
                    <tr class="hover:bg-slate-50/50">
                        <td class="px-4 py-3">
                            <div class="font-bold text-slate-800">{{ $detail->barang->nama_barang ?? 'Barang Dihapus' }}</div>
                            <div class="text-xs text-slate-500 font-mono">{{ $detail->barang->kode_barang ?? '-' }}</div>
                        </td>
                        <td class="px-4 py-3 text-center text-slate-600">{{ $detail->stok_sistem }}</td>
                        <td class="px-4 py-3 text-center font-bold text-slate-800">{{ $detail->stok_fisik }}</td>
                        <td class="px-4 py-3 text-center">
                            @if($detail->selisih > 0)
                                <span class="text-green-600 font-bold bg-green-50 px-2 py-1 rounded">+{{ $detail->selisih }}</span>
                            @elseif($detail->selisih < 0)
                                <span class="text-red-600 font-bold bg-red-50 px-2 py-1 rounded">{{ $detail->selisih }}</span>
                            @else
                                <span class="text-slate-400 font-medium bg-slate-100 px-2 py-1 rounded">0</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-8 text-slate-400 italic">Tidak ada detail item yang dicatat.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    #content, #content * {
        visibility: visible;
    }
    #content {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
    /* Hide buttons when printing */
    button, a {
        display: none !important;
    }
}
</style>
@endsection
