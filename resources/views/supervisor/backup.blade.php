@extends('layouts.supervisor')

@section('title', 'Backup Database')
@section('page-title', 'Pusat Keamanan & Pemulihan')

@section('content')
<div class="max-w-3xl mx-auto space-y-8">
    
    {{-- Info Card --}}
    <div class="bg-blue-50 border border-blue-100 rounded-xl p-6 flex gap-4 items-start">
        <div class="p-3 bg-blue-100 text-blue-600 rounded-lg shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div>
            <h3 class="font-bold text-blue-800 text-lg mb-1">Backup Berkala</h3>
            <p class="text-blue-600/80 text-sm leading-relaxed">
                Fitur ini memungkinkan Anda mengunduh seluruh data aplikasi (Barang, Transaksi, User, dll) dalam format JSON. 
                Simpan file ini di tempat aman. File ini dapat digunakan untuk pemulihan data jika terjadi masalah.
            </p>
        </div>
    </div>

    {{-- Download Card --}}
    <div class="bg-white rounded-xl shadow-lg border border-slate-100 p-8 text-center">
        <div class="w-20 h-20 bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
            </svg>
        </div>
        
        <h3 class="text-2xl font-bold text-slate-800 mb-2">Unduh Database</h3>
        <p class="text-slate-500 mb-8 max-w-md mx-auto">
            Klik tombol di bawah untuk memulai proses backup. Tanggal dan waktu akan otomatis disertakan dalam nama file.
        </p>

        <form action="{{ route('supervisor.backup.download') }}" method="POST">
            @csrf
            <button type="submit" class="bg-gradient-to-r from-indigo-600 to-blue-600 text-white px-8 py-3 rounded-full font-bold shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 hover:scale-105 transition-all active:scale-95 flex items-center gap-3 mx-auto">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Download Backup (.json)
            </button>
        </form>

        <div class="mt-8 pt-8 border-t border-slate-100 text-xs text-slate-400">
            @php
                $lastBackup = \App\Models\ActivityLog::where('action', 'backup')->latest()->first();
            @endphp
            Terakhir dibackup: <span class="font-mono text-slate-500">{{ $lastBackup ? $lastBackup->created_at->diffForHumans() : 'Belum pernah' }}</span>
            (Log tidak selalu akurat jika backup dilakukan manual)
        </div>
    </div>

</div>
@endsection
