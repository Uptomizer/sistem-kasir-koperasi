@extends('layouts.app')

@section('title', 'Sistem Kasir Koperasi SMK')

@section('content')
<div class="no-select">
{{-- WELCOME SPLASH --}}
<div id="landingSplash"
     class="fixed inset-0 bg-white z-[9999]
            flex items-center justify-center">

    <h1
        class="text-3xl md:text-5xl font-bold text-blue-900
               opacity-0 animate-welcome-in text-center px-6">
        Sistem Kasir & Inventaris<br>
        Koperasi Sekolah
    </h1>
</div>

{{-- HERO SECTION --}}
<section
    class="relative min-h-screen flex items-center justify-center overflow-hidden bg-cover bg-center"
    style="background-image: url('{{ asset('images/koperasi-bg.jpg') }}');"
>

    {{-- Overlay --}}
    <div class="absolute inset-0 bg-white/80 backdrop-blur-sm"></div>

    {{-- Awan --}}
    <div class="absolute inset-0 pointer-events-none">
        <div class="cloud cloud-1"></div>
        <div class="cloud cloud-2"></div>
        <div class="cloud cloud-3"></div>
    </div>

    {{-- Content --}}
    <div class="relative z-10 text-center max-w-2xl px-6">
        <h1 class="text-4xl md:text-5xl font-bold text-blue-900 mb-4 h-[120px]">
            <span id="typing-text"></span>
            <span class="typing-cursor">|</span>
        </h1>

        <p class="text-gray-700 mb-8 leading-relaxed">
            Aplikasi modern untuk membantu koperasi sekolah dalam
            mencatat transaksi penjualan dan mengelola stok barang
            secara efisien, rapi, dan terstruktur.
        </p>

        <a href="{{ route('login') }}"
           class="inline-flex items-center justify-center
                  bg-blue-700 text-white
                  px-8 py-3 rounded-xl
                  shadow-lg hover:bg-blue-800
                  transition duration-200">
            Masuk ke Sistem
        </a>
    </div>
</section>

{{-- FITUR SECTION --}}
<section class="bg-white py-16">
    <div class="max-w-6xl mx-auto px-6">

        <h2 class="text-2xl font-bold text-center text-gray-800 mb-10">
            Fitur Utama Aplikasi
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

    {{-- CARD 1 --}}
    <div
        class="group border rounded-xl p-6 text-center
               transition-all duration-300 ease-out
               hover:-translate-y-2 hover:shadow-xl
               hover:border-blue-600">

        <div
            class="text-blue-700 text-4xl mb-3
                   transition-transform duration-300
                   group-hover:scale-110">
            ⚡
        </div>

        <h3
            class="font-semibold mb-2
                   transition-colors duration-300
                   group-hover:text-blue-700">
            Kasir Cepat
        </h3>

        <p class="text-sm text-gray-600">
            Proses transaksi cepat tanpa hitung manual.
        </p>
    </div>

    {{-- CARD 2 --}}
    <div
        class="group border rounded-xl p-6 text-center
               transition-all duration-300 ease-out
               hover:-translate-y-2 hover:shadow-xl
               hover:border-blue-600">

        <div
            class="text-blue-700 text-4xl mb-3
                   transition-transform duration-300
                   group-hover:scale-110">
            📦
        </div>

        <h3
            class="font-semibold mb-2
                   transition-colors duration-300
                   group-hover:text-blue-700">
            Inventaris Barang
        </h3>

        <p class="text-sm text-gray-600">
            Pantau stok barang koperasi secara real-time.
        </p>
    </div>

    {{-- CARD 3 --}}
    <div
        class="group border rounded-xl p-6 text-center
               transition-all duration-300 ease-out
               hover:-translate-y-2 hover:shadow-xl
               hover:border-blue-600">

        <div
            class="text-blue-700 text-4xl mb-3
                   transition-transform duration-300
                   group-hover:scale-110">
            📊
        </div>

        <h3
            class="font-semibold mb-2
                   transition-colors duration-300
                   group-hover:text-blue-700">
            Laporan Penjualan
        </h3>

        <p class="text-sm text-gray-600">
            Rekap laporan otomatis dan terstruktur.
        </p>
    </div>

</div>

    </div>
</section>

{{-- FITUR LAINNYA SECTION --}}
<section class="bg-slate-50 py-16">
    <div class="max-w-6xl mx-auto px-6">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-12">
            Fitur Unggulan Lainnya
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <!-- Item 1 -->
            <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-100 flex items-start gap-4 transition-all duration-300 hover:shadow-lg hover:-translate-y-1 hover:border-blue-200">
                <div class="bg-blue-100 text-blue-600 p-2.5 rounded-lg shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800 mb-1">Multi-User Role</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Akses khusus untuk Administrator dan Kasir dengan batasan hak akses yang aman.
                    </p>
                </div>
            </div>

            <!-- Item 2 -->
            <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-100 flex items-start gap-4 transition-all duration-300 hover:shadow-lg hover:-translate-y-1 hover:border-indigo-200">
                <div class="bg-indigo-100 text-indigo-600 p-2.5 rounded-lg shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800 mb-1">Dashboard Real-time</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Pantau performa penjualan, total pendapatan, dan keuntungan harian secara langsung.
                    </p>
                </div>
            </div>

            <!-- Item 3 -->
            <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-100 flex items-start gap-4 transition-all duration-300 hover:shadow-lg hover:-translate-y-1 hover:border-emerald-200">
                <div class="bg-emerald-100 text-emerald-600 p-2.5 rounded-lg shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800 mb-1">Laporan Keuangan</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Rekap otomatis pendapatan dan keuntungan bersih untuk memudahkan audit keuangan.
                    </p>
                </div>
            </div>

            <!-- Item 4 -->
            <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-100 flex items-start gap-4 transition-all duration-300 hover:shadow-lg hover:-translate-y-1 hover:border-orange-200">
                <div class="bg-orange-100 text-orange-600 p-2.5 rounded-lg shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800 mb-1">Desain Responsif</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Tampilan antarmuka yang modern, responsif, dan mudah diakses dari perangkat tablet atau laptop.
                    </p>
                </div>
            </div>

            <!-- Item 5 -->
            <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-100 flex items-start gap-4 transition-all duration-300 hover:shadow-lg hover:-translate-y-1 hover:border-rose-200">
                <div class="bg-rose-100 text-rose-600 p-2.5 rounded-lg shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800 mb-1">Manajemen Kategori</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Kelompokkan barang berdasarkan kategori untuk kemudahan pencarian dan pengelolaan inventaris.
                    </p>
                </div>
            </div>

            <!-- Item 6 -->
            <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-100 flex items-start gap-4 transition-all duration-300 hover:shadow-lg hover:-translate-y-1 hover:border-purple-200">
                <div class="bg-purple-100 text-purple-600 p-2.5 rounded-lg shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800 mb-1">Sistem Aman</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Dilengkapi dengan autentikasi pengguna yang aman untuk melindungi data transaksi dan stok.
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>
</div>
{{-- ================= ANIMATION SCRIPT ================= --}}
<script>
const texts = [
    'Sistem Kasir & Inventaris Koperasi',
    'Satu Aplikasi Untuk Semua'
]

let textIndex = 0
let charIndex = 0
let typing = true
const speed = 80
const pause = 1500

const el = document.getElementById('typing-text')

function typeLoop() {
    if (typing) {
        if (charIndex < texts[textIndex].length) {
            el.textContent += texts[textIndex].charAt(charIndex)
            charIndex++
            setTimeout(typeLoop, speed)
        } else {
            typing = false
            setTimeout(typeLoop, pause)
        }
    } else {
        if (charIndex > 0) {
            el.textContent = texts[textIndex].substring(0, charIndex - 1)
            charIndex--
            setTimeout(typeLoop, speed / 2)
        } else {
            typing = true
            textIndex = (textIndex + 1) % texts.length
            setTimeout(typeLoop, 400)
        }
    }
}

typeLoop()
</script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
        const splash = document.getElementById('landingSplash')
        if (splash) {
            splash.classList.add('fade-out')
            setTimeout(() => splash.remove(), 700)
        }
    }, 2500)
})
</script>

{{-- ================= CLOUD ANIMATION CSS ================= --}}
<style>
.cloud {
    position: absolute;
    background: white;
    opacity: 0.6;
    border-radius: 9999px;
    filter: blur(1px);
}

.cloud::before,
.cloud::after {
    content: '';
    position: absolute;
    background: white;
    width: 100%;
    height: 100%;
    border-radius: 9999px;
}

.cloud-1 {
    width: 120px;
    height: 40px;
    top: 20%;
    left: -150px;
    animation: moveCloud 60s linear infinite;
}

.cloud-2 {
    width: 180px;
    height: 60px;
    top: 45%;
    left: -200px;
    animation: moveCloud 90s linear infinite;
}

.cloud-3 {
    width: 100px;
    height: 35px;
    top: 65%;
    left: -120px;
    animation: moveCloud 75s linear infinite;
}

@keyframes moveCloud {
    from {
        transform: translateX(0);
    }
    to {
        transform: translateX(120vw);
    }
}

.typing-cursor {
    animation: blink 1s infinite;
}

@keyframes blink {
    0%, 50%, 100% { opacity: 1; }
    25%, 75% { opacity: 0; }
}
</style>
<style>
@keyframes welcomeIn {
    from {
        opacity: 0;
        transform: translateY(12px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes welcomeOut {
    from {
        opacity: 1;
    }
    to {
        opacity: 0;
    }
}

.animate-welcome-in {
    animation: welcomeIn 0.7s ease-out forwards;
}

.fade-out {
    animation: welcomeOut 0.7s ease-in forwards;
}
</style>


@endsection
