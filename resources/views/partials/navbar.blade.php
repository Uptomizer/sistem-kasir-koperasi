<div class="no-select fixed w-full z-50 top-0 transition-all duration-300">
    <nav class="bg-white/80 backdrop-blur-xl shadow-sm border-b border-white/40 supports-[backdrop-filter]:bg-white/60">
        <div class="max-w-7xl mx-auto px-6 h-20 flex justify-between items-center">
            
            {{-- Logo / Brand --}}
            <a href="{{ route('landing') }}" class="flex items-center gap-3 group select-none">
                {{-- Logo Icon --}}
                <div class="relative flex items-center justify-center w-10 h-10 bg-gradient-to-br from-blue-700 to-indigo-600 rounded-xl shadow-lg shadow-blue-500/20 group-hover:shadow-blue-600/30 group-hover:scale-105 transition-all duration-300">
                    <span class="font-black text-xl text-white tracking-tighter">K</span>
                    {{-- Glow Effect --}}
                    <div class="absolute inset-0 rounded-xl bg-gradient-to-br from-white/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                </div>

                {{-- Logo Text --}}
                <div class="flex flex-col">
                    <span class="text-lg font-bold text-slate-800 leading-none tracking-tight group-hover:text-blue-700 transition-colors duration-300">
                        Koperasi<span class="text-blue-600">App</span>
                    </span>
                    <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-widest mt-0.5">
                        Sistem Inventaris
                    </span>
                </div>
            </a>

            {{-- Right Status Pill (Professional Touch) --}}
            <div class="flex items-center">
                <div class="hidden md:flex items-center gap-2 px-4 py-1.5 bg-slate-50/80 border border-slate-200/60 rounded-full backdrop-blur-sm">
                    <span class="relative flex h-2 w-2">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    <span class="text-xs font-semibold text-slate-600 tracking-wide">
                        Secure & Online
                    </span>
                </div>
            </div>
            
        </div>
    </nav>
</div>