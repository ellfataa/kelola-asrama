<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 leading-tight flex items-center gap-2.5">
            <div class="p-2 bg-indigo-50 text-indigo-600 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
            </div>
            {{ __('Dashboard Statistik') }}
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
        $currentIncome = 0;
        foreach($rooms as $room) {
            $currentIncome += $room->price * $room->residents_count;
        }

        // 7. Ambil 5 Penghuni Terakhir (Aktif)
        $latestResidents = \App\Models\Resident::with('room')->latest()->take(5)->get();

        // 8. Ambil 5 Penghuni Keluar Terakhir (Riwayat)
        $latestResidentOuts = \App\Models\ResidentOut::latest('exit_date')->take(5)->get();
    @endphp

    <div class="space-y-8 pb-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">

        {{-- ROW 1: STATISTIK UTAMA --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 pt-4">

            {{-- Card Total Kamar --}}
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-6 sm:p-8 flex flex-col justify-between group hover:shadow-xl hover:shadow-blue-100/50 transition-all duration-300 hover:-translate-y-1">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <p class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest">Total Unit Kamar</p>
                        <h3 class="text-4xl font-extrabold text-slate-800 mt-2">{{ $totalRooms }}</h3>
                    </div>
                    <div class="p-2.5 rounded-xl bg-slate-100 text-slate-500 border border-slate-200 flex items-center justify-center shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                </div>
                <div class="flex items-center gap-2 mt-auto pt-4 border-t border-slate-100">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-600 text-[11px] font-bold border border-emerald-100">
                        {{ $availableRooms }} Kosong
                    </span>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-rose-50 text-rose-600 text-[11px] font-bold border border-rose-100">
                        {{ $fullRooms }} Penuh
                    </span>
                </div>
            </div>

            {{-- Card Total Penghuni --}}
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-6 sm:p-8 flex flex-col justify-between group hover:shadow-xl hover:shadow-emerald-100/50 transition-all duration-300 hover:-translate-y-1">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <p class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest">Total Taruna</p>
                        <h3 class="text-4xl font-extrabold text-slate-800 mt-2">{{ $totalResidents }}</h3>
                    </div>
                    <div class="p-2.5 rounded-xl bg-slate-100 text-slate-500 border border-slate-200 flex items-center justify-center shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                </div>
                <div class="flex items-center justify-between mt-auto pt-4 border-t border-slate-100">
                    <span class="text-[11px] font-bold text-slate-500">Kapasitas: {{ $totalCapacity }} Bed</span>
                    <span class="text-[11px] font-bold text-indigo-500 bg-indigo-50 px-2 py-1 rounded-lg">Sisa: {{ $availableSlots }} Bed</span>
                </div>
            </div>

            {{-- Card Estimasi Pendapatan --}}
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-6 sm:p-8 flex flex-col justify-between group hover:shadow-xl hover:shadow-purple-100/50 transition-all duration-300 hover:-translate-y-1 md:col-span-2 lg:col-span-1">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <p class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest">Est. Pendapatan</p>
                        <h3 class="text-2xl sm:text-2xl font-semibold text-slate-800 mt-2 tracking-tight">Rp{{ number_format($currentIncome, 0, ',', '.') }}</h3>
                    </div>
                    <div class="p-2.5 rounded-xl bg-slate-100 text-slate-500 border border-slate-200 flex items-center justify-center shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <div class="flex items-center gap-2 mt-auto pt-4 border-t border-slate-100">
                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    <span class="text-[11px] font-bold text-slate-500">Siklus per Semester berjalan</span>
                </div>
            </div>
        </div>

        {{-- ROW 2: PROGRESS OKUPANSI & PENGHUNI TERBARU --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- KIRI: STATISTIK OKUPANSI & ACTIONS --}}
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-6 sm:p-8 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-base font-extrabold text-slate-900">Tingkat Okupansi</h3>
                        <span class="text-[10px] font-bold text-center text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-md border border-indigo-100 uppercase tracking-widest">{{ date('M Y') }}</span>
                    </div>

                    <div class="relative w-40 h-40 mx-auto mb-6 flex items-center justify-center">
                        <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                            {{-- Background Circle --}}
                            <path class="text-slate-100" stroke-width="3" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                            {{-- Progress Circle --}}
                            <path class="text-indigo-500" stroke-dasharray="{{ $occupancyRate }}, 100" stroke-width="3" stroke-linecap="round" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        </svg>
                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <span class="text-3xl font-black text-slate-800">{{ $occupancyRate }}<span class="text-lg text-slate-400">%</span></span>
                        </div>
                    </div>

                    <div class="flex justify-center items-center gap-6 text-sm text-slate-600 mb-8 text-center">
                        <div class="flex flex-col">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Terisi</span>
                            <span class="font-extrabold text-indigo-600 text-lg">{{ $occupiedSlots }}</span>
                        </div>
                        <div class="h-8 w-px bg-slate-200"></div>
                        <div class="flex flex-col">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Kapasitas</span>
                            <span class="font-extrabold text-slate-800 text-lg">{{ $totalCapacity }}</span>
                        </div>
                    </div>
                </div>

                <div class="border-t border-slate-100 pt-6">
                    <h3 class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-4">Aksi Cepat</h3>
                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('residents.create') }}" class="group flex flex-col items-center justify-center p-3 bg-slate-50 border border-slate-200 rounded-2xl hover:bg-indigo-600 hover:border-indigo-600 hover:text-white transition-all duration-300 text-slate-600">
                            <svg class="w-6 h-6 mb-1 text-indigo-500 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                            <span class="text-[10px] font-bold text-center">Tambah Data Taruna</span>
                        </a>
                        <a href="{{ route('rooms.create') }}" class="group flex flex-col items-center justify-center p-3 bg-slate-50 border border-slate-200 rounded-2xl hover:bg-emerald-500 hover:border-emerald-500 hover:text-white transition-all duration-300 text-slate-600">
                            <svg class="w-6 h-6 mb-1 text-emerald-500 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            <span class="text-[10px] font-bold text-center">Tambah Data Kamar</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- KANAN: PENGHUNI TERBARU & RIWAYAT KELUAR --}}
            <div class="lg:col-span-2 space-y-6 flex flex-col">

                {{-- TABEL TARUNA TERDAFTAR DI ASEAMA TERBARU --}}
                <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-6 sm:p-8">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-base font-extrabold text-slate-900">Taruna Terdaftar Asrama</h3>
                            <p class="text-xs font-medium text-slate-500 mt-1">Data taruna terbaru yang terdaftar pada asrama.</p>
                        </div>
                        <a href="{{ route('residents.index') }}" class="hidden sm:inline-flex items-center text-[11px] font-extrabold text-indigo-600 bg-indigo-50 border border-indigo-100 px-3 py-1.5 rounded-lg hover:bg-indigo-600 hover:text-white transition-colors uppercase tracking-wider">
                            Lihat Semua
                        </a>
                    </div>

                    <div class="overflow-x-auto rounded-2xl border border-slate-100 flex-1 custom-scrollbar">
                        <table class="w-full text-left border-collapse whitespace-nowrap">
                            <thead class="bg-slate-50 border-b border-slate-100 text-[10px] uppercase font-extrabold text-slate-400 tracking-wider">
                                <tr>
                                    <th class="p-4 pl-5">Taruna</th>
                                    <th class="p-4">Alokasi Kamar</th>
                                    <th class="p-4 text-right pr-5">Tanggal Masuk Asrama</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($latestResidents as $resident)
                                    <tr class="hover:bg-slate-50/80 transition duration-150 group">
                                        <td class="p-3 pl-5">
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-500 text-white flex items-center justify-center text-xs font-bold shadow-sm">
                                                    {{ substr($resident->name, 0, 1) }}
                                                </div>
                                                <div class="flex flex-col">
                                                    <span class="font-extrabold text-slate-800 text-sm group-hover:text-indigo-600 transition-colors">{{ $resident->name }}</span>
                                                    <span class="text-[10px] font-medium text-slate-400">{{ $resident->identity_number }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-3">
                                            <div class="flex items-center gap-1.5">
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-indigo-50 border border-indigo-100 text-indigo-700 text-[10px] font-bold">
                                                    Unit {{ $resident->room->number ?? '-' }}
                                                </span>
                                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-md bg-emerald-50 border border-emerald-100 text-emerald-700 text-[10px] font-extrabold">
                                                    Bed #{{ $resident->bed_slot }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="p-3 text-right pr-5">
                                            <span class="text-xs font-bold text-slate-500">
                                                {{ $resident->entry_date->format('d M Y') }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="py-12 text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <div class="w-12 h-12 bg-slate-50 border border-slate-100 rounded-2xl flex items-center justify-center mb-3">
                                                    <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                                </div>
                                                <span class="text-sm font-bold text-slate-600">Belum ada data taruna</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Tombol Mobile --}}
                    <div class="mt-4 sm:hidden flex justify-center">
                        <a href="{{ route('residents.index') }}" class="w-full text-center text-[11px] font-extrabold text-indigo-600 bg-indigo-50 border border-indigo-100 px-4 py-2.5 rounded-xl hover:bg-indigo-600 hover:text-white transition-colors uppercase tracking-wider">
                            Lihat Semua Taruna
                        </a>
                    </div>
                </div>

                {{-- TABEL TARUNA KELUAR TERBARU --}}
                <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-6 sm:p-8">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-base font-extrabold text-slate-900">Taruna Keluar Asrama</h3>
                            <p class="text-xs font-medium text-slate-500 mt-1">Riwayat taruna yang baru saja keluar dari asrama.</p>
                        </div>
                        <a href="{{ route('resident_outs.index') }}" class="hidden sm:inline-flex items-center text-[11px] font-extrabold text-rose-600 bg-rose-50 border border-rose-100 px-3 py-1.5 rounded-lg hover:bg-rose-600 hover:text-white transition-colors uppercase tracking-wider">
                            Lihat Riwayat
                        </a>
                    </div>

                    <div class="overflow-x-auto rounded-2xl border border-slate-100 flex-1 custom-scrollbar">
                        <table class="w-full text-left border-collapse whitespace-nowrap">
                            <thead class="bg-slate-50 border-b border-slate-100 text-[10px] uppercase font-extrabold text-slate-400 tracking-wider">
                                <tr>
                                    <th class="p-4 pl-5">Mantan Taruna</th>
                                    <th class="p-4">Kamar Terakhir</th>
                                    <th class="p-4 text-right pr-5">Tanggal Keluar Asrama</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($latestResidentOuts as $out)
                                    <tr class="hover:bg-slate-50/80 transition duration-150 group">
                                        <td class="p-3 pl-5">
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center text-xs font-bold shadow-sm">
                                                    {{ substr($out->name, 0, 1) }}
                                                </div>
                                                <div class="flex flex-col">
                                                    <span class="font-extrabold text-slate-700 text-sm group-hover:text-rose-600 transition-colors">{{ $out->name }}</span>
                                                    <span class="text-[10px] font-medium text-slate-400">{{ $out->identity_number }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-3">
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-slate-100 border border-slate-200 text-slate-600 text-[10px] font-bold">
                                                {{ $out->room_info }}
                                            </span>
                                        </td>
                                        <td class="p-3 text-right pr-5">
                                            <span class="text-xs font-bold text-orange-500">
                                                {{ $out->exit_date->format('d M Y') }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="py-12 text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <div class="w-12 h-12 bg-slate-50 border border-slate-100 rounded-2xl flex items-center justify-center mb-3">
                                                    <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                                                </div>
                                                <span class="text-sm font-bold text-slate-600">Belum ada riwayat keluar</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Tombol Mobile --}}
                    <div class="mt-4 sm:hidden flex justify-center">
                        <a href="{{ route('resident_outs.index') }}" class="w-full text-center text-[11px] font-extrabold text-rose-600 bg-rose-50 border border-rose-100 px-4 py-2.5 rounded-xl hover:bg-rose-600 hover:text-white transition-colors uppercase tracking-wider">
                            Lihat Semua Riwayat
                        </a>
                    </div>
                </div>
            </div>

        </div>

    </div>
</x-app-layout>
