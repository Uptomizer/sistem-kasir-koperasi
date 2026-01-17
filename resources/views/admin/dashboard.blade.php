@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Overview Dashboard')

@section('content')

{{-- STAT CARDS --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
        <div class="flex justify-between items-start">
            <div>
                <h3 class="text-sm font-medium text-slate-500 uppercase tracking-wider">Total Barang</h3>
                <p class="text-3xl font-bold text-slate-800 mt-2">
                    {{ number_format($totalBarang) }}
                </p>
            </div>
            <div class="p-3 bg-blue-50 rounded-lg text-blue-600">
                📦
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
        <div class="flex justify-between items-start">
            <div>
                <h3 class="text-sm font-medium text-slate-500 uppercase tracking-wider">Kategori</h3>
                <p class="text-3xl font-bold text-slate-800 mt-2">
                    {{ number_format($totalKategori) }}
                </p>
            </div>
            <div class="p-3 bg-indigo-50 rounded-lg text-indigo-600">
                🏷️
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
        <div class="flex justify-between items-start">
            <div>
                <h3 class="text-sm font-medium text-slate-500 uppercase tracking-wider">Transaksi Hari Ini</h3>
                <p class="text-3xl font-bold text-slate-800 mt-2">
                    {{ number_format($totalTransaksiHariIni) }}
                </p>
            </div>
            <div class="p-3 bg-emerald-50 rounded-lg text-emerald-600">
                💰
            </div>
        </div>
    </div>

</div>

{{-- CHARTS SECTION --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    
    {{-- Sales Chart --}}
    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
        <h3 class="font-bold text-slate-800 text-lg mb-4">Laporan Penjualan Bulanan</h3>
        <div class="relative h-64 w-full">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    {{-- Profit Chart --}}
    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
        <h3 class="font-bold text-slate-800 text-lg mb-4">Keuntungan Bulanan</h3>
        <div class="relative h-64 w-full">
            <canvas id="profitChart"></canvas>
        </div>
    </div>

</div>

{{-- LAPORAN PENJUALAN SECTION --}}
<div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
        <h2 class="font-bold text-slate-800 text-lg">Laporan Penjualan Teratas</h2>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4">Barang</th>
                    <th class="px-6 py-4 text-center">Jumlah Terjual</th>
                    <th class="px-6 py-4 text-right">Total Pendapatan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($laporan as $index => $row)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4 font-medium text-slate-800">
                            <div class="flex items-center gap-3">
                                <span class="text-slate-400 text-xs w-4">#{{ $index + 1 }}</span>
                                {{ $row->nama_barang }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center font-medium text-slate-600">
                            {{ number_format($row->total_jumlah) }}
                        </td>
                        <td class="px-6 py-4 text-right font-bold text-slate-700">
                            Rp {{ number_format($row->total_penjualan) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center py-10 text-slate-500">
                            <div class="flex flex-col items-center gap-2">
                                <span class="text-2xl opacity-50">📂</span>
                                <div>Belum ada transaksi hari ini</div>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        const labels = @json($months);
        const salesData = @json($salesData);
        const profitData = @json($profitData);

        // 1. Sales Chart
        const ctxSales = document.getElementById('salesChart').getContext('2d');
        new Chart(ctxSales, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Terjual (Unit)',
                    data: salesData,
                    backgroundColor: 'rgba(59, 130, 246, 0.6)', // Blue-500
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat('id-ID').format(value) + ' Unit';
                            }
                        }
                    }
                }
            }
        });

        // 2. Profit Chart (Line Chart)
        const ctxProfit = document.getElementById('profitChart').getContext('2d');
        new Chart(ctxProfit, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Keuntungan (Rp)',
                    data: profitData,
                    borderColor: 'rgba(16, 185, 129, 1)', // Emerald-500
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: 'rgba(16, 185, 129, 1)',
                    pointHoverBackgroundColor: 'rgba(16, 185, 129, 1)',
                    pointHoverBorderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
