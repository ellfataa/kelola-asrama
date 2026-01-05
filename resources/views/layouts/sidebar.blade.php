<aside :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}"
       class="bg-white border-r border-gray-200 w-64 min-h-screen fixed md:static top-0 left-0 z-40 transform transition-transform duration-300 md:translate-x-0 flex flex-col shrink-0">

    <div class="h-16 flex items-center justify-center border-b border-gray-100">
        <a href="{{ route('dashboard') }}">
            <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
        </a>
    </div>

    <div class="py-4 px-3 space-y-1 overflow-y-auto">
        <div class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">
            Menu Utama
        </div>

        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="flex items-center px-4 py-2 rounded-md w-full {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700 border-l-0' : 'text-gray-700 hover:bg-gray-100' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
            {{ __('Dashboard') }}
        </x-nav-link>

        <x-nav-link :href="route('rooms.index')" :active="request()->routeIs('rooms.*')" class="flex items-center px-4 py-2 rounded-md w-full {{ request()->routeIs('rooms.*') ? 'bg-blue-50 text-blue-700 border-l-0' : 'text-gray-700 hover:bg-gray-100' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            {{ __('Data Kamar') }}
        </x-nav-link>

        <x-nav-link :href="route('residents.index')" :active="request()->routeIs('residents.*')" class="flex items-center px-4 py-2 rounded-md w-full {{ request()->routeIs('residents.*') ? 'bg-blue-50 text-blue-700 border-l-0' : 'text-gray-700 hover:bg-gray-100' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            {{ __('Data Penghuni') }}
        </x-nav-link>
    </div>
</aside>
