<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Data Penghuni') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Format Data untuk JS --}}
                    @php
                        $roomsData = [];
                        foreach($rooms as $r) {
                            $roomsData[$r->id] = [
                                'capacity' => $r->capacity,
                                'taken_beds' => $r->residents->where('id', '!=', $resident->id)->pluck('bed_slot')->toArray(), // Kecualikan diri sendiri agar bed saat ini tidak dianggap taken
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
                          }">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', $resident->name) }}" class="border-gray-300 rounded-md w-full mt-1" required>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block font-medium text-sm text-gray-700">NIM / KTP</label>
                                <input type="text" name="identity_number" value="{{ old('identity_number', $resident->identity_number) }}" class="border-gray-300 rounded-md w-full mt-1" required>
                            </div>
                            <div>
                                <label class="block font-medium text-sm text-gray-700">No. HP</label>
                                <input type="text" name="phone" value="{{ old('phone', $resident->phone) }}" class="border-gray-300 rounded-md w-full mt-1">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700">Pindah Kamar</label>
                            <select name="room_id" x-model="selectedRoom" @change="selectedBed = null" class="border-gray-300 rounded-md w-full mt-1">
                                @foreach($rooms as $room)
                                    @php
                                        // Hitung sisa, tapi jika ini kamar sendiri, tambahkan 1 ke sisa (karena diri sendiri akan pindah/stay)
                                        $isMyRoom = $resident->room_id == $room->id;
                                        $filled = $room->residents->count();
                                        $sisa = $room->capacity - $filled + ($isMyRoom ? 1 : 0);
                                    @endphp
                                    <option value="{{ $room->id }}" {{ $sisa <= 0 ? 'disabled' : '' }} class="{{ $sisa <= 0 ? 'bg-gray-100 text-gray-400' : '' }}">
                                        Kamar {{ $room->number }} {{ $isMyRoom ? '(Saat Ini)' : '(Sisa ' . $sisa . ')' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div x-show="selectedRoom" class="mb-6 p-4 border border-blue-200 bg-blue-50 rounded-lg" x-transition>
                            <label class="block font-bold text-sm text-blue-800 mb-3 text-center">Pilih Posisi Bed:</label>
                            <input type="hidden" name="bed_slot" :value="selectedBed">

                            <div class="grid grid-cols-2 gap-3 justify-center">
                                <template x-for="i in getCapacity()">
                                    <div
                                        @click="!isBedTaken(i) ? selectedBed = i : null"
                                        :class="{
                                            'bg-red-100 border-red-300 opacity-60 cursor-not-allowed': isBedTaken(i),
                                            'bg-green-100 border-green-500 ring-2 ring-green-300 scale-105': selectedBed == i,
                                            'bg-white border-gray-300 hover:border-blue-400 cursor-pointer': !isBedTaken(i) && selectedBed != i
                                        }"
                                        class="border-2 rounded-lg p-3 flex flex-col items-center justify-center h-20 relative select-none transition-all"
                                    >
                                        <svg class="w-6 h-6 mb-1" :class="isBedTaken(i) ? 'text-red-400' : (selectedBed == i ? 'text-green-600' : 'text-gray-400')" fill="currentColor" viewBox="0 0 20 20"><path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"></path></svg>
                                        <span class="text-xs font-bold" x-text="'Bed ' + i"></span>
                                        <span x-show="isBedTaken(i)" class="absolute top-1 right-1 text-[9px] bg-red-200 text-red-800 px-1 rounded font-bold">ISI</span>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div class="flex justify-between items-end border-t pt-4 mt-4">
                            <div class="w-1/2">
                                <label class="block font-medium text-sm text-gray-700">Tanggal Masuk</label>
                                <input type="date" name="entry_date" value="{{ old('entry_date', $resident->entry_date->format('Y-m-d')) }}" class="border-gray-300 rounded-md w-full mt-1" required>
                            </div>

                            <div class="flex gap-3">
                                <a href="{{ route('residents.index') }}" class="py-2 px-4 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 h-10 flex items-center">Batal</a>
                                <button type="submit" class="py-2 px-6 bg-blue-600 text-white rounded font-bold h-10">Update</button>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
