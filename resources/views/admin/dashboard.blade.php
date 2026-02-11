@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page-title', 'Pusat Manajemen Koperasi')

@section('content')

{{-- OPERATIONAL STATS --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8 no-select">
    
    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 hover:shadow-md transition-all">
        <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Barang</h3>
        <p class="text-3xl font-bold text-slate-700 mt-2" id="stat-barang">{{ number_format($totalBarang) }}</p>
        <span class="text-xs text-slate-400 font-medium">Item Terdaftar</span>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 hover:shadow-md transition-all">
        <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Kategori</h3>
        <p class="text-3xl font-bold text-slate-700 mt-2" id="stat-kategori">{{ number_format($totalKategori) }}</p>
        <span class="text-xs text-slate-400 font-medium">Kategori Produk</span>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 hover:shadow-md transition-all">
        <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider">User Sistem</h3>
        <p class="text-3xl font-bold text-slate-700 mt-2" id="stat-user">{{ number_format($totalUser) }}</p>
        <span class="text-xs text-slate-400 font-medium">Pengguna Aktif</span>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 hover:shadow-md transition-all">
        <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Opname Pending</h3>
        <p class="text-3xl font-bold text-slate-700 mt-2" id="stat-opname">{{ number_format($totalStokOpname) }}</p>
        <span class="text-xs {{ $totalStokOpname > 0 ? 'text-orange-500' : 'text-slate-400' }} font-medium" id="stat-opname-label">
            {{ $totalStokOpname > 0 ? 'Perlu Review' : 'Semua Selesai' }}
        </span>
    </div>

</div>

{{-- ADMIN QUICK ACTIONS --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    
    <a href="{{ route('admin.barang.index') }}" class="group bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg hover:shadow-blue-500/30 transition-all transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-lg">Tambah Barang Baru</h3>
            <div class="bg-white/20 p-2 rounded-lg group-hover:bg-white/30 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
            </div>
        </div>
        <p class="text-blue-100 text-sm">Input data barang baru ke sistem</p>
    </a>

    <a href="{{ route('admin.stok-opname.index') }}" class="group bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl p-6 text-white shadow-lg hover:shadow-emerald-500/30 transition-all transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-lg">Stock Opname</h3>
            <div class="bg-white/20 p-2 rounded-lg group-hover:bg-white/30 transition-colors">
                 <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
        </div>
        <p class="text-emerald-100 text-sm">Mulai sesi opname stok fisik</p>
    </a>

     <a href="{{ route('admin.kasir.index') }}" class="group bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl p-6 text-white shadow-lg hover:shadow-indigo-500/30 transition-all transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-lg">Kelola User</h3>
            <div class="bg-white/20 p-2 rounded-lg group-hover:bg-white/30 transition-colors">
                 <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
        </div>
        <p class="text-indigo-100 text-sm">Manajemen kasir dan staff</p>
    </a>

</div>


@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        setInterval(function() {
            fetch('{{ route("admin.dashboard.stats") }}')
                .then(response => response.json())
                .then(data => {
                    if(document.getElementById('stat-barang')) document.getElementById('stat-barang').textContent = data.total_barang;
                    if(document.getElementById('stat-kategori')) document.getElementById('stat-kategori').textContent = data.total_kategori;
                    if(document.getElementById('stat-user')) document.getElementById('stat-user').textContent = data.total_user;
                    if(document.getElementById('stat-opname')) document.getElementById('stat-opname').textContent = data.total_stok_opname;
                    
                    const opnameLabel = document.getElementById('stat-opname-label');
                    if(opnameLabel) {
                        if(data.total_stok_opname > 0) {
                            opnameLabel.textContent = 'Perlu Review';
                            opnameLabel.className = 'text-xs text-orange-500 font-medium';
                        } else {
                            opnameLabel.textContent = 'Semua Selesai';
                            opnameLabel.className = 'text-xs text-slate-400 font-medium';
                        }
                    }
                })
                .catch(error => console.error('Error fetching dashboard stats:', error));
        }, 15000);
    });
</script>
@endpush
