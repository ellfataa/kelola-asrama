<nav class="bg-white/80 backdrop-blur-xl border-b border-slate-200/80 h-20 flex items-center justify-between px-4 sm:px-6 lg:px-8 w-full sticky top-0 z-40 transition-all duration-300">

    {{-- Kiri: Hamburger Menu & Greeting --}}
    <div class="flex items-center gap-4 sm:gap-6">
        <button @click="sidebarOpen = !sidebarOpen"
                class="text-slate-500 hover:text-indigo-600 focus:outline-none lg:hidden p-2 rounded-xl hover:bg-indigo-50 transition-colors focus:ring-2 focus:ring-indigo-500/30">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path x-show="!sidebarOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                <path x-show="sidebarOpen" style="display: none;" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>

        <div class="hidden sm:flex flex-col cursor-default">
            <h1 class="text-xl font-extrabold text-slate-900 tracking-tight leading-none mb-1">Overview</h1>
            <p class="text-xs font-medium text-slate-500">
                Selamat datang kembali, <span class="text-slate-700 font-bold">{{ Auth::user()->name }}</span>
            </p>
        </div>
    </div>

    {{-- Kanan: Profile Dropdown --}}
    <div class="flex items-center">
        <x-dropdown align="right" width="56">
            <x-slot name="trigger">
                <button class="flex items-center gap-3 p-1.5 pr-2 sm:pr-4 rounded-full border border-transparent hover:border-slate-200 hover:bg-slate-50 hover:shadow-sm focus:outline-none transition-all duration-200 group">

                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 text-white shadow-md flex items-center justify-center font-bold text-lg group-hover:scale-105 transition-transform">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>

                    {{-- Nama & Role --}}
                    <div class="text-right hidden sm:block">
                        <div class="text-sm font-bold text-slate-800 group-hover:text-indigo-600 transition-colors leading-tight">
                            {{ Auth::user()->name }}
                        </div>
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">
                            Administrator
                        </div>
                    </div>

                    <svg class="w-4 h-4 text-slate-400 group-hover:text-indigo-500 transition-colors ml-1 hidden sm:block" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                </button>
            </x-slot>

            <x-slot name="content">
                <div class="px-4 py-3 border-b border-slate-100 sm:hidden">
                    <p class="text-sm font-bold text-slate-900 truncate">{{ Auth::user()->name }}</p>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Administrator</p>
                </div>

                {{-- Menu Profile --}}
                <x-dropdown-link :href="route('profile.edit')" class="flex items-center gap-2.5 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                    {{ __('Profil') }}
                </x-dropdown-link>

                {{-- Menu Logout --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-dropdown-link :href="route('logout')"
                            class="flex items-center  bg-rose-50 gap-2.5 px-4 py-2.5 text-sm font-bold text-rose-600 hover:bg-rose-50 hover:text-rose-700 transition-colors"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                        </svg>
                        {{ __('Log Out') }}
                    </x-dropdown-link>
                </form>
            </x-slot>
        </x-dropdown>
    </div>
</nav>
