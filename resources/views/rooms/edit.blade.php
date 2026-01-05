<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Kamar') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- ALPINE.JS LOGIC --}}
                    <div x-data="{
                            // PRE-FILL DATA SAAT INI
                            selectedLocation: {{ $room->location_code }},
                            selectedLabel: '{{ $room->number }}',

                            // Data 'taken' dari controller
                            takenLocations: {{ json_encode($takenLocations) }},

                            // Kita butuh ID kamar ini untuk pengecualian logika 'disabled'
                            currentRoomLocation: {{ $room->location_code }},

                            // MAPPING DENAH (Harus sama persis dengan create)
                            roomMap: {{ json_encode($roomMap) }},

                            selectRoom(id, label) {
                                this.selectedLocation = id;
                                this.selectedLabel = label;
                            },

                            // Helper function: Cek apakah kotak harus dimatikan (disabled)
                            isTaken(id) {
                                // Kotak diambil JIKA ada di list taken DAN bukan lokasi kamar ini sendiri
                                return this.takenLocations.includes(id) && id !== this.currentRoomLocation;
                            }
                        }"
                        class="flex flex-col md:flex-row gap-8">

                        <div class="w-full md:w-1/3 bg-gray-900 p-6 rounded-xl shadow-inner flex flex-col items-center">
                            <h3 class="text-white font-bold mb-6 text-center text-lg">Denah Asrama</h3>

                            <div class="grid grid-cols-2 gap-4">
                                <template x-for="room in roomMap" :key="room.id">
                                    <button
                                        type="button"
                                        {{-- Gunakan helper function isTaken --}}
                                        :disabled="isTaken(room.id)"
                                        @click="selectRoom(room.id, room.label)"

                                        :class="{
                                            'bg-blue-600 opacity-60 cursor-not-allowed ring-0': isTaken(room.id),
                                            'bg-green-500 ring-4 ring-green-300 transform scale-105 shadow-lg': selectedLocation == room.id,
                                            'bg-gray-200 hover:bg-white hover:shadow-md': !isTaken(room.id) && selectedLocation != room.id
                                        }"
                                        class="w-20 h-20 rounded-lg transition-all duration-200 flex flex-col items-center justify-center border border-gray-400 group relative"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-1" :class="selectedLocation == room.id || isTaken(room.id) ? 'text-white' : 'text-gray-500'" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                        </svg>

                                        <span class="text-xs font-bold" :class="selectedLocation == room.id || isTaken(room.id) ? 'text-white' : 'text-gray-800'" x-text="room.label"></span>
                                    </button>
                                </template>
                            </div>

                            <div class="mt-6 text-xs text-gray-400 flex flex-col gap-2 w-full px-4">
                                <div class="flex items-center gap-2"><div class="w-3 h-3 bg-gray-200 rounded"></div> Kosong/Pindah Sini</div>
                                <div class="flex items-center gap-2"><div class="w-3 h-3 bg-green-500 rounded"></div> Lokasi Saat Ini</div>
                                <div class="flex items-center gap-2"><div class="w-3 h-3 bg-blue-600 rounded"></div> Kamar Lain (Terisi)</div>
                            </div>
                        </div>

                        <div class="w-full md:w-2/3">
                            <form action="{{ route('rooms.update', $room) }}" method="POST">
                                @csrf
                                @method('PUT')

                                {{-- Input Hidden Location Code --}}
                                <input type="hidden" name="location_code" :value="selectedLocation">

                                <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                    <h4 class="font-bold text-yellow-800 mb-2">Mode Edit:</h4>
                                    <p class="text-sm text-yellow-700">Lokasi kamar saat ini ditandai dengan warna <span class="font-bold text-green-600">Hijau</span>. Klik kotak abu-abu jika ingin memindahkan lokasi kamar.</p>
                                </div>

                                {{-- NOMOR KAMAR (READONLY) --}}
                                <div class="mb-4">
                                    <label class="block font-medium text-sm text-gray-700">Nomor Kamar</label>
                                    <input type="text" name="number" x-model="selectedLabel"
                                           readonly
                                           class="bg-gray-100 cursor-not-allowed border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full mt-1 font-bold text-lg text-gray-600"
                                           required>
                                    @error('number') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label class="block font-medium text-sm text-gray-700">Kapasitas</label>
                                        <input type="number" name="capacity" value="{{ old('capacity', $room->capacity) }}" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full mt-1" required>
                                    </div>
                                    <div>
                                        <label class="block font-medium text-sm text-gray-700">Harga</label>
                                        <input type="number" name="price" value="{{ old('price', $room->price) }}" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full mt-1" required>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="block font-medium text-sm text-gray-700">Keterangan</label>
                                    <textarea name="description" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full mt-1">{{ old('description', $room->description) }}</textarea>
                                </div>

                                <div class="flex justify-end mt-8">
                                    <a href="{{ route('rooms.index') }}" class="mr-4 py-2 px-4 bg-gray-200 rounded text-gray-700">Batal</a>
                                    <button type="submit" class="py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                                        Update Perubahan
                                    </button>
                                </div>
                            </form>
                        </div>

                    </div>
                    {{-- END ALPINE --}}

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
