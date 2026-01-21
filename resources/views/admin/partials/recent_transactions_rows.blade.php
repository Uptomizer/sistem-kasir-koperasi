@forelse ($recentTransactions as $trx)
    <tr class="hover:bg-slate-50/80 transition-colors">
        <td class="px-6 py-4 font-medium text-slate-800">
            <span class="bg-slate-100 text-slate-600 px-2 py-1 rounded text-xs border border-slate-200">
                #TRX-{{ str_pad($trx->id_penjualan, 5, '0', STR_PAD_LEFT) }}
            </span>
        </td>
        <td class="px-6 py-4 text-slate-600">
            {{ \Carbon\Carbon::parse($trx->created_at)->format('H:i') }} WIB
        </td>
        <td class="px-6 py-4 text-slate-600">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center text-xs font-bold text-blue-600 overflow-hidden">
                    @if($trx->user->profile_photo)
                        <img src="{{ asset('storage/profile-photos/' . $trx->user->profile_photo) }}" alt="Profile" class="w-full h-full object-cover">
                    @else
                        {{ substr($trx->user->nama_user ?? '?', 0, 1) }}
                    @endif
                </div>
                {{ $trx->user->nama_user ?? 'Kasir' }}
            </div>
        </td>
        <td class="px-6 py-4 text-right font-bold text-emerald-600">
            +Rp {{ number_format($trx->total, 0, ',', '.') }}
        </td>
    </tr>
@empty
    <tr>
        <td colspan="4" class="text-center py-10 text-slate-500">
            <div class="flex flex-col items-center gap-3 py-4">
                <div class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="font-medium">Belum ada transaksi hari ini</div>
                <div class="text-xs text-slate-400">Transaksi baru akan muncul di sini.</div>
            </div>
        </td>
    </tr>
@endforelse
