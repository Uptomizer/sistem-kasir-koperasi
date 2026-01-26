@foreach ($barang as $row)
<tr class="hover:bg-slate-50/80 transition-colors">
    <td class="px-6 py-4 font-medium text-slate-800">
        {{ $row->nama_barang }}
        @if($row->kode_barang)
            <div class="text-xs text-slate-400 font-mono mt-1">{{ $row->kode_barang }}</div>
        @endif
    </td>
    <td class="px-6 py-4 text-slate-600">
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
            {{ $row->kategori->nama_kategori }}
        </span>
    </td>
    <td class="px-6 py-4 text-right font-medium text-slate-700">
        Rp {{ number_format($row->harga_jual) }}
    </td>
    <td class="px-6 py-4 text-center">
        @if($row->stok <= 5)
            <span class="text-red-600 font-bold bg-red-50 px-2 py-1 rounded">
                {{ $row->stok }}
            </span>
        @else
            <span class="text-slate-600 font-medium bg-slate-100 px-2 py-1 rounded">
                {{ $row->stok }}
            </span>
        @endif
    </td>
    <td class="px-6 py-4 text-center">
        <div class="flex items-center justify-center gap-2">
            <button type="button"
               onclick="openEditBarangModal(this)"
               data-action="{{ route('admin.barang.update', $row) }}"
               data-nama="{{ $row->nama_barang }}"
               data-kode="{{ $row->kode_barang }}"
               data-kategori="{{ $row->id_kategori }}"
               data-beli="{{ $row->harga_beli }}"
               data-jual="{{ $row->harga_jual }}"
               class="text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 p-2 rounded-md transition-colors"
               title="Edit">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
            </button>
            
            <a href="{{ route('admin.barang.printBarcode', $row) }}"
               target="_blank"
               class="text-slate-600 hover:text-slate-800 bg-slate-50 hover:bg-slate-100 p-2 rounded-md transition-colors"
               title="Cetak Barcode">
               <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
            </a>

            <button
               type="button"
               onclick="openStokModal(this)"
               data-action="{{ route('admin.barang.updateStok', $row) }}"
               data-name="{{ $row->nama_barang }}"
               data-stok="{{ $row->stok }}"
               class="text-emerald-600 hover:text-emerald-800 bg-emerald-50 hover:bg-emerald-100 p-2 rounded-md transition-colors"
               title="Kelola Stok">
               <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
            </button>

                <button
                    type="button"
                    onclick="openDeleteModal('{{ route('admin.barang.destroy', $row) }}')"
                    class="text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100 p-2 rounded-md transition-colors"
                    title="Hapus">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                </button>
        </div>
    </td>
</tr>
@endforeach
@if($barang->isEmpty())
    <tr>
        <td colspan="5" class="text-center py-12 text-slate-500">
            <span class="block mb-2 text-2xl opacity-40">📦</span>
            Belum ada barang
        </td>
    </tr>
@endif
