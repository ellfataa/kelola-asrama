<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 leading-tight flex items-center gap-2">
            <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    {{-- Perhitungan Data Real-time --}}
    @php
        $rooms = \App\Models\Room::withCount('residents')->get();
        $totalRooms = $rooms->count();
        $totalResidents = \App\Models\Resident::count();
        $totalCapacity = $rooms->sum('capacity');
        $occupiedSlots = $rooms->sum('residents_count');
        $availableSlots = $totalCapacity - $occupiedSlots;
        if($availableSlots < 0) $availableSlots = 0;
        $occupancyRate = $totalCapacity > 0 ? round(($occupiedSlots / $totalCapacity) * 100) : 0;
        $fullRooms = $rooms->filter(function($room) {
            return $room->residents_count >= $room->capacity;
        })->count();
        $availableRooms = $totalRooms - $fullRooms;
        $currentIncome = \App\Models\Resident::with('room')->get()->sum(function($resident) {
            return $resident->room->price ?? 0;
        });
        $latestResidents = \App\Models\Resident::with('room')->latest()->take(5)->get();
    @endphp

    <div class="space-y-6">

        {{-- ROW 1: STATISTIK UTAMA --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Card Total Kamar --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <span class="text-xs font-bold px-2 py-1 rounded-full bg-slate-100 text-slate-500">Kamar</span>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500 uppercase tracking-wide">Total Kamar</p>
                    <h3 class="text-3xl font-extrabold text-slate-800 mt-1">{{ $totalRooms }}</h3>
                    <p class="text-sm mt-2 flex items-center gap-2">
                        <span class="text-emerald-600 font-semibold bg-emerald-50 px-2 py-0.5 rounded-md">{{ $availableRooms }} Tersedia</span>
                        <span class="text-slate-400">/</span>
                        <span class="text-red-500 font-semibold">{{ $fullRooms }} Penuh</span>
                    </p>
                </div>
            </div>

            {{-- Card Penghuni --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <span class="text-xs font-bold px-2 py-1 rounded-full bg-slate-100 text-slate-500">Penghuni</span>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500 uppercase tracking-wide">Total Penghuni</p>
                    <h3 class="text-3xl font-extrabold text-slate-800 mt-1">{{ $totalResidents }}</h3>
                    <p class="text-sm text-green-500 mt-2">Orang aktif terdaftar</p>
                </div>
            </div>

            {{-- Card Pendapatan --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-bold uppercase text-slate-400">Sisa Tempat Tidur</span>
                        <span class="block text-lg font-bold {{ $availableSlots == 0 ? 'text-red-500' : 'text-slate-700' }}">{{ $availableSlots }}</span>
                    </div>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500 uppercase tracking-wide">Estimasi Income</p>
                    <h3 class="text-3xl font-extrabold text-slate-800 mt-1">Rp {{ number_format($currentIncome, 0, ',', '.') }}</h3>
                    <p class="text-sm text-indigo-600 mt-2 font-medium">Akumulasi Bulan Ini</p>
                </div>
            </div>
        </div>

        {{-- ROW 2: PROGRESS OKUPANSI & PENGHUNI TERBARU --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- KIRI: STATISTIK OKUPANSI & ACTIONS --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col justify-between">
                <div>
                    <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center">
                        Statistik Okupansi
                        <span class="ml-2 text-xs font-normal text-slate-500 bg-yellow-100 px-2 py-1 rounded-full">{{ date('F Y') }}</span>
                    </h3>

                    <div class="flex items-end justify-between text-sm text-slate-600 mb-2">
                        <span class="font-medium">Terpakai: <b class="text-indigo-600">{{ $occupiedSlots }}</b> tempat tidur</span>
                        <span class="text-xs text-slate-400">Total Kapasitas: {{ $totalCapacity }}</span>
                    </div>

                    {{-- Modern Progress Bar --}}
                    <div class="w-full bg-slate-100 rounded-full h-4 overflow-hidden shadow-inner mb-8">
                        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 h-4 rounded-full flex items-center justify-center transition-all duration-1000 ease-out shadow-lg shadow-indigo-200"
                             style="width: {{ $occupancyRate }}%">
                        </div>
                    </div>
                    <div class="text-center">
                        <span class="text-4xl font-extrabold text-slate-800">{{ $occupancyRate }}<span class="text-2xl text-slate-400">%</span></span>
                        <p class="text-sm text-slate-500">Tingkat Keterisian</p>
                    </div>
                </div>

                <div class="mt-8 border-t border-slate-100 pt-6">
                    <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-4">Aksi Cepat</h3>
                    <div class="grid grid-cols-1 gap-3">
                        <a href="{{ route('residents.create') }}" class="flex items-center justify-center w-full px-4 py-3 bg-green-500 text-white rounded-xl hover:bg-green-800 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 font-bold text-sm">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                            Tambah Penghuni Baru
                        </a>
                        <a href="{{ route('rooms.create') }}" class="flex items-center justify-center w-full px-4 py-3 bg-cyan-300 border border-slate-200 text-slate-700 rounded-xl hover:bg-cyan-100 hover:border-slate-300 hover:shadow-sm transition-all duration-200 font-bold text-sm">
                            <svg class="w-5 h-5 mr-2 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            Tambah Kamar Baru
                        </a>
                    </div>
                </div>
            </div>

            {{-- KANAN: PENGHUNI TERBARU (TABEL MINI) --}}
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Penghuni Terbaru</h3>
                        <p class="text-sm text-slate-500">5 data terakhir yang ditambahkan</p>
                    </div>
                    <a href="{{ route('residents.index') }}" class="inline-flex items-center text-sm font-semibold text-indigo-600 hover:text-indigo-800 hover:underline transition">
                        Lihat Semua
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>

                <div class="overflow-hidden rounded-xl border border-slate-100">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 border-b border-slate-100 text-xs uppercase font-bold text-slate-400 tracking-wider">
                            <tr>
                                <th class="py-4 px-6">Nama</th>
                                <th class="py-4 px-6">Kamar</th>
                                <th class="py-4 px-6">Bed</th>
                                <th class="py-4 px-6 text-right">Tgl Masuk</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($latestResidents as $resident)
                                <tr class="hover:bg-slate-50/50 transition duration-150">
                                    <td class="py-4 px-6 font-semibold text-slate-800">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-bold mr-3">
                                                {{ substr($resident->name, 0, 1) }}
                                            </div>
                                            {{ $resident->name }}
                                        </div>
                                    </td>
                                    <td class="py-4 px-6">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">
                                            Unit {{ $resident->room->number }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 text-slate-500">Slot {{ $resident->bed_slot }}</td>
                                    <td class="py-4 px-6 text-right font-medium">{{ $resident->entry_date->format('d M Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-8 text-center text-slate-400 italic bg-slate-50/50">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-8 h-8 mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                            Belum ada data penghuni.
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
