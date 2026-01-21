@extends('layouts.admin')

@section('title', 'Barang')
@section('page-title', 'Manajemen Barang')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
    
    <div class="px-6 py-5 border-b border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4 bg-slate-50/50">
        <h2 class="font-bold text-slate-800 text-lg whitespace-nowrap">Daftar Barang</h2>
        
        <div class="flex flex-col md:flex-row items-center gap-3 w-full md:w-auto">
            {{-- SEARCH & FILTER --}}
            <div class="flex items-center gap-2 w-full md:w-auto">
                <div class="relative w-full md:w-64">
                    <input type="text" name="search" placeholder="Cari barang..." 
                           class="bg-white border border-slate-300 text-slate-700 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-9 pr-4 py-2 shadow-sm outline-none transition-all">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <select name="kategori" 
                        class="bg-white border border-slate-300 text-slate-700 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full md:w-auto pl-3 pr-8 py-2 shadow-sm cursor-pointer outline-none">
                    <option value="">Semua Kategori</option>
                    @foreach($kategori as $k)
                        <option value="{{ $k->id_kategori }}">
                            {{ $k->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button
                onclick="window.fetchItems ? window.fetchItems(true) : location.reload()"
                class="bg-white border border-slate-300 text-slate-600 px-3 py-2 rounded-lg font-medium text-sm
                       hover:bg-slate-50 hover:text-blue-600 transition-colors flex items-center gap-2 whitespace-nowrap group"
                       title="Refresh Data">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform group-hover:rotate-180 duration-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
            </button>

            <button
                onclick="openBarangModal()"
                class="bg-blue-600 text-white px-5 py-2 rounded-lg font-medium text-sm
                       shadow-lg shadow-blue-600/20 hover:bg-blue-700 hover:shadow-blue-700/30
                       transition-all active:scale-95 flex items-center gap-2 w-full md:w-auto justify-center whitespace-nowrap">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Tambah
            </button>
        </div>
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
        <div class="overflow-x-auto overflow-y-auto max-h-[500px] rounded-lg border border-slate-200">
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
                <tbody class="divide-y divide-slate-100 bg-white" id="itemsTableBody">
                    @include('admin.barang.partials.list', ['barang' => $barang])
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
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

    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.querySelector('input[name="search"]');
        const categoryInput = document.querySelector('select[name="kategori"]');
        const itemsTable = document.getElementById('itemsTableBody');

        function fetchItems(animate = true) {
            const search = searchInput.value;
            const category = categoryInput.value;
            
            // Loading state ONLY if animate is true
            if (animate) {
                itemsTable.style.opacity = '0.5';
            }

            fetch(`{{ route('admin.barang.items') }}?search=${search}&kategori=${category}`)
                .then(response => response.text())
                .then(html => {
                    itemsTable.innerHTML = html;
                    
                    if (animate) {
                        itemsTable.style.opacity = '1';
                    }
                })
                .catch(err => {
                    console.error('Error fetching items:', err);
                    if (animate) {
                        itemsTable.style.opacity = '1';
                    }
                });
        }
        
        // Expose to global
        window.fetchItems = fetchItems;

        // Auto Refresh every 45 seconds (45000ms) - Silent (no animation)
        setInterval(() => {
            fetchItems(false);
        }, 45000);

        // Debounce helper
        function debounce(func, timeout = 300){
            let timer;
            return (...args) => {
                clearTimeout(timer);
                timer = setTimeout(() => { func.apply(this, args); }, timeout);
            };
        }

        const debouncedFetch = debounce(() => fetchItems(), 300);

        if(searchInput) {
            searchInput.addEventListener('input', debouncedFetch);
        }

        if(categoryInput) {
            categoryInput.addEventListener('change', fetchItems);
        }
    });
</script>

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
            <form method="POST" action="{{ route('admin.barang.store') }}" onsubmit="showLoading(this.querySelector('button[type=submit]'), 'Simpan...')">
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

</div>

{{-- MODAL EDIT BARANG --}}
<div id="editBarangModal"
     class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 hidden">

    <div class="flex items-center justify-center min-h-screen px-4">
        <div
            class="bg-white rounded-2xl shadow-xl w-full max-w-lg
                   animate-modal-in">

            {{-- Header --}}
            <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center">
                <h3 class="font-bold text-slate-800 text-lg">
                    Edit Barang
                </h3>
                <button onclick="closeEditBarangModal()"
                        class="text-slate-400 hover:text-slate-600 text-xl">
                    &times;
                </button>
            </div>

            {{-- Body --}}
            <form id="editBarangForm" method="POST" action="" onsubmit="showLoading(this.querySelector('button[type=submit]'), 'Update...')">
                @csrf
                @method('PUT')

                <div class="p-6 space-y-4">
                    <div>
                        <label class="text-sm font-medium text-slate-700 mb-1 block">Nama Barang</label>
                        <input id="editNamaBarang" name="nama_barang" required
                               placeholder="Nama Barang"
                               class="w-full border border-slate-300 px-4 py-2 rounded-lg
                                      focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-700 mb-1 block">Kategori</label>
                        <select id="editIdKategori" name="id_kategori" required
                                class="w-full border border-slate-300 px-4 py-2 rounded-lg
                                       focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            <option value="">Pilih Kategori</option>
                            @foreach ($kategori as $k)
                                <option value="{{ $k->id_kategori }}">
                                    {{ $k->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-slate-700 mb-1 block">Harga Beli</label>
                            <input id="editHargaBeli" name="harga_beli" type="number" required
                                   placeholder="0"
                                   class="w-full border border-slate-300 px-4 py-2 rounded-lg
                                          focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        </div>

                        <div>
                            <label class="text-sm font-medium text-slate-700 mb-1 block">Harga Jual</label>
                            <input id="editHargaJual" name="harga_jual" type="number" required
                                   placeholder="0"
                                   class="w-full border border-slate-300 px-4 py-2 rounded-lg
                                          focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="px-6 py-4 border-t border-slate-200 flex justify-end gap-3">
                    <button type="button"
                            onclick="closeEditBarangModal()"
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
            <form id="stokForm" method="POST" action="" onsubmit="showLoading(this.querySelector('button[type=submit]'), 'Simpan...')">
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

// EDIT MODAL FUNCTIONS
function openEditBarangModal(btn) {
    const action = btn.dataset.action
    const nama = btn.dataset.nama
    const kategori = btn.dataset.kategori
    const beli = btn.dataset.beli
    const jual = btn.dataset.jual

    document.getElementById('editBarangForm').action = action
    document.getElementById('editNamaBarang').value = nama
    document.getElementById('editIdKategori').value = kategori
    document.getElementById('editHargaBeli').value = beli
    document.getElementById('editHargaJual').value = jual

    document.getElementById('editBarangModal').classList.remove('hidden')
    setTimeout(() => document.getElementById('editNamaBarang').focus(), 100)
}

function closeEditBarangModal() {
    document.getElementById('editBarangModal').classList.add('hidden')
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
        closeEditBarangModal()
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
