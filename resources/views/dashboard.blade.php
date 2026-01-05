<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Overview') }}
        </h2>
    </x-slot>

    {{-- LOGIC PHP: Menghitung Data Real-time --}}
    @php
        // 1. Hitung Total Kamar
        $totalRooms = \App\Models\Room::count();

        // 2. Hitung Total Penghuni Aktif
        $totalResidents = \App\Models\Resident::count();

        // 3. Hitung Total Kapasitas Asrama (Sum dari kolom capacity semua kamar)
        $totalCapacity = \App\Models\Room::sum('capacity');

        // 4. Hitung Sisa Slot
        $availableSlots = $totalCapacity - $totalResidents;

        // 5. Hitung Persentase Hunian (Okupansi)
        $occupancyRate = $totalCapacity > 0 ? round(($totalResidents / $totalCapacity) * 100) : 0;

        // 6. Hitung Estimasi Pendapatan (Hanya dari penghuni yang ada)
        // Logic: Mengambil semua penghuni, lalu menjumlahkan harga kamar mereka
        $currentIncome = \App\Models\Resident::with('room')->get()->sum(function($resident) {
            return $resident->room->price;
        });
    @endphp

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- GRID STATISTIK --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

                {{-- CARD 1: TOTAL KAMAR --}}
                <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Kamar</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalRooms }}</p>
                        <p class="text-xs text-blue-600 mt-1 font-semibold">Unit Siap Huni</p>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-full text-blue-600">
                        {{-- Icon Rumah/Bed --}}
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    </div>
                </div>

                {{-- CARD 2: PENGHUNI (REAL DATA) --}}
                <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Penghuni</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalResidents }}</p>
                        <p class="text-xs text-green-600 mt-1 font-semibold">Orang Terdaftar</p>
                    </div>
                    <div class="p-3 bg-green-100 rounded-full text-green-600">
                        {{-- Icon Users --}}
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                </div>

                {{-- CARD 3: PENDAPATAN RIIL & SISA SLOT --}}
                <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-purple-500 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Potensi Pendapatan</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">Rp {{ number_format($currentIncome, 0, ',', '.') }}</p>
                        <p class="text-xs text-purple-600 mt-1 font-semibold">Bulan Ini (Aktif)</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-400 uppercase">Sisa Slot</p>
                        <p class="text-2xl font-bold text-gray-700">{{ $availableSlots }}</p>
                    </div>
                </div>

            </div>

            {{-- SECTION STATISTIK OKUPANSI --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- Kiri: Bar Chart Sederhana (Okupansi) --}}
                <div class="md:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Tingkat Hunian Asrama</h3>

                        <div class="mb-2 flex justify-between text-sm text-gray-600">
                            <span>Terisi: {{ $totalResidents }} orang</span>
                            <span>Kapasitas Total: {{ $totalCapacity }} orang</span>
                        </div>

                        {{-- Progress Bar Container --}}
                        <div class="w-full bg-gray-200 rounded-full h-6 dark:bg-gray-700 overflow-hidden">
                            {{-- Progress Bar Fill --}}
                            <div class="bg-blue-600 h-6 rounded-full text-xs font-medium text-blue-100 text-center p-0.5 leading-none transition-all duration-500"
                                 style="width: {{ $occupancyRate }}%">
                                {{ $occupancyRate }}%
                            </div>
                        </div>

                        <p class="text-sm text-gray-500 mt-4">
                            Saat ini asrama terisi <b>{{ $occupancyRate }}%</b> dari total kapasitas.
                            Masih tersedia <b>{{ $availableSlots }}</b> tempat tidur kosong.
                        </p>
                    </div>
                </div>

                {{-- Kanan: Quick Actions / Welcome --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-2">Aksi Cepat</h3>
                        <div class="flex flex-col space-y-3 mt-4">
                            <a href="{{ route('residents.create') }}" class="block w-full text-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                + Tambah Penghuni
                            </a>
                            <a href="{{ route('rooms.create') }}" class="block w-full text-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150">
                                + Tambah Kamar
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
