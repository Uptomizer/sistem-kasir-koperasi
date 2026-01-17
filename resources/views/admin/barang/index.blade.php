@extends('layouts.admin')

@section('title', 'Barang')
@section('page-title', 'Manajemen Barang')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
    
    <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
        <h2 class="font-bold text-slate-800 text-lg">Daftar Barang</h2>
        <button
    onclick="openBarangModal()"
    class="bg-blue-600 text-white px-5 py-2 rounded-lg font-medium text-sm
           shadow-lg shadow-blue-600/20 hover:bg-blue-700 hover:shadow-blue-700/30
           transition-all active:scale-95 flex items-center gap-2">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
    </svg>
    Tambah Barang
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
        <div class="overflow-x-auto overflow-y-auto max-h-[450px] rounded-lg border border-slate-200">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-200 uppercase tracking-wider text-xs sticky top-0 z-10 shadow-sm">
                    <tr>
                        <th class="px-6 py-4">Barang</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4 text-right">Harga Jual</th>
                        <th class="px-6 py-4 text-center">Stok</th>
                        <th class="px-6 py-4 text-center w-48">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @foreach ($barang as $row)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4 font-medium text-slate-800">
                            {{ $row->nama_barang }}
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
                                <a href="{{ route('admin.barang.edit', $row) }}"
                                   class="text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 p-2 rounded-md transition-colors"
                                   title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
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
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection
{{-- MODAL TAMBAH BARANG --}}
<div id="barangModal"
     class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 hidden">

    <div class="flex items-center justify-center min-h-screen px-4">
        <div
            class="bg-white rounded-2xl shadow-xl w-full max-w-lg
                   animate-modal-in">

            {{-- Header --}}
            <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center">
                <h3 class="font-bold text-slate-800 text-lg">
                    Tambah Barang
                </h3>
                <button onclick="closeBarangModal()"
                        class="text-slate-400 hover:text-slate-600 text-xl">
                    &times;
                </button>
            </div>

            {{-- Body --}}
            <form method="POST" action="{{ route('admin.barang.store') }}">
                @csrf

                <div class="p-6 space-y-4">

                    <input name="nama_barang" required
                           placeholder="Nama Barang"
                           class="w-full border border-slate-300 px-4 py-2 rounded-lg
                                  focus:ring-2 focus:ring-blue-500 focus:outline-none">

                    <select name="id_kategori" required
                            class="w-full border border-slate-300 px-4 py-2 rounded-lg
                                   focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <option value="">Pilih Kategori</option>
                        @foreach ($kategori as $k)
                            <option value="{{ $k->id_kategori }}">
                                {{ $k->nama_kategori }}
                            </option>
                        @endforeach
                    </select>

                    <input name="harga_beli" type="number" required
                           placeholder="Harga Beli"
                           class="w-full border border-slate-300 px-4 py-2 rounded-lg
                                  focus:ring-2 focus:ring-blue-500 focus:outline-none">

                    <input name="harga_jual" type="number" required
                           placeholder="Harga Jual"
                           class="w-full border border-slate-300 px-4 py-2 rounded-lg
                                  focus:ring-2 focus:ring-blue-500 focus:outline-none">

                    <input name="stok" type="number" required
                           placeholder="Stok Awal"
                           class="w-full border border-slate-300 px-4 py-2 rounded-lg
                                  focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                {{-- Footer --}}
                <div class="px-6 py-4 border-t border-slate-200 flex justify-end gap-3">
                    <button type="button"
                            onclick="closeBarangModal()"
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

{{-- MODAL DELETE BARANG --}}
<div id="deleteModal"
     class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 hidden">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm animate-modal-in p-6 text-center">
            
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4 text-red-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>

            <h3 class="text-xl font-bold text-slate-800 mb-2">Hapus Barang?</h3>
            <p class="text-slate-500 mb-6">Tindakan ini tidak dapat dibatalkan. Barang yang dihapus mungkin akan mempengaruhi laporan.</p>

            <form id="deleteForm" method="POST" action="">
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

</div>

{{-- MODAL STOK --}}
<div id="stokModal"
     class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 hidden">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm animate-modal-in">
            
            {{-- Header --}}
            <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center">
                <h3 class="font-bold text-slate-800 text-lg">Update Stok</h3>
                <button onclick="closeStokModal()" class="text-slate-400 hover:text-slate-600 text-xl">&times;</button>
            </div>

            {{-- Body --}}
            <form id="stokForm" method="POST" action="">
                @csrf
                @method('PUT')

                <div class="p-6 space-y-4">
                    <div>
                        <label class="text-sm font-medium text-slate-500 mb-1 block">Nama Barang</label>
                        <input type="text" id="stokNamaBarang" disabled
                               class="w-full bg-slate-50 border border-slate-200 px-4 py-2 rounded-lg text-slate-700">
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-700 mb-1 block">Jumlah Stok</label>
                        <input type="number" name="stok" id="stokInput" required min="0"
                               class="w-full border border-slate-300 px-4 py-2 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>
                </div>

                {{-- Footer --}}
                <div class="px-6 py-4 border-t border-slate-200 flex justify-end gap-3">
                    <button type="button" onclick="closeStokModal()"
                            class="px-4 py-2 rounded-lg text-slate-600 hover:bg-slate-100">
                        Batal
                    </button>
                    <button type="submit"
                            class="bg-emerald-600 text-white px-5 py-2 rounded-lg hover:bg-emerald-700 transition font-medium shadow-lg shadow-emerald-600/20">
                        Simpan Stok
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
function openBarangModal() {
    document.getElementById('barangModal').classList.remove('hidden')
}

function closeBarangModal() {
    document.getElementById('barangModal').classList.add('hidden')
}

// STOK MODAL FUNCTIONS
function openStokModal(btn) {
    const action = btn.dataset.action
    const name = btn.dataset.name
    const stok = btn.dataset.stok

    document.getElementById('stokForm').action = action
    document.getElementById('stokNamaBarang').value = name
    document.getElementById('stokInput').value = stok
    
    document.getElementById('stokModal').classList.remove('hidden')
    // Focus input after modal opens
    setTimeout(() => document.getElementById('stokInput').focus(), 100)
}

function closeStokModal() {
    document.getElementById('stokModal').classList.add('hidden')
}

// Tutup modal dengan ESC
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        closeBarangModal()
        closeDeleteModal()
        closeStokModal()
    }
})

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
