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
<section class="bg-white py-20">
    <div class="max-w-6xl mx-auto px-6">

        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">
                Solusi Digital Koperasi Sekolah
            </h2>
            <p class="text-gray-600 max-w-2xl mx-auto text-lg">
                Didesain khusus untuk meningkatkan efisiensi operasional koperasi dengan fitur-fitur modern dan mudah digunakan.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

            {{-- CARD 1: KASIR --}}
            <div class="group relative p-8 bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                
                <div class="relative z-10">
                    <div class="w-16 h-16 mb-6 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center text-3xl shadow-sm group-hover:scale-110 transition-transform duration-300">
                        🖥️
                    </div>
                    
                    <h3 class="text-xl font-bold text-gray-800 mb-3 group-hover:text-blue-700 transition-colors">
                        Sistem Kasir Pintar
                    </h3>
                    
                    <p class="text-gray-600 leading-relaxed">
                        Antarmuka kasir yang responsif memudahkan input transaksi penjualan. Dilengkapi fitur pencarian barang cepat untuk melayani siswa tanpa antrian panjang.
                    </p>
                </div>
            </div>

            {{-- CARD 2: INVENTARIS --}}
            <div class="group relative p-8 bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-emerald-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                <div class="relative z-10">
                    <div class="w-16 h-16 mb-6 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-3xl shadow-sm group-hover:scale-110 transition-transform duration-300">
                        📦
                    </div>

                    <h3 class="text-xl font-bold text-gray-800 mb-3 group-hover:text-emerald-700 transition-colors">
                        Manajemen Stok Lengkap
                    </h3>

                    <p class="text-gray-600 leading-relaxed">
                        Kelola ribuan item barang dan kategori dengan mudah. Pantau ketersediaan stok secara real-time untuk menghindari kekosongan barang di koperasi.
                    </p>
                </div>
            </div>

            {{-- CARD 3: LAPORAN --}}
            <div class="group relative p-8 bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-purple-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                <div class="relative z-10">
                    <div class="w-16 h-16 mb-6 rounded-2xl bg-purple-100 text-purple-600 flex items-center justify-center text-3xl shadow-sm group-hover:scale-110 transition-transform duration-300">
                        📈
                    </div>

                    <h3 class="text-xl font-bold text-gray-800 mb-3 group-hover:text-purple-700 transition-colors">
                        Laporan & Grafik Analitik
                    </h3>

                    <p class="text-gray-600 leading-relaxed">
                        Dapatkan wawasan bisnis melalui grafik penjualan harian dan bulanan. Rekap laporan keuangan dapat diekspor otomatis untuk kebutuhan administrasi.
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- FITUR LAINNYA SECTION --}}
<section class="bg-slate-50 py-20">
    <div class="max-w-6xl mx-auto px-6">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">
                Fitur Unggulan Lainnya
            </h2>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Kelengkapan fitur yang mendukung operasional koperasi anda sehari-hari.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <!-- Item 1: Multi-User -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-start gap-5 transition-all duration-300 hover:shadow-lg hover:-translate-y-1 hover:border-blue-200 group">
                <div class="bg-blue-50 text-blue-600 p-3 rounded-xl shrink-0 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800 mb-2">Akses Multi-Level</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Pemisahan hak akses yang jelas antara <strong>Administrator</strong> (pengelola penuh) dan <strong>Kasir</strong> (fokus penjualan) demi keamanan data.
                    </p>
                </div>
            </div>

            <!-- Item 2: Dashboard -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-start gap-5 transition-all duration-300 hover:shadow-lg hover:-translate-y-1 hover:border-indigo-200 group">
                <div class="bg-indigo-50 text-indigo-600 p-3 rounded-xl shrink-0 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800 mb-2">Dashboard Statistik</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Visualisasi data penjualan harian, mingguan, dan bulanan dalam bentuk grafik interaktif yang mudah dipahami.
                    </p>
                </div>
            </div>

            <!-- Item 3: History -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-start gap-5 transition-all duration-300 hover:shadow-lg hover:-translate-y-1 hover:border-emerald-200 group">
                <div class="bg-emerald-50 text-emerald-600 p-3 rounded-xl shrink-0 group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800 mb-2">Riwayat Transaksi</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Rekaman lengkap setiap transaksi yang terjadi, memudahkan penelusuran data penjualan lampau secara detail.
                    </p>
                </div>
            </div>

            <!-- Item 4: Fast Search -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-start gap-5 transition-all duration-300 hover:shadow-lg hover:-translate-y-1 hover:border-orange-200 group">
                <div class="bg-orange-50 text-orange-600 p-3 rounded-xl shrink-0 group-hover:bg-orange-600 group-hover:text-white transition-colors duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800 mb-2">Pencarian Instan</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Fitur pencarian barang real-time (Ajax) memungkinkan kasir menemukan produk dalam hitungan ke-sekian detik.
                    </p>
                </div>
            </div>

            <!-- Item 5: Kategori -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-start gap-5 transition-all duration-300 hover:shadow-lg hover:-translate-y-1 hover:border-rose-200 group">
                <div class="bg-rose-50 text-rose-600 p-3 rounded-xl shrink-0 group-hover:bg-rose-600 group-hover:text-white transition-colors duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800 mb-2">Kategori Produk</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Pengelompokan barang berdasarkan kategori (ATK, Makanan, Seragam, dll) untuk manajemen inventaris yang lebih rapi.
                    </p>
                </div>
            </div>

            <!-- Item 6: Export -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-start gap-5 transition-all duration-300 hover:shadow-lg hover:-translate-y-1 hover:border-purple-200 group">
                <div class="bg-purple-50 text-purple-600 p-3 rounded-xl shrink-0 group-hover:bg-purple-600 group-hover:text-white transition-colors duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800 mb-2">Ekspor Laporan</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Unduh laporan penjualan dan stok dalam format digital untuk kebutuhan arsip, audit, atau pelaporan sekolah.
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
