<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('rooms.index') }}" class="p-2 text-slate-400 hover:text-indigo-600 bg-white rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="font-bold text-xl text-slate-800 leading-tight">
                {{ __('Edit Data Kamar') }}
            </h2>
        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto pb-12 px-4 sm:px-6 lg:px-8">
        @php
            // PERUBAHAN: Buat mapping array yang index-nya adalah bed_slot aktual
            $mappedResidents = [];
            foreach($room->residents as $res) {
                $mappedResidents[$res->bed_slot] = $res->name;
            }
        @endphp

        <div x-data="{
                capacity: {{ $room->capacity }},
                roomNumber: '{{ $room->number }}',
                price: '{{ $room->price }}',
                // PERUBAHAN: Gunakan object mapping yang sudah sesuai bed_slot
                residents: {{ json_encode((object)$mappedResidents) }}
            }"
            class="flex flex-col lg:flex-row gap-8 lg:gap-10 items-start">

            {{-- LEFT: FORM EDIT --}}
            <div class="w-full lg:w-7/12 bg-white overflow-hidden shadow-sm rounded-3xl border border-slate-100">
                <div class="p-6 sm:p-8">

                    <div class="border-b border-slate-100 pb-5 mb-6">
                        <h3 class="text-lg font-extrabold text-slate-900 tracking-tight">Update Informasi Kamar</h3>
                        <p class="text-sm text-slate-500 font-medium mt-1">Ubah data identitas, kapasitas, atau harga kamar.</p>
                    </div>

                    <form action="{{ route('rooms.update', $room) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block font-bold text-xs text-slate-500 uppercase tracking-wider mb-2">Nomor/Nama Kamar</label>
                            <input type="text" name="number" x-model="roomNumber"
                                class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 py-3 px-4 transition-all duration-200 text-slate-900 font-semibold" required>
                            @error('number') <span class="text-rose-500 text-xs font-medium mt-1.5 block">{{ $message }}</span> @enderror
                        </div>

                        {{-- INFO BOX: Pemadatan Bed --}}
                        <div class="bg-amber-50 border border-amber-200 p-4 rounded-2xl flex gap-3 shadow-sm">
                            <div class="flex-shrink-0 mt-0.5">
                                <svg class="h-5 w-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-amber-800 font-medium leading-relaxed">
                                    <span class="font-bold text-amber-900 block mb-0.5">Pemadatan Bed Otomatis</span>
                                    Jika kapasitas kamar dikurangi, sistem akan memindahkan penghuni di urutan teratas ke kasur yang masih kosong secara otomatis.
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="block font-bold text-xs text-slate-500 uppercase tracking-wider mb-2">Kapasitas (Bed)</label>
                                @php
                                    $minCapacity = $room->residents()->count();
                                    if($minCapacity == 0) $minCapacity = 1;
                                @endphp
                                <div class="relative">
                                    <input type="number" name="capacity" x-model="capacity"
                                        class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 py-3 pl-4 pr-12 transition-all duration-200 font-extrabold text-slate-900 text-lg"
                                        required min="{{ $minCapacity }}" max="20">
                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    </div>
                                </div>
                                @if($minCapacity > 0)
                                    <p class="text-[11px] font-bold text-rose-500 mt-2">*Batas min: {{ $minCapacity }} Bed (Sudah terisi)</p>
                                @endif
                                @error('capacity') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block font-bold text-xs text-slate-500 uppercase tracking-wider mb-2">Harga per Semester</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <span class="text-slate-400 font-bold text-sm">Rp</span>
                                    </div>
                                    <input type="number" name="price" x-model="price"
                                        class="block w-full rounded-xl border-slate-200 bg-slate-50 pl-12 pr-4 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 py-3 transition-all duration-200 font-bold text-slate-900" required>
                                </div>
                                @error('price') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block font-bold text-xs text-slate-500 uppercase tracking-wider mb-2">Fasilitas/Keterangan Tambahan</label>
                            <textarea name="description" rows="3"
                                class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 py-3 px-4 transition-all duration-200 text-sm"
                                placeholder="Misal: AC, Kamar Mandi Dalam, WiFi...">{{ old('description', $room->description) }}</textarea>
                        </div>

                        <div class="pt-4 flex flex-col sm:flex-row items-center gap-3 border-t border-slate-100">
                            <button type="submit" class="w-full sm:w-auto flex-1 py-3.5 px-6 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 font-bold shadow-lg hover:shadow-indigo-500/30 transition-all duration-200 transform hover:-translate-y-0.5 focus:ring-4 focus:ring-indigo-500/30">
                                Simpan Perubahan
                            </button>
                            <a href="{{ route('rooms.index') }}" class="w-full sm:w-auto text-center py-3.5 px-6 bg-red-500 border border-slate-200 text-white rounded-xl hover:bg-red-600 font-bold transition-colors">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- RIGHT: PREVIEW STICKY --}}
            <div class="w-full lg:w-5/12 lg:sticky lg:top-28">
                <div class="bg-slate-50 border border-slate-200 rounded-3xl p-6 relative overflow-hidden min-h-[380px] flex flex-col shadow-sm">
                    <div class="absolute inset-0 opacity-[0.04] pointer-events-none" style="background-image: radial-gradient(#0f172a 1px, transparent 1px); background-size: 24px 24px;"></div>

                    <div class="relative z-10 flex flex-col h-full w-full max-w-sm mx-auto">
                        <div class="flex items-center justify-center gap-2 mb-6">
                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            <h3 class="text-slate-500 font-bold uppercase tracking-widest text-[10px]">Preview Tampilan Kamar</h3>
                        </div>

                        <div class="flex-1 flex flex-col">
                            {{-- Card Simulasi --}}
                            <div class="bg-white w-full rounded-2xl shadow-xl shadow-indigo-100/50 border border-slate-100 overflow-hidden transform transition-all duration-300">

                                {{-- Header Card Simulasi --}}
                                <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
                                    <div class="w-10 h-10 shrink-0 rounded-xl bg-indigo-600 flex items-center justify-center text-white font-extrabold text-lg shadow-sm">
                                        <span x-text="roomNumber ? roomNumber.charAt(0).toUpperCase() : '?'"></span>
                                    </div>
                                    <div class="flex flex-col truncate">
                                        <span class="font-extrabold text-lg text-slate-900 leading-tight truncate" x-text="roomNumber || 'Nama Kamar'"></span>
                                        <div class="flex items-center gap-1 mt-0.5 text-emerald-500">
                                            <span class="text-[10px] font-bold tracking-wider">Rp <span x-text="price ? new Intl.NumberFormat('id-ID').format(price) : '0'"></span></span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Body Card Simulasi (Bed) --}}
                                <div class="p-5">
                                    <div class="grid gap-2.5" :class="capacity > 6 ? 'grid-cols-3' : 'grid-cols-2'">
                                        {{-- PERUBAHAN: Looping cek index i langsung ke mapping --}}
                                        <template x-for="i in parseInt(capacity || 0)">
                                            <div class="h-12 rounded-xl flex flex-col items-center justify-center relative border-2 transition-all"
                                                 :class="residents[i] ? 'bg-rose-50 border-rose-100' : 'bg-emerald-50 border-emerald-100'">

                                                <svg class="w-4 h-4 mb-0.5" :class="residents[i] ? 'text-rose-400' : 'text-emerald-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 14a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H9a2 2 0 01-2-2v-4z M7 14V6a2 2 0 012-2h8a2 2 0 012 2v8 M7 14h10 M7 10h10 M7 6h10"></path>
                                                </svg>

                                                <span class="text-[9px] font-bold truncate max-w-[80%] text-center px-1" :class="residents[i] ? 'text-rose-700' : 'text-emerald-600'" x-text="residents[i] ? residents[i] : 'Tersedia'"></span>
                                                <span class="absolute top-1 right-1.5 text-[8px] font-bold" :class="residents[i] ? 'text-rose-400' : 'text-emerald-500'" x-text="'#'+i"></span>

                                                <div x-show="residents[i]" class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-rose-500 rounded-full border-2 border-white"></div>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                {{-- Footer Card Simulasi --}}
                                <div class="px-5 py-2.5 bg-slate-50 border-t border-slate-100 flex items-center justify-between text-xs">
                                    <div class="flex items-center gap-1.5">
                                        <span class="relative flex h-2 w-2">
                                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full" :class="{{ $room->residents->count() }} >= capacity ? 'bg-rose-400' : 'bg-emerald-400'" style="opacity: 0.75;"></span>
                                          <span class="relative inline-flex rounded-full h-2 w-2" :class="{{ $room->residents->count() }} >= capacity ? 'bg-rose-500' : 'bg-emerald-500'"></span>
                                        </span>
                                        <span class="font-bold text-slate-600" x-text="{{ $room->residents->count() }} >= capacity ? 'Kamar Penuh' : 'Tersedia'"></span>
                                    </div>
                                    <span class="text-[10px] font-extrabold bg-slate-200 text-slate-600 px-2 py-0.5 rounded-md flex items-center gap-1">
                                        <span>{{ $room->residents->count() }}</span>/<span x-text="capacity"></span> Bed
                                    </span>
                                </div>
                            </div>

                            <p class="text-[11px] text-red-400 mt-5 text-center font-medium">
                                *Visualisasi di atas menampilkan rancangan layout sebelum disimpan.
                            </p>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
