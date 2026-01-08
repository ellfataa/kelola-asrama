<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 leading-tight flex items-center gap-2">
            <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            {{ __('Denah & Daftar Kamar') }}
        </h2>
    </x-slot>

    <div class="space-y-6">

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Visualisasi Area Asrama</h3>
                <p class="text-sm text-slate-500">Kelola ketersediaan kamar dan pantau slot tempat tidur.</p>
            </div>
            <a href="{{ route('rooms.create') }}" class="group inline-flex items-center justify-center px-5 py-2.5 text-sm font-bold text-white transition-all duration-200 bg-indigo-600 rounded-xl hover:bg-indigo-700 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600">
                <svg class="w-5 h-5 mr-2 -ml-1 transition-transform group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Kamar
            </a>
        </div>

        {{-- GRID KAMAR --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($rooms as $room)
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-xl hover:border-indigo-200 transition-all duration-300 relative group overflow-hidden">

                    {{-- Header Card --}}
                    <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-700 font-bold text-sm shadow-sm">
                                {{ substr($room->number, 0, 1) }}
                            </div>
                            <span class="font-bold text-lg text-slate-800">{{ $room->number }}</span>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-white border border-slate-200 text-slate-600 shadow-sm">
                            {{ number_format($room->price/1000, 0) }}k
                            <span class="text-[10px] text-slate-400 font-normal ml-1">/bln</span>
                        </span>
                    </div>

                    {{-- Body: Visualisasi Bed --}}
                    <div class="p-5">
                        <div class="grid grid-cols-2 gap-3 mb-4">
                            @for($i = 1; $i <= $room->capacity; $i++)
                                @php
                                    $isTaken = $room->residents->contains('bed_slot', $i);
                                @endphp

                                <div class="relative h-12 rounded-lg border-2 flex items-center justify-center transition-colors duration-200
                                    {{ $isTaken
                                        ? 'bg-rose-50 border-rose-200 text-rose-600'
                                        : 'bg-emerald-50 border-emerald-200 text-emerald-600 hover:border-emerald-400 cursor-help'
                                    }}"
                                    title="Bed {{ $i }}: {{ $isTaken ? 'Terisi oleh Penghuni' : 'Slot Kosong' }}">

                                    {{-- Icon Bed --}}
                                    <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 14a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H9a2 2 0 01-2-2v-4z M7 14V6a2 2 0 012-2h8a2 2 0 012 2v8 M7 14h10 M7 10h10 M7 6h10"></path>
                                    </svg>
                                    <span class="absolute top-0.5 right-1 text-[8px] font-bold opacity-60">{{ $i }}</span>

                                    @if($isTaken)
                                        <div class="absolute -top-1 -right-1 w-3 h-3 bg-rose-500 rounded-full border-2 border-white"></div>
                                    @endif
                                </div>
                            @endfor
                        </div>

                        {{-- Footer Info --}}
                        <div class="flex items-center justify-between text-xs text-slate-500">
                            <span class="flex items-center gap-1">
                                <span class="w-2 h-2 rounded-full {{ $room->residents_count >= $room->capacity ? 'bg-rose-500' : 'bg-emerald-500' }}"></span>
                                {{ $room->residents_count >= $room->capacity ? 'Penuh' : 'Tersedia' }}
                            </span>
                            <span class="font-medium">{{ $room->residents_count }} / {{ $room->capacity }} Terisi</span>
                        </div>
                    </div>

                    {{-- Overlay Action Buttons (Hover Effect) --}}
                    <div class="absolute inset-0 bg-white/95 backdrop-blur-sm flex flex-col items-center justify-center gap-3 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-y-4 group-hover:translate-y-0 z-10">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Menu Kamar</span>
                        <div class="flex gap-2">
                            <a href="{{ route('rooms.edit', $room) }}" class="inline-flex items-center px-4 py-2 bg-amber-500 text-white rounded-xl text-sm font-bold shadow-md hover:bg-amber-600 transition">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                Edit
                            </a>
                            <form action="{{ route('rooms.destroy', $room) }}" method="POST" onsubmit="return confirm('Hapus kamar beserta datanya?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-rose-600 text-white rounded-xl text-sm font-bold shadow-md hover:bg-rose-700 transition">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center border-2 border-dashed border-slate-300 rounded-3xl bg-slate-50">
                    <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-slate-900">Belum ada kamar</h3>
                    <p class="mt-1 text-sm text-slate-500">Mulai dengan menambahkan unit kamar baru.</p>
                    <div class="mt-6">
                        <a href="{{ route('rooms.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            Tambah Kamar
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $rooms->links() }}
        </div>
    </div>
</x-app-layout>
