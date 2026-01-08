<aside :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}"
       class="bg-slate-900 border-r border-slate-800 w-72 min-h-screen fixed md:static top-0 left-0 z-40 transform transition-transform duration-300 md:translate-x-0 flex flex-col shrink-0 shadow-2xl md:shadow-none">

    <div class="h-20 flex items-center px-6 border-b border-slate-800 bg-slate-900/50 backdrop-blur-sm">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 w-full">
            <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center border border-white/5 shadow-inner">
                <img src="{{ asset('assets/images/logo-amn.webp') }}" alt="Logo AMN" class="w-6 h-6 object-contain">
            </div>
            <div class="flex flex-col">
                <span class="font-bold text-lg tracking-tight text-white leading-tight">Asrama AMN</span>
                <span class="text-[10px] uppercase font-bold text-indigo-400 tracking-widest">Cilacap</span>
            </div>
        </a>
    </div>

    <div class="py-6 px-4 space-y-2 overflow-y-auto flex-1 custom-scrollbar">
        <div class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-3 mt-2">
            Menu Utama
        </div>

        <a href="{{ route('dashboard') }}"
           class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group font-medium {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-900/50' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
            {{ __('Dashboard') }}
        </a>

        <a href="{{ route('rooms.index') }}"
           class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group font-medium {{ request()->routeIs('rooms.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-900/50' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('rooms.*') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            {{ __('Data Kamar') }}
        </a>

        <a href="{{ route('residents.index') }}"
           class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group font-medium {{ request()->routeIs('residents.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-900/50' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('residents.*') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            {{ __('Data Penghuni') }}
        </a>
    </div>

    <div class="p-4 border-t border-slate-800 bg-slate-900">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center justify-center w-full px-4 py-3 text-sm font-bold text-slate-400 hover:text-white hover:bg-rose-600/90 rounded-xl transition-all duration-200 shadow-sm hover:shadow-rose-900/20 group">
                <svg class="w-5 h-5 mr-2 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                Sign Out
            </button>
        </form>
        <div class="mt-4 text-center">
            <p class="text-[10px] text-slate-600">v1.0.0 &copy; {{ date('Y') }}</p>
        </div>
    </div>
</aside>
