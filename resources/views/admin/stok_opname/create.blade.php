@extends('layouts.admin')

@section('title', 'Mulai Stok Opname')
@section('page-title', 'Stok Opname Baru')

@section('content')
<form action="{{ route('admin.stok-opname.store') }}" method="POST" id="opnameForm">
    @csrf
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <div class="lg:col-span-1 space-y-4">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
                <h3 class="font-bold text-slate-800 mb-4 border-b border-slate-100 pb-2">Informasi Opname</h3>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Kode Opname</label>
                    <input type="text" name="kode_opname" value="{{ $kode_opname }}" readonly class="w-full bg-slate-50 border border-slate-300 rounded-lg px-3 py-2 text-slate-500 cursor-not-allowed">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-slate-700 focus:ring-blue-500 focus:outline-none">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Petugas</label>
                    <input type="text" value="{{ Auth::user()->nama_user }}" readonly class="w-full bg-slate-50 border border-slate-300 rounded-lg px-3 py-2 text-slate-500 cursor-not-allowed">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Catatan</label>
                    <textarea name="catatan" rows="3" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-slate-700 focus:ring-blue-500 focus:outline-none" placeholder="Contoh: Audit Tahunan"></textarea>
                </div>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-xl font-bold shadow-lg shadow-blue-600/20 hover:bg-blue-700 transition">
                Simpan Stok Opname
            </button>
        </div>

        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-slate-100 flex flex-col h-[calc(100vh-10rem)]">
            <div class="p-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50 sticky top-0 z-10">
                <h3 class="font-bold text-slate-800">Daftar Barang</h3>
                <input type="text" id="searchItem" placeholder="Cari Kode / Nama Barang..." class="border border-slate-300 rounded-lg px-3 py-1.5 text-sm w-64 focus:ring-blue-500 focus:outline-none">
            </div>
            
            <div class="overflow-y-auto flex-1 p-0">
                <table class="w-full text-left text-sm" id="itemTable">
                    <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100 sticky top-0 z-10">
                        <tr>
                            <th class="px-4 py-3 bg-slate-50">Barang</th>
                            <th class="px-4 py-3 bg-slate-50 text-center w-24">Stok Sys</th>
                            <th class="px-4 py-3 bg-slate-50 w-32">Stok Fisik</th>
                            <th class="px-4 py-3 bg-slate-50 text-center w-24">Selisih</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($barangs as $item)
                        <tr class="hover:bg-slate-50/50 item-row">
                            <td class="px-4 py-3">
                                <div class="font-bold text-slate-800 item-name">{{ $item->nama_barang }}</div>
                                <div class="text-xs text-slate-500 font-mono item-code">
                                    {{ $item->kode_barang ?? '-' }} 
                                    @if($item->barcode) <span class="bg-slate-100 px-1 rounded ml-1">{{ $item->barcode }}</span> @endif
                                </div>
                                <div class="text-[10px] text-blue-500">{{ $item->kategori->nama_kategori }}</div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2 py-1 bg-slate-100 rounded text-slate-600 font-medium stok-sys" data-val="{{ $item->stok }}">{{ $item->stok }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <input type="number" name="fisik[{{ $item->id_barang }}]" 
                                       class="w-full border border-slate-300 rounded-lg px-2 py-1 text-center font-bold focus:ring-blue-500 focus:outline-none physical-input"
                                       placeholder="...">
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="diff-val text-slate-300 font-bold">-</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-3 bg-slate-50 text-xs text-slate-500 text-center border-t border-slate-100">
                * Kosongkan Stok Fisik jika barang tidak dihitung (skip)
            </div>
        </div>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('searchItem');
    const tableRows = document.querySelectorAll('.item-row');
    const inputs = document.querySelectorAll('.physical-input');

    // Search Filtering
    searchInput.addEventListener('input', (e) => {
        const term = e.target.value.toLowerCase();
        
        tableRows.forEach(row => {
            const name = row.querySelector('.item-name').innerText.toLowerCase();
            const code = row.querySelector('.item-code').innerText.toLowerCase();
            
            if (name.includes(term) || code.includes(term)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Real-time diff calculation
    inputs.forEach(input => {
        input.addEventListener('input', updateDiff);
        // Also update on load if values exist (e.g. back from validation error)
        if(input.value) updateDiff({target: input});
    });

    function updateDiff(e) {
        const input = e.target;
        const row = input.closest('tr');
        const sysVal = parseInt(row.querySelector('.stok-sys').dataset.val);
        const physValStr = input.value;
        const diffEl = row.querySelector('.diff-val');

        if (physValStr === '') {
            diffEl.innerText = '-';
            diffEl.className = 'diff-val text-slate-300 font-bold';
            return;
        }

        const physVal = parseInt(physValStr);
        const diff = physVal - sysVal;

        if (diff > 0) {
            diffEl.innerText = '+' + diff;
            diffEl.className = 'diff-val text-green-600 font-bold bg-green-50 px-2 py-0.5 rounded';
        } else if (diff < 0) {
            diffEl.innerText = diff;
            diffEl.className = 'diff-val text-red-600 font-bold bg-red-50 px-2 py-0.5 rounded';
        } else {
            diffEl.innerText = '0';
            diffEl.className = 'diff-val text-slate-400 font-bold bg-slate-100 px-2 py-0.5 rounded';
        }
    }
    
    // Warn before leave if dirty? Maybe overkill for now.
});
</script>
@endsection
