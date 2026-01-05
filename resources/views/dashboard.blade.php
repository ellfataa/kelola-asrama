<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Overview') }}
        </h2>
    </x-slot>

    {{-- LOGIC PHP: Perhitungan Data Real-time & Cerdas --}}
    @php
        // 1. Ambil Semua Data Kamar dengan jumlah penghuni
        $rooms = \App\Models\Room::withCount('residents')->get();

        // 2. Variabel Statistik Dasar
        $totalRooms = $rooms->count();
        $totalResidents = \App\Models\Resident::count();

        // 3. Logic Hitung Kapasitas & Okupansi (Support Exclusive)
        $totalCapacity = 0;
        $occupiedSlots = 0; // Slot yang "dianggap" terisi

        foreach($rooms as $room) {
            $totalCapacity += $room->capacity;

            if ($room->is_exclusive) {
                // Jika Exclusive, seluruh kapasitas dianggap TERPAKAI
                $occupiedSlots += $room->capacity;
            } else {
                // Jika Reguler, terpakai sesuai jumlah orang
                $occupiedSlots += $room->residents_count;
            }
        }

        // Hitung sisa slot tersedia
        $availableSlots = $totalCapacity - $occupiedSlots;
        if($availableSlots < 0) $availableSlots = 0;

        // Persentase Okupansi
        $occupancyRate = $totalCapacity > 0 ? round(($occupiedSlots / $totalCapacity) * 100) : 0;

        // 4. Hitung Estimasi Pendapatan (Real)
        $currentIncome = \App\Models\Resident::with('room')->get()->sum(function($resident) {
            return $resident->room->price;
        });
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
                        <p class="text-xs text-blue-600 mt-1 font-semibold">Unit Asset</p>
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

            {{-- ROW 2: PROGRESS OKUPANSI & SHORTCUT --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- KIRI: STATISTIK OKUPANSI --}}
                <div class="md:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 flex flex-col justify-center">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Statistik Okupansi Asrama</h3>

                    <div class="flex items-center justify-between text-sm text-gray-600 mb-2">
                        <span>Terpakai: <b>{{ $occupiedSlots }}</b> slot ({{ $occupancyRate }}%)</span>
                        <span>Total Kapasitas: <b>{{ $totalCapacity }}</b> slot</span>
                    </div>

                    {{-- Progress Bar --}}
                    <div class="w-full bg-gray-200 rounded-full h-6 overflow-hidden shadow-inner">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-6 rounded-full flex items-center justify-center text-xs font-bold text-white transition-all duration-1000 ease-out"
                             style="width: {{ $occupancyRate }}%">
                             {{ $occupancyRate }}%
                        </div>
                    </div>

                    <div class="mt-4 p-4 bg-gray-50 rounded-lg border border-gray-100 text-sm text-gray-600">
                        <p>
                            <span class="font-bold text-gray-800">Info:</span>
                            Perhitungan slot terpakai di atas sudah otomatis memperhitungkan kamar dengan status
                            <span class="font-bold text-purple-600">Exclusive Booking</span> (dihitung penuh).
                        </p>
                    </div>
                </div>

                {{-- KANAN: AKSI CEPAT --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Aksi Cepat</h3>
                    <div class="space-y-4">
                        <a href="{{ route('residents.create') }}" class="flex items-center justify-center w-full px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold text-sm shadow-md hover:shadow-lg">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                            Daftar Penghuni Baru
                        </a>
                        <a href="{{ route('rooms.create') }}" class="flex items-center justify-center w-full px-4 py-3 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-semibold text-sm shadow-sm hover:shadow">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            Tambah Unit Kamar
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
