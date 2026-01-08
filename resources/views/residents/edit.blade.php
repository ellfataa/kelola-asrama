<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 leading-tight flex items-center gap-2">
            <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            {{ __('Edit Data Penghuni') }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-slate-100">
            <div class="p-8">

                {{-- Logic PHP --}}
                @php
                    $roomsData = [];
                    foreach($rooms as $r) {
                        $roomsData[$r->id] = [
                            'capacity' => $r->capacity,
                            'taken_beds' => $r->residents->where('id', '!=', $resident->id)->pluck('bed_slot')->toArray(),
                            'price' => number_format($r->price, 0, ',', '.')
                        ];
                    }
                @endphp

                <form action="{{ route('residents.update', $resident) }}" method="POST"
                      x-data="{
                          selectedRoom: '{{ $resident->room_id }}',
                          selectedBed: {{ $resident->bed_slot }},
                          roomsData: {{ json_encode($roomsData) }},
                          isBedTaken(bedNum) {
                              if(!this.selectedRoom) return false;
                              return this.roomsData[this.selectedRoom].taken_beds.includes(bedNum);
                          },
                          getCapacity() {
                              if(!this.selectedRoom) return 0;
                              return this.roomsData[this.selectedRoom].capacity;
                          }
                      }"
                      class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="border-b border-slate-100 pb-4 mb-4 flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-bold text-slate-800">Update Biodata</h3>
                            <p class="text-sm text-slate-500">Edit informasi pribadi penghuni.</p>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-lg">
                            {{ substr($resident->name, 0, 1) }}
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block font-bold text-xs text-slate-500 uppercase tracking-wider mb-2">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', $resident->name) }}" class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-3 transition-all duration-200" required>
                        </div>

                        <div>
                            <label class="block font-bold text-xs text-slate-500 uppercase tracking-wider mb-2">NIM / KTP</label>
                            <input type="text" name="identity_number" value="{{ old('identity_number', $resident->identity_number) }}" class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-3 transition-all duration-200" required>
                        </div>

                        <div>
                            <label class="block font-bold text-xs text-slate-500 uppercase tracking-wider mb-2">No. HP</label>
                            <input type="text" name="phone" value="{{ old('phone', $resident->phone) }}" class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-3 transition-all duration-200">
                        </div>
                    </div>

                    <div class="border-b border-slate-100 pb-4 pt-4 mb-4">
                        <h3 class="text-lg font-bold text-slate-800">Pindah / Update Kamar</h3>
                        <p class="text-sm text-slate-500">Ubah unit atau posisi bed jika diperlukan.</p>
                    </div>

                    <div>
                        <label class="block font-bold text-xs text-slate-500 uppercase tracking-wider mb-2">Pilih Unit Kamar</label>
                        <select name="room_id" x-model="selectedRoom" @change="selectedBed = null"
                                class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-3 transition-all duration-200 cursor-pointer">
                            @foreach($rooms as $room)
                                @php
                                    $isMyRoom = $resident->room_id == $room->id;
                                    $filled = $room->residents->count();
                                    $sisa = $room->capacity - $filled + ($isMyRoom ? 1 : 0);
                                @endphp
                                <option value="{{ $room->id }}" {{ $sisa <= 0 ? 'disabled' : '' }} class="{{ $sisa <= 0 ? 'bg-slate-100 text-slate-400' : '' }}">
                                    Kamar {{ $room->number }} {{ $isMyRoom ? '(Kamar Saat Ini)' : '(Sisa ' . $sisa . ' Slot)' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div x-show="selectedRoom" class="mt-6 bg-slate-50 border border-slate-200 rounded-2xl p-6">
                         <div class="flex justify-between items-center mb-4">
                            <label class="font-bold text-slate-700">Posisi Bed</label>
                        </div>
                        <input type="hidden" name="bed_slot" :value="selectedBed">

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                            <template x-for="i in getCapacity()">
                                <div @click="!isBedTaken(i) ? selectedBed = i : null"
                                     :class="{
                                         'bg-slate-100 border-slate-200 opacity-50 cursor-not-allowed': isBedTaken(i),
                                         'bg-indigo-50 border-indigo-500 ring-2 ring-indigo-200 text-indigo-700': selectedBed == i,
                                         'bg-white border-slate-200 hover:border-indigo-400 hover:shadow-md cursor-pointer text-slate-600': !isBedTaken(i) && selectedBed != i
                                     }"
                                     class="border-2 rounded-xl p-3 flex flex-col items-center justify-center h-20 relative transition-all duration-200">

                                    <svg class="w-6 h-6 mb-1" :class="isBedTaken(i) ? 'text-slate-300' : (selectedBed == i ? 'text-indigo-600' : 'text-slate-300')" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 14a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H9a2 2 0 01-2-2v-4z M7 14V6a2 2 0 012-2h8a2 2 0 012 2v8 M7 14h10 M7 10h10 M7 6h10"></path>
                                    </svg>
                                    <span class="text-xs font-bold" x-text="'Slot ' + i"></span>

                                    <div x-show="selectedBed == i" class="absolute top-1 right-1">
                                        <svg class="w-3 h-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                </div>
                            </template>
                        </div>
                         <div x-show="!selectedBed" class="mt-3 text-xs text-red-500 text-center font-medium">* Pilih slot bed</div>
                    </div>

                    <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-4">
                         <a href="{{ route('residents.index') }}" class="py-3 px-6 bg-white border border-slate-200 text-slate-600 rounded-xl hover:bg-red-400 hover:text-white font-bold transition-colors">Batal</a>
                         <button type="submit" :disabled="!selectedBed" :class="!selectedBed ? 'opacity-50 cursor-not-allowed bg-slate-400' : 'bg-green-500 hover:bg-green-800 shadow-lg hover:shadow-xl'" class="py-3 px-8 text-white rounded-xl font-bold transition-all duration-200">
                             Update Data
                         </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>
