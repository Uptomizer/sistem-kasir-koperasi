@extends('layouts.admin')

@section('title', 'Laporan Penjualan')
@section('page-title', 'Laporan Penjualan')

@section('content')

{{-- FILTER SECTION --}}
<div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 mb-8">
    <form method="GET" action="{{ route('admin.laporan.index') }}" class="flex flex-col md:flex-row md:items-end gap-4">
        
        <div class="flex-1 max-w-xs">
            <label class="block text-sm font-medium text-slate-700 mb-2">Filter Tanggal</label>
            <input type="date"
                   name="tanggal"
                   value="{{ request('tanggal') }}"
                   class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
        </div>

        <div class="flex gap-3">
            <button class="bg-blue-600 text-white px-5 py-2 rounded-lg font-medium text-sm
                           shadow-md shadow-blue-600/20 hover:bg-blue-700 hover:shadow-blue-700/30
                           transition-all active:scale-95 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                </svg>
                Filter
            </button>

            @if(request('tanggal'))
                <a href="{{ route('admin.laporan.index') }}" 
                   class="bg-slate-100 text-slate-600 px-5 py-2 rounded-lg font-medium text-sm hover:bg-slate-200 transition-colors">
                   Reset
                </a>
            @endif

            <a href="{{ route('admin.laporan.export', ['tanggal' => request('tanggal')]) }}"
               class="bg-emerald-600 text-white px-5 py-2 rounded-lg font-medium text-sm
                      shadow-md shadow-emerald-600/20 hover:bg-emerald-700 hover:shadow-emerald-700/30
                      transition-all active:scale-95 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
                Export Excel
            </a>
        </div>
    </form>
</div>

{{-- SUMMARY / PROFIT CARD --}}
@if ($penjualan->count())
<div class="bg-white rounded-xl shadow-sm border border-emerald-100 p-6 mb-8 flex items-center gap-4 bg-emerald-50/30">
    <div class="p-3 bg-emerald-100 text-emerald-600 rounded-lg">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
    </div>
    <div>
        <div class="text-sm font-medium text-emerald-800 uppercase tracking-wider">Total Keuntungan ({{ request('tanggal') ? \Carbon\Carbon::parse(request('tanggal'))->translatedFormat('d F Y') : 'Semua Data' }})</div>
        <div class="text-3xl font-bold text-emerald-600 mt-1">
            Rp {{ number_format($totalKeuntunganHarian) }}
        </div>
    </div>
</div>
@endif

{{-- TRANSACTION LIST --}}
<div id="laporan-list" class="space-y-6">
    @include('admin.laporan.partials.list')
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const listContainer = document.getElementById('laporan-list');

        listContainer.addEventListener('click', function(e) {
            // Cek apakah yang diklik adalah link pagination atau child dari link pagination
            const link = e.target.closest('.pagination a');
            
            if (link) {
                e.preventDefault();
                
                const url = link.getAttribute('href');
                
                // Tambahkan loading state visual (opsional)
                listContainer.style.opacity = '0.5';
                listContainer.style.pointerEvents = 'none';

                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    listContainer.innerHTML = html;
                    
                    // Kembalikan state visual
                    listContainer.style.opacity = '1';
                    listContainer.style.pointerEvents = 'auto';

                    // Scroll ke atas list (opsional, agar UX lebih nyaman)
                    listContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                    listContainer.style.opacity = '1';
                    listContainer.style.pointerEvents = 'auto';
                });
            }
        });
    });
</script>
@endpush
