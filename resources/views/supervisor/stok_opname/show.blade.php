@extends('layouts.supervisor')

@section('title', 'Detail Stok Opname')
@section('page-title', 'Detail Stok Opname')

@section('content')
@if(session('error'))
<div class="mb-4 bg-red-50 text-red-600 px-4 py-3 rounded-lg text-sm border border-red-100 flex items-center gap-2">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
    </svg>
    {{ session('error') }}
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Main Detail --}}
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h3 class="font-bold text-slate-800">Rincian Barang</h3>
                <span class="text-sm text-slate-500">Total Item: {{ $opname->detail->count() }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100">
                        <tr>
                            <th class="px-4 py-3">Barang</th>
                            <th class="px-4 py-3 text-right">Stok Sistem</th>
                            <th class="px-4 py-3 text-right">Stok Fisik</th>
                            <th class="px-4 py-3 text-right">Selisih</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($opname->detail as $item)
                        <tr class="hover:bg-slate-50/50">
                            <td class="px-4 py-3 font-medium text-slate-800">
                                {{ $item->barang->nama_barang ?? 'Deleted Item' }}
                                <div class="text-xs text-slate-400">{{ $item->barang->kode_barang ?? '-' }}</div>
                            </td>
                            <td class="px-4 py-3 text-right">{{ $item->stok_sistem }}</td>
                            <td class="px-4 py-3 text-right font-bold">{{ $item->stok_fisik }}</td>
                            <td class="px-4 py-3 text-right">
                                <span class="{{ $item->selisih < 0 ? 'text-red-600' : ($item->selisih > 0 ? 'text-green-600' : 'text-slate-400') }} font-bold">
                                    {{ $item->selisih > 0 ? '+' : '' }}{{ $item->selisih }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Sidebar Info & Action --}}
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
            <h3 class="font-bold text-slate-800 mb-4 text-lg">Informasi Opname</h3>
            
            <div class="space-y-4 text-sm">
                <div>
                    <label class="block text-slate-400 text-xs mb-1">Kode Opname</label>
                    <div class="font-medium text-slate-800">{{ $opname->kode_opname }}</div>
                </div>
                <div>
                    <label class="block text-slate-400 text-xs mb-1">Tanggal</label>
                    <div class="font-medium text-slate-800">{{ date('d F Y', strtotime($opname->tanggal)) }}</div>
                </div>
                <div>
                    <label class="block text-slate-400 text-xs mb-1">Petugas (Admin)</label>
                    <div class="font-medium text-slate-800">{{ $opname->user->nama_user ?? '-' }}</div>
                </div>
                <div>
                    <label class="block text-slate-400 text-xs mb-1">Status</label>
                    @if($opname->status == 'selesai')
                        <span class="inline-block bg-green-100 text-green-700 px-2 py-1 rounded-lg text-xs font-bold">Selesai</span>
                    @elseif($opname->status == 'pending')
                        <span class="inline-block bg-amber-100 text-amber-700 px-2 py-1 rounded-lg text-xs font-bold">Menunggu Persetujuan</span>
                    @else
                        <span class="inline-block bg-red-100 text-red-700 px-2 py-1 rounded-lg text-xs font-bold">{{ ucfirst($opname->status) }}</span>
                    @endif
                </div>
                @if($opname->catatan)
                <div>
                    <label class="block text-slate-400 text-xs mb-1">Catatan</label>
                    <div class="p-3 bg-slate-50 rounded-lg text-slate-600 italic border border-slate-100">
                        {!! nl2br(e(explode('[Supervisor]:', $opname->catatan)[0])) !!}
                    </div>
                </div>
                @endif
            </div>

            @if($opname->status == 'pending')
            <div class="mt-8 pt-6 border-t border-slate-100">
                <h4 class="font-bold text-slate-800 mb-3">Tindakan Verifikasi</h4>
                <div class="grid grid-cols-2 gap-3">
                    <button type="button" onclick="openVerifyModal('reject')" class="px-4 py-2 rounded-lg border border-red-200 text-red-600 hover:bg-red-50 font-medium transition-colors text-center">
                        Tolak
                    </button>
                    <button type="button" onclick="openVerifyModal('approve')" class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 font-bold transition-colors shadow-lg shadow-blue-600/20 text-center">
                        Setujui
                    </button>
                </div>
            </div>
            @endif
        </div>
        
        
        
        @if($opname->status != 'pending')
        <div class=" border-t border-slate-100 grid gap-3">
            <a href="{{ route('supervisor.stok-opname.export-pdf', $opname->id_stok_opname) }}" target="_blank" class="w-full border border-red-200 text-red-700 px-4 py-2 rounded-lg hover:bg-red-50 transition font-medium text-sm text-center flex items-center justify-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
                Download PDF
            </a>
        </div>
        @endif
        <a href="{{ route('supervisor.stok-opname.index') }}" class="block text-center text-slate-500 hover:text-slate-700 text-sm font-medium">
            &larr; Kembali ke Daftar
        </a>
    </div>
</div>

@endsection

@push('modals')
{{-- VERIFICATION MODAL --}}
<div id="verifyModal" class="fixed inset-0 z-[9999] hidden bg-slate-900/50 backdrop-blur-sm transition-opacity">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md p-6 animate-fade-in-up">
            
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-slate-800 text-lg" id="modalTitle">Konfirmasi</h3>
                <button onclick="closeVerifyModal()" class="text-slate-400 hover:text-slate-600 text-2xl transition-colors">&times;</button>
            </div>

            <div id="modalIcon" class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                {{-- Icon injected by JS --}}
            </div>

            <p id="modalMessage" class="text-center text-slate-600 mb-6 px-4">
                {{-- Message injected by JS --}}
            </p>

            <form action="{{ route('supervisor.stok-opname.verify', $opname->id_stok_opname) }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="action" id="inputAction">
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Catatan Supervisor (Opsional)</label>
                    <textarea name="catatan_supervisor" rows="3" class="w-full rounded-lg border-slate-300 focus:ring-blue-500 focus:border-blue-500 text-sm" placeholder="Tambahkan alasan atau catatan..."></textarea>
                </div>
                
                <div class="grid grid-cols-2 gap-3 pt-2">
                    <button type="button" onclick="closeVerifyModal()" class="px-4 py-2 rounded-lg text-slate-600 hover:bg-slate-100 font-medium transition-colors border border-slate-200">
                        Batal
                    </button>
                    <button type="submit" id="modalSubmitBtn" class="px-4 py-2 rounded-lg text-white font-bold transition-colors shadow-lg">
                        Konfirmasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
    const verifyModal = document.getElementById('verifyModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalMessage = document.getElementById('modalMessage');
    const modalIcon = document.getElementById('modalIcon');
    const inputAction = document.getElementById('inputAction');
    const modalSubmitBtn = document.getElementById('modalSubmitBtn');

    function openVerifyModal(action) {
        verifyModal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');

        if (action === 'approve') {
            modalTitle.innerText = 'Setujui Stok Opname';
            modalMessage.innerText = 'Apakah Anda yakin ingin menyetujui hasil stok opname ini? Stok barang di sistem akan diperbarui secara otomatis sesuai jumlah fisik.';
            inputAction.value = 'approve';
            
            // Style for Approve
            modalIcon.className = 'w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 bg-emerald-100 text-emerald-600';
            modalIcon.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            `;
            modalSubmitBtn.className = 'px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold transition-colors shadow-lg shadow-emerald-600/20';
            modalSubmitBtn.innerText = 'Ya, Setujui';

        } else {
            modalTitle.innerText = 'Tolak Stok Opname';
            modalMessage.innerText = 'Apakah Anda yakin ingin menolak pengajuan ini? Status akan berubah menjadi Batal dan stok sistem tidak akan berubah.';
            inputAction.value = 'reject';

            // Style for Reject
            modalIcon.className = 'w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 bg-red-100 text-red-600';
            modalIcon.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            `;
            modalSubmitBtn.className = 'px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white font-bold transition-colors shadow-lg shadow-red-600/20';
            modalSubmitBtn.innerText = 'Tolak Pengajuan';
        }
    }

    function closeVerifyModal() {
        verifyModal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    // Close on Escape
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeVerifyModal();
        }
    });
</script>
@endpush
