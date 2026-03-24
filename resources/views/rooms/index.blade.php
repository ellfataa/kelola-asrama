<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 w-full">
            <h2 class="font-bold text-xl text-slate-800 leading-tight flex items-center gap-2.5">
                <div class="p-2 bg-indigo-50 text-indigo-600 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                {{ __('Daftar & Denah Kamar') }}
            </h2>
            <div class="w-full sm:w-auto flex justify-end">
                <a href="{{ route('rooms.create') }}" class="w-full sm:w-auto justify-center inline-flex items-center px-5 py-2.5 bg-indigo-600 text-white rounded-xl font-bold text-sm shadow-md hover:bg-indigo-700 hover:shadow-indigo-500/30 transition-all duration-200 transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Kamar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6 pb-10">
        {{-- Notifikasi sukses dan gagal --}}
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" class="bg-emerald-50 text-emerald-700 p-4 rounded-xl border border-emerald-200 flex justify-between items-center shadow-sm mb-6">
                <div class="flex items-center gap-2"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> <span class="font-bold">{{ session('success') }}</span></div>
                <button @click="show = false">&times;</button>
            </div>
        @endif
        @if(session('error'))
            <div x-data="{ show: true }" x-show="show" class="bg-rose-50 text-rose-700 p-4 rounded-xl border border-rose-200 flex justify-between items-center shadow-sm mb-6">
                <div class="flex items-center gap-2"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> <span class="font-bold">{{ session('error') }}</span></div>
                <button @click="show = false">&times;</button>
            </div>
        @endif

        {{-- INFO BAR --}}
        <div class="flex items-center justify-between text-sm text-slate-500 px-1">
            <p>Menampilkan <span class="font-bold text-slate-800">{{ $rooms->count() }}</span> dari <span class="font-bold text-slate-800">{{ $rooms->total() }}</span> total kamar</p>
        </div>

        {{-- GRID KAMAR --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($rooms as $room)
                <div class="bg-white rounded-3xl border border-slate-200 shadow-sm hover:shadow-xl hover:shadow-indigo-100/50 hover:border-indigo-200 transition-all duration-300 relative group overflow-hidden flex flex-col">

                    {{-- Header Card --}}
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-start">
                        <div class="flex gap-3">
                            <div class="w-12 h-12 shrink-0 rounded-2xl bg-white border border-slate-200 flex items-center justify-center text-slate-700 font-extrabold text-xl shadow-sm group-hover:bg-indigo-600 group-hover:text-white group-hover:border-indigo-600 transition-all duration-300">
                                {{ substr($room->number, 0, 1) }}
                            </div>
                            <div class="flex flex-col">
                                <h4 class="font-extrabold text-xl text-slate-900 group-hover:text-indigo-600 transition-colors leading-tight">{{ $room->number }}</h4>
                                <div class="flex items-center gap-1.5 mt-1 text-slate-500">
                                    <span class="text-[11px] font-bold tracking-wider">Rp {{ number_format($room->price, 0, ',', '.') }}<span class="text-[9px] font-medium opacity-70">/Smt</span></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Body: Visualisasi Bed --}}
                    <div class="p-6 flex-1 flex flex-col">
                        <div class="grid grid-cols-2 gap-3">
                            @for($i = 1; $i <= $room->capacity; $i++)
                                @php
                                    $resident = $room->residents->firstWhere('bed_slot', $i);
                                    $isTaken = $resident !== null;
                                @endphp

                                <div class="relative h-14 rounded-xl border-2 flex flex-col items-center justify-center transition-all duration-200
                                    {{ $isTaken
                                        ? 'bg-rose-50/50 border-rose-100 text-rose-700'
                                        : 'bg-emerald-50/50 border-emerald-100 text-emerald-600 hover:border-emerald-300'
                                    }}"
                                    title="Bed {{ $i }}: {{ $isTaken ? $resident->name : 'Tersedia' }}">

                                    <span class="absolute top-1 right-1.5 text-[9px] font-bold opacity-40">#{{ $i }}</span>

                                    @if($isTaken)
                                        <svg class="w-4 h-4 mb-0.5 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 14a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H9a2 2 0 01-2-2v-4z M7 14V6a2 2 0 012-2h8a2 2 0 012 2v8 M7 14h10 M7 10h10 M7 6h10"></path></svg>
                                        <span class="text-[9px] font-bold truncate max-w-[90%] px-1 text-center leading-tight">
                                            {{ Str::limit($resident->name, 10) }}
                                        </span>
                                        <div class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-rose-500 rounded-full border-2 border-white"></div>
                                    @else
                                        <svg class="w-5 h-5 mb-0.5 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 14a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H9a2 2 0 01-2-2v-4z M7 14V6a2 2 0 012-2h8a2 2 0 012 2v8 M7 14h10 M7 10h10 M7 6h10"></path>
                                        </svg>
                                        <span class="text-[9px] font-bold opacity-50 uppercase tracking-wider">Kosong</span>
                                        <div class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-emerald-400 rounded-full border-2 border-white"></div>
                                    @endif
                                </div>
                            @endfor
                        </div>

                        {{-- Section Keterangan / Fasilitas --}}
                        @if($room->description)
                        <div class="mt-5 pt-4 border-t border-slate-100 border-dashed flex items-start gap-2">
                            <svg class="w-3.5 h-3.5 text-indigo-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <p class="text-[10px] font-medium text-slate-500 leading-relaxed break-words line-clamp-3" title="{{ $room->description }}">
                                Fasilitas: {{ $room->description }}
                            </p>
                        </div>
                        @endif
                    </div>

                    {{-- Footer Info --}}
                    <div class="px-6 py-3 bg-slate-50 border-t border-slate-100 flex items-center justify-between text-xs">
                        <div class="flex items-center gap-1.5">
                            <span class="relative flex h-2 w-2">
                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ $room->residents_count >= $room->capacity ? 'bg-rose-400' : 'bg-emerald-400' }} opacity-75"></span>
                              <span class="relative inline-flex rounded-full h-2 w-2 {{ $room->residents_count >= $room->capacity ? 'bg-rose-500' : 'bg-emerald-500' }}"></span>
                            </span>
                            <span class="font-bold text-slate-700">
                                {{ $room->residents_count >= $room->capacity ? 'Kamar Penuh' : 'Kamar Terisi' }}
                            </span>
                        </div>
                        <span class="text-[10px] font-extrabold bg-slate-200 text-slate-600 px-2 py-1 rounded-md">
                            {{ $room->residents_count }}/{{ $room->capacity }} Bed
                        </span>
                    </div>

                    {{-- Hover Menu Action --}}
                    <div class="absolute inset-0 bg-white/95 backdrop-blur-[2px] flex flex-col items-center justify-center gap-5 opacity-0 group-hover:opacity-100 transition-all duration-300 z-10">
                        <div class="flex flex-col items-center mb-2">
                            <span class="font-bold text-slate-800 text-lg">{{ $room->number }}</span>
                            <span class="text-[10px] font-bold text-indigo-500 uppercase tracking-widest">Opsi Manajemen</span>
                        </div>

                        <div class="flex gap-4">
                            <a href="{{ route('rooms.edit', $room) }}" class="flex flex-col items-center justify-center w-14 h-14 rounded-2xl bg-white border border-slate-200 shadow-md hover:border-amber-400 hover:text-amber-600 hover:-translate-y-1 transition-all">
                                <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                <span class="text-[9px] font-bold">Edit</span>
                            </a>

                            <form action="{{ route('rooms.destroy', $room) }}" method="POST"
                                  onsubmit="
                                    @if($room->residents_count > 0)
                                        alert('⛔ GAGAL MENGHAPUS!\n\nKamar ini masih ditempati oleh {{ $room->residents_count }} penghuni.\nSilakan pindahkan atau hapus data penghuni terlebih dahulu.');
                                        return false;
                                    @else
                                        return confirm('⚠️ PERINGATAN\n\nApakah Anda yakin ingin menghapus kamar {{ $room->number }}?\nData yang dihapus tidak dapat dikembalikan.');
                                    @endif
                                  ">
                                @csrf @method('DELETE')
                                <button type="submit" class="flex flex-col items-center justify-center w-14 h-14 rounded-2xl bg-white border border-slate-200 shadow-md hover:border-rose-500 hover:text-rose-600 hover:-translate-y-1 transition-all">
                                    <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    <span class="text-[9px] font-bold">Hapus</span>
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            @empty
                <div class="col-span-full py-24 text-center border-2 border-dashed border-slate-200 rounded-3xl bg-slate-50/50">
                    <div class="flex flex-col items-center justify-center">
                        <div class="w-16 h-16 bg-white border border-slate-100 rounded-2xl shadow-sm flex items-center justify-center mb-4">
                            <svg class="h-8 w-8 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800">Kamar Belum Tersedia</h3>
                        <p class="text-slate-500 text-sm mt-1 mb-6 max-w-sm mx-auto">Sistem belum memiliki data kamar untuk dikelola. Silakan buat unit kamar pertama Anda.</p>
                        <a href="{{ route('rooms.create') }}" class="inline-flex items-center px-6 py-3 shadow-md font-bold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 hover:shadow-indigo-500/30 transition-all hover:-translate-y-0.5">
                            Tambah Kamar Pertama
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
