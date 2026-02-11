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

{{-- MAIN FEATURES SECTION --}}
<section class="bg-white py-24 relative overflow-hidden">
    {{-- Background Decors --}}
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
        <div class="absolute top-10 left-10 w-64 h-64 bg-blue-50 rounded-full mix-blend-multiply filter blur-3xl opacity-30"></div>
        <div class="absolute bottom-10 right-10 w-64 h-64 bg-purple-50 rounded-full mix-blend-multiply filter blur-3xl opacity-30"></div>
    </div>

    <div class="max-w-7xl mx-auto px-6 relative z-10">
        
        <div class="text-center mb-20 animate-on-scroll">
            <h2 class="text-3xl md:text-5xl font-bold text-slate-900 mb-6 tracking-tight">
                Fitur Lengkap, Tampilan Sederhana
            </h2>
            <p class="text-slate-600 max-w-2xl mx-auto text-lg leading-relaxed">
                Platform kasir modern yang dirancang khusus untuk kebutuhan Koperasi Sekolah. Menggabungkan kemudahan transaksi dengan kedalaman analisis data.
            </p>
        </div>

        {{-- GRID UTAMA (CORE FEATURES) --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-24 animate-on-scroll">
            {{-- Feature 1 --}}
            <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-xl shadow-slate-200/40 hover:shadow-2xl hover:shadow-blue-200/40 hover:-translate-y-1 transition-all duration-300 group">
                <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-3xl mb-6 group-hover:scale-110 transition-transform">
                    🖥️
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">Kasir Pintar (POS)</h3>
                <p class="text-slate-600 leading-relaxed text-sm">
                    Transaksi super cepat dengan pencarian barang otomatis (Ajax). Interface yang bersih memudahkan petugas kasir bekerja efisien.
                </p>
            </div>

            {{-- Feature 2 --}}
            <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-xl shadow-slate-200/40 hover:shadow-2xl hover:shadow-emerald-200/40 hover:-translate-y-1 transition-all duration-300 group">
                <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center text-3xl mb-6 group-hover:scale-110 transition-transform">
                    📋
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">Stok Opname Digital</h3>
                <p class="text-slate-600 leading-relaxed text-sm">
                    Validasi stok fisik vs sistem kini lebih mudah. Fitur opname digital dengan sistem approval supervisor untuk mencegah selisih stok.
                </p>
            </div>

            {{-- Feature 3 --}}
            <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-xl shadow-slate-200/40 hover:shadow-2xl hover:shadow-purple-200/40 hover:-translate-y-1 transition-all duration-300 group">
                <div class="w-14 h-14 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center text-3xl mb-6 group-hover:scale-110 transition-transform">
                    📈
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">Analitik Profit</h3>
                <p class="text-slate-600 leading-relaxed text-sm">
                    Pantau keuntungan bersih, omset, dan tren penjualan harian/mingguan secara real-time melalui Dashboard Supervisor yang canggih.
                </p>
            </div>
        </div>

        {{-- DETAILED FEATURES LIST (ZIG ZAG SIMPLE) --}}
        <div class="space-y-24">
            
            {{-- Row 1: Barcode & Print --}}
            <div class="flex flex-col md:flex-row items-center gap-12 animate-on-scroll">
                <div class="w-full md:w-1/2">
                    <div class="bg-slate-50 rounded-3xl p-8 border border-slate-100 relative overflow-hidden group hover:border-blue-100 transition-colors">
                        <div class="absolute right-0 top-0 p-3 opacity-10 text-9xl select-none group-hover:scale-110 transition-transform duration-500">🏷️</div>
                        <div class="relative z-10 flex flex-col items-center justify-center h-48 sm:h-64 text-center space-y-4">
                            <div class="w-16 h-16 bg-white rounded-full shadow-sm flex items-center justify-center text-3xl">🖨️</div>
                            <div class="font-mono text-slate-500 bg-white px-3 py-1 rounded border border-slate-200 text-sm">TRX-0001-2024</div>
                        </div>
                    </div>
                </div>
                <div class="w-full md:w-1/2">
                    <h3 class="text-3xl font-bold text-slate-900 mb-4">Barcode System & Printing</h3>
                    <p class="text-slate-600 text-lg leading-relaxed mb-6">
                        Tak perlu alat mahal. Sistem kami bisa <strong>Generate Barcode</strong> sendiri untuk barang yang belum memiliki label. Support cetak label massal dan printer thermal 58mm/80mm untuk struk.
                    </p>
                    <div class="flex flex-wrap gap-3">
                        <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-sm font-semibold">Auto Generate</span>
                        <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-sm font-semibold">Thermal Printer</span>
                        <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-sm font-semibold">Label Cetak</span>
                    </div>
                </div>
            </div>

            {{-- Row 2: Audit & Security --}}
            <div class="flex flex-col md:flex-row-reverse items-center gap-12 animate-on-scroll">
                <div class="w-full md:w-1/2">
                    <div class="bg-slate-50 rounded-3xl p-8 border border-slate-100 relative overflow-hidden group hover:border-emerald-100 transition-colors">
                         <div class="absolute right-0 top-0 p-3 opacity-10 text-9xl select-none group-hover:scale-110 transition-transform duration-500">🛡️</div>
                         <div class="relative z-10 flex flex-col items-center justify-center h-48 sm:h-64 text-center space-y-4">
                            <div class="w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600 text-4xl mb-2">👁️</div>
                            <div class="bg-white border border-slate-200 px-4 py-2 rounded-lg text-slate-700 font-bold shadow-sm">
                                Activity Log Recorded
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-full md:w-1/2">
                    <h3 class="text-3xl font-bold text-slate-900 mb-4">Audit Log & Keamanan Data</h3>
                    <p class="text-slate-600 text-lg leading-relaxed mb-6">
                        Transparansi total. Setiap aktivitas (Input, Edit, Hapus) tercatat otomatis dalam <strong>Audit Log</strong>. Sistem Role-Based (Admin, Supervisor, Kasir) menjaga integritas data sensistif dari akses tidak sah.
                    </p>
                    <div class="flex flex-wrap gap-3">
                        <span class="px-3 py-1 bg-emerald-50 text-emerald-700 rounded-full text-sm font-semibold">User Tracking</span>
                        <span class="px-3 py-1 bg-emerald-50 text-emerald-700 rounded-full text-sm font-semibold">Role Based</span>
                        <span class="px-3 py-1 bg-emerald-50 text-emerald-700 rounded-full text-sm font-semibold">Anti Fraud</span>
                    </div>
                </div>
            </div>

            {{-- Row 3: Discounts & Promo --}}
             <div class="flex flex-col md:flex-row items-center gap-12 animate-on-scroll">
                <div class="w-full md:w-1/2">
                    <div class="bg-slate-50 rounded-3xl p-8 border border-slate-100 relative overflow-hidden group hover:border-pink-100 transition-colors">
                        <div class="absolute right-0 top-0 p-3 opacity-10 text-9xl select-none group-hover:scale-110 transition-transform duration-500">🏷️</div>
                        <div class="relative z-10 flex flex-col items-center justify-center h-48 sm:h-64 text-center space-y-4">
                            <div class="w-20 h-20 bg-pink-100 rounded-full flex items-center justify-center text-pink-600 text-4xl mb-2">%</div>
                            <div class="bg-white border border-slate-200 px-4 py-2 rounded-lg text-slate-700 font-bold shadow-sm">
                                Diskon Aktif: Akhir Tahun
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-full md:w-1/2">
                    <h3 class="text-3xl font-bold text-slate-900 mb-4">Manajemen Diskon & Promosi</h3>
                    <p class="text-slate-600 text-lg leading-relaxed mb-6">
                        Tingkatkan penjualan dengan strategi harga yang fleksibel. Buat promo diskon (Nominal/Persen), atur periode aktif, dan sistem akan otomatis memotong harga saat transaksi.
                    </p>
                    <div class="flex flex-wrap gap-3">
                        <span class="px-3 py-1 bg-pink-50 text-pink-700 rounded-full text-sm font-semibold">Auto Discount</span>
                        <span class="px-3 py-1 bg-pink-50 text-pink-700 rounded-full text-sm font-semibold">Flash Sale</span>
                        <span class="px-3 py-1 bg-pink-50 text-pink-700 rounded-full text-sm font-semibold">Promo Management</span>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>
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
