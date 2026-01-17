<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Kasir') - Koperasi</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
    <aside class="fixed inset-y-0 left-0 w-64 bg-green-900 text-white z-50 shadow-2xl transition-transform duration-300 ease-in-out">
        
        {{-- Logo Section --}}
        <div class="h-16 flex items-center gap-3 px-6 border-b border-green-800 bg-green-900">
            <div class="w-8 h-8 rounded-lg bg-green-500 flex items-center justify-center shadow-lg shadow-green-500/20">
                <span class="font-bold text-lg text-white">K</span>
            </div>
            <div class="font-bold text-lg tracking-tight">
                Koperasi<span class="text-green-400">Kasir</span>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="p-4 space-y-1 overflow-y-auto h-[calc(100vh-4rem)]">
            
            <div class="text-xs font-semibold text-green-400/80 uppercase tracking-wider mb-2 mt-2 px-2">
                Menu Transaksi
            </div>

            <a href="{{ route('kasir.dashboard') }}"
               class="group flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200
               {{ request()->routeIs('kasir.dashboard') 
                   ? 'bg-green-700 text-white shadow-lg shadow-green-900/20' 
                   : 'text-green-100/70 hover:bg-green-800 hover:text-white' }}">
                <span class="text-xl">🛒</span>
                <span class="font-medium">Transaksi Baru</span>
            </a>

        </nav>
    </aside>

    {{-- MAIN CONTENT WRAPPER --}}
    <div class="ml-64 min-h-screen flex flex-col transition-all duration-300">
        
        {{-- TOPBAR --}}
        <header class="h-16 glass-header sticky top-0 z-40 px-8 flex justify-between items-center bg-white/80">
            
            {{-- Page Title --}}
            <div class="flex items-center gap-4">
                <h1 class="text-xl font-bold text-slate-800 tracking-tight">
                    @yield('page-title', 'Dashboard Kasir')
                </h1>
            </div>

            {{-- Right Actions --}}
            <div class="flex items-center gap-4">
                
                {{-- User Profile --}}
                <div class="flex items-center gap-3 pl-6 border-l border-gray-200">
                    <div class="text-right hidden md:block">
                        <div class="text-sm font-semibold text-slate-700">{{ Auth::user()->name ?? 'Kasir' }}</div> 
                    </div>
                    <div class="w-10 h-10 rounded-full bg-green-100 border-2 border-white shadow-sm overflow-hidden flex items-center justify-center text-green-600 font-bold">
                        {{ substr(Auth::user()->name ?? 'K', 0, 1) }}
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

<x-toast />
<x-welcome />
</body>
</html>
