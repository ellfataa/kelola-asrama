<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 leading-tight flex items-center gap-2">
            <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    @php
        // 1. Ambil Semua Data Kamar
        $rooms = \App\Models\Room::withCount('residents')->get();
        
        // 2. Variabel Statistik Dasar
        $totalRooms = $rooms->count();
        $totalResidents = \App\Models\Resident::count();
        $totalCapacity = $rooms->sum('capacity'); 
        
        // 3. Hitung Slot Terpakai & Tersedia
        $occupiedSlots = $rooms->sum('residents_count'); 
        $availableSlots = $totalCapacity - $occupiedSlots;
        if($availableSlots < 0) $availableSlots = 0;

        // 4. Persentase Okupansi
        $occupancyRate = $totalCapacity > 0 ? round(($occupiedSlots / $totalCapacity) * 100) : 0;

        // 5. Hitung Kamar Penuh vs Tersedia
        $fullRooms = $rooms->filter(function($room) {
            return $room->residents_count >= $room->capacity;
        })->count();
        $availableRooms = $totalRooms - $fullRooms;

        // 6. Hitung Estimasi Pendapatan
        // Menggunakan query builder agar lebih ringan daripada load model resident
        // Asumsi: Income dihitung dari (Harga Kamar * Jumlah Penghuni di kamar tsb)
        $currentIncome = 0;
        foreach($rooms as $room) {
            $currentIncome += $room->price * $room->residents_count;
        }

        // 7. Ambil 5 Penghuni Terakhir
        $latestResidents = \App\Models\Resident::with('room')->latest()->take(5)->get();
    @endphp

    <div class="space-y-8">

        {{-- ROW 1: STATISTIK UTAMA --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            {{-- Card Total Kamar --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col justify-between hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-sm font-bold text-slate-400 uppercase tracking-wider">Total Unit Kamar</p>
                        <h3 class="text-3xl font-extrabold text-slate-800 mt-1">{{ $totalRooms }}</h3>
                    </div>
                    <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                </div>
                <div class="flex items-center gap-3 text-sm font-medium">
                    <span class="bg-emerald-50 text-emerald-700 px-2 py-1 rounded-md">{{ $availableRooms }} Tersedia</span>
                    <span class="text-slate-300">|</span>
                    <span class="text-slate-500">{{ $fullRooms }} Penuh</span>
                </div>
            </div>

            {{-- Card Total Penghuni --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col justify-between hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-sm font-bold text-slate-400 uppercase tracking-wider">Total Penghuni</p>
                        <h3 class="text-3xl font-extrabold text-slate-800 mt-1">{{ $totalResidents }}</h3>
                    </div>
                    <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                </div>
                <div class="text-sm font-medium text-slate-500">
                    Orang aktif terdaftar di asrama
                </div>
            </div>

            {{-- Card Estimasi Pendapatan --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col justify-between hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-sm font-bold text-slate-400 uppercase tracking-wider">Estimasi Income</p>
                        <h3 class="text-2xl font-extrabold text-slate-800 mt-1">Rp {{ number_format($currentIncome, 0, ',', '.') }}</h3>
                    </div>
                    <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <div class="flex justify-between items-center text-sm font-medium border-t border-slate-50 pt-3 mt-1">
                    <span class="text-indigo-600">Per Bulan (Aktif)</span>
                    <span class="text-slate-400 text-xs">Sisa Bed: <b class="text-slate-600 text-sm">{{ $availableSlots }}</b></span>
                </div>
            </div>
        </div>

        {{-- ROW 2: PROGRESS OKUPANSI & PENGHUNI TERBARU --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- KIRI: STATISTIK OKUPANSI & ACTIONS --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col justify-between">
                <div>
                    <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center justify-between">
                        Statistik Okupansi
                        <span class="text-xs font-normal text-slate-500 bg-slate-100 px-2 py-1 rounded-lg">{{ date('M Y') }}</span>
                    </h3>

                    <div class="flex items-end justify-between text-sm text-slate-600 mb-2">
                        <span class="font-medium">Terpakai: <b class="text-indigo-600">{{ $occupiedSlots }}</b> Bed</span>
                        <span class="text-xs text-slate-400">Total: {{ $totalCapacity }}</span>
                    </div>

                    {{-- Modern Progress Bar --}}
                    <div class="w-full bg-slate-100 rounded-full h-4 overflow-hidden shadow-inner mb-4">
                        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 h-4 rounded-full flex items-center justify-center transition-all duration-1000 ease-out shadow-lg shadow-indigo-200" 
                             style="width: {{ $occupancyRate }}%">
                        </div>
                    </div>
                    
                    <div class="flex justify-center items-baseline gap-1 mb-8">
                        <span class="text-4xl font-extrabold text-slate-800">{{ $occupancyRate }}</span>
                        <span class="text-xl font-bold text-slate-400">%</span>
                        <span class="ml-2 text-sm text-slate-500 font-medium">Tingkat Keterisian</span>
                    </div>
                </div>

                <div class="border-t border-slate-100 pt-6">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Shortcut Menu</h3>
                    <div class="grid grid-cols-1 gap-3">
                        <a href="{{ route('residents.create') }}" class="group flex items-center justify-center w-full px-4 py-3 bg-emerald-500 text-white rounded-xl hover:bg-emerald-600 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 font-bold text-sm">
                            <svg class="w-5 h-5 mr-2 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                            Tambah Penghuni
                        </a>
                        <a href="{{ route('rooms.create') }}" class="group flex items-center justify-center w-full px-4 py-3 bg-white border border-slate-200 text-slate-700 rounded-xl hover:border-indigo-300 hover:text-indigo-600 hover:shadow-sm transition-all duration-200 font-bold text-sm">
                            <svg class="w-5 h-5 mr-2 text-slate-400 group-hover:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            Tambah Kamar
                        </a>
                    </div>
                </div>
            </div>

            {{-- KANAN: PENGHUNI TERBARU --}}
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Penghuni Terbaru</h3>
                        <p class="text-sm text-slate-500">5 data terakhir yang ditambahkan.</p>
                    </div>
                    <a href="{{ route('residents.index') }}" class="inline-flex items-center text-sm font-semibold text-indigo-600 hover:text-indigo-800 hover:underline transition">
                        Lihat Semua
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>

                <div class="overflow-x-auto rounded-xl border border-slate-100 flex-1">
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
                                            <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-bold mr-3 border border-indigo-50">
                                                {{ substr($resident->name, 0, 1) }}
                                            </div>
                                            {{ $resident->name }}
                                        </div>
                                    </td>
                                    <td class="py-4 px-6">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                            Unit {{ $resident->room->number }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6">
                                        <span class="text-xs font-medium text-slate-500 bg-slate-50 px-2 py-1 rounded border border-slate-100">
                                            Slot {{ $resident->bed_slot }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 text-right font-medium text-slate-500">
                                        {{ $resident->entry_date->format('d M Y') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-12 text-center text-slate-400 italic">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-10 h-10 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
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