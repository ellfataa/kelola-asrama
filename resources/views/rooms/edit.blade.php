<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 leading-tight flex items-center gap-2">
            <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            {{ __('Edit Kamar') }}
        </h2>
    </x-slot>

    <div class="max-w-6xl mx-auto">
        <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-slate-100">
            <div class="p-8">

                {{-- ALPINE JS --}}
                <div x-data="{
                        capacity: {{ $room->capacity }},
                        roomNumber: '{{ $room->number }}',
                        // Data penghuni existing (bed_slot => nama)
                        residents: {{ json_encode($room->residents->pluck('name', 'bed_slot')) }}
                    }"
                    class="flex flex-col lg:flex-row gap-10">

                    {{-- LEFT: FORM EDIT --}}
                    <div class="w-full lg:w-1/2 space-y-6">
                        <div class="border-b border-slate-100 pb-4 mb-4">
                            <h3 class="text-lg font-bold text-slate-800">Update Informasi Kamar</h3>
                            <p class="text-sm text-slate-500">Ubah data kapasitas atau harga.</p>
                        </div>

                        <form action="{{ route('rooms.update', $room) }}" method="POST" class="space-y-6">
                            @csrf
                            @method('PUT')

                            <div>
                                <label class="block font-bold text-xs text-slate-500 uppercase tracking-wider mb-2">Nomor Kamar</label>
                                <input type="text" name="number" x-model="roomNumber"
                                    class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-3 transition-all duration-200" required>
                                @error('number') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                            </div>

                            {{-- Warning Box Capacity --}}
                            <div class="bg-amber-50 border-l-4 border-amber-400 p-4 rounded-r-xl">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-amber-700">
                                            <b>Perhatian:</b> Jangan mengurangi kapasitas di bawah jumlah penghuni yang ada ({{ $room->residents->count() }} orang).
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-5">
                                <div>
                                    <label class="block font-bold text-xs text-slate-500 uppercase tracking-wider mb-2">Kapasitas</label>
                                    <input type="number" name="capacity" x-model="capacity"
                                        class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-3 transition-all duration-200 font-bold"
                                        required min="{{ $room->residents->count() ?: 1 }}">
                                </div>
                                <div>
                                    <label class="block font-bold text-xs text-slate-500 uppercase tracking-wider mb-2">Harga / Semester</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">Rp</span>
                                        </div>
                                        <input type="number" name="price" value="{{ old('price', $room->price) }}"
                                            class="block w-full rounded-xl border-slate-200 bg-slate-50 pl-10 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-3 transition-all duration-200" required>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block font-bold text-xs text-slate-500 uppercase tracking-wider mb-2">Keterangan</label>
                                <textarea name="description" rows="3"
                                    class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-3 transition-all duration-200">{{ old('description', $room->description) }}</textarea>
                            </div>

                            <div class="flex justify-end gap-4 pt-4 border-t border-slate-100">
                                <a href="{{ route('rooms.index') }}" class="py-3 px-6 bg-white border border-slate-200 text-slate-600 rounded-xl hover:bg-red-400 hover:text-white font-bold transition-colors">Batal</a>
                                <button type="submit" class="py-3 px-6 bg-green-500 text-white rounded-xl hover:bg-green-800 font-bold shadow-lg hover:shadow-xl transition-all duration-200">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>

                    {{-- RIGHT: LIVE PREVIEW (DATA REAL) --}}
                    <div class="w-full lg:w-1/2">
                        <div class="h-full bg-slate-50 border-2 border-dashed border-slate-300 rounded-2xl p-6 flex flex-col relative overflow-hidden">
                            <div class="absolute inset-0 opacity-[0.03] pointer-events-none" style="background-image: linear-gradient(#4f46e5 1px, transparent 1px), linear-gradient(to right, #4f46e5 1px, transparent 1px); background-size: 20px 20px;"></div>

                            <div class="relative z-10 flex flex-col h-full">
                                <h3 class="text-slate-400 font-bold mb-6 uppercase tracking-wider text-xs text-center flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    Live Status Kamar
                                </h3>

                                <div class="flex-1 flex flex-col items-center justify-center">
                                    {{-- Card Preview --}}
                                    <div class="bg-white w-full max-w-sm rounded-2xl shadow-xl border border-slate-100 overflow-hidden">

                                        {{-- Header Preview --}}
                                        <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/80">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-700 font-bold text-sm shadow-sm">
                                                    <span x-text="roomNumber ? roomNumber.charAt(0).toUpperCase() : '?'"></span>
                                                </div>
                                                <span class="font-bold text-lg text-slate-800" x-text="roomNumber || 'Nama Kamar'"></span>
                                            </div>
                                        </div>

                                        {{-- Bed Grid Preview --}}
                                        <div class="p-5">
                                            <div class="grid gap-3" :class="capacity > 6 ? 'grid-cols-3' : 'grid-cols-2'">
                                                <template x-for="i in parseInt(capacity || 0)">
                                                    {{-- Logic Warna: Merah jika bed terisi (ada di residents) --}}
                                                    <div class="h-14 rounded-lg flex flex-col items-center justify-center relative border-2"
                                                         :class="residents[i]
                                                            ? 'bg-rose-50 border-rose-200'
                                                            : 'bg-emerald-50 border-emerald-100'">

                                                        {{-- Icon Bed --}}
                                                        <svg class="w-5 h-5 mb-0.5"
                                                             :class="residents[i] ? 'text-rose-400' : 'text-emerald-300'"
                                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 14a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H9a2 2 0 01-2-2v-4z M7 14V6a2 2 0 012-2h8a2 2 0 012 2v8 M7 14h10 M7 10h10 M7 6h10"></path>
                                                        </svg>

                                                        {{-- Nama Penghuni / Status Kosong --}}
                                                        <span class="text-[9px] font-bold truncate max-w-[80%] text-center px-1"
                                                              :class="residents[i] ? 'text-rose-700' : 'text-emerald-600'"
                                                              x-text="residents[i] ? residents[i] : 'Kosong'">
                                                        </span>

                                                        {{-- Nomor Bed --}}
                                                        <span class="absolute top-1 right-1 text-[8px] font-bold opacity-60"
                                                              :class="residents[i] ? 'text-rose-800' : 'text-emerald-600'"
                                                              x-text="i"></span>

                                                        {{-- Indikator Bulat --}}
                                                        <div x-show="residents[i]" class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-rose-500 rounded-full border-2 border-white"></div>
                                                    </div>
                                                </template>
                                            </div>

                                            <div class="mt-4 text-center">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">
                                                    Kapasitas: <span x-text="capacity" class="mx-1 font-bold"></span> Bed
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <p class="text-xs text-slate-400 mt-6 text-center italic">
                                    *Visualisasi di atas menampilkan data penghuni aktual di kamar ini.
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
