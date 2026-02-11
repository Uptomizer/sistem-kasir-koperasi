@forelse($riwayat as $row)
<tr class="hover:bg-slate-50/50 transition">
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="font-medium text-slate-800">{{ $row->created_at->format('d M Y') }}</div>
        <div class="text-xs text-slate-400">{{ $row->created_at->format('H:i') }}</div>
    </td>
    <td class="px-6 py-4">
        <div class="font-bold text-slate-800">{{ $row->barang->nama_barang ?? 'Deleted' }}</div>
        <div class="text-xs text-slate-500 font-mono">{{ $row->barang->kode_barang ?? '-' }}</div>
    </td>
    <td class="px-6 py-4">
        @if($row->jenis == 'masuk')
            <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs font-bold">Masuk</span>
        @elseif($row->jenis == 'keluar')
            <span class="bg-red-100 text-red-700 px-2 py-1 rounded-full text-xs font-bold">Keluar</span>
        @else
            <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded-full text-xs font-bold">Adjust</span>
        @endif
    </td>
    <td class="px-6 py-4 text-center">
        <span class="font-bold text-lg {{ $row->jenis == 'masuk' ? 'text-green-600' : ($row->jenis == 'keluar' ? 'text-red-600' : 'text-blue-600') }}">
            {{ $row->jenis == 'keluar' ? '-' : '+' }}{{ $row->jumlah }}
        </span>
    </td>
    <td class="px-6 py-4 text-center font-mono font-medium text-slate-700">
        {{ $row->stok_akhir }}
    </td>
    <td class="px-6 py-4">
        <div class="text-slate-800">{{ $row->keterangan ?? '-' }}</div>
        @if($row->referensi)
            <div class="text-xs text-slate-400 font-mono mt-0.5 bg-slate-100 inline-block px-1 rounded">{{ $row->referensi }}</div>
        @endif
    </td>
    <td class="px-6 py-4 text-xs text-slate-500">
        {{ $row->user->nama_user ?? 'System' }}
    </td>
</tr>
@empty
<tr>
    <td colspan="7" class="text-center py-12 text-slate-400">
        <div class="flex flex-col items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Belum ada riwayat mutasi stok.
        </div>
    </td>
</tr>
@endforelse
