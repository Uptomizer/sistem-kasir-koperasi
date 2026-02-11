@extends('layouts.supervisor')

@section('title', 'Kelola Diskon')
@section('page-title', 'Manajemen Diskon & Promosi')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 mb-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="font-bold text-slate-800 text-lg">Daftar Diskon</h3>
            <p class="text-slate-500 text-sm">Kelola promo dan potongan harga.</p>
        </div>
        <button onclick="openModal()" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Diskon
        </button>
    </div>

    @if(session('success'))
    <div class="mb-4 bg-emerald-50 text-emerald-600 px-4 py-3 rounded-lg text-sm border border-emerald-100 flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
        </svg>
        {{ session('success') }}
    </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4">Nama Promo</th>
                    <th class="px-6 py-4">Tipe</th>
                    <th class="px-6 py-4">Nilai</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Periode</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($discounts as $diskon)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-6 py-4 font-medium text-slate-800">{{ $diskon->name }}</td>
                    <td class="px-6 py-4 capitalize text-slate-600">{{ $diskon->type == 'percent' ? 'Persentase' : 'Nominal Tetap' }}</td>
                    <td class="px-6 py-4 font-bold text-slate-700">
                        @if($diskon->type == 'percent')
                            {{ $diskon->value }}%
                        @else
                            Rp {{ number_format($diskon->value, 0, ',', '.') }}
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded text-xs font-bold {{ $diskon->status == 'active' ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-100 text-slate-500' }}">
                            {{ $diskon->status == 'active' ? 'Aktif' : 'Tidak Aktif' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-slate-500 text-xs">
                        @if($diskon->start_date && $diskon->end_date)
                            {{ \Carbon\Carbon::parse($diskon->start_date)->format('d M') }} - {{ \Carbon\Carbon::parse($diskon->end_date)->format('d M Y') }}
                        @else
                            Selamanya
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center flex justify-center gap-2">
                        <button onclick='openModal(@json($diskon))' class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>
                        <button onclick="openDeleteModal('{{ route('supervisor.diskon.destroy', $diskon->id) }}')" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-10 text-slate-500">
                        Belum ada data diskon.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $discounts->links() }}
    </div>
</div>

@endsection

@push('modals')
{{-- CREATE/EDIT MODAL --}}
<div id="diskonModal" 
     class="fixed inset-0 z-[9999] hidden bg-slate-900/50 backdrop-blur-sm transition-opacity">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg p-6 animate-fade-in-up">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-slate-800 text-lg" id="modalTitle">
                    Tambah Diskon Baru
                </h3>
                <button onclick="closeModal()" 
                        class="text-slate-400 hover:text-slate-600 text-2xl transition-colors">
                    &times;
                </button>
            </div>

            <form id="diskonForm" method="POST" class="space-y-4">
                @csrf
                <div id="methodField"></div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nama Promo</label>
                    <input type="text" name="name" id="inputName" class="w-full rounded-lg border-slate-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Contoh: Diskon Lebaran" required>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Tipe Potongan</label>
                        <select name="type" id="inputType" class="w-full rounded-lg border-slate-300 focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="percent">Persentase (%)</option>
                            <option value="fixed">Nominal (Rp)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Nilai Potongan</label>
                        <input type="number" name="value" id="inputValue" class="w-full rounded-lg border-slate-300 focus:ring-blue-500 focus:border-blue-500" placeholder="10" required min="0">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal Mulai</label>
                        <input type="date" name="start_date" id="inputStartDate" class="w-full rounded-lg border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal Berakhir</label>
                        <input type="date" name="end_date" id="inputEndDate" class="w-full rounded-lg border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Status</label>
                    <div class="flex gap-4">
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="status" value="active" id="statusActive" class="peer sr-only">
                            <div class="flex items-center justify-center gap-2 border border-slate-200 rounded-lg p-2.5 text-slate-600 transition-all hover:bg-slate-50 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:text-emerald-700 peer-checked:shadow-sm">
                                <div class="w-2 h-2 rounded-full bg-current"></div>
                                <span class="text-sm font-medium">Aktif</span>
                            </div>
                        </label>
                        
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="status" value="inactive" id="statusInactive" class="peer sr-only">
                            <div class="flex items-center justify-center gap-2 border border-slate-200 rounded-lg p-2.5 text-slate-600 transition-all hover:bg-slate-50 peer-checked:border-red-500 peer-checked:bg-red-50 peer-checked:text-red-700 peer-checked:shadow-sm">
                                <div class="w-2 h-2 rounded-full bg-current"></div>
                                <span class="text-sm font-medium">Tidak Aktif</span>
                            </div>
                        </label>
                    </div>
                     <p class="text-xs text-slate-500 mt-1">*Diskon tidak aktif akan auto-hapus setelah 5 hari.</p>
                </div>

                <div class="pt-4 flex gap-3 justify-end border-t border-slate-100 mt-4">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 rounded-lg text-slate-600 hover:bg-slate-100 font-medium transition-colors">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 text-white font-bold hover:bg-blue-700 transition-colors shadow-lg shadow-blue-600/20">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- DELETE CONFIRMATION MODAL --}}
<div id="deleteModal" 
     class="fixed inset-0 z-[9999] hidden bg-slate-900/50 backdrop-blur-sm transition-opacity">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-sm p-6 animate-fade-in-up text-center">
            
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4 text-red-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </div>

            <h3 class="text-xl font-bold text-slate-800 mb-2">Hapus Diskon?</h3>
            <p class="text-slate-500 mb-6">Tindakan ini tidak dapat dibatalkan. Data diskon akan dihapus permanen.</p>

            <form id="deleteForm" method="POST" class="flex gap-3 justify-center">
                @csrf
                @method('DELETE')
                <button type="button" onclick="closeDeleteModal()" class="flex-1 px-4 py-2 rounded-xl text-slate-600 hover:bg-slate-100 font-medium transition-colors">
                    Batal
                </button>
                <button type="submit" class="flex-1 px-4 py-2 rounded-xl bg-red-600 text-white font-bold hover:bg-red-700 transition-colors shadow-lg shadow-red-600/20">
                    Hapus
                </button>
            </form>
        </div>
    </div>
</div>

<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.3s ease-out forwards;
    }
</style>
@endpush

@push('scripts')
<script>
    const modal = document.getElementById('diskonModal');
    const form = document.getElementById('diskonForm');
    const methodField = document.getElementById('methodField');
    const modalTitle = document.getElementById('modalTitle');
    
    // Delete Modal Elements
    const deleteModal = document.getElementById('deleteModal');
    const deleteForm = document.getElementById('deleteForm');

    // Form Inputs
    const inputName = document.getElementById('inputName');
    const inputType = document.getElementById('inputType');
    const inputValue = document.getElementById('inputValue');
    const inputStartDate = document.getElementById('inputStartDate');
    const inputEndDate = document.getElementById('inputEndDate');

    // Radio Buttons
    const statusActive = document.getElementById('statusActive');
    const statusInactive = document.getElementById('statusInactive');

    const storeUrl = "{{ route('supervisor.diskon.store') }}";
    const updateUrlBase = "{{ url('supervisor/diskon') }}";

    function openModal(data = null) {
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden'); // Prevent background scrolling

        form.reset();

        if (data) {
            // Edit Mode
            modalTitle.innerText = 'Edit Diskon';
            form.action = `${updateUrlBase}/${data.id}`;
            methodField.innerHTML = '<input type="hidden" name="_method" value="PUT">';
            
            inputName.value = data.name;
            inputType.value = data.type;
            inputValue.value = data.value;
            inputStartDate.value = data.start_date ? data.start_date.split('T')[0].split(' ')[0] : '';
            inputEndDate.value = data.end_date ? data.end_date.split('T')[0].split(' ')[0] : '';
            
            // Set Status Radio
            if (data.status === 'active') {
                statusActive.checked = true;
            } else {
                statusInactive.checked = true;
            }
            
        } else {
            // Create Mode
            modalTitle.innerText = 'Tambah Diskon Baru';
            form.action = storeUrl;
            methodField.innerHTML = '';
            
            // Set defaults
            statusActive.checked = true;
        }
    }

    function closeModal() {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
    
    // Delete Modal Functions
    function openDeleteModal(actionUrl) {
        deleteForm.action = actionUrl;
        deleteModal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeDeleteModal() {
        deleteModal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    // Close on Escape
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeModal();
            closeDeleteModal();
        }
    });
</script>
@endpush
