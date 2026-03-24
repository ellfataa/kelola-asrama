<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('residents.index') }}" class="p-2 text-slate-400 hover:text-indigo-600 bg-white rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="font-bold text-xl text-slate-800 leading-tight">
                {{ __('Edit Data Taruna') }}
            </h2>
        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto pb-12 px-4 sm:px-6 lg:px-8">

        @php
            $roomsData = [];
            foreach($rooms as $r) {
                $roomsData[$r->id] = [
                    'number' => $r->number,
                    'capacity' => $r->capacity,
                    // Abaikan bed penghuni ini sendiri agar kasurnya saat ini bisa dipilih kembali
                    'taken_beds' => $r->residents->where('id', '!=', $resident->id)->pluck('bed_slot')->toArray(),
                    'price' => number_format($r->price, 0, ',', '.')
                ];
            }
        @endphp

        {{-- ALPINE JS BINDING --}}
        <div x-data="{
                selectedRoom: '{{ old('room_id', $resident->room_id) }}',
                selectedBed: {{ old('bed_slot', $resident->bed_slot) ?? 'null' }},
                roomsData: {{ json_encode($roomsData) }},
                entryDate: '{{ old('entry_date', $resident->entry_date->format('Y-m-d')) }}',
                exitDate: '',

                calculateExitDate() {
                    if(!this.entryDate) return;
                    let date = new Date(this.entryDate);
                    // Tambah 4 bulan
                    date.setMonth(date.getMonth() + 4);
                    // Tambah 10 hari
                    date.setDate(date.getDate() + 10);

                    let d = date.getDate().toString().padStart(2, '0');
                    let m = (date.getMonth() + 1).toString().padStart(2, '0');
                    let y = date.getFullYear();
                    this.exitDate = y + '-' + m + '-' + d;
                },
                isBedTaken(bedNum) {
                    if(!this.selectedRoom || !this.roomsData[this.selectedRoom]) return false;
                    return this.roomsData[this.selectedRoom].taken_beds.includes(bedNum);
                },
                getCapacity() {
                    if(!this.selectedRoom || !this.roomsData[this.selectedRoom]) return 0;
                    return this.roomsData[this.selectedRoom].capacity;
                },
                getPrice() {
                    if(!this.selectedRoom || !this.roomsData[this.selectedRoom]) return 0;
                    return this.roomsData[this.selectedRoom].price;
                },
                getRoomNumber() {
                    if(!this.selectedRoom || !this.roomsData[this.selectedRoom]) return '?';
                    return this.roomsData[this.selectedRoom].number;
                }
            }"
            x-init="calculateExitDate()"
            class="flex flex-col lg:flex-row gap-8 lg:gap-10 items-start">

            {{-- Kolom Kiri: FORM EDIT --}}
            <div class="w-full lg:w-7/12 bg-white overflow-hidden shadow-sm rounded-3xl border border-slate-100">
                <div class="p-6 sm:p-8">

                    <div class="border-b border-slate-100 pb-5 mb-6 flex justify-between items-center">
                        <div>
                            <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">Update Biodata</h3>
                            <p class="text-sm text-slate-500 font-medium mt-1">Perbarui data taruna atau pindahkan posisi kasur/kamar.</p>
                        </div>
                        <div class="hidden sm:flex w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-500 text-white items-center justify-center font-bold text-xl shadow-md">
                            {{ substr($resident->name, 0, 1) }}
                        </div>
                    </div>

                    <form action="{{ route('residents.update', $resident) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="bed_slot" :value="selectedBed">

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="sm:col-span-2">
                                <label class="block font-bold text-xs text-slate-500 uppercase tracking-wider mb-2">Nama Lengkap</label>
                                <input type="text" name="name" value="{{ old('name', $resident->name) }}" class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 py-3.5 px-4 transition-all duration-200 font-semibold text-slate-900" required>
                                @error('name') <span class="text-rose-500 text-xs font-bold mt-1.5 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block font-bold text-xs text-slate-500 uppercase tracking-wider mb-2">NIM/KTP</label>
                                <input type="text" name="identity_number" value="{{ old('identity_number', $resident->identity_number) }}" class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 py-3.5 px-4 transition-all duration-200 font-mono text-slate-900" required>
                                @error('identity_number') <span class="text-rose-500 text-xs font-bold mt-1.5 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block font-bold text-xs text-slate-500 uppercase tracking-wider mb-2">No. HP (WhatsApp)</label>
                                <input type="text" name="phone" value="{{ old('phone', $resident->phone) }}"
                                       oninput="
                                            this.value = this.value.replace(/[^0-9]/g, '');
                                            if(this.value.length > 0 && !this.value.startsWith('0')) this.value = '08';
                                            if(this.value.length > 1 && !this.value.startsWith('08')) this.value = '08' + this.value.substring(2);
                                       "
                                       inputmode="numeric" maxlength="15"
                                       class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 py-3.5 px-4 transition-all duration-200 font-semibold text-slate-900">
                                @error('phone') <span class="text-rose-500 text-xs font-bold mt-1.5 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="border-t border-slate-100 pt-6">
                                 <label class="block font-bold text-xs text-slate-500 uppercase tracking-wider mb-2">Tanggal Masuk Asrama</label>
                                 <div class="relative">
                                     <input type="date" value="{{ $resident->entry_date->format('Y-m-d') }}" readonly
                                            class="block w-full rounded-xl border-slate-200 bg-slate-100 text-slate-500 py-3.5 px-4 transition-all duration-200 font-semibold cursor-not-allowed">
                                 </div>
                            </div>

                            <div class="border-t border-slate-100 pt-6">
                                 <label class="block font-bold text-xs text-orange-500 uppercase tracking-wider mb-2">Tanggal Keluar Asrama</label>
                                 <div class="relative">
                                     <input type="date" x-model="exitDate" readonly
                                            class="block w-full rounded-xl border-orange-100 bg-orange-50 text-orange-700 py-3.5 px-4 transition-all duration-200 font-bold cursor-not-allowed outline-none focus:ring-0">
                                 </div>
                            </div>
                        </div>

                        {{-- SECTION PEMILIHAN KAMAR --}}
                        <div class="bg-amber-50/40 border border-amber-100 rounded-3xl p-6 mt-8">
                            <label class="block font-extrabold text-sm text-slate-800 tracking-wide mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                Pindah Kamar/Bed (Opsional)
                            </label>

                            <select name="room_id" x-model="selectedRoom" @change="selectedBed = null"
                                    class="block w-full rounded-xl border-slate-200 bg-white focus:bg-white focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 py-4 px-4 transition-all duration-200 cursor-pointer font-bold text-slate-800 shadow-sm">
                                @foreach($rooms as $room)
                                    @php
                                        $isMyRoom = $resident->room_id == $room->id;
                                        // Kapasitas sisa: jika ini kamar saya, seolah slot saya kosong
                                        $sisa = $room->capacity - $room->residents->count() + ($isMyRoom ? 1 : 0);
                                    @endphp
                                    <option value="{{ $room->id }}" {{ old('room_id', $resident->room_id) == $room->id ? 'selected' : '' }} {{ (!$isMyRoom && $sisa <= 0) ? 'disabled' : '' }} class="{{ (!$isMyRoom && $sisa <= 0) ? 'text-slate-300' : '' }}">
                                        Kamar {{ $room->number }}
                                        {{ $isMyRoom ? '(Kamar Saat Ini)' : (($sisa <= 0) ? '(PENUH)' : '- Sisa ' . $sisa . ' Bed') }}
                                    </option>
                                @endforeach
                            </select>

                            @error('room_id') <span class="text-rose-500 text-xs font-bold mt-2 block">{{ $message }}</span> @enderror
                            @error('bed_slot') <span class="text-rose-500 text-xs font-bold mt-2 block">{{ $message }}</span> @enderror

                            <div x-show="selectedRoom && !selectedBed" class="mt-4 flex items-start gap-2 bg-amber-50 border border-amber-200 text-amber-700 p-3 rounded-xl shadow-sm">
                                <svg class="w-5 h-5 flex-shrink-0 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span class="text-xs font-bold leading-relaxed">Silakan pilih ulang posisi kasur/bed di panel visual sebelah kanan.</span>
                            </div>
                        </div>

                        <div class="pt-6 flex flex-col sm:flex-row items-center justify-start gap-3 border-t border-slate-100">
                            <button type="submit" :disabled="!selectedRoom || !selectedBed" :class="(!selectedRoom || !selectedBed) ? 'opacity-50 cursor-not-allowed bg-slate-400' : 'bg-indigo-600 hover:bg-indigo-700 shadow-lg hover:shadow-indigo-500/30 transform hover:-translate-y-0.5'" class="w-full sm:w-auto py-3.5 px-8 text-white rounded-xl font-bold transition-all duration-200 focus:ring-4 focus:ring-indigo-500/30">
                                Simpan Perubahan
                            </button>
                            <a href="{{ route('residents.index') }}" class="w-full sm:w-auto text-center py-3.5 px-6 bg-red-500 border border-white text-white rounded-xl hover:bg-red-800 font-bold transition-colors">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Kolom Kanan: INTERACTIVE PREVIEW STICKY --}}
            <div class="w-full lg:w-5/12 lg:sticky lg:top-28">
                <div class="bg-slate-50 border border-slate-200 rounded-3xl p-6 relative overflow-hidden min-h-[380px] flex flex-col shadow-sm transition-all duration-500"
                     :class="selectedRoom ? 'ring-4 ring-indigo-500/20 border-indigo-200 bg-indigo-50/30' : ''">

                    <div class="absolute inset-0 opacity-[0.04] pointer-events-none" style="background-image: radial-gradient(#0f172a 1px, transparent 1px); background-size: 24px 24px;"></div>

                    <div class="relative z-10 flex flex-col h-full w-full max-w-sm mx-auto">
                        <div class="flex items-center justify-center gap-2 mb-6">
                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"></path></svg>
                            <h3 class="text-slate-500 font-bold uppercase tracking-widest text-[10px]">Pilih Posisi Kasur/Bed</h3>
                        </div>

                        {{-- Tampilan saat kamar belum dipilih --}}
                        <div x-show="!selectedRoom" class="flex-1 flex flex-col items-center justify-center text-center opacity-60 mt-10">
                            <svg class="w-16 h-16 text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            <h4 class="text-slate-700 font-bold text-lg mb-1">Visualisasi Kamar</h4>
                            <p class="text-xs text-slate-500 max-w-[200px] leading-relaxed">Silakan pilih unit kamar di formulir sebelah kiri untuk melihat denah.</p>
                        </div>

                        {{-- Tampilan interaktif saat kamar sudah dipilih --}}
                        <div x-show="selectedRoom" x-transition.opacity.duration.500ms class="flex-1 flex flex-col" style="display: none;">

                            <div class="bg-white w-full rounded-2xl shadow-xl shadow-indigo-100/50 border border-slate-100 overflow-hidden transform transition-all duration-300">
                                {{-- Header Card --}}
                                <div class="px-5 py-4 border-b border-slate-100 bg-white flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 shrink-0 rounded-xl bg-indigo-600 flex items-center justify-center text-white font-extrabold text-lg shadow-sm">
                                            <span x-text="getRoomNumber().charAt(0).toUpperCase()"></span>
                                        </div>
                                        <div class="flex flex-col truncate">
                                            <span class="font-extrabold text-lg text-slate-900 leading-tight truncate" x-text="getRoomNumber()"></span>
                                            <div class="flex items-center gap-1 mt-0.5 text-emerald-500">
                                                <span class="text-[10px] font-bold tracking-wider">Rp <span x-text="getPrice()"></span><span class="text-[8px] opacity-70">/Smt</span></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Bed Grid Selector --}}
                                <div class="p-5 bg-slate-50/50">
                                    <div class="grid gap-2.5" :class="getCapacity() > 6 ? 'grid-cols-3' : 'grid-cols-2'">
                                        <template x-for="i in getCapacity()">
                                            <div @click="!isBedTaken(i) ? selectedBed = i : null"
                                                 :class="{
                                                     'bg-rose-50 border-rose-200 opacity-60 cursor-not-allowed': isBedTaken(i),
                                                     'bg-indigo-600 border-indigo-600 shadow-lg shadow-indigo-300/50 transform -translate-y-1': selectedBed == i,
                                                     'bg-white border-slate-200 hover:border-indigo-400 hover:bg-indigo-50 hover:shadow-md cursor-pointer': !isBedTaken(i) && selectedBed != i
                                                 }"
                                                 class="border-2 rounded-xl h-16 flex flex-col items-center justify-center relative transition-all duration-200 group">

                                                <svg class="w-5 h-5 mb-0.5 transition-colors duration-200"
                                                     :class="isBedTaken(i) ? 'text-rose-400' : (selectedBed == i ? 'text-white' : 'text-emerald-400 group-hover:text-indigo-500')"
                                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 14a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H9a2 2 0 01-2-2v-4z M7 14V6a2 2 0 012-2h8a2 2 0 012 2v8 M7 14h10 M7 10h10 M7 6h10"></path>
                                                </svg>

                                                <span class="text-[9px] font-extrabold uppercase tracking-wide transition-colors"
                                                      :class="isBedTaken(i) ? 'text-rose-700' : (selectedBed == i ? 'text-white' : 'text-slate-600 group-hover:text-indigo-700')"
                                                      x-text="isBedTaken(i) ? 'Terisi' : (selectedBed == i ? 'Dipilih' : 'Bed ' + i)"></span>

                                                <span x-show="!isBedTaken(i) && selectedBed != i" class="absolute top-1 right-1.5 text-[8px] font-bold text-slate-400" x-text="'#'+i"></span>

                                                {{-- Indikator Terisi --}}
                                                <div x-show="isBedTaken(i)" class="absolute -top-1 -right-1 w-3 h-3 bg-rose-500 rounded-full border-2 border-white shadow-sm"></div>

                                                {{-- Indikator Terpilih (Checklist) --}}
                                                <div x-show="selectedBed == i" class="absolute -top-1.5 -right-1.5 w-4 h-4 bg-indigo-100 rounded-full border border-white text-indigo-700 flex items-center justify-center shadow-sm">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <p class="text-[11px] text-red-500 mt-5 text-center font-medium bg-white p-2 rounded-lg border border-slate-200/60 shadow-sm">
                                Klik pada kotak kasur yang tersedia untuk mengalokasikan.
                            </p>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
