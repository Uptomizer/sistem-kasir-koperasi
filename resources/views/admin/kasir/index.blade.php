@extends('layouts.admin')

@section('title', 'Manajemen Kasir')
@section('page-title', 'Manajemen Kasir')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
    
    <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
        <h2 class="font-bold text-slate-800 text-lg">Daftar Akun Kasir</h2>
        <button
            onclick="openKasirModal()"
            class="bg-blue-600 text-white px-5 py-2 rounded-lg font-medium text-sm
                   shadow-lg shadow-blue-600/20 hover:bg-blue-700 hover:shadow-blue-700/30
                   transition-all active:scale-95 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Tambah Kasir
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
    
    @if ($errors->any())
        <div class="px-6 pt-6">
            <div class="bg-red-50 text-red-700 p-4 rounded-lg border border-red-100">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="p-6">
        <div class="overflow-x-auto rounded-lg border border-slate-200">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-200 uppercase tracking-wider text-xs">
                    <tr>
                        <th class="px-6 py-4">Nama Lengkap</th>
                        <th class="px-6 py-4">Username</th>
                        <th class="px-6 py-4">Dibuat Pada</th>
                        <th class="px-6 py-4 text-center w-48">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse ($kasir as $row)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 font-medium text-slate-800">
                                <div class="flex items-center gap-3">
                                    @if ($row->profile_photo)
                                        <div class="w-8 h-8 rounded-full bg-slate-200 border border-slate-200 shadow-sm overflow-hidden">
                                            <img src="{{ asset('storage/profile-photos/' . $row->profile_photo) }}" alt="Profile" class="w-full h-full object-cover">
                                        </div>
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-xs ring-2 ring-white">
                                            {{ substr($row->nama_user, 0, 1) }}
                                        </div>
                                    @endif
                                    {{ $row->nama_user }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-600">
                                {{ $row->username }}
                            </td>
                            <td class="px-6 py-4 text-slate-500 text-xs">
                                {{ $row->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button 
                                       type="button"
                                       onclick="openEditKasirModal(this)"
                                       data-action="{{ route('admin.kasir.update', $row->id_user) }}"
                                       data-nama="{{ $row->nama_user }}"
                                       data-username="{{ $row->username }}"
                                       class="text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 p-2 rounded-md transition-colors"
                                       title="Edit">
                                       <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                                    </button>

                                    <button
                                        type="button"
                                        onclick="openDeleteModal('{{ route('admin.kasir.destroy', $row->id_user) }}')"
                                        class="text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100 p-2 rounded-md transition-colors"
                                        title="Hapus">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-12 text-slate-500">
                                <span class="block mb-2 text-2xl opacity-40">👥</span>
                                Belum ada akun kasir
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

{{-- MODAL TAMBAH KASIR --}}
<div id="kasirModal"
     class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 hidden">

    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md animate-modal-in">

            {{-- Header --}}
            <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center">
                <h3 class="font-bold text-slate-800 text-lg">Tambah Kasir</h3>
                <button onclick="closeKasirModal()" class="text-slate-400 hover:text-slate-600 text-xl">&times;</button>
            </div>

            {{-- Form --}}
            <form method="POST" action="{{ route('admin.kasir.store') }}" enctype="multipart/form-data" onsubmit="showLoading(this.querySelector('button[type=submit]'), 'Simpan...')">
                @csrf

                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap</label>
                        <input name="nama_user" required placeholder="Contoh: John Doe" class="w-full border border-slate-300 px-4 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Username</label>
                        <input name="username" required placeholder="Username untuk login" class="w-full border border-slate-300 px-4 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Foto Profil (Opsional)</label>
                        <input type="file" name="profile_photo" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                        <input type="password" name="password" required placeholder="******" class="w-full border border-slate-300 px-4 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                </div>

                {{-- Footer --}}
                <div class="px-6 py-4 border-t border-slate-200 flex justify-end gap-3">
                    <button type="button" onclick="closeKasirModal()" class="px-4 py-2 rounded-lg text-slate-600 hover:bg-slate-100">Batal</button>
                    <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition font-medium">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL EDIT KASIR --}}
<div id="editKasirModal"
     class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 hidden">

    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md animate-modal-in">

            {{-- Header --}}
            <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center">
                <h3 class="font-bold text-slate-800 text-lg">Edit Kasir</h3>
                <button onclick="closeEditKasirModal()" class="text-slate-400 hover:text-slate-600 text-xl">&times;</button>
            </div>

            {{-- Form --}}
            <form id="editKasirForm" method="POST" action="" enctype="multipart/form-data" onsubmit="showLoading(this.querySelector('button[type=submit]'), 'Update...')">
                @csrf
                @method('PUT')

                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap</label>
                        <input id="editNama" name="nama_user" required placeholder="Contoh: John Doe" class="w-full border border-slate-300 px-4 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Username</label>
                        <input id="editUsername" name="username" required placeholder="Username" class="w-full border border-slate-300 px-4 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Ganti Foto (Opsional)</label>
                        <input type="file" name="profile_photo" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Password Baru <span class="text-xs font-normal text-slate-400">(Biarkan kosong jika tidak diubah)</span></label>
                        <div class="relative">
                            <input id="editPassword" type="password" name="password" placeholder="******" class="w-full border border-slate-300 px-4 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none pr-10">
                            <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600">
                                <svg id="eyeIconOpen" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg id="eyeIconClosed" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.059 10.059 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="px-6 py-4 border-t border-slate-200 flex justify-end gap-3">
                    <button type="button" onclick="closeEditKasirModal()" class="px-4 py-2 rounded-lg text-slate-600 hover:bg-slate-100">Batal</button>
                    <button type="submit" class="bg-indigo-600 text-white px-5 py-2 rounded-lg hover:bg-indigo-700 transition font-medium">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL DELETE --}}
<div id="deleteModal"
     class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 hidden">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm animate-modal-in p-6 text-center">
            
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4 text-red-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>

            <h3 class="text-xl font-bold text-slate-800 mb-2">Hapus Akun Kasir?</h3>
            <p class="text-slate-500 mb-6">Kasir ini tidak akan bisa login lagi ke sistem.</p>

            <form id="deleteForm" method="POST" action="" onsubmit="showLoading(this.querySelector('button[type=submit]'), 'Hapus...')">
                @csrf
                @method('DELETE')
                <div class="flex gap-3 justify-center">
                    <button type="button" onclick="closeDeleteModal()" class="px-5 py-2.5 rounded-xl text-slate-600 hover:bg-slate-100 font-medium transition-colors w-full cursor-pointer">Batal</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-red-600 text-white hover:bg-red-700 font-medium transition-colors w-full shadow-lg shadow-red-600/30 cursor-pointer">Ya, Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openKasirModal() {
    document.getElementById('kasirModal').classList.remove('hidden')
}

function closeKasirModal() {
    document.getElementById('kasirModal').classList.add('hidden')
}

function openEditKasirModal(btn) {
    const action = btn.dataset.action
    const nama = btn.dataset.nama
    const username = btn.dataset.username

    document.getElementById('editKasirForm').action = action
    document.getElementById('editNama').value = nama
    document.getElementById('editUsername').value = username
    document.getElementById('editPassword').value = ''; // Reset password field
    
    document.getElementById('editKasirModal').classList.remove('hidden')
}

function closeEditKasirModal() {
    document.getElementById('editKasirModal').classList.add('hidden')
}

// Tutup modal dengan ESC
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        closeKasirModal()
        closeDeleteModal()
        closeEditKasirModal()
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

function togglePassword() {
    const passwordInput = document.getElementById('editPassword');
    const eyeOpen = document.getElementById('eyeIconOpen');
    const eyeClosed = document.getElementById('eyeIconClosed');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeOpen.classList.add('hidden');
        eyeClosed.classList.remove('hidden');
    } else {
        passwordInput.type = 'password';
        eyeOpen.classList.remove('hidden');
        eyeClosed.classList.add('hidden');
    }
}
</script>
<style>
@keyframes modalIn {
    from { opacity: 0; transform: scale(0.95) translateY(10px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
}
.animate-modal-in { animation: modalIn 0.25s ease-out; }
</style>
