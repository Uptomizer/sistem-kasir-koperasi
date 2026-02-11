@extends('layouts.admin')

@section('title', 'Kategori')
@section('page-title', 'Manajemen Kategori')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
    
    <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
        <h2 class="font-bold text-slate-800 text-lg">Daftar Kategori</h2>
        @if(auth()->user()->role === 'admin')
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
        @endif

    </div>

    <div id="alert-container" class="px-6 pt-6 {{ session('success') ? '' : 'hidden' }}">
        @if (session('success'))
            <div class="bg-emerald-50 text-emerald-700 p-4 rounded-lg flex items-center gap-3 border border-emerald-100 alert-content">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif
    </div>

    <div class="p-6">
        <div class="overflow-x-auto rounded-lg border border-slate-200">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-200 uppercase tracking-wider text-xs">
                    <tr>
                        <th class="px-6 py-4">Nama Kategori</th>
                        <th class="px-6 py-4 text-center w-48">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white" id="categoryTableBody">
                    @include('admin.kategori.partials.list', ['kategori' => $kategori])
                </tbody>
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
{{-- MODAL TAMBAH KATEGORI --}}
<div id="kategoriModal"
     class="fixed inset-0 bg-black/40 backdrop-blur-sm z-[9999] hidden">

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
            <form id="kategoriForm" method="POST" action="{{ route('admin.kategori.store') }}">
                @csrf

                <div class="p-6 space-y-4">
                    <input
                        name="nama_kategori"
                        required
                        maxlength="30"
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
     class="fixed inset-0 bg-black/40 backdrop-blur-sm z-[9999] hidden">

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
            <form id="editKategoriForm" method="POST" action="">
                @csrf
                @method('PUT')

                <div class="p-6 space-y-4">
                    <input
                        id="editNamaKategori"
                        name="nama_kategori"
                        required
                        maxlength="30"
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
     class="fixed inset-0 bg-black/40 backdrop-blur-sm z-[9999] hidden">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm animate-modal-in p-6 text-center">
            
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4 text-red-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>

            <h3 class="text-xl font-bold text-slate-800 mb-2">Hapus Kategori?</h3>
            <p class="text-slate-500 mb-6">Tindakan ini tidak dapat dibatalkan. Kategori yang dihapus akan hilang dari sistem.</p>

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

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // --- ALERT SYSTEM ---
        function showAlert(message) {
            const container = document.getElementById('alert-container');
            container.innerHTML = `
                <div class="bg-emerald-50 text-emerald-700 p-4 rounded-lg flex items-center gap-3 border border-emerald-100 alert-content animate-fade-in-up">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span>${message}</span>
                </div>
            `;
            container.classList.remove('hidden');
            
            setTimeout(() => {
                container.classList.add('hidden');
            }, 3000);
        }

        // --- FETCH CATEGORIES ---
        const tableBody = document.getElementById('categoryTableBody');
        
        function fetchCategories() {
            tableBody.style.opacity = '0.5';
            
            fetch("{{ route('admin.kategori.list') }}")
                .then(res => res.text())
                .then(html => {
                    tableBody.innerHTML = html;
                    tableBody.style.opacity = '1';
                })
                .catch(err => {
                    console.error(err);
                    tableBody.style.opacity = '1';
                });
        }
        
        // Expose to global if needed
        window.fetchCategories = fetchCategories;

        // --- AJAX HANDLER ---
        function handleAjaxForm(formId, closeCallback) {
            const form = document.getElementById(formId);
            if(!form) return;

            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const btn = form.querySelector('button[type="submit"]');
                const originalText = btn.innerHTML;
                let loadingText = 'Memproses...';
                if(formId === 'deleteForm') loadingText = 'Menghapus...';
                
                btn.disabled = true;
                btn.innerHTML = `<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> ${loadingText}`;

                fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(async res => {
                    const contentType = res.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                         throw new Error('Respons bukan JSON');
                    }
                    if (!res.ok) {
                        const errData = await res.json();
                        throw new Error(errData.message || 'Terjadi kesalahan');
                    }
                    return res.json();
                })
                .then(data => {
                    if(data.message) {
                        showAlert(data.message);
                    } else {
                        showAlert('Berhasil!');
                    }
                    
                    fetchCategories();
                    if(closeCallback) closeCallback();
                    
                    // Reset add form if successful
                    if (formId === 'kategoriForm') form.reset();
                })
                .catch(err => {
                    console.error(err);
                    alert('Gagal: ' + err.message);
                })
                .finally(() => {
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                });
            });
        }

        // Initialize Forms
        handleAjaxForm('kategoriForm', closeKategoriModal);
        handleAjaxForm('editKategoriForm', closeEditKategoriModal);
        handleAjaxForm('deleteForm', closeDeleteModal);

        // Auto Refresh Categories (15s)
        setInterval(() => {
            const isAnyModalOpen = !document.getElementById('kategoriModal').classList.contains('hidden') || 
                                   !document.getElementById('editKategoriModal').classList.contains('hidden') || 
                                   !document.getElementById('deleteModal').classList.contains('hidden');
                                   
            if (!isAnyModalOpen) {
                fetchCategories();
            }
        }, 15000);
    });

    // --- MODAL FUNCTIONS (Global Scope) ---
    function openKategoriModal() {
        document.getElementById('kategoriModal').classList.remove('hidden')
        setTimeout(() => document.querySelector('#kategoriModal input').focus(), 100)
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

    function openDeleteModal(actionUrl) {
        document.getElementById('deleteForm').action = actionUrl
        document.getElementById('deleteModal').classList.remove('hidden')
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden')
    }
    
    // Key Listener
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            closeKategoriModal()
            closeDeleteModal()
            closeEditKategoriModal()
        }
    })
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
