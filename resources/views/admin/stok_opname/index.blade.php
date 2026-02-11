@extends('layouts.admin')

@section('title', 'Stok Opname')
@section('page-title', 'Riwayat Stok Opname')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
        <h2 class="font-bold text-slate-800 text-lg">Daftar Stok Opname</h2>
        <a href="{{ route('admin.stok-opname.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition flex items-center gap-2 font-medium">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Mulai Stok Opname
        </a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100">
                <tr>
                    <th class="px-6 py-4">Kode Opname</th>
                    <th class="px-6 py-4">Tanggal</th>
                    <th class="px-6 py-4">Petugas</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Catatan</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100" id="stok-opname-body">
                @include('admin.stok_opname.partials.rows')
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        setInterval(function() {
            const url = new URL(window.location.href);
            fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.text())
            .then(html => {
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = html;
                // If the controller returns the partial, it's just tr rows.
                // If it returns full view, we need to extract body.
                // My controller will return partial.
                if(document.getElementById('stok-opname-body')) {
                    document.getElementById('stok-opname-body').innerHTML = html;
                }
            })
            .catch(error => console.error('Error refreshing table:', error));
        }, 15000);
    });
</script>
@endpush
