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
                        // Kita reload ini nanti via server, di sini hanya visual statis utk alpine
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

                            {{-- INFO BOX --}}
                            <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-r-xl">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-red-700">
                                            <b>Jika Anda mengurangi kapasitas, sistem akan otomatis memindahkan penghuni di bed nomor besar ke bed kosong di nomor kecil.</b>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-5">
                                <div>
                                    <label class="block font-bold text-xs text-slate-500 uppercase tracking-wider mb-2">Kapasitas</label>

                                    {{-- GANTI MIN KE COUNT (BUKAN MAX SLOT) --}}
                                    @php
                                        $minCapacity = $room->residents()->count();
                                        if($minCapacity == 0) $minCapacity = 1;
                                    @endphp

                                    <input type="number" name="capacity" x-model="capacity"
                                        class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-3 transition-all duration-200 font-bold"
                                        required
                                        min="{{ $minCapacity }}">

                                    @if($minCapacity > 0)
                                        <p class="text-[11px] text-bold text-red-600 mt-1">Min: {{ $minCapacity }} (Sesuai jumlah penghuni)</p>
                                    @endif

                                    @error('capacity') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block font-bold text-xs text-slate-500 uppercase tracking-wider mb-2">Harga</label>
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

                    {{-- RIGHT: LIVE PREVIEW (Sama seperti sebelumnya) --}}
                    <div class="w-full lg:w-1/2">
                        <div class="h-full bg-slate-50 border-2 border-dashed border-slate-300 rounded-2xl p-6 flex flex-col relative overflow-hidden">
                            {{-- ... Kode Preview Visual Bed (Sama seperti sebelumnya) ... --}}
                            <div class="relative z-10 flex flex-col h-full">
                                <h3 class="text-slate-400 font-bold mb-6 uppercase tracking-wider text-xs text-center flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    Live Status Kamar
                                </h3>

                                <div class="flex-1 flex flex-col items-center justify-center">
                                    <div class="bg-white w-full max-w-sm rounded-2xl shadow-xl border border-slate-100 overflow-hidden">
                                        <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/80">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-700 font-bold text-sm shadow-sm">
                                                    <span x-text="roomNumber ? roomNumber.charAt(0).toUpperCase() : '?'"></span>
                                                </div>
                                                <span class="font-bold text-lg text-slate-800" x-text="roomNumber || 'Nama Kamar'"></span>
                                            </div>
                                        </div>
                                        <div class="p-5">
                                            <div class="grid gap-3" :class="capacity > 6 ? 'grid-cols-3' : 'grid-cols-2'">
                                                <template x-for="i in parseInt(capacity || 0)">
                                                    <div class="h-14 rounded-lg flex flex-col items-center justify-center relative border-2"
                                                         :class="residents[i] ? 'bg-rose-50 border-rose-200' : 'bg-emerald-50 border-emerald-100'">
                                                        <svg class="w-5 h-5 mb-0.5" :class="residents[i] ? 'text-rose-400' : 'text-emerald-300'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 14a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H9a2 2 0 01-2-2v-4z M7 14V6a2 2 0 012-2h8a2 2 0 012 2v8 M7 14h10 M7 10h10 M7 6h10"></path>
                                                        </svg>
                                                        <span class="text-[9px] font-bold truncate max-w-[80%] text-center px-1" :class="residents[i] ? 'text-rose-700' : 'text-emerald-600'" x-text="residents[i] ? residents[i] : 'Kosong'"></span>
                                                        <span class="absolute top-1 right-1 text-[8px] font-bold opacity-60" :class="residents[i] ? 'text-rose-800' : 'text-emerald-600'" x-text="i"></span>
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
                                    *Visualisasi menampilkan data sebelum perubahan disimpan.
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
