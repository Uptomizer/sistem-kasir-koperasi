@extends('layouts.admin')

@section('title', 'Kategori')
@section('page-title', 'Manajemen Kategori')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
    
    <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
        <h2 class="font-bold text-slate-800 text-lg">Daftar Kategori</h2>
        <button
    onclick="openKategoriModal()"
    class="bg-blue-600 text-white px-5 py-2 rounded-lg font-medium text-sm
           shadow-lg shadow-blue-600/20 hover:bg-blue-700 hover:shadow-blue-700/30
           transition-all active:scale-95 flex items-center gap-2">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
    </svg>
    Tambah Kategori
</button>

    </div>

    @if (session('success'))
        <div class="px-6 pt-6">
            <div class="bg-emerald-50 text-emerald-700 p-4 rounded-lg flex items-center gap-3 border border-emerald-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                {{ session('success') }}
            </div>
        </div>
    @endif

    <div class="p-6">
        <div class="overflow-x-auto rounded-lg border border-slate-200">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-200 uppercase tracking-wider text-xs">
                    <tr>
                        <th class="px-6 py-4">Nama Kategori</th>
                        <th class="px-6 py-4 text-center w-48">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
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
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
{{-- MODAL TAMBAH KATEGORI --}}
<div id="kategoriModal"
     class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 hidden">

    <div class="flex items-center justify-center min-h-screen px-4">
        <div
            class="bg-white rounded-2xl shadow-xl w-full max-w-md
                   animate-modal-in">

            {{-- Header --}}
            <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center">
                <h3 class="font-bold text-slate-800 text-lg">
                    Tambah Kategori
                </h3>
                <button onclick="closeKategoriModal()"
                        class="text-slate-400 hover:text-slate-600 text-xl">
                    &times;
                </button>
            </div>

            {{-- Form --}}
            <form method="POST" action="{{ route('admin.kategori.store') }}" onsubmit="showLoading(this.querySelector('button[type=submit]'), 'Simpan...')">
                @csrf

                <div class="p-6 space-y-4">
                    <input
                        name="nama_kategori"
                        required
                        placeholder="Nama Kategori"
                        class="w-full border border-slate-300 px-4 py-2 rounded-lg
                               focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                {{-- Footer --}}
                <div class="px-6 py-4 border-t border-slate-200 flex justify-end gap-3">
                    <button type="button"
                            onclick="closeKategoriModal()"
                            class="px-4 py-2 rounded-lg text-slate-600 hover:bg-slate-100">
                        Batal
                    </button>

                    <button type="submit"
                            class="bg-blue-600 text-white px-5 py-2 rounded-lg
                                   hover:bg-blue-700 transition font-medium">
                        Simpan
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

{{-- MODAL EDIT KATEGORI --}}
<div id="editKategoriModal"
     class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 hidden">

    <div class="flex items-center justify-center min-h-screen px-4">
        <div
            class="bg-white rounded-2xl shadow-xl w-full max-w-md
                   animate-modal-in">

            {{-- Header --}}
            <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center">
                <h3 class="font-bold text-slate-800 text-lg">
                    Edit Kategori
                </h3>
                <button onclick="closeEditKategoriModal()"
                        class="text-slate-400 hover:text-slate-600 text-xl">
                    &times;
                </button>
            </div>

            {{-- Form --}}
            <form id="editKategoriForm" method="POST" action="" onsubmit="showLoading(this.querySelector('button[type=submit]'), 'Update...')">
                @csrf
                @method('PUT')

                <div class="p-6 space-y-4">
                    <input
                        id="editNamaKategori"
                        name="nama_kategori"
                        required
                        placeholder="Nama Kategori"
                        class="w-full border border-slate-300 px-4 py-2 rounded-lg
                               focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                {{-- Footer --}}
                <div class="px-6 py-4 border-t border-slate-200 flex justify-end gap-3">
                    <button type="button"
                            onclick="closeEditKategoriModal()"
                            class="px-4 py-2 rounded-lg text-slate-600 hover:bg-slate-100">
                        Batal
                    </button>

                    <button type="submit"
                            class="bg-indigo-600 text-white px-5 py-2 rounded-lg
                                   hover:bg-indigo-700 transition font-medium">
                        Update
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

{{-- MODAL DELETE KATEGORI --}}
<div id="deleteModal"
     class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 hidden">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm animate-modal-in p-6 text-center">
            
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4 text-red-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>

            <h3 class="text-xl font-bold text-slate-800 mb-2">Hapus Kategori?</h3>
            <p class="text-slate-500 mb-6">Tindakan ini tidak dapat dibatalkan. Kategori yang dihapus akan hilang dari sistem.</p>

            <form id="deleteForm" method="POST" action="" onsubmit="showLoading(this.querySelector('button[type=submit]'), 'Hapus...')">
                @csrf
                @method('DELETE')
                <div class="flex gap-3 justify-center">
                    <button type="button"
                            onclick="closeDeleteModal()"
                            class="px-5 py-2.5 rounded-xl text-slate-600 hover:bg-slate-100 font-medium transition-colors w-full cursor-pointer">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-5 py-2.5 rounded-xl bg-red-600 text-white hover:bg-red-700 font-medium transition-colors w-full shadow-lg shadow-red-600/30 cursor-pointer">
                        Ya, Hapus
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
function openKategoriModal() {
    document.getElementById('kategoriModal').classList.remove('hidden')
}

function closeKategoriModal() {
    document.getElementById('kategoriModal').classList.add('hidden')
}

function openEditKategoriModal(btn) {
    const action = btn.dataset.action
    const name = btn.dataset.name

    document.getElementById('editKategoriForm').action = action
    document.getElementById('editNamaKategori').value = name
    
    document.getElementById('editKategoriModal').classList.remove('hidden')
    setTimeout(() => document.getElementById('editNamaKategori').focus(), 100)
}

function closeEditKategoriModal() {
    document.getElementById('editKategoriModal').classList.add('hidden')
}


// Tutup modal dengan ESC
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        closeKategoriModal()
        closeDeleteModal()
        closeEditKategoriModal()
    }
})

function showLoading(btn, text) {
    btn.disabled = true;
    btn.innerHTML = `
        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        ${text}
    `;
    return true;
}

function openDeleteModal(actionUrl) {
    document.getElementById('deleteForm').action = actionUrl
    document.getElementById('deleteModal').classList.remove('hidden')
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden')
}
</script>
<style>
@keyframes modalIn {
    from {
        opacity: 0;
        transform: scale(0.95) translateY(10px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

.animate-modal-in {
    animation: modalIn 0.25s ease-out;
}
</style>
