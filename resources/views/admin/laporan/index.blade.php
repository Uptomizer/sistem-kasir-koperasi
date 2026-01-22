@extends('layouts.admin')

@section('title', 'Laporan Penjualan')
@section('page-title', 'Laporan Penjualan')

@section('content')

{{-- FILTER SECTION --}}
<div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 mb-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        
        <div class="flex-1">
             <h3 class="text-slate-800 font-bold text-lg">Semua Data Penjualan</h3>
             <p class="text-slate-500 text-sm">Rekapitulasi lengkap riwayat transaksi.</p>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('admin.laporan.export') }}"
               class="bg-emerald-600 text-white px-5 py-2 rounded-lg font-medium text-sm
                      shadow-md shadow-emerald-600/20 hover:bg-emerald-700 hover:shadow-emerald-700/30
                      transition-all active:scale-95 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
                Export Excel
            </a>
        </div>
    </div>
</div>

{{-- SUMMARY / PROFIT CARD --}}
@if ($penjualan->count())
<div class="bg-white rounded-xl shadow-sm border border-emerald-100 p-6 mb-8 flex items-center gap-4 bg-emerald-50/30">
    <div class="p-3 bg-emerald-100 text-emerald-600 rounded-lg">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
    </div>
    <div class="flex-1">
        <div class="flex items-center justify-between mb-1">
            <div class="text-sm font-medium text-emerald-800 uppercase tracking-wider">Total Keuntungan</div>
            
            {{-- Arrow Filters --}}
            <div class="flex items-center gap-1 bg-emerald-100/50 rounded-lg p-0.5 border border-emerald-100">
                <button onclick="changeProfitFilter(-1)" 
                        class="p-1 text-emerald-600 hover:text-emerald-800 hover:bg-emerald-200 rounded transition duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <span id="profitFilterLabel" class="text-[10px] font-bold text-emerald-700 w-[70px] text-center select-none uppercase tracking-wide">
                    {{ request('filter_profit', 'harian') }}
                </span>
                <button onclick="changeProfitFilter(1)" 
                        class="p-1 text-emerald-600 hover:text-emerald-800 hover:bg-emerald-200 rounded transition duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </div>
        
        <div id="total-profit-amount" class="text-3xl font-bold text-emerald-600">
            Rp {{ number_format($totalKeuntunganHarian) }}
        </div>
        <div class="text-xs text-emerald-600 mt-1" id="profit-subtitle">
            {{ request('tanggal') ? \Carbon\Carbon::parse(request('tanggal'))->translatedFormat('d F Y') : 'Hari Ini' }}
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
        const profitAmount  = document.getElementById('total-profit-amount');
        const filterForm    = document.getElementById('filter-form');

        // Function to fetchData
        function fetchData(url, silent = false) {
            // Visual loading state only if not silent
            if (!silent) {
                listContainer.style.opacity = '0.5';
                listContainer.style.pointerEvents = 'none';
            }

            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                // Update List
                listContainer.innerHTML = data.html;
                
                // Update Total Profit if element exists
                if(profitAmount && (data.totalProfit !== undefined)) {
                    profitAmount.textContent = data.totalProfit;
                }
                
                // Update Profit Subtitle
                const subtitleEl = document.getElementById('profit-subtitle');
                if(subtitleEl && data.profitSubtitle) {
                    subtitleEl.textContent = data.profitSubtitle;
                }

                if (!silent) {
                    // Restore State
                    listContainer.style.opacity = '1';
                    listContainer.style.pointerEvents = 'auto';

                    // Scroll to top of list only on manual navigation
                    listContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            })
            .catch(error => {
                console.error('Error fetching data:', error);
                if (!silent) {
                    listContainer.style.opacity = '1';
                    listContainer.style.pointerEvents = 'auto';
                }
            });
        }

        // Handle Form Filter
        if(filterForm) {
            filterForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const queryParams = new URLSearchParams(formData).toString();
                const url = `${this.action}?${queryParams}`;

                // Update URL Browser without reload
                window.history.pushState(null, '', url);
                
                fetchData(url);
            });
        }

        // Handle Pagination Links
        listContainer.addEventListener('click', function(e) {
            const link = e.target.closest('.pagination-links a, nav[role="navigation"] a');
            
            if (link) {
                e.preventDefault();
                const url = link.getAttribute('href');
                
                if(url) {
                    // Update URL Browser
                    window.history.pushState(null, '', url);
                    fetchData(url);
                }
            }
        });

        // Handle Browser Back/Forward buttons
        window.addEventListener('popstate', function() {
            fetchData(window.location.href);
        });

        // Auto Refresh every 15 seconds (15000ms) - Silent
        setInterval(() => {
            fetchData(window.location.href, true);
        }, 15000);
    });

    // --- PROFIT FILTER LOGIC ---
    const PROFIT_MODES = ['harian', 'mingguan', 'bulanan', 'tahunan'];
    const PROFIT_LABELS = ['Hari Ini', 'Mingguan', 'Bulanan', 'Tahunan'];
    let currentProfitIndex = 0;

    // Sync initial state from URL or Default
    const urlParams = new URLSearchParams(window.location.search);
    const initialFilter = urlParams.get('filter_profit') || 'harian';
    currentProfitIndex = PROFIT_MODES.indexOf(initialFilter);
    if (currentProfitIndex === -1) currentProfitIndex = 0; // fallback

    // Update Label UI immediately
    const labelEl = document.getElementById('profitFilterLabel');
    if(labelEl) labelEl.textContent = PROFIT_LABELS[currentProfitIndex];

    window.changeProfitFilter = function(direction) {
        currentProfitIndex += direction;
        
        // Wrap around
        if (currentProfitIndex < 0) currentProfitIndex = PROFIT_MODES.length - 1;
        if (currentProfitIndex >= PROFIT_MODES.length) currentProfitIndex = 0;

        const newFilter = PROFIT_MODES[currentProfitIndex];
        const newLabel = PROFIT_LABELS[currentProfitIndex];
        
        // Update Label
        document.getElementById('profitFilterLabel').textContent = newLabel;

        // Update URL
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('filter_profit', newFilter);
        
        // Remove 'tanggal' if switching to general filters to avoid confusion? 
        // No, let's keep it specific: "Daily profit for X date", "Monthly profit containing X date"
        
        window.history.pushState(null, '', currentUrl.toString());
        
        // Trigger Fetch from Global Scope (need to access fetchData inside DOMLoaded... wait)
        // Since fetchData is inside closure, we reload page or better yet, make fetchData accessible or trigger event.
        // Easiest here: dispatch custom event or use the popstate handler by pushing state.
        // Actually, let's just trigger a popstate event manually or just reload for simplicity?
        // Better: Dispatch 'popstate' to trigger the listener we added.
        window.dispatchEvent(new Event('popstate'));
    }


    // --- DETAIL MODAL LOGIC (Copied from Dashboard) ---
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
                    <div class="space-y-4">
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

                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
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
</script>
@endpush
<div class="no-select">
{{-- DETAIL MODAL --}}
<div id="detailModal" class="fixed inset-0 z-[60] hidden bg-slate-900/50 backdrop-blur-sm items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md flex flex-col overflow-hidden animate-fade-in-up">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
            <h3 class="font-bold text-slate-800 text-lg">Detail Transaksi</h3>
            <button onclick="closeDetailModal()" class="text-slate-400 hover:text-slate-600 text-2xl">&times;</button>
        </div>
        <div id="detailContent" class="p-6 overflow-y-auto bg-white max-h-[80vh]"></div>
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
