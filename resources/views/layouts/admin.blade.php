<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - Koperasi</title>
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
        
        {{-- Brand Section --}}
        <div class="h-20 flex items-center px-8 border-b border-white/5 bg-gradient-to-r from-transparent to-white/[0.02]">
            <div class="flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-blue-500/20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                    </svg>
                </div>
                <div>
                    <span class="font-bold">
                        Koperasi<span class="text-blue-600">App</span>
                    </span>
                    <p class="text-[10px] uppercase tracking-widest text-blue-400 font-semibold mt-1">Admin Panel</p>
                </div>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-1 custom-scrollbar">
            
            <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest px-4 mb-4 mt-2">
                Menu Utama
            </div>

            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group border border-transparent
               {{ request()->routeIs('admin.dashboard') 
                   ? 'bg-blue-600/10 text-blue-400 border-blue-600/20 shadow-sm' 
                   : 'hover:bg-white/5 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition-colors {{ request()->routeIs('admin.dashboard') ? 'text-blue-400' : 'text-slate-400 group-hover:text-blue-400' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="7" height="9"></rect>
                    <rect x="14" y="3" width="7" height="5"></rect>
                    <rect x="14" y="12" width="7" height="9"></rect>
                    <rect x="3" y="16" width="7" height="5"></rect>
                </svg>
                <span class="font-medium text-sm">Dashboard</span>
            </a>

            <a href="{{ route('admin.kategori.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group border border-transparent
               {{ request()->routeIs('admin.kategori.*') 
                   ? 'bg-blue-600/10 text-blue-400 border-blue-600/20 shadow-sm' 
                   : 'hover:bg-white/5 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition-colors {{ request()->routeIs('admin.kategori.*') ? 'text-blue-400' : 'text-slate-400 group-hover:text-blue-400' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                    <line x1="7" y1="7" x2="7.01" y2="7"></line>
                </svg>
                <span class="font-medium text-sm">Kategori</span>
            </a>

            <a href="{{ route('admin.barang.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group border border-transparent
               {{ request()->routeIs('admin.barang.*') 
                   ? 'bg-blue-600/10 text-blue-400 border-blue-600/20 shadow-sm' 
                   : 'hover:bg-white/5 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition-colors {{ request()->routeIs('admin.barang.*') ? 'text-blue-400' : 'text-slate-400 group-hover:text-blue-400' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                    <line x1="12" y1="22.08" x2="12" y2="12"></line>
                </svg>
                <span class="font-medium text-sm">Barang</span>
            </a>

            <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest px-4 mb-4 mt-8">
                Laporan & Sistem
            </div>

            <a href="{{ route('admin.laporan.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group border border-transparent
               {{ request()->routeIs('admin.laporan.*') 
                   ? 'bg-blue-600/10 text-blue-400 border-blue-600/20 shadow-sm' 
                   : 'hover:bg-white/5 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition-colors {{ request()->routeIs('admin.laporan.*') ? 'text-blue-400' : 'text-slate-400 group-hover:text-blue-400' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                    <polyline points="10 9 9 9 8 9"></polyline>
                </svg>
                <span class="font-medium text-sm">Laporan</span>
            </a>

            <a href="{{ route('admin.kasir.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group border border-transparent
               {{ request()->routeIs('admin.kasir.*') 
                   ? 'bg-blue-600/10 text-blue-400 border-blue-600/20 shadow-sm' 
                   : 'hover:bg-white/5 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition-colors {{ request()->routeIs('admin.kasir.*') ? 'text-blue-400' : 'text-slate-400 group-hover:text-blue-400' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
                <span class="font-medium text-sm">Akun Kasir</span>
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
                        <div class="text-sm font-semibold text-slate-700">Administrator</div>
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
@stack('scripts')
</body>
</html>
