@forelse($logs as $log)
<tr class="hover:bg-slate-50/50 transition-colors">
    <td class="px-6 py-4 text-slate-500 whitespace-nowrap">
        <div class="font-medium text-slate-700">{{ $log->created_at->format('d M Y') }}</div>
        <div class="text-xs">{{ $log->created_at->format('H:i:s') }}</div>
    </td>
    <td class="px-6 py-4 font-medium text-slate-800">
        <div class="flex items-center gap-2">
            @if($log->user && $log->user->profile_photo)
                <img src="{{ asset('storage/' . $log->user->profile_photo) }}" alt="Profile" class="w-6 h-6 rounded-full object-cover">
            @else
                <div class="w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center text-[10px] font-bold text-slate-500">
                    {{ substr($log->user->nama_user ?? '?', 0, 1) }}
                </div>
            @endif
            {{ $log->user->nama_user ?? 'System / Deleted User' }}
        </div>
    </td>
    <td class="px-6 py-4 text-xs text-slate-500 uppercase">
        {{ $log->user->role ?? '-' }}
    </td>
    <td class="px-6 py-4">
        <span class="px-2 py-1 rounded text-xs font-bold 
        {{ $log->action == 'delete' ? 'bg-red-100 text-red-600' : 
            ($log->action == 'create' ? 'bg-emerald-100 text-emerald-600' : 
            ($log->action == 'update' ? 'bg-amber-100 text-amber-600' : 'bg-blue-100 text-blue-600')) }}">
            {{ ucfirst($log->action) }}
        </span>
    </td>
    <td class="px-6 py-4 text-slate-700 font-medium">
        {{ $log->target }}
    </td>
    <td class="px-6 py-4 text-slate-500 text-xs italic max-w-xs truncate" title="{{ $log->details }}">
        {{ $log->details ?? '-' }}
    </td>
</tr>
@empty
<tr>
    <td colspan="6" class="text-center py-10 text-slate-400">
        Belum ada aktivitas tercatat.
    </td>
</tr>
@endforelse
