@forelse ($barang as $b)
<tr class="hover:bg-slate-50/80 transition-colors group">
    <td class="px-6 py-3 font-medium text-slate-800">
        {{ $b->nama_barang }}
        <div class="text-xs text-slate-400 font-normal">{{ $b->kategori->nama_kategori ?? '-' }}</div>
        @if($b->kode_barang)
            <div class="text-[10px] text-slate-400 font-mono mt-0.5">{{ $b->kode_barang }}</div>
        @endif
    </td>
    <td class="px-6 py-3 text-right font-medium text-slate-700 font-mono">
        Rp {{ number_format($b->harga_jual) }}
    </td>
    <td class="px-6 py-3 text-center">
        @if($b->stok <= 5 && $b->stok > 0)
            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                {{ $b->stok }}
            </span>
        @elseif($b->stok == 0)
            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-100 text-slate-500">
                0
            </span>
        @else
            <span class="text-slate-600">{{ $b->stok }}</span>
        @endif
    </td>
    <td class="px-6 py-3 text-center">
        @if ($b->stok > 0)
            <button
                type="button"
                class="add-item bg-green-50 text-green-600 hover:bg-green-600 hover:text-white p-2 rounded-lg transition-all active:scale-95 shadow-sm hover:shadow-md border border-green-200 hover:border-green-600"
                title="Tambah ke Keranjang"
                data-id="{{ $b->id_barang }}"
                data-nama="{{ $b->nama_barang }}"
                data-harga="{{ $b->harga_jual }}"
                data-stok="{{ $b->stok }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
            </button>
        @else
            <button
                type="button"
                disabled
                class="bg-slate-100 text-slate-400 p-2 rounded-lg cursor-not-allowed border border-slate-200"
                title="Stok Habis">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                </svg>
            </button>
        @endif
    </td>
</tr>
@empty
<tr>
    <td colspan="4" class="text-center py-10 text-slate-500">
        <span class="block mb-2 text-2xl opacity-40">🔍</span>
        Tidak ada barang ditemukan
    </td>
</tr>
@endforelse
