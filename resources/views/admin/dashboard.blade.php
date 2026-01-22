@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Overview Dashboard')

@section('content')

{{-- STAT CARDS --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 no-select">

    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
        <div class="flex justify-between items-start">
            <div>
                <h3 class="text-sm font-medium text-slate-500 uppercase tracking-wider">Total Barang</h3>
                <p class="text-3xl font-bold text-slate-800 mt-2" id="statTotalBarang">
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
                <p class="text-3xl font-bold text-slate-800 mt-2" id="statTotalKategori">
                    {{ number_format($totalKategori) }}
                </p>
            </div>
            <div class="p-3 bg-indigo-50 rounded-lg text-indigo-600">
                🏷️
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow relative overflow-hidden group">
        <!-- Slide Container -->
        <div id="statSlider" class="relative">
            <!-- Slide 1: Pendapatan -->
            <div class="w-full shrink-0 transition-opacity duration-700 opacity-100" id="slideRevenue">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-sm font-medium text-slate-500 uppercase tracking-wider flex items-center gap-2">
                            Pendapatan Minggu Ini 
                            <span class="text-[10px] bg-blue-100 text-blue-600 px-1.5 py-0.5 rounded-full">Revenue</span>
                        </h3>
                        <div class="mt-2">
                            <p class="text-3xl font-bold text-slate-800" id="statPendapatan">
                                Rp {{ number_format($pendapatanMingguIni, 0, ',', '.') }}
                            </p>
                            <p class="text-sm text-slate-500 mt-1 font-medium" id="statTransaksiCount">
                                {{ $totalTransaksiMingguIni }} Transaksi Berhasil
                            </p>
                        </div>
                    </div>
                    <div class="p-3 bg-emerald-50 rounded-lg text-emerald-600">
                        💰
                    </div>
                </div>
            </div>
            
            <!-- Slide 2: Keuntungan (Absolute on top) -->
            <div class="w-full shrink-0 absolute top-0 left-0 transition-opacity duration-700 opacity-0" id="slideProfit">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-sm font-medium text-slate-500 uppercase tracking-wider flex items-center gap-2">
                            Keuntungan Minggu Ini
                            <span class="text-[10px] bg-emerald-100 text-emerald-600 px-1.5 py-0.5 rounded-full">Profit</span>
                        </h3>
                        <div class="mt-2">
                            <p class="text-3xl font-bold text-emerald-600" id="statKeuntungan">
                                Rp {{ number_format($keuntunganMingguIni, 0, ',', '.') }}
                            </p>
                             <p class="text-sm text-emerald-600/70 mt-1 font-medium">
                                Margin Bersih
                            </p>
                        </div>
                    </div>
                    <div class="p-3 bg-emerald-50 rounded-lg text-emerald-600">
                        📈
                    </div>
                </div>
            </div>
        </div>

        <!-- Progress Bar Visual (Optional, helps user know when it changes) -->
        <div class="absolute bottom-0 left-0 h-1 bg-emerald-500/20 w-full">
            <div id="slideProgress" class="h-full bg-emerald-500 w-0 transition-all duration-[25000ms] ease-linear"></div>
        </div>
    </div>

</div>

{{-- CHARTS SECTION --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8 no-select">
    
    {{-- Sales Chart --}}
    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-bold text-slate-800 text-lg">Laporan Penjualan</h3>
            {{-- Arrow Filter Control --}}
            <div class="flex items-center gap-1 bg-slate-50 rounded-lg p-1 border border-slate-200">
                <button onclick="changeFilter('sales', -1)" 
                        class="p-1 text-slate-400 hover:text-blue-600 hover:bg-white rounded-md transition duration-200 shadow-sm hover:shadow">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <span id="salesFilterLabel" class="text-xs font-bold text-slate-700 w-[80px] text-center select-none uppercase tracking-wide">Bulanan</span>
                <button onclick="changeFilter('sales', 1)" 
                        class="p-1 text-slate-400 hover:text-blue-600 hover:bg-white rounded-md transition duration-200 shadow-sm hover:shadow">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </div>
        <div class="relative h-64 w-full">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    {{-- Profit Chart --}}
    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-bold text-slate-800 text-lg">Keuntungan</h3>
            {{-- Arrow Filter Control --}}
            <div class="flex items-center gap-1 bg-slate-50 rounded-lg p-1 border border-slate-200">
                <button onclick="changeFilter('profit', -1)" 
                        class="p-1 text-slate-400 hover:text-emerald-600 hover:bg-white rounded-md transition duration-200 shadow-sm hover:shadow">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <span id="profitFilterLabel" class="text-xs font-bold text-slate-700 w-[80px] text-center select-none uppercase tracking-wide">Bulanan</span>
                <button onclick="changeFilter('profit', 1)" 
                        class="p-1 text-slate-400 hover:text-emerald-600 hover:bg-white rounded-md transition duration-200 shadow-sm hover:shadow">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </div>
        <div class="relative h-64 w-full">
            <canvas id="profitChart"></canvas>
        </div>
    </div>

</div>

{{-- RIWAYAT TRANSAKSI HARI INI SECTION --}}
<div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden no-select">
    <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
        <h2 class="font-bold text-slate-800 text-lg">Riwayat Transaksi Hari Ini</h2>
        <a href="javascript:void(0)" onclick="openHistoryModal()" class="text-sm text-blue-600 hover:text-blue-700 font-medium">Lihat Semua →</a>
    </div>

    <div class="overflow-x-auto max-h-[400px] overflow-y-auto scrollbar-thin scrollbar-thumb-slate-200 hover:scrollbar-thumb-slate-300">
        <table class="w-full text-sm text-left relative">
            <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-200 sticky top-0 z-10 shadow-sm">
                <tr>
                    <th class="px-6 py-4 bg-slate-50">ID Transaksi</th>
                    <th class="px-6 py-4 bg-slate-50">Waktu</th>
                    <th class="px-6 py-4 bg-slate-50">Kasir</th>
                    <th class="px-6 py-4 text-right bg-slate-50">Total Belanja</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @include('admin.partials.recent_transactions_rows', ['recentTransactions' => $recentTransactions])
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let salesChartInstance = null;
    let profitChartInstance = null;

    document.addEventListener('DOMContentLoaded', function() {
        
        // Initial Data (passed from controller)
        const initialLabels = @json($months);
        const initialSales = @json($salesData);
        const initialProfit = @json($profitData);

        // 1. Initialize Sales Chart
        const ctxSales = document.getElementById('salesChart').getContext('2d');
        salesChartInstance = new Chart(ctxSales, {
            type: 'bar',
            data: {
                labels: initialLabels,
                datasets: [{
                    label: 'Terjual (Unit)',
                    data: initialSales,
                    backgroundColor: 'rgba(59, 130, 246, 0.6)', 
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
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

        // 2. Initialize Profit Chart
        const ctxProfit = document.getElementById('profitChart').getContext('2d');
        profitChartInstance = new Chart(ctxProfit, {
            type: 'line',
            data: {
                labels: initialLabels,
                datasets: [{
                    label: 'Keuntungan (Rp)',
                    data: initialProfit,
                    borderColor: 'rgba(16, 185, 129, 1)',
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
                plugins: { legend: { display: false } },
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

    // Function to update chart via AJAX
    // Filter Configuration
    const FILTER_MODES = ['daily', 'weekly', 'monthly'];
    const FILTER_LABELS = ['Hari Ini', 'Mingguan', 'Bulanan'];
    
    // Current State (Default: Monthly = index 2)
    let filterState = {
        'sales': 2,
        'profit': 2
    };

    function changeFilter(type, direction) {
        // Calculate new index with wrap-around
        let currentIndex = filterState[type];
        let newIndex = currentIndex + direction;
        
        if (newIndex < 0) newIndex = FILTER_MODES.length - 1;
        if (newIndex >= FILTER_MODES.length) newIndex = 0;
        
        // Update State
        filterState[type] = newIndex;
        
        // Update Label
        const labelEl = document.getElementById(type + 'FilterLabel');
        labelEl.textContent = FILTER_LABELS[newIndex];
        
        // Trigger Update
        updateChart(type);
    }

    function updateChart(type) {
        const index = filterState[type];
        const filter = FILTER_MODES[index];
        const endpoint = `{{ route('admin.chart.data') }}?filter=${filter}`;

        fetch(endpoint)
            .then(response => response.json())
            .then(data => {
                if (type === 'sales') {
                    // Update Sales Chart
                    salesChartInstance.data.labels = data.labels;
                    salesChartInstance.data.datasets[0].data = data.sales;
                    salesChartInstance.update();
                } else {
                    // Update Profit Chart
                    profitChartInstance.data.labels = data.labels;
                    profitChartInstance.data.datasets[0].data = data.profit;
                    profitChartInstance.update();
                }
            })
            .catch(error => console.error('Error fetching chart data:', error));
    }

    // --- HISTORY MODAL LOGIC ---
    let historyFilterState = 0; // 0=daily, 1=weekly, 2=monthly

    function openHistoryModal() {
        document.getElementById('historyModal').classList.remove('hidden');
        document.getElementById('historyModal').classList.add('flex');
        loadHistoryData();
    }

    function closeHistoryModal() {
        document.getElementById('historyModal').classList.add('hidden');
        document.getElementById('historyModal').classList.remove('flex');
    }

    function changeHistoryFilter(direction) {
        const MODES = ['daily', 'weekly', 'monthly'];
        const LABELS = ['Hari Ini', 'Mingguan', 'Bulanan'];

        let newIndex = historyFilterState + direction;
        if (newIndex < 0) newIndex = MODES.length - 1;
        if (newIndex >= MODES.length) newIndex = 0;

        historyFilterState = newIndex;
        document.getElementById('historyFilterLabel').textContent = LABELS[newIndex];
        
        loadHistoryData();
    }

    function loadHistoryData() {
        const MODES = ['daily', 'weekly', 'monthly'];
        const filter = MODES[historyFilterState];
        const contentDiv = document.getElementById('historyContent');
        
        contentDiv.innerHTML = '<div class="text-center py-10"><span class="loading loading-spinner text-blue-600"></span> Memuat data...</div>';

        fetch(`{{ route('admin.history.data') }}?filter=${filter}`)
            .then(res => res.json())
            .then(data => {
                contentDiv.innerHTML = data.html;
            })
            .catch(err => {
                console.error(err);
                contentDiv.innerHTML = '<div class="text-center py-10 text-red-500">Gagal memuat data.</div>';
            });
    }

    // --- DETAIL MODAL LOGIC ---
    // --- DETAIL MODAL LOGIC ---
    function openDetailModal(id) {
        document.getElementById('detailModal').classList.remove('hidden');
        document.getElementById('detailModal').classList.add('flex');
        
        const contentDiv = document.getElementById('detailContent');
        contentDiv.innerHTML = '<div class="text-center py-10 text-slate-500">Memuat detail...</div>';

        fetch(`{{ url('admin/transaksi') }}/${id}`)
            .then(res => res.json())
            .then(data => {
                let itemsHtml = '';
                data.items.forEach(item => {
                    itemsHtml += `
                        <div class="flex justify-between items-center py-2 border-b border-slate-50 last:border-0">
                            <div>
                                <div class="font-bold text-slate-800">${item.nama_barang}</div>
                                <div class="text-xs text-slate-500">${item.qty} x Rp ${new Intl.NumberFormat('id-ID').format(item.harga)}</div>
                            </div>
                            <div class="font-mono text-slate-700 font-bold">
                                Rp ${new Intl.NumberFormat('id-ID').format(item.subtotal)}
                            </div>
                        </div>
                    `;
                });

                contentDiv.innerHTML = `
                    <div class="space-y-4 no-select">
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-slate-500">No. Transaksi</span>
                                <span class="font-bold text-slate-800">#TRX-${data.id.toString().padStart(5, '0')}</span>
                            </div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-slate-500">Kasir</span>
                                <span class="font-bold text-slate-800">${data.kasir}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-500">Waktu</span>
                                <span class="font-bold text-slate-800">${new Date(data.tanggal).toLocaleString('id-ID')}</span>
                            </div>
                        </div>

                        <div>
                            <h4 class="font-bold text-slate-800 mb-2 border-b border-slate-200 pb-2">Item Belanja</h4>
                            <div class="max-h-60 overflow-y-auto pr-1">
                                ${itemsHtml}
                            </div>
                        </div>

                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 no-select">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-slate-600 font-medium">Total Belanja</span>
                                <span class="font-bold text-xl text-blue-600">Rp ${new Intl.NumberFormat('id-ID').format(data.total)}</span>
                            </div>
                            <div class="flex justify-between items-center mb-1 text-sm">
                                <span class="text-slate-500">Bayar (Cash)</span>
                                <span class="font-mono text-slate-700">Rp ${new Intl.NumberFormat('id-ID').format(data.bayar)}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm border-t border-slate-200 pt-2 mt-2">
                                <span class="text-slate-600 font-medium">Kembali</span>
                                <span class="font-bold text-emerald-600 font-mono">Rp ${new Intl.NumberFormat('id-ID').format(data.kembali)}</span>
                            </div>
                        </div>
                    </div>
                `;
            })
            .catch(err => {
                console.error(err);
                contentDiv.innerHTML = '<div class="text-center py-10 text-red-500">Gagal memuat detail transaksi.</div>';
            });
    }

    function closeDetailModal() {
        document.getElementById('detailModal').classList.add('hidden');
        document.getElementById('detailModal').classList.remove('flex');
    }

    // --- AUTO REFRESH LOGIC ---
    // --- AUTO REFRESH LOGIC ---
    function refereshDashboard() {
        // 1. Refresh Stats (Cards & Recent Table)
        fetch('{{ route("admin.dashboard.stats") }}')
            .then(res => res.json())
            .then(data => {
                // Update Counters
                document.querySelector('#statTotalBarang').innerText = data.total_barang;
                document.querySelector('#statTotalKategori').innerText = data.total_kategori;
                document.querySelector('#statPendapatan').innerText = data.pendapatan_minggu_ini;
                // Update Keuntungan
                if(document.getElementById('statKeuntungan')) {
                     document.getElementById('statKeuntungan').innerText = data.keuntungan_minggu_ini;
                }
                document.querySelector('#statTransaksiCount').innerText = data.transaksi_minggu_ini;

                // Update Recent Table (Only tbody)
                const tableBody = document.querySelector('table tbody');
                if(tableBody) tableBody.innerHTML = data.recent_html;
            })
            .catch(err => console.error('Error refreshing stats:', err));

        // 2. Refresh Charts (re-fetch current filter)
        updateChart('sales');
        updateChart('profit');
    }

    // Interval 15 seconds (15000 ms)
    setInterval(refereshDashboard, 15000);

    // --- SLIDER LOGIC ---
    const slideRevenue = document.querySelector('#statSlider > div:first-child');
    const slideProfit = document.getElementById('slideProfit');
    const progressBar = document.getElementById('slideProgress');
    let isRevenueShown = true;

    function toggleSlide() {
        if (!slideRevenue || !slideProfit) return;

        isRevenueShown = !isRevenueShown;
        
        if (isRevenueShown) {
            // Show Revenue (Fade In)
            slideRevenue.classList.remove('opacity-0');
            slideRevenue.classList.add('opacity-100');
            
            // Hide Profit (Fade Out)
            slideProfit.classList.add('opacity-0');
            slideProfit.classList.remove('opacity-100');
        } else {
             // Hide Revenue (Fade Out)
            slideRevenue.classList.add('opacity-0');
            slideRevenue.classList.remove('opacity-100');
            
             // Show Profit (Fade In)
            slideProfit.classList.remove('opacity-0');
            slideProfit.classList.add('opacity-100');
        }

        // Reset Progress Bar Animation
        if(progressBar) {
            progressBar.style.transition = 'none';
            progressBar.style.width = '0%';
            setTimeout(() => {
                progressBar.style.transition = 'width 25000ms linear';
                progressBar.style.width = '100%';
            }, 50);
        }
    }

    // Start Slider Interval (25 seconds)
    setInterval(toggleSlide, 25000);
    
    // Start initial progress
    if(progressBar) {
        setTimeout(() => {
             progressBar.style.width = '100%';
        }, 100);
    }
</script>
@endpush

{{-- HISTORY MODAL --}}
<div id="historyModal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm items-center justify-center p-4 no-select">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col overflow-hidden animate-fade-in-up">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
            <h3 class="font-bold text-slate-800 text-lg">Riwayat Transaksi Lengkap</h3>
            <div class="flex items-center gap-2">
                <div class="flex items-center gap-1 bg-white rounded-lg p-1 border border-slate-200 shadow-sm">
                    <button onclick="changeHistoryFilter(-1)" class="p-1 text-slate-400 hover:text-blue-600 hover:bg-slate-50 rounded-md transition duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                    </button>
                    <span id="historyFilterLabel" class="text-xs font-bold text-slate-700 w-[80px] text-center select-none uppercase tracking-wide">Hari Ini</span>
                    <button onclick="changeHistoryFilter(1)" class="p-1 text-slate-400 hover:text-blue-600 hover:bg-slate-50 rounded-md transition duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </button>
                </div>
                <button onclick="closeHistoryModal()" class="p-2 text-slate-400 hover:text-red-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
        </div>
        <div id="historyContent" class="p-6 overflow-y-auto bg-slate-50/30 flex-1"></div>
    </div>
</div>

{{-- DETAIL MODAL --}}
<div id="detailModal" class="fixed inset-0 z-[60] hidden bg-slate-900/50 backdrop-blur-sm items-center justify-center p-4 no-select">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md flex flex-col overflow-hidden animate-fade-in-up">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
            <h3 class="font-bold text-slate-800 text-lg">Detail Transaksi</h3>
            <button onclick="closeDetailModal()" class="text-slate-400 hover:text-slate-600 text-2xl">&times;</button>
        </div>
        <div id="detailContent" class="p-6 overflow-y-auto bg-white max-h-[80vh]"></div>
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
