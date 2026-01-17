@if (session('welcome'))
<div class="no-select">
<div id="welcomeSplash"
     class="fixed inset-0 bg-white z-[9999]
            flex items-center justify-center">

    <h1
        class="text-4xl md:text-5xl font-bold text-blue-900
               opacity-0 animate-welcome-in">
        {{ session('welcome') }}
    </h1>
</div>
</div>
<style>
@keyframes welcomeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
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
    animation: welcomeIn 0.6s ease-out forwards;
}

.fade-out {
    animation: welcomeOut 0.6s ease-in forwards;
}
</style>

<script>
setTimeout(() => {
    const splash = document.getElementById('welcomeSplash')
    if (splash) {
        splash.classList.add('fade-out')
        setTimeout(() => splash.remove(), 600)
    }
}, 2500)
</script>
@endif
