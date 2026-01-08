<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 leading-tight flex items-center gap-2">
            <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            {{ __('Denah & Daftar Kamar') }}
        </h2>
    </x-slot>

    <div class="space-y-8">

        {{-- Top Action Bar --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Manajemen Kamar</h3>
                <p class="text-sm text-slate-500">Total kamar: <span class="font-bold text-indigo-600">{{ $rooms->total() }}</span> unit</p>
            </div>

            <a href="{{ route('rooms.create') }}" class="inline-flex items-center px-5 py-2.5 bg-indigo-600 text-white rounded-xl font-bold text-sm shadow-md hover:bg-indigo-700 hover:shadow-lg transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Kamar Baru
            </a>
        </div>

        {{-- Alert Messages --}}
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" class="bg-emerald-50 text-emerald-700 p-4 rounded-xl border border-emerald-200 flex justify-between items-center shadow-sm">
                <div class="flex items-center gap-2"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> <span class="font-bold">{{ session('success') }}</span></div>
                <button @click="show = false">&times;</button>
            </div>
        @endif
        @if(session('error'))
            <div x-data="{ show: true }" x-show="show" class="bg-rose-50 text-rose-700 p-4 rounded-xl border border-rose-200 flex justify-between items-center shadow-sm">
                <div class="flex items-center gap-2"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> <span class="font-bold">{{ session('error') }}</span></div>
                <button @click="show = false">&times;</button>
            </div>
        @endif

        {{-- GRID KAMAR --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($rooms as $room)
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-indigo-100/50 hover:border-indigo-100 transition-all duration-300 relative group overflow-hidden flex flex-col">

                    {{-- Header Card --}}
                    <div class="px-6 py-4 border-b border-slate-50 bg-slate-50/50 flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-white border border-slate-100 flex items-center justify-center text-slate-700 font-bold text-lg shadow-sm group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300">
                                {{ substr($room->number, 0, 1) }}
                            </div>
                            <div>
                                <h4 class="font-bold text-lg text-slate-800 leading-none group-hover:text-indigo-600 transition-colors">{{ $room->number }}</h4>
                                <span class="text-[10px] text-slate-400 font-medium uppercase tracking-wide">
                                    {{ number_format($room->price/1000, 0) }}k / Semester
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Body: Visualisasi Bed --}}
                    <div class="p-6 flex-1 flex flex-col">
                        
                        {{-- Grid Bed --}}
                        <div class="grid grid-cols-2 gap-3 mb-2">
                            @for($i = 1; $i <= $room->capacity; $i++)
                                @php
                                    // Cari penghuni berdasarkan bed_slot
                                    $resident = $room->residents->firstWhere('bed_slot', $i);
                                    $isTaken = $resident !== null;
                                @endphp

                                <div class="relative h-16 rounded-xl border-2 flex flex-col items-center justify-center transition-all duration-200
                                    {{ $isTaken 
                                        ? 'bg-rose-50/50 border-rose-100 text-rose-700' 
                                        : 'bg-emerald-50/50 border-emerald-100 text-emerald-600 hover:border-emerald-300 hover:shadow-sm cursor-help' 
                                    }}"
                                    {{-- Tooltip Nama Lengkap --}}
                                    title="Bed {{ $i }}: {{ $isTaken ? $resident->name . ' (' . $resident->identity_number . ')' : 'Tersedia' }}">
                                    
                                    {{-- Nomor Bed (Pojok Kanan Atas) --}}
                                    <span class="absolute top-1 right-2 text-[9px] font-bold opacity-40">#{{ $i }}</span>

                                    @if($isTaken)
                                        {{-- Icon Bed --}}
                                        <svg class="w-4 h-4 mb-0.5 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 14a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H9a2 2 0 01-2-2v-4z M7 14V6a2 2 0 012-2h8a2 2 0 012 2v8 M7 14h10 M7 10h10 M7 6h10"></path></svg>
                                        
                                        {{-- Nama Penghuni (Dipotong) --}}
                                        <span class="text-[10px] font-bold truncate max-w-[90%] px-1 text-center leading-tight">
                                            {{ Str::limit($resident->name, 8) }}
                                        </span>
                                        
                                        {{-- Indikator Merah --}}
                                        <div class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-rose-500 rounded-full border-2 border-white"></div>
                                    @else
                                        {{-- Tampilan Kosong --}}

                                        <svg class="w-6 h-6 mb-1 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 14a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H9a2 2 0 01-2-2v-4z M7 14V6a2 2 0 012-2h8a2 2 0 012 2v8 M7 14h10 M7 10h10 M7 6h10"></path>
                                        </svg>
                                        <span class="text-[10px] font-medium opacity-60 uppercase tracking-wider">Kosong</span>
                                        {{-- Indikator Hijau --}}
                                        <div class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-emerald-400 rounded-full border-2 border-white"></div>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>

                    {{-- Footer Info --}}
                    <div class="px-6 py-3 bg-slate-50 border-t border-slate-100 flex items-center justify-between text-xs">
                        <div class="flex items-center gap-1.5">
                            <span class="relative flex h-2 w-2">
                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ $room->residents_count >= $room->capacity ? 'bg-rose-400' : 'bg-emerald-400' }} opacity-75"></span>
                              <span class="relative inline-flex rounded-full h-2 w-2 {{ $room->residents_count >= $room->capacity ? 'bg-rose-500' : 'bg-emerald-500' }}"></span>
                            </span>
                            <span class="font-semibold text-slate-600">
                                {{ $room->residents_count >= $room->capacity ? 'Penuh' : 'Tersedia' }}
                            </span>
                        </div>
                        <span class="text-slate-400 font-medium">
                            {{ $room->residents_count }}/{{ $room->capacity }} Bed
                        </span>
                    </div>

                    {{-- Hover Menu Action --}}
                    <div class="absolute inset-0 bg-white/90 backdrop-blur-[2px] flex flex-col items-center justify-center gap-4 opacity-0 group-hover:opacity-100 transition-all duration-300 z-10">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-200 pb-1">Opsi Kamar</span>
                        <div class="flex gap-3">
                            <a href="{{ route('rooms.edit', $room) }}" class="flex flex-col items-center justify-center w-14 h-14 rounded-2xl bg-white border border-slate-200 shadow-lg hover:border-amber-400 hover:text-amber-600 hover:-translate-y-1 transition-all">
                                <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                <span class="text-[9px] font-bold">Edit</span>
                            </a>
                            
                            {{-- FORM HAPUS DENGAN LOGIC POP-UP --}}
                            <form action="{{ route('rooms.destroy', $room) }}" method="POST"
                                  onsubmit="
                                    @if($room->residents_count > 0)
                                        {{-- LOGIC 1: Jika ada penghuni, tampilkan Alert & Batalkan Submit --}}
                                        alert('⛔ GAGAL MENGHAPUS!\n\nKamar ini masih ditempati oleh {{ $room->residents_count }} penghuni.\nSilakan pindahkan atau hapus data penghuni terlebih dahulu.');
                                        return false;
                                    @else
                                        {{-- LOGIC 2: Jika kosong, tampilkan Konfirmasi Biasa --}}
                                        return confirm('⚠️ PERINGATAN\n\nApakah Anda yakin ingin menghapus kamar {{ $room->number }}?\nData yang dihapus tidak dapat dikembalikan.');
                                    @endif
                                  ">
                                @csrf @method('DELETE')
                                <button type="submit" class="flex flex-col items-center justify-center w-14 h-14 rounded-2xl bg-white border border-slate-200 shadow-lg hover:border-rose-500 hover:text-rose-600 hover:-translate-y-1 transition-all">
                                    <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    <span class="text-[9px] font-bold">Hapus</span>
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            @empty
                <div class="col-span-full py-20 text-center border-2 border-dashed border-slate-200 rounded-3xl bg-slate-50/50">
                    <div class="flex flex-col items-center justify-center">
                        <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                            <svg class="h-8 w-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800">Belum ada kamar</h3>
                        <p class="text-slate-500 text-sm mt-1 mb-6 max-w-sm mx-auto">Database kamar masih kosong. Silakan tambahkan unit kamar baru untuk mulai mengelola asrama.</p>
                        <a href="{{ route('rooms.create') }}" class="inline-flex items-center px-6 py-3 border border-transparent shadow-sm text-sm font-bold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                            + Buat Kamar Pertama
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $rooms->links() }}
        </div>
    </div>
</x-app-layout>