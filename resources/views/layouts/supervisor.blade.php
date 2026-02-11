<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Supervisor Dashboard') - Koperasi</title>
    @vite('resources/css/app.css')

    <style>
        body { font-family: 'Inter', sans-serif; }
        .glass-header {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(229, 231, 235, 0.5);
        }
    </style>
</head>
<body class="bg-slate-50 text-gray-800 antialiased">
<div class="no-select">

    {{-- FIXED SIDEBAR --}}
    <aside class="fixed inset-y-0 left-0 w-64 bg-[#0B1120] text-slate-300 z-50 transition-transform duration-300 ease-in-out border-r border-slate-800/50 flex flex-col">
        
        @php
            $activeClass = 'bg-slate-700/50 text-slate-200 border-slate-600/50 shadow-sm';
            $inactiveClass = 'hover:bg-white/5 hover:text-white';
            $iconActive = 'text-slate-200';
            $iconInactive = 'text-slate-500 group-hover:text-slate-200';
        @endphp

        {{-- Brand Section --}}
        <div class="h-20 flex items-center px-8 border-b border-white/5 bg-gradient-to-r from-transparent to-white/[0.02]">
            <div class="flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-slate-600 to-slate-700 flex items-center justify-center shadow-lg shadow-slate-500/20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                    </svg>
                </div>
                <div>
                    <span class="font-bold">
                        Koperasi<span class="text-slate-400">App</span>
                    </span>
                    <p class="text-[10px] uppercase tracking-widest text-slate-500 font-semibold mt-1">
                        Supervisor
                    </p>
                </div>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-1 custom-scrollbar">
            
            <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest px-4 mb-4 mt-2">
                Menu Utama
            </div>

            <a href="{{ route('supervisor.dashboard') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group border border-transparent
               {{ request()->routeIs('supervisor.dashboard')
                   ? $activeClass 
                   : $inactiveClass }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition-colors {{ request()->routeIs('supervisor.dashboard') ? $iconActive : $iconInactive }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="7" height="9"></rect>
                    <rect x="14" y="3" width="7" height="5"></rect>
                    <rect x="14" y="12" width="7" height="9"></rect>
                    <rect x="3" y="16" width="7" height="5"></rect>
                </svg>
                <span class="font-medium text-sm">Dashboard</span>
            </a>

            <a href="{{ route('supervisor.diskon.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group border border-transparent
               {{ request()->routeIs('supervisor.diskon.*') 
                   ? $activeClass 
                   : $inactiveClass }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition-colors {{ request()->routeIs('supervisor.diskon.*') ? $iconActive : $iconInactive }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
                </svg>
                <span class="font-medium text-sm">Diskon & Promo</span>
            </a>

            <a href="{{ route('supervisor.riwayat-stok.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group border border-transparent
               {{ request()->routeIs('supervisor.riwayat-stok.*') 
                   ? $activeClass 
                   : $inactiveClass }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition-colors {{ request()->routeIs('supervisor.riwayat-stok.*') ? $iconActive : $iconInactive }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                </svg>
                <span class="font-medium text-sm">Riwayat Stok</span>
            </a>

            <a href="{{ route('supervisor.stok-opname.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group border border-transparent
               {{ request()->routeIs('supervisor.stok-opname.*') 
                   ? $activeClass 
                   : $inactiveClass }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition-colors {{ request()->routeIs('supervisor.stok-opname.*') ? $iconActive : $iconInactive }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                <span class="font-medium text-sm">Verifikasi Stok</span>
            </a>

            <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest px-4 mb-4 mt-8">
                Laporan & Sistem
            </div>

            <a href="{{ route('supervisor.laporan.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group border border-transparent
               {{ request()->routeIs('supervisor.laporan.*') 
                   ? $activeClass 
                   : $inactiveClass }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition-colors {{ request()->routeIs('supervisor.laporan.*') ? $iconActive : $iconInactive }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                    <polyline points="10 9 9 9 8 9"></polyline>
                </svg>
                <span class="font-medium text-sm">Laporan</span>
            </a>

            <a href="{{ route('supervisor.audit') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group border border-transparent
               {{ request()->routeIs('supervisor.audit') 
                   ? $activeClass 
                   : $inactiveClass }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition-colors {{ request()->routeIs('supervisor.audit') ? $iconActive : $iconInactive }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span class="font-medium text-sm">Log Audit</span>
            </a>

            <a href="{{ route('supervisor.backup') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group border border-transparent
               {{ request()->routeIs('supervisor.backup') 
                   ? $activeClass 
                   : $inactiveClass }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition-colors {{ request()->routeIs('supervisor.backup') ? $iconActive : $iconInactive }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                </svg>
                <span class="font-medium text-sm">Backup DB</span>
            </a>
            
        </nav>

        {{-- Sidebar Footer --}}
        <div class="px-6 py-4 border-t border-white/5 bg-black/10">
            <div class="flex items-center gap-3">
                <div class="flex-1">
                    <p class="text-xs font-medium text-white">Sistem Inventaris</p>
                    <p class="text-[10px] text-slate-500">Koperasi</p>
                </div>
            </div>
        </div>
    </aside>

    {{-- MAIN CONTENT WRAPPER --}}
    <div class="ml-64 min-h-screen flex flex-col transition-all duration-300">
        
        {{-- TOPBAR --}}
        <header class="h-16 glass-header sticky top-0 z-40 px-8 flex justify-between items-center bg-white/80">
            
            {{-- Page Title --}}
            <div class="flex items-center gap-4">
                <h1 class="text-xl font-bold text-slate-800 tracking-tight">
                    @yield('page-title', 'Dashboard')
                </h1>
            </div>

            {{-- Right Actions --}}
            <div class="flex items-center gap-4">
                
                {{-- User Profile --}}
                <div class="flex items-center gap-3 pl-6 border-l border-gray-200">
                    <div class="text-right hidden md:block">
                        <div class="text-sm font-semibold text-slate-700">
                            Supervisor
                        </div>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-slate-200 border-2 border-white shadow-sm overflow-hidden flex items-center justify-center text-slate-500">
                        👤
                    </div>
                    
                    <form id="logoutForm" method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="button" 
                                onclick="openLogoutModal()"
                                class="p-2 text-slate-400 hover:text-red-600 transition-colors rounded-lg hover:bg-red-50"
                                title="Logout">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                        </button>
                    </form>
                </div>
            </div>
        </header>

        {{-- PAGE CONTENT --}}
        <main class="p-8">
            <div class="max-w-7xl mx-auto">
                <div class="animate-fade-in-up">
                    @yield('content')
                </div>
            </div>
        </main>

        {{-- FOOTER --}}
        <footer class="mt-auto py-6 text-center text-sm text-slate-400">
            <p>&copy; {{ date('Y') }} Koperasi. All rights reserved.</p>
        </footer>

    </div>

</div>

{{-- Simple Animation Style --}}
<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.4s ease-out forwards;
    }
</style>

{{-- LOGOUT MODAL --}}
<div class="no-select">
<div id="logoutModal"
     class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 hidden">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm animate-modal-in p-6 text-center">
            
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4 text-red-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
            </div>

            <h3 class="text-xl font-bold text-slate-800 mb-2">Keluar Aplikasi?</h3>
            <p class="text-slate-500 mb-6">Anda akan diarahkan kembali ke halaman login.</p>

            <div class="flex gap-3 justify-center">
                <button type="button"
                        onclick="closeLogoutModal()"
                        class="px-5 py-2.5 rounded-xl text-slate-600 hover:bg-slate-100 font-medium transition-colors w-full cursor-pointer">
                    Batal
                </button>
                <button type="button"
                        id="logoutConfirmBtn"
                        onclick="confirmLogout()"
                        class="px-5 py-2.5 rounded-xl bg-red-600 text-white hover:bg-red-700 font-medium transition-colors w-full shadow-lg shadow-red-600/30 cursor-pointer flex items-center justify-center gap-2">
                    Ya, Keluar
                </button>
            </div>

        </div>
    </div>
</div>
</div>
<script>
    function openLogoutModal() {
        document.getElementById('logoutModal').classList.remove('hidden')
    }

    function closeLogoutModal() {
        document.getElementById('logoutModal').classList.add('hidden')
    }

    function confirmLogout() {
        const btn = document.getElementById('logoutConfirmBtn');
        btn.disabled = true;
        btn.innerHTML = `
            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Keluar...
        `;
        document.getElementById('logoutForm').submit();
    }

    // Close modal on Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeLogoutModal();
        }
    });
</script>

<x-welcome />
@stack('modals')
@stack('scripts')
</body>
</html>
