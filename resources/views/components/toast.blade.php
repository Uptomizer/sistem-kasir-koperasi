@if (session('success'))
<div class="no-select">
<div id="success-popup" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-[2px] opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-3xl p-8 shadow-2xl transform scale-90 transition-all duration-300 flex flex-col items-center justify-center min-w-[320px]">
        
        {{-- Animated Checkmark --}}
        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mb-4">
            <svg class="w-10 h-10 text-green-600 animate-check" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
            </svg>
        </div>

        <h3 class="text-2xl font-bold text-slate-800 mb-2">Berhasil!</h3>
        <p class="text-slate-500 text-center font-medium">{{ session('success') }}</p>
    </div>
</div>

<style>
@keyframes checkPop {
    0% { transform: scale(0); opacity: 0; }
    50% { transform: scale(1.2); }
    100% { transform: scale(1); opacity: 1; }
}
.animate-check {
    animation: checkPop 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
}
</style>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const popup = document.getElementById('success-popup');
        const content = popup.querySelector('div');

        // Fade In
        setTimeout(() => {
            popup.classList.remove('opacity-0');
            content.classList.remove('scale-90');
            content.classList.add('scale-100');
        }, 100);

        // Fade Out & Remove
        setTimeout(() => {
            popup.classList.add('opacity-0');
            content.classList.remove('scale-100');
            content.classList.add('scale-90');
            
            setTimeout(() => {
                popup.remove();
            }, 300);
        }, 3000); // Tampil selama 3 detik
    });
</script>
@endif

@if ($errors->any())
<div id="toast-error"
     class="fixed top-5 right-5 bg-red-600 text-white px-6 py-4 rounded-xl shadow-lg shadow-red-600/20 z-50 animate-bounce-in flex items-center gap-3">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    <span class="font-medium">{{ $errors->first() }}</span>
</div>
<script>
    setTimeout(() => {
        const error = document.getElementById('toast-error')
        if (error) {
            error.style.opacity = '0';
            error.style.transform = 'translateX(100%)';
            error.style.transition = 'all 0.5s ease';
            setTimeout(() => error.remove(), 500);
        }
    }, 4000)
</script>
@endif
</div>