@extends('layouts.admin')

@section('title', 'Statistik Penjualan')
@section('page-title', 'Statistik Barang Terlaris')

@section('content')
<div class="space-y-6">
    {{-- Filters --}}
    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 bg-slate-50/50">
        <form action="{{ route('admin.statistik.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="text-xs font-bold text-slate-500 uppercase block mb-1">Bulan</label>
                <select name="month" class="text-sm border-slate-300 rounded-lg px-4 py-2 w-40">
                    @for($i=1; $i<=12; $i++)
                        <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>
                            {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                        </option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="text-xs font-bold text-slate-500 uppercase block mb-1">Tahun</label>
                <select name="year" class="text-sm border-slate-300 rounded-lg px-4 py-2 w-32">
                    @for($i=date('Y'); $i>=date('Y')-5; $i--)
                        <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg text-sm font-bold shadow-lg shadow-blue-600/20 hover:bg-blue-700 transition">
                Tampilkan
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        {{-- Top 10 Best Sellers (Qty) --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden flex flex-col h-full">
            <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-green-50/50">
                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                    <span class="bg-green-100 text-green-600 p-1.5 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 8.586 15.586 4H12z" clip-rule="evenodd" /></svg>
                    </span>
                    Top 10 Terlaris (Qty)
                </h3>
                <span class="text-xs font-bold bg-white text-slate-500 border px-2 py-1 rounded">
                    {{ date('F Y', mktime(0, 0, 0, $month, 1, $year)) }}
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100">
                        <tr>
                            <th class="px-5 py-3 w-10">Rank</th>
                            <th class="px-5 py-3">Barang</th>
                            <th class="px-5 py-3 text-right">Terjual</th>
                            <th class="px-5 py-3 text-right">Total (Rp)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($topItems as $index => $item)
                        <tr class="hover:bg-slate-50/50">
                            <td class="px-5 py-3 font-mono text-slate-400 font-bold">#{{ $index + 1 }}</td>
                            <td class="px-5 py-3">
                                <div class="font-bold text-slate-700">{{ $item->barang->nama_barang ?? 'Deleted Item' }}</div>
                                <div class="text-[10px] text-slate-400">{{ $item->barang->kode_barang ?? '-' }}</div>
                            </td>
                            <td class="px-5 py-3 text-right font-bold text-green-600 text-lg">
                                {{ $item->total_qty }}
                            </td>
                            <td class="px-5 py-3 text-right text-slate-600">
                                Rp {{ number_format($item->total_revenue) }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-8 text-slate-400">Belum ada data penjualan periode ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Top Kategori --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden flex flex-col h-full">
            <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-blue-50/50">
                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                    <span class="bg-blue-100 text-blue-600 p-1.5 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z" /></svg>
                    </span>
                    Kategori Populer
                </h3>
            </div>
            <div class="p-6">
                 {{-- Simple Bar Chart Visualization using CSS --}}
                 <div class="space-y-4">
                    @forelse($topCategories as $cat)
                        @php
                            $maxQty = $topCategories->max('total_qty');
                            $percent = $maxQty > 0 ? ($cat->total_qty / $maxQty) * 100 : 0;
                        @endphp
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="font-medium text-slate-700">{{ $cat->nama_kategori }}</span>
                                <span class="font-bold text-slate-800">{{ $cat->total_qty }} item</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                                <div class="bg-blue-500 h-2.5 rounded-full transition-all duration-500" style="width: {{ $percent }}%"></div>
                            </div>
                            <div class="text-[10px] text-slate-400 mt-1 text-right">Rp {{ number_format($cat->total_revenue) }}</div>
                        </div>
                    @empty
                        <div class="text-center text-slate-400 italic">Belum ada data.</div>
                    @endforelse
                 </div>
            </div>
        </div>

    </div>
    
    {{-- Top Revenue Table (Optional / Full Width) --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-5 border-b border-slate-100 bg-amber-50/50">
            <h3 class="font-bold text-slate-800 flex items-center gap-2">
                <span class="bg-amber-100 text-amber-600 p-1.5 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" /><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd" /></svg>
                </span>
                Top 10 Kontributor Omset (Revenue)
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 w-10">Rank</th>
                        <th class="px-6 py-4">Barang</th>
                        <th class="px-6 py-4 text-center">Terjual</th>
                        <th class="px-6 py-4 text-right">Total Omset</th>
                        <th class="px-6 py-4 text-right w-48">Kontribusi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @php $grandTotal = $topRevenue->sum('total_revenue'); @endphp
                    @forelse($topRevenue as $index => $item)
                    @php $share = $grandTotal > 0 ? ($item->total_revenue / $grandTotal) * 100 : 0; @endphp
                    <tr class="hover:bg-slate-50/50">
                        <td class="px-6 py-4 font-mono text-slate-400 font-bold">#{{ $index + 1 }}</td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-700">{{ $item->barang->nama_barang ?? 'Deleted' }}</div>
                        </td>
                        <td class="px-6 py-4 text-center font-mono text-slate-600">
                            {{ $item->total_qty }}
                        </td>
                        <td class="px-6 py-4 text-right font-bold text-amber-600">
                            Rp {{ number_format($item->total_revenue) }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-full bg-slate-100 rounded-full h-1.5 flex-1">
                                    <div class="bg-amber-500 h-1.5 rounded-full" style="width: {{ $share }}%"></div>
                                </div>
                                <span class="text-[10px] text-slate-400 font-mono w-8 text-right">{{ number_format($share, 1) }}%</span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-8 text-slate-400">Belum ada data.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
