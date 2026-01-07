<nav class="bg-white/80 backdrop-blur-md border-b border-slate-200 h-20 flex items-center justify-between px-4 sm:px-6 lg:px-8 w-full sticky top-0 z-20">

    <div class="flex items-center">
        <button @click="sidebarOpen = !sidebarOpen" class="text-slate-500 hover:text-slate-700 focus:outline-none md:hidden p-2 rounded-md hover:bg-slate-100 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path x-show="!sidebarOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                <path x-show="sidebarOpen" style="display: none;" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>

        <div class="hidden md:block">
            <h1 class="text-xl font-bold text-slate-800">Overview</h1>
            <p class="text-xs text-slate-500">Selamat datang kembali, {{ Auth::user()->name }}</p>
        </div>
    </div>

    <div class="flex items-center gap-4">

        <x-dropdown align="right" width="48">
            <x-slot name="trigger">
                <button class="flex items-center gap-3 focus:outline-none group">
                    <div class="text-right hidden sm:block">
                        <div class="text-sm font-semibold text-slate-700 group-hover:text-indigo-600 transition">{{ Auth::user()->name }}</div>
                        <div class="text-xs text-slate-400">Administrator</div>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-indigo-100 border-2 border-white shadow-sm flex items-center justify-center text-indigo-600 font-bold text-lg group-hover:scale-105 transition-transform">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </button>
            </x-slot>

            <x-slot name="content">
                <x-dropdown-link :href="route('profile.edit')" class="hover:bg-indigo-50 hover:text-indigo-600">
                    {{ __('Profile') }}
                </x-dropdown-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-dropdown-link :href="route('logout')"
                            class="text-red-600 hover:bg-red-50"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-dropdown-link>
                </form>
            </x-slot>
        </x-dropdown>
    </div>
</nav>
