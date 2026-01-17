@forelse ($penjualan as $trx)
<div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-md transition-shadow">
    
    {{-- Card Header --}}
    <div class="bg-slate-50/50 px-6 py-4 border-b border-slate-100 flex flex-col md:flex-row justify-between md:items-center gap-2">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <div>
                <div class="text-sm text-slate-500">No. Transaksi #{{ 1000 + $loop->iteration }} (ID: {{ $trx->id }})</div>
                <div class="font-bold text-slate-800">
                    {{ \Carbon\Carbon::parse($trx->tanggal)->translatedFormat('l, d F Y - H:i') }}
                </div>
            </div>
        </div>
        
        <div class="flex items-center gap-3 bg-white px-3 py-1.5 rounded-full border border-slate-200 shadow-sm">
            <div class="w-2 h-2 rounded-full bg-green-500"></div>
            <span class="text-xs font-medium text-slate-600">
                Kasir: <span class="text-slate-900 font-bold">{{ $trx->user->nama_user ?? 'Umum' }}</span>
            </span>
        </div>
    </div>

    {{-- Table Items --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-200">
                <tr>
                    <th class="px-6 py-3 w-1/2">Barang</th>
                    <th class="px-6 py-3 text-center">Qty</th>
                    <th class="px-6 py-3 text-right">Harga Satuan</th>
                    <th class="px-6 py-3 text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach ($trx->detail as $item)
                <tr class="hover:bg-slate-50/50">
                    <td class="px-6 py-3 font-medium text-slate-800">
                        {{ $item->barang->nama_barang ?? 'Barang Dihapus' }}
                    </td>
                    <td class="px-6 py-3 text-center text-slate-600">
                        {{ $item->jumlah }}
                    </td>
                    <td class="px-6 py-3 text-right text-slate-600 font-mono">
                        Rp {{ number_format($item->harga) }}
                    </td>
                    <td class="px-6 py-3 text-right font-bold text-slate-700 font-mono">
                        Rp {{ number_format($item->subtotal) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-slate-50/50 border-t border-slate-100">
                <tr>
                    <td colspan="3" class="px-6 py-4 text-right font-bold text-slate-600 uppercase tracking-wider text-xs">Total Transaksi</td>
                    <td class="px-6 py-4 text-right font-bold text-blue-700 text-lg">
                        Rp {{ number_format($trx->total) }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

</div>
@empty
<div class="text-center py-16 bg-white rounded-xl border border-dashed border-slate-300">
    <div class="p-4 bg-slate-50 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4 text-slate-400">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 011.414.414l5 5a1 1 0 01.414 1.414V19a2 2 0 01-2 2z" />
        </svg>
    </div>
    <h3 class="text-lg font-medium text-slate-900">Belum ada data transaksi</h3>
    <p class="text-slate-500 max-w-sm mx-auto mt-2">
        Silakan pilih tanggal lain atau lakukan transaksi penjualan di menu Kasir.
    </p>
</div>
@endforelse

{{-- PAGINATION --}}
<div class="mt-8 pagination-links">
    {{ $penjualan->links() }}
</div>
