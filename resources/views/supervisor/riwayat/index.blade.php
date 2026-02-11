@extends('layouts.supervisor')

@section('title', 'Riwayat Mutasi Stok')
@section('page-title', 'Riwayat Mutasi Stok')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
    {{-- Filtering --}}
    <div class="p-6 border-b border-slate-100 bg-slate-50/50">
        <form id="filterForm" action="{{ route('supervisor.riwayat-stok.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="text-xs font-bold text-slate-500 uppercase">Periode</label>
                <div class="flex gap-2 mt-1">
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full text-sm border-slate-300 rounded-lg">
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full text-sm border-slate-300 rounded-lg">
                </div>
            </div>
            <div>
                <label class="text-xs font-bold text-slate-500 uppercase">Jenis Mutasi</label>
                <select name="jenis" class="w-full text-sm border-slate-300 rounded-lg mt-1">
                    <option value="">Semua Jenis</option>
                    <option value="masuk" {{ request('jenis') == 'masuk' ? 'selected' : '' }}>Masuk (Beli/Retur)</option>
                    <option value="keluar" {{ request('jenis') == 'keluar' ? 'selected' : '' }}>Keluar (Jual/Rusak)</option>
                    <option value="penyesuaian" {{ request('jenis') == 'penyesuaian' ? 'selected' : '' }}>Penyesuaian (Opname)</option>
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="text-xs font-bold text-slate-500 uppercase">Cari Barang</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Kode / Nama Barang..." class="w-full text-sm border-slate-300 rounded-lg mt-1">
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100">
                <tr>
                    <th class="px-6 py-4">Waktu</th>
                    <th class="px-6 py-4">Barang</th>
                    <th class="px-6 py-4">Jenis</th>
                    <th class="px-6 py-4 text-center">Jumlah</th>
                    <th class="px-6 py-4 text-center">Stok Akhir</th>
                    <th class="px-6 py-4">Keterangan / Ref</th>
                    <th class="px-6 py-4">User</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100" id="riwayat-body">
                @include('supervisor.riwayat.partials.rows')
            </tbody>
        </table>
    </div>
    
    {{-- Pagination --}}
    <div class="p-6 border-t border-slate-100">
        {{ $riwayat->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
    const filterForm = document.getElementById('filterForm');
    const tableBody = document.getElementById('riwayat-body');
    const paginationContainer = document.querySelector('.p-6.border-t.border-slate-100'); // Assuming this class structure

    // Debounce function
    function debounce(func, timeout = 500){
        let timer;
        return (...args) => {
            clearTimeout(timer);
            timer = setTimeout(() => { func.apply(this, args); }, timeout);
        };
    }

    function fetchResults() {
        const formData = new FormData(filterForm);
        const params = new URLSearchParams(formData);
        const url = `${filterForm.action}?${params.toString()}`;

        // Update URL without reload
        window.history.pushState({}, '', url);

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json()) // Expecting JSON with 'html' and 'pagination'
        .then(data => {
            if (data.html) tableBody.innerHTML = data.html;
            if (data.pagination) paginationContainer.innerHTML = data.pagination;
        })
        .catch(error => console.error('Error fetching data:', error));
    }

    // Attach listeners
    const inputs = filterForm.querySelectorAll('input, select');
    inputs.forEach(input => {
        if (input.tagName === 'SELECT' || input.type === 'date') {
            input.addEventListener('change', fetchResults);
        } else {
            input.addEventListener('input', debounce(fetchResults));
        }
    });

    // Handle Pagination Clicks
    document.addEventListener('click', function(e) {
        if (e.target.closest('.pagination a')) {
            e.preventDefault();
            const url = e.target.closest('.pagination a').href;
            
            window.history.pushState({}, '', url);

            fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.json())
            .then(data => {
                if (data.html) tableBody.innerHTML = data.html;
                if (data.pagination) paginationContainer.innerHTML = data.pagination;
            });
        }
    });
</script>
@endpush
