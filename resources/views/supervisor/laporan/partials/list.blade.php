<div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4">ID Transaksi</th>
                    <th class="px-6 py-4">Waktu</th>
                    <th class="px-6 py-4">Kasir</th>
                    <th class="px-6 py-4 text-right">Total Belanja</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($penjualan as $trx)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4 font-medium text-slate-800">
                            <span class="bg-slate-100 text-slate-600 px-2 py-1 rounded text-xs border border-slate-200">
                                #TRX-{{ str_pad($trx->id_penjualan, 5, '0', STR_PAD_LEFT) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-600">
                            {{ \Carbon\Carbon::parse($trx->tanggal)->translatedFormat('d M Y, H:i') }} WIB
                        </td>
                        <td class="px-6 py-4 text-slate-600">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-xs font-bold text-blue-600 overflow-hidden relative">
                                    @if($trx->user && $trx->user->profile_photo)
                                        <img src="{{ asset('storage/' . $trx->user->profile_photo) }}" alt="Profile" class="w-full h-full object-cover">
                                    @else
                                        <span class="absolute inset-0 flex items-center justify-center bg-blue-100 text-blue-600">
                                            {{ substr($trx->user->nama_user ?? '?', 0, 1) }}
                                        </span>
                                    @endif
                                </div>
                                {{ $trx->user->nama_user ?? 'Kasir' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right font-bold text-emerald-600">
                            +Rp {{ number_format($trx->total, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            {{-- Optional: Add a button to view details if needed later --}}
                            <button onclick="openDetailModal({{ $trx->id_penjualan }})" 
                                    class="text-blue-600 hover:text-blue-800 text-xs font-medium bg-blue-50 px-3 py-1.5 rounded-full transition-colors hover:bg-blue-100">
                                Detail
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-10 text-slate-500">
                            <div class="flex flex-col items-center gap-2">
                                <span class="text-2xl opacity-50">🧾</span>
                                <div>Belum ada data transaksi</div>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- PAGINATION --}}
<div class="mt-8 pagination-links">
    @if($penjualan instanceof \Illuminate\Pagination\LengthAwarePaginator)
        {{ $penjualan->links() }}
    @endif
</div>
