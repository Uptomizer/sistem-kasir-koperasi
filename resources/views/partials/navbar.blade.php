<div class="no-select fixed w-full z-50 top-0 transition-all duration-300">
    <nav class="bg-white/70 backdrop-blur-md shadow-sm border-b border-white/20">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            
            {{-- Logo / Brand --}}
            <a href="{{ route('landing') }}" class="flex items-center gap-2 group">
                <div class="bg-blue-700 text-white w-8 h-8 flex items-center justify-center rounded-lg shadow-md group-hover:bg-blue-800 transition">
                    <span class="font-bold text-lg">K</span>
                </div>
                <div class="text-xl font-bold text-blue-900 tracking-tight">
                    Koperasi<span class="text-blue-700"></span>
                </div>
            </a>

            {{-- Button Login --}}
            <a href="{{ route('login') }}"
               class="bg-blue-700 text-white px-6 py-2 rounded-full font-medium text-sm
                      shadow-lg shadow-blue-700/30 hover:bg-blue-800 hover:shadow-blue-800/40
                      hover:-translate-y-0.5 transition-all duration-300 active:scale-95">
                Login Apps
            </a>
        </div>
    </nav>
</div>