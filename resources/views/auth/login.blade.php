@extends('layouts.app')
<style>
.typing-cursor {
    animation: blink 1s infinite;
}

@keyframes blink {
    0%, 50%, 100% { opacity: 1; }
    25%, 75% { opacity: 0; }
}
</style>

@section('title', 'Login | Sistem Kasir Koperasi SMK')

@section('content')
<div class="no-select">
<section
    class="relative min-h-screen flex items-center justify-center overflow-hidden bg-cover bg-center"
    style="background-image: url('{{ asset('images/koperasi-bg.jpg') }}');">
    <div id="loadingOverlay"
     class="hidden fixed inset-0 bg-white/70 backdrop-blur-sm
            flex items-center justify-center z-50">

    <div class="text-center">
        <div class="animate-spin rounded-full h-12 w-12
                    border-4 border-blue-700 border-t-transparent mx-auto mb-4"></div>
        <p class="text-blue-900 font-semibold">
            Memproses login...
        </p>
    </div>
</div>


    {{-- Overlay --}}
    <div class="absolute inset-0 bg-white/80 backdrop-blur-sm"></div>

    {{-- Awan --}}
    <div class="absolute inset-0 pointer-events-none">
        <div class="cloud cloud-1"></div>
        <div class="cloud cloud-2"></div>
        <div class="cloud cloud-3"></div>
    </div>

    {{-- Login Card --}}
    <div class="relative z-10 w-full max-w-md bg-white/90 rounded-2xl shadow-xl p-8">

        <h1 class="text-2xl font-bold text-center text-blue-900 mb-2 h-[32px]">
    <span id="login-title"></span>
    <span id="cursor-title" class="typing-cursor">|</span>
</h1>

<p class="text-center text-sm text-gray-600 mb-6 h-[20px]">
    <span id="login-subtitle"></span>
    <span id="cursor-subtitle" class="typing-cursor">|</span>
</p>


        {{-- Error --}}
        @if ($errors->any())
            <div class="mb-4 text-sm text-red-600 bg-red-100 p-2 rounded">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.process') }}" class="space-y-4">
            @csrf

            <div>
                <label class="text-sm text-gray-600">Username</label>
                <input
                    type="text"
                    name="username"
                    required
                    autofocus
                    class="w-full border px-4 py-2 rounded-lg mt-1
                           focus:ring-2 focus:ring-blue-600 focus:outline-none"
                >
            </div>

            <div>
                <label class="text-sm text-gray-600">Password</label>
                <input
                    type="password"
                    name="password"
                    required
                    class="w-full border px-4 py-2 rounded-lg mt-1
                           focus:ring-2 focus:ring-blue-600 focus:outline-none"
                >
            </div>

            <button
    type="submit"
    id="loginBtn"
    class="w-full bg-blue-700 text-white py-2 rounded-lg
           hover:bg-blue-800 transition font-semibold
           flex items-center justify-center gap-2">
    
    <span id="loginText">Login</span>

    <svg id="loginSpinner"
         class="hidden animate-spin h-5 w-5 text-white"
         xmlns="http://www.w3.org/2000/svg"
         fill="none"
         viewBox="0 0 24 24">
        <circle class="opacity-25"
                cx="12" cy="12" r="10"
                stroke="currentColor"
                stroke-width="4"></circle>
        <path class="opacity-75"
              fill="currentColor"
              d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
    </svg>

</button>

        </form>

        <div class="text-center mt-6">
            <a href="{{ route('landing') }}"
               class="text-sm text-blue-700 hover:underline">
                ← Kembali ke Halaman Utama
            </a>
        </div>

    </div>
</section>
</div>
{{-- ================= CLOUD ANIMATION (SAMA SEPERTI LANDING) ================= --}}
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
</style>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const titleText = 'Masuk ke Sistem'
    const subtitleText = 'Sistem Kasir & Inventaris Koperasi SMK'

    const titleEl = document.getElementById('login-title')
    const subtitleEl = document.getElementById('login-subtitle')
    const cursorTitle = document.getElementById('cursor-title')
    const cursorSubtitle = document.getElementById('cursor-subtitle')

    let i = 0

    function typeTitle() {
        if (i < titleText.length) {
            titleEl.textContent += titleText.charAt(i)
            i++
            setTimeout(typeTitle, 80)
        } else {
            cursorTitle.style.display = 'none'
            setTimeout(typeSubtitle, 300)
        }
    }

    let j = 0
    function typeSubtitle() {
        if (j < subtitleText.length) {
            subtitleEl.textContent += subtitleText.charAt(j)
            j++
            setTimeout(typeSubtitle, 50)
        } else {
            cursorSubtitle.style.display = 'none'
        }
    }

    typeTitle()
})
document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form')
    const btn = document.getElementById('loginBtn')
    const text = document.getElementById('loginText')
    const spinner = document.getElementById('loginSpinner')

    form.addEventListener('submit', () => {
        btn.disabled = true
        btn.classList.add('opacity-70', 'cursor-not-allowed')

        text.textContent = 'Memproses...'
        spinner.classList.remove('hidden')
    })
})
const overlay = document.getElementById('loadingOverlay')

form.addEventListener('submit', () => {
    overlay.classList.remove('hidden')
})

</script>

@endsection
