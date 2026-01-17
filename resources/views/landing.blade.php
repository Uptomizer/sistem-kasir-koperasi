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
