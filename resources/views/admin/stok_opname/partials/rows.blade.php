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
            <span class="bg-amber-100 text-amber-700 px-2 py-1 rounded-full text-xs font-bold">Pending</span>
        @else
            <span class="bg-red-100 text-red-700 px-2 py-1 rounded-full text-xs font-bold">{{ ucfirst($opname->status) }}</span>
        @endif
    </td>
    <td class="px-6 py-4 text-slate-500 italic truncate max-w-xs">{{ Str::limit(explode('[Supervisor]:', $opname->catatan ?? '')[0], 50) ?: '-' }}</td>
    <td class="px-6 py-4 text-center">
        <a href="{{ route('admin.stok-opname.show', $opname->id_stok_opname) }}" class="text-blue-600 hover:text-blue-800 font-medium text-xs border border-blue-200 bg-blue-50 px-3 py-1.5 rounded-lg transition">
            Lihat Detail
        </a>
    </td>
</tr>
@empty
<tr>
    <td colspan="6" class="text-center py-12 text-slate-400">
        <div class="flex flex-col items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
            </svg>
            Belum ada riwayat stok opname.
        </div>
    </td>
</tr>
@endforelse
