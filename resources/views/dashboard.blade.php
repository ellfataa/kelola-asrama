<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Overview') }}
        </h2>
    </x-slot>

    {{-- LOGIC PHP: Perhitungan Data Real-time --}}
    @php
        // 1. Ambil Semua Data Kamar
        $rooms = \App\Models\Room::withCount('residents')->get();

        // 2. Variabel Statistik Dasar
        $totalRooms = $rooms->count();
        $totalResidents = \App\Models\Resident::count();
        $totalCapacity = $rooms->sum('capacity'); // Total kapasitas semua kamar

        // 3. Hitung Slot Terpakai (Murni berdasarkan jumlah penghuni)
        $occupiedSlots = $rooms->sum('residents_count');

        // 4. Hitung Sisa Slot
        $availableSlots = $totalCapacity - $occupiedSlots;
        if($availableSlots < 0) $availableSlots = 0;

        // 5. Persentase Okupansi
        $occupancyRate = $totalCapacity > 0 ? round(($occupiedSlots / $totalCapacity) * 100) : 0;

        // 6. Hitung Kamar Penuh vs Tersedia
        $fullRooms = $rooms->filter(function($room) {
            return $room->residents_count >= $room->capacity;
        })->count();
        $availableRooms = $totalRooms - $fullRooms;

        // 7. Hitung Estimasi Pendapatan (Real based on Residents)
        $currentIncome = \App\Models\Resident::with('room')->get()->sum(function($resident) {
            return $resident->room->price ?? 0;
        });

        // 8. Ambil 5 Penghuni Terakhir untuk Widget
        $latestResidents = \App\Models\Resident::with('room')->latest()->take(5)->get();
    @endphp

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- ROW 1: STATISTIK UTAMA --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Card Total Kamar --}}
                <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Kamar</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalRooms }}</p>
                        <p class="text-xs text-blue-600 mt-1 font-semibold">
                            {{ $availableRooms }} Tersedia / {{ $fullRooms }} Penuh
                        </p>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-full text-blue-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                </div>

                {{-- Card Penghuni --}}
                <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Penghuni</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalResidents }}</p>
                        <p class="text-xs text-green-600 mt-1 font-semibold">Orang Aktif</p>
                    </div>
                    <div class="p-3 bg-green-100 rounded-full text-green-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                </div>

                {{-- Card Pendapatan & Sisa Slot --}}
                <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-purple-500 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Estimasi Income</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">Rp {{ number_format($currentIncome, 0, ',', '.') }}</p>
                        <p class="text-xs text-purple-600 mt-1 font-semibold">Bulan Ini</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-400 uppercase">Sisa Slot</p>
                        <p class="text-2xl font-bold {{ $availableSlots == 0 ? 'text-red-600' : 'text-gray-700' }}">{{ $availableSlots }}</p>
                    </div>
                </div>
            </div>

            {{-- ROW 2: PROGRESS OKUPANSI & PENGHUNI TERBARU --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- KIRI: STATISTIK OKUPANSI --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Statistik Okupansi</h3>

                    <div class="flex items-center justify-between text-sm text-gray-600 mb-2">
                        <span>Terpakai: <b>{{ $occupiedSlots }}</b> bed</span>
                        <span>Total: <b>{{ $totalCapacity }}</b> bed</span>
                    </div>

                    {{-- Progress Bar --}}
                    <div class="w-full bg-gray-200 rounded-full h-6 overflow-hidden shadow-inner mb-6">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-6 rounded-full flex items-center justify-center text-xs font-bold text-white transition-all duration-1000 ease-out"
                             style="width: {{ $occupancyRate }}%">
                             {{ $occupancyRate }}%
                        </div>
                    </div>

                    <h3 class="text-lg font-bold text-gray-800 mb-3 border-t pt-4">Aksi Cepat</h3>
                    <div class="space-y-3">
                        <a href="{{ route('residents.create') }}" class="flex items-center justify-center w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold text-sm shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                            Daftar Penghuni Baru
                        </a>
                        <a href="{{ route('rooms.create') }}" class="flex items-center justify-center w-full px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-semibold text-sm shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            Tambah Unit Kamar
                        </a>
                    </div>
                </div>

                {{-- KANAN: PENGHUNI TERBARU (TABEL MINI) --}}
                <div class="lg:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-800">Penghuni Terbaru</h3>
                        <a href="{{ route('residents.index') }}" class="text-sm text-blue-600 hover:underline">Lihat Semua</a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-gray-600">
                            <thead class="bg-gray-50 border-b">
                                <tr>
                                    <th class="py-3 px-2">Nama</th>
                                    <th class="py-3 px-2">Kamar</th>
                                    <th class="py-3 px-2">Bed</th>
                                    <th class="py-3 px-2 text-right">Tgl Masuk</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($latestResidents as $resident)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-3 px-2 font-medium text-gray-800">{{ $resident->name }}</td>
                                        <td class="py-3 px-2">
                                            <span class="bg-blue-100 text-blue-800 py-1 px-2 rounded-full text-xs font-bold">
                                                {{ $resident->room->number }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-2">Bed {{ $resident->bed_slot }}</td>
                                        <td class="py-3 px-2 text-right">{{ $resident->entry_date->format('d M Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-4 text-center text-gray-400 italic">Belum ada data penghuni.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
