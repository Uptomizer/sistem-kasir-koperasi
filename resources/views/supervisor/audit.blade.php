@extends('layouts.supervisor')

@section('title', 'Audit Log')
@section('page-title', 'Rekaman Aktivitas Sistem')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 mb-8">
    <div class="mb-6">
        <h3 class="font-bold text-slate-800 text-lg">Log Aktivitas User</h3>
        <p class="text-slate-500 text-sm">Memantau setiap perubahan data penting dalam sistem.</p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-200 uppercase tracking-wider text-xs">
                <tr>
                    <th class="px-6 py-4">Waktu</th>
                    <th class="px-6 py-4">User</th>
                    <th class="px-6 py-4">Role</th>
                    <th class="px-6 py-4">Aksi</th>
                    <th class="px-6 py-4">Target / Entitas</th>
                    <th class="px-6 py-4">Detail Tambahan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100" id="audit-logs-body">
                @include('supervisor.partials.audit_rows')
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $logs->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        setInterval(function() {
            const url = new URL(window.location.href);
            fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.text())
            .then(html => {
               if(document.getElementById('audit-logs-body')) {
                   document.getElementById('audit-logs-body').innerHTML = html;
               }
            })
            .catch(error => console.error('Error refreshing audit logs:', error));
        }, 15000);
    });
</script>
@endpush
