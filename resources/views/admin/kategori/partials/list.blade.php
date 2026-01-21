@forelse ($kategori as $row)
    <tr class="hover:bg-slate-50/80 transition-colors">
        <td class="px-6 py-4 font-medium text-slate-800">
            {{ $row->nama_kategori }}
        </td>
        <td class="px-6 py-4 text-center">
            <div class="flex items-center justify-center gap-2">
                <button 
                   type="button"
                   onclick="openEditKategoriModal(this)"
                   data-action="{{ route('admin.kategori.update', $row) }}"
                   data-name="{{ $row->nama_kategori }}"
                   class="text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 p-2 rounded-md transition-colors"
                   title="Edit">
                   <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                </button>
                <button
                    type="button"
                    onclick="openDeleteModal('{{ route('admin.kategori.destroy', $row) }}')"
                    class="text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100 p-2 rounded-md transition-colors"
                    title="Hapus">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                </button>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="2" class="text-center py-12 text-slate-500">
            <span class="block mb-2 text-2xl opacity-40">🏷️</span>
            Belum ada kategori
        </td>
    </tr>
@endforelse
