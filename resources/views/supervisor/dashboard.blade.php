@extends('layouts.supervisor')

@section('title', 'Dashboard Supervisor')
@section('page-title', 'Pusat Analisis & Audit')

@section('content')

{{-- TOP STATS (ANALYTIC FOCUSED) --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8 no-select">
    
    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
        <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Omzet (Minggu ni)</h3>
        <p class="text-2xl font-bold text-slate-700 mt-2" id="stats-omzet">Rp {{ number_format($pendapatanMingguIni, 0, ',', '.') }}</p>
        <span class="text-xs text-emerald-500 font-medium" id="stats-transaksi">+{{ $totalTransaksiMingguIni }} Transaksi</span>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
        <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Profit Bersih</h3>
        <p class="text-2xl font-bold text-slate-700 mt-2" id="stats-profit">Rp {{ number_format($keuntunganMingguIni, 0, ',', '.') }}</p>
        <span class="text-xs text-slate-400 font-medium">Margin Minggu Ini</span>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
        <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Diskon Aktif</h3>
        <p class="text-2xl font-bold text-slate-700 mt-2" id="stats-discounts">{{ $activeDiscounts }}</p>
        <span class="text-xs text-blue-500 font-medium">Program Promosi</span>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
        <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Mutasi Stok</h3>
        <p class="text-2xl font-bold text-slate-700 mt-2" id="stats-mutations-count">{{ $recentMutations->count() }}</p>
        <span class="text-xs text-slate-400 font-medium">Aktivitas Terakhir</span>
    </div>

</div>

{{-- ANALYTIC CHARTS --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8 no-select">
    
    {{-- Sales Trend --}}
    <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-sm border border-slate-200">
        <div class="flex flex-col md:flex-row justify-between items-center mb-4 gap-4">
            {{-- Chart Mode Toggle --}}
            <div class="flex items-center gap-4">
                <button id="prevChartBtn" class="p-2 rounded-lg hover:bg-slate-100 text-slate-400 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <h3 class="font-bold text-slate-700 text-lg min-w-[200px] text-center" id="chartTitle">Tren Penjualan</h3>
                <button id="nextChartBtn" class="p-2 rounded-lg hover:bg-slate-100 text-slate-400 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>

            {{-- Time Filter --}}
            <select id="chartTimeFilter" class="border border-slate-200 rounded-lg text-sm text-slate-600 focus:ring-indigo-500 focus:border-indigo-500 py-2 px-3 bg-slate-50">
                <option value="monthly">Bulanan</option>
                <option value="weekly">Mingguan</option>
            </select>
        </div>
        <div class="relative h-72 w-full">
            <canvas id="mainChart"></canvas>
        </div>
    </div>

    {{-- Top Products --}}
    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
        <h3 class="font-bold text-slate-700 text-lg mb-4">Top 5 Produk (Bulan ini)</h3>
        <div class="space-y-4" id="top-products-list">
            @forelse($topProducts as $index => $product)
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="w-6 h-6 rounded-full bg-slate-100 text-slate-500 text-xs flex items-center justify-center font-bold">
                        {{ $index + 1 }}
                    </span>
                    <div class="text-sm font-medium text-slate-700 truncate max-w-[120px]" title="{{ $product->nama_barang }}">
                        {{ $product->nama_barang }}
                    </div>
                </div>
                <div class="text-sm font-bold text-slate-600">
                    {{ number_format($product->total_qty) }} <span class="text-xs font-normal text-slate-400">Sold</span>
                </div>
            </div>
            @empty
            <div class="text-center text-slate-400 py-10 text-sm">Belum ada data penjualan</div>
            @endforelse
        </div>

        <h3 class="font-bold text-slate-700 text-lg mt-8 mb-4">Jam Sibuk (Hari ini)</h3>
        <div class="relative h-32 w-full">
            <canvas id="busyHourChart"></canvas>
        </div>
    </div>

</div>

{{-- AUDIT & MONITORING --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8 no-select">
    
    {{-- LOG AUDIT --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
            <h3 class="font-bold text-slate-700">Audit Log Terbaru</h3>
            <span class="text-xs text-slate-400">Aktivitas User</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-200 text-xs">
                    <tr>
                        <th class="px-4 py-3">User</th>
                        <th class="px-4 py-3">Aksi</th>
                        <th class="px-4 py-3">Detail</th>
                        <th class="px-4 py-3 text-right">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100" id="audit-log-body">
                    @forelse($logs as $log)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-4 py-3 font-medium text-slate-700">{{ $log->user->nama_user ?? 'System' }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded text-xs font-bold 
                                {{ $log->action == 'delete' ? 'bg-red-100 text-red-600' : 
                                   ($log->action == 'create' ? 'bg-emerald-100 text-emerald-600' : 'bg-blue-100 text-blue-600') }}">
                                {{ ucfirst($log->action) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-slate-500 truncate max-w-[150px]">{{ $log->target }}</td>
                        <td class="px-4 py-3 text-right text-slate-400 text-xs">{{ $log->created_at->diffForHumans() }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-slate-400 text-sm">Belum ada log aktivitas</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- STOCK MUTATION MONITOR --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
            <h3 class="font-bold text-slate-700">Mutasi Stok Terakhir</h3>
            <span class="text-xs text-slate-400">Monitor Stok</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-200 text-xs">
                    <tr>
                        <th class="px-4 py-3">Barang</th>
                        <th class="px-4 py-3 text-center">Jml</th>
                        <th class="px-4 py-3">Jenis</th>
                        <th class="px-4 py-3 text-right">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100" id="mutations-list-body">
                    @forelse($recentMutations as $mutasi)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-4 py-3 font-medium text-slate-700 max-w-[120px] truncate" title="{{ $mutasi->barang->nama_barang ?? '-' }}">
                            {{ $mutasi->barang->nama_barang ?? 'Item Dihapus' }}
                        </td>
                        <td class="px-4 py-3 text-center font-bold {{ $mutasi->jenis == 'masuk' ? 'text-emerald-600' : 'text-red-500' }}">
                            {{ $mutasi->jenis == 'masuk' ? '+' : '-' }}{{ $mutasi->jumlah }}
                        </td>
                        <td class="px-4 py-3 text-xs text-slate-500 capitalize">{{ $mutasi->jenis }}</td>
                        <td class="px-4 py-3 text-right text-slate-400 text-xs">{{ $mutasi->created_at->format('H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-slate-400 text-sm">Belum ada mutasi stok</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- ACTION SHORTCUTS --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <a href="{{ route('supervisor.backup') }}" class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-all flex flex-col items-center gap-2 group">
        <div class="w-10 h-10 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center group-hover:scale-110 transition-transform">
            💾
        </div>
        <span class="text-sm font-medium text-slate-600">Backup Database</span>
    </a>
    
    <a href="{{ route('supervisor.diskon.index') }}" class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-all flex flex-col items-center gap-2 group">
        <div class="w-10 h-10 rounded-full bg-pink-50 text-pink-600 flex items-center justify-center group-hover:scale-110 transition-transform">
            🏷️
        </div>
        <span class="text-sm font-medium text-slate-600">Kelola Diskon</span>
    </a>

    <a href="{{ route('supervisor.laporan.index') }}" class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-all flex flex-col items-center gap-2 group">
        <div class="w-10 h-10 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center group-hover:scale-110 transition-transform">
            📈
        </div>
        <span class="text-sm font-medium text-slate-600">Laporan Lengkap</span>
    </a>

    <a href="{{ route('supervisor.audit') }}" class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-all flex flex-col items-center gap-2 group">
        <div class="w-10 h-10 rounded-full bg-cyan-50 text-cyan-600 flex items-center justify-center group-hover:scale-110 transition-transform">
            📋
        </div>
        <span class="text-sm font-medium text-slate-600">Audit Log</span>
    </a>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Data passed from Controller
        const months = @json($months);
        const salesData = @json($salesData);
        const profitData = @json($profitData);
        const busyLabels = @json($busyHoursLabels);
        const busyData = @json($busyHoursData);

        // ... Charts Initialization ...

        // 1. MAIN CHART (Combined Sales & Profit)
        const ctxMain = document.getElementById('mainChart').getContext('2d');
        const mainChart = new Chart(ctxMain, {
            type: 'bar', // Default type, datasets define their own
            data: {
                labels: months,
                datasets: [
                    {
                        label: 'Penjualan (Unit)',
                        data: salesData,
                        type: 'bar',
                        backgroundColor: 'rgba(99, 102, 241, 0.5)', // Indigo
                        borderColor: 'rgba(99, 102, 241, 1)',
                        borderWidth: 1,
                        borderRadius: 4,
                        yAxisID: 'y',
                        hidden: false // Initial visible
                    },
                    {
                        label: 'Profit (Rp)',
                        data: profitData,
                        type: 'line',
                        borderColor: 'rgba(16, 185, 129, 1)', // Emerald
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        yAxisID: 'y1',
                        hidden: true // Initial hidden
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        beginAtZero: true,
                        title: { display: true, text: 'Unit' }
                    },
                    y1: {
                        type: 'linear',
                        display: false, // Initially hidden since profit hidden
                        position: 'right',
                        grid: { drawOnChartArea: false },
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + (value/1000) + 'k';
                            }
                        }
                    }
                }
            }
        });

        // --- CHART TOGGLE LOGIC ---
        let currentChartMode = 0; // 0: Sales, 1: Profit
        const chartTitleEl = document.getElementById('chartTitle');
        const chartFilterEl = document.getElementById('chartTimeFilter');
        
        function updateChartDisplay() {
            if (currentChartMode === 0) {
                // Show Sales
                mainChart.getDatasetMeta(0).hidden = false;
                mainChart.getDatasetMeta(1).hidden = true;
                mainChart.options.scales.y.display = true;
                mainChart.options.scales.y1.display = false;
                chartTitleEl.textContent = 'Tren Penjualan (Unit)';
            } else {
                // Show Profit
                mainChart.getDatasetMeta(0).hidden = true;
                mainChart.getDatasetMeta(1).hidden = false;
                mainChart.options.scales.y.display = false;
                mainChart.options.scales.y1.display = true;
                chartTitleEl.textContent = 'Tren Profit (Rupiah)';
            }
            mainChart.update();
        }

        document.getElementById('prevChartBtn').addEventListener('click', () => {
            currentChartMode = currentChartMode === 0 ? 1 : 0;
            updateChartDisplay();
        });

        document.getElementById('nextChartBtn').addEventListener('click', () => {
            currentChartMode = currentChartMode === 0 ? 1 : 0;
            updateChartDisplay();
        });

        // --- DATA FETCH LOGIC (Weekly/Monthly) ---
        function updateChartData(filter) {
            fetch(`{{ url('supervisor/chart-data') }}?filter=${filter}`)
                .then(r => r.json())
                .then(d => {
                    mainChart.data.labels = d.labels;
                    mainChart.data.datasets[0].data = d.sales;
                    mainChart.data.datasets[1].data = d.profit;
                    mainChart.update();
                })
                .catch(e => console.error("Chart data fetch failed", e));
        }

        if(chartFilterEl) {
            chartFilterEl.addEventListener('change', function(e) {
                updateChartData(e.target.value);
            });
        }

        // Initialize display
        updateChartDisplay();


        // AUTO REFRESH DASHBOARD (15s)
        setInterval(function() {
            // 1. Stats
            fetch('{{ route("supervisor.dashboard.data", ["section" => "stats"]) }}')
                .then(r => r.json())
                .then(data => {
                    if(document.getElementById('stats-omzet')) document.getElementById('stats-omzet').innerText = 'Rp ' + data.omzet;
                    if(document.getElementById('stats-transaksi')) document.getElementById('stats-transaksi').innerText = '+' + data.transaksi + ' Transaksi';
                    if(document.getElementById('stats-profit')) document.getElementById('stats-profit').innerText = 'Rp ' + data.profit;
                    if(document.getElementById('stats-discounts')) document.getElementById('stats-discounts').innerText = data.discounts;
                    if(document.getElementById('stats-mutations-count')) document.getElementById('stats-mutations-count').innerText = data.mutations;
                });

            // 2. Top Products
            fetch('{{ route("supervisor.dashboard.data", ["section" => "top_products"]) }}')
                .then(r => r.json()).then(d => {
                    if(document.getElementById('top-products-list')) document.getElementById('top-products-list').innerHTML = d.html;
                });
            
            // 3. Audit Log
            fetch('{{ route("supervisor.dashboard.data", ["section" => "audit"]) }}')
                .then(r => r.json()).then(d => {
                    if(document.getElementById('audit-log-body')) document.getElementById('audit-log-body').innerHTML = d.html;
                });

            // 4. Mutations List
            fetch('{{ route("supervisor.dashboard.data", ["section" => "mutations"]) }}')
                .then(r => r.json()).then(d => {
                    if(document.getElementById('mutations-list-body')) document.getElementById('mutations-list-body').innerHTML = d.html;
                });

            // 5. Refresh Main Chart (Based on Filter)
            const filter = document.getElementById('chartTimeFilter') ? document.getElementById('chartTimeFilter').value : 'monthly';
            fetch(`{{ url('supervisor/chart-data') }}?filter=${filter}`)
                .then(r => r.json())
                .then(d => {
                    mainChart.data.labels = d.labels;
                    mainChart.data.datasets[0].data = d.sales;
                    mainChart.data.datasets[1].data = d.profit;
                    mainChart.update();
                })
                .catch(e => console.error("Chart update failed", e));
            
        }, 15000);

        // 2. BUSY HOUR CHART
        const ctxBusy = document.getElementById('busyHourChart').getContext('2d');
        const gradientBusy = ctxBusy.createLinearGradient(0, 0, 0, 150);
        gradientBusy.addColorStop(0, 'rgba(249, 115, 22, 0.5)'); // Orange
        gradientBusy.addColorStop(1, 'rgba(249, 115, 22, 0.0)');

        new Chart(ctxBusy, {
            type: 'line',
            data: {
                labels: busyLabels,
                datasets: [{
                    label: 'Transaksi',
                    data: busyData,
                    borderColor: 'rgba(249, 115, 22, 1)',
                    backgroundColor: gradientBusy,
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 0,
                    pointHoverRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { maxTicksLimit: 8 }
                    },
                    y: {
                        display: false,
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
@endpush
