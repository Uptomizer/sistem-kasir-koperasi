@extends('layouts.supervisor')

@section('title', 'Verifikasi Stok Opname')
@section('page-title', 'Verifikasi Stok Opname')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
    {{-- Header --}}
    <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
        <div>
            <h2 class="font-bold text-slate-800 text-lg">Daftar Pengajuan Stok Opname</h2>
            <p class="text-slate-500 text-sm">Verifikasi dan setujui hasil stok opname.</p>
        </div>
    </div>

    @if(session('success'))
    <div class="mx-6 mt-6 bg-emerald-50 text-emerald-600 px-4 py-3 rounded-lg text-sm border border-emerald-100 flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
        </svg>
        {{ session('success') }}
    </div>
    @endif
    
    <div class="overflow-x-auto mt-4">
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100">
                <tr>
                    <th class="px-6 py-4">Kode Opname</th>
                    <th class="px-6 py-4">Tanggal</th>
                    <th class="px-6 py-4">Petugas</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Catatan</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($opnames as $opname)
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="px-6 py-4 font-medium text-slate-800">{{ $opname->kode_opname }}</td>
                    <td class="px-6 py-4">{{ date('d M Y', strtotime($opname->tanggal)) }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center gap-1 bg-slate-100 px-2 py-1 rounded text-xs font-medium text-slate-600">
                            {{ $opname->user->nama_user ?? 'Unknown' }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if($opname->status == 'selesai')
                            <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs font-bold">Selesai</span>
                        @elseif($opname->status == 'pending')
                            <span class="bg-amber-100 text-amber-700 px-2 py-1 rounded-full text-xs font-bold">Menunggu Persetujuan</span>
                        @else
                            <span class="bg-red-100 text-red-700 px-2 py-1 rounded-full text-xs font-bold">{{ ucfirst($opname->status) }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-slate-500 italic truncate max-w-xs">{{ Str::limit(explode('[Supervisor]:', $opname->catatan ?? '')[0], 50) ?: '-' }}</td>
                    <td class="px-6 py-4 text-center">
                        <a href="{{ route('supervisor.stok-opname.show', $opname->id_stok_opname) }}" 
                           class="inline-block px-3 py-1.5 rounded-lg text-xs font-medium border transition-colors
                           {{ $opname->status == 'pending' ? 'bg-blue-600 text-white border-blue-600 hover:bg-blue-700' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100' }}">
                            {{ $opname->status == 'pending' ? 'Verifikasi' : 'Lihat Detail' }}
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-12 text-slate-400">
                        Belum ada data stok opname.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="p-4 border-t border-slate-100">
        {{ $opnames->links() }}
    </div>
</div>
@endsection
