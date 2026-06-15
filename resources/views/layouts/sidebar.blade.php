<aside :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}"
       class="bg-slate-900 border-r border-slate-800 w-[280px] h-screen fixed top-0 left-0 z-50 transform transition-transform duration-300 lg:translate-x-0 flex flex-col shrink-0 shadow-2xl lg:shadow-none lg:sticky lg:top-0">

    {{-- Header / Logo Sidebar --}}
    <div class="h-20 flex items-center px-6 border-b border-slate-800/60 bg-slate-900/50 backdrop-blur-md shrink-0">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3.5 w-full group">
            <div class="w-10 h-10 rounded-xl bg-indigo-500/10 flex items-center justify-center border border-indigo-500/20 shadow-inner group-hover:scale-105 group-hover:bg-indigo-500/20 transition-all duration-300">
                <img src="{{ asset('assets/images/logo-amn.webp') }}" alt="Logo AMN" class="w-6 h-6 object-contain">
            </div>
            <div class="flex flex-col">
                <span class="font-bold text-lg tracking-tight text-white leading-none mb-1">Nautica</span>
                <span class="text-[10px] uppercase font-bold text-indigo-400 tracking-[0.15em]">Asrama AMN Cilacap</span>
            </div>
        </a>
    </div>

    {{-- Menu Navigasi --}}
    <div class="py-6 px-4 space-y-1.5 overflow-y-auto flex-1 custom-scrollbar">

        <div class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-3 mt-2">
            Menu Utama
        </div>

        {{-- Menu Dashboard --}}
        <a href="{{ route('dashboard') }}"
           class="flex items-center px-3.5 py-3 rounded-xl transition-all duration-200 group font-medium text-sm {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <svg class="w-5 h-5 mr-3.5 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }} transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            {{ __('Dashboard') }}
        </a>

        {{-- Menu Data Kamar --}}
        <a href="{{ route('rooms.index') }}"
           class="flex items-center px-3.5 py-3 rounded-xl transition-all duration-200 group font-medium text-sm {{ request()->routeIs('rooms.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <svg class="w-5 h-5 mr-3.5 {{ request()->routeIs('rooms.*') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }} transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            {{ __('Data Kamar') }}
        </a>

        <div class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-3 mt-6">
            Manajemen Taruna
        </div>

        {{-- Menu Data Taruna Terdaftar --}}
        <a href="{{ route('residents.index') }}"
           class="flex items-center px-3.5 py-3 rounded-xl transition-all duration-200 group font-medium text-sm {{ request()->routeIs('residents.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <svg class="w-5 h-5 mr-3.5 {{ request()->routeIs('residents.*') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }} transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
            </svg>
            {{ __('Data Taruna Terdaftar Asrama') }}
        </a>

        {{-- Menu Data Taruna Keluar --}}
        <a href="{{ route('resident_outs.index') }}"
           class="flex items-center px-3.5 py-3 rounded-xl transition-all duration-200 group font-medium text-sm {{ request()->routeIs('resident_outs.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <svg class="w-5 h-5 mr-3.5 {{ request()->routeIs('resident_outs.*') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }} transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M22 10.5h-6m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
            </svg>
            {{ __('Data Taruna Keluar Asrama') }}
        </a>

    </div>

    <div class="p-4 border-t border-slate-800/60 bg-slate-900 shrink-0">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center w-full px-4 py-3 text-sm font-bold text-slate-400 bg-slate-800/50 hover:text-white hover:bg-rose-500 rounded-xl transition-all duration-300 shadow-sm hover:shadow-rose-500/25 group">
                <svg class="w-5 h-5 mr-3 transition-transform duration-300 group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                </svg>
                Log Out
            </button>
        </form>
        <div class="mt-4 text-center">
            <p class="text-[10px] font-medium text-slate-500 tracking-wider">v1.0.0 &copy; {{ date('Y') }}</p>
        </div>
    </div>
</aside>
