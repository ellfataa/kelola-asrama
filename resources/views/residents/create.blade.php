<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 leading-tight flex items-center gap-2">
            <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
            {{ __('Tambah Penghuni Baru') }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-slate-100">
            <div class="p-8">

                {{-- Logic PHP tetap utuh --}}
                @php
                    $roomsData = [];
                    foreach($rooms as $r) {
                        $roomsData[$r->id] = [
                            'capacity' => $r->capacity,
                            'taken_beds' => $r->residents->pluck('bed_slot')->toArray(),
                            'price' => number_format($r->price, 0, ',', '.')
                        ];
                    }
                @endphp

                <form action="{{ route('residents.store') }}" method="POST"
                      x-data="{
                          selectedRoom: '',
                          selectedBed: null,
                          roomsData: {{ json_encode($roomsData) }},
                          isBedTaken(bedNum) {
                              if(!this.selectedRoom) return false;
                              return this.roomsData[this.selectedRoom].taken_beds.includes(bedNum);
                          },
                          getCapacity() {
                              if(!this.selectedRoom) return 0;
                              return this.roomsData[this.selectedRoom].capacity;
                          },
                          getPrice() {
                              if(!this.selectedRoom) return 0;
                              return this.roomsData[this.selectedRoom].price;
                          }
                      }"
                      class="space-y-6">
                    @csrf

                    <div class="border-b border-slate-100 pb-4 mb-4">
                        <h3 class="text-lg font-bold text-slate-800">Biodata Penghuni</h3>
                        <p class="text-sm text-slate-500">Lengkapi data diri calon penghuni.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block font-bold text-xs text-slate-500 uppercase tracking-wider mb-2">Nama Lengkap</label>
                            <input type="text" name="name" class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-3 transition-all duration-200" placeholder="Sesuai KTP" required>
                            @error('name') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block font-bold text-xs text-slate-500 uppercase tracking-wider mb-2">NIM / KTP</label>
                            <input type="text" name="identity_number" class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-3 transition-all duration-200" placeholder="Nomor Identitas" required>
                            @error('identity_number') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block font-bold text-xs text-slate-500 uppercase tracking-wider mb-2">No. HP (WhatsApp)</label>
                            <input type="text" name="phone" class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-3 transition-all duration-200" placeholder="08..." >
                        </div>

                        <div>
                             <label class="block font-bold text-xs text-slate-500 uppercase tracking-wider mb-2">Tanggal Masuk</label>
                             <input type="date" name="entry_date" value="{{ date('Y-m-d') }}" class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-3 transition-all duration-200" required>
                        </div>
                    </div>

                    <div class="border-b border-slate-100 pb-4 pt-4 mb-4">
                        <h3 class="text-lg font-bold text-slate-800">Alokasi Kamar</h3>
                        <p class="text-sm text-slate-500">Pilih unit kamar dan slot tempat tidur.</p>
                    </div>

                    <div>
                        <label class="block font-bold text-xs text-slate-500 uppercase tracking-wider mb-2">Pilih Unit Kamar</label>
                        <select name="room_id" x-model="selectedRoom" @change="selectedBed = null"
                                class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-3 transition-all duration-200 cursor-pointer">
                            <option value="">-- Pilih Kamar Tersedia --</option>
                            @foreach($rooms as $room)
                                @php
                                    $sisa = $room->capacity - $room->residents->count();
                                    $isFull = $sisa <= 0;
                                @endphp
                                <option value="{{ $room->id }}" {{ $isFull ? 'disabled' : '' }} class="{{ $isFull ? 'bg-slate-100 text-slate-400' : '' }}">
                                    [ Unit {{ $room->number }} ] - {{ $isFull ? 'PENUH' : 'Sisa ' . $sisa . ' Slot' }} - Rp {{ number_format($room->price, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                        @error('room_id') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    {{-- Visualisasi Grid Bed --}}
                    <div x-show="selectedRoom" x-transition.opacity.duration.500ms class="mt-6 bg-slate-50 border border-slate-200 rounded-2xl p-6">
                        <div class="flex justify-between items-center mb-4">
                            <label class="font-bold text-slate-700">Pilih Posisi Bed</label>
                            <span class="text-xs font-bold bg-white border border-slate-200 px-3 py-1 rounded-full text-slate-600">
                                Harga: Rp <span x-text="getPrice()"></span> / semester
                            </span>
                        </div>

                        <input type="hidden" name="bed_slot" :value="selectedBed">

                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                            <template x-for="i in getCapacity()">
                                <div @click="!isBedTaken(i) ? selectedBed = i : null"
                                     :class="{
                                         'bg-slate-100 border-slate-200 opacity-50 cursor-not-allowed': isBedTaken(i),
                                         'bg-indigo-50 border-indigo-500 ring-2 ring-indigo-200 text-indigo-700': selectedBed == i,
                                         'bg-white border-slate-200 hover:border-indigo-400 hover:shadow-md cursor-pointer text-slate-600': !isBedTaken(i) && selectedBed != i
                                     }"
                                     class="border-2 rounded-xl p-4 flex flex-col items-center justify-center h-24 relative transition-all duration-200">

                                    {{-- Bed Icon --}}
                                    <svg class="w-8 h-8 mb-2" :class="isBedTaken(i) ? 'text-slate-300' : (selectedBed == i ? 'text-indigo-600' : 'text-slate-300')" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 14a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H9a2 2 0 01-2-2v-4z M7 14V6a2 2 0 012-2h8a2 2 0 012 2v8 M7 14h10 M7 10h10 M7 6h10"></path>
                                    </svg>

                                    <span class="text-xs font-bold" x-text="'Slot ' + i"></span>

                                    <div x-show="isBedTaken(i)" class="absolute top-2 right-2">
                                        <span class="block w-2 h-2 bg-red-400 rounded-full"></span>
                                    </div>
                                    <div x-show="selectedBed == i" class="absolute top-2 right-2">
                                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                </div>
                            </template>
                        </div>
                        <div x-show="!selectedBed" class="mt-3 text-xs text-red-500 text-center font-medium">* Silakan klik salah satu slot bed yang tersedia</div>
                        @error('bed_slot') <span class="text-red-500 text-sm block mt-2">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-4">
                         <a href="{{ route('residents.index') }}" class="py-3 px-6 bg-white border border-slate-200 text-slate-600 rounded-xl hover:bg-red-400 hover:text-white font-bold transition-colors">Batal</a>
                         <button type="submit" :disabled="!selectedBed" :class="!selectedBed ? 'opacity-50 cursor-not-allowed bg-slate-400' : 'bg-green-500 hover:bg-green-800 shadow-lg hover:shadow-xl'" class="py-3 px-8 text-white rounded-xl font-bold transition-all duration-200">
                             Simpan Data
                         </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>
