<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Kamar Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- ALPINE.JS LOGIC --}}
                    <div x-data="{
                            selectedLocation: null,
                            selectedLabel: '',
                            takenLocations: {{ json_encode($takenLocations) }},

                            // DEFINISI DENAH KAMAR (Bisa diubah namanya sesuai kebutuhan)
                            roomMap: {{ json_encode($roomMap) }},

                            selectRoom(id, label) {
                                this.selectedLocation = id;
                                this.selectedLabel = label;
                            }
                        }"
                        class="flex flex-col md:flex-row gap-8">

                        <div class="w-full md:w-1/3 bg-gray-900 p-6 rounded-xl shadow-inner flex flex-col items-center">
                            <h3 class="text-white font-bold mb-6 text-center text-lg">Denah Asrama</h3>

                            {{-- Grid Layout --}}
                            <div class="grid grid-cols-2 gap-4">
                                <template x-for="room in roomMap" :key="room.id">
                                    <button
                                        type="button"
                                        {{-- Disable jika sudah ada di DB --}}
                                        :disabled="takenLocations.includes(room.id)"

                                        {{-- Logic Klik --}}
                                        @click="selectRoom(room.id, room.label)"

                                        {{-- Styling Dinamis --}}
                                        :class="{
                                            'bg-blue-600 opacity-60 cursor-not-allowed ring-0': takenLocations.includes(room.id),
                                            'bg-green-500 ring-4 ring-green-300 transform scale-105 shadow-lg': selectedLocation == room.id,
                                            'bg-gray-200 hover:bg-white hover:shadow-md': !takenLocations.includes(room.id) && selectedLocation != room.id
                                        }"
                                        class="w-20 h-20 rounded-lg transition-all duration-200 flex flex-col items-center justify-center border border-gray-400 group relative"
                                    >
                                        {{-- Icon Kasur --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-1" :class="selectedLocation == room.id || takenLocations.includes(room.id) ? 'text-white' : 'text-gray-500'" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                        </svg>

                                        {{-- Label Kamar --}}
                                        <span class="text-xs font-bold" :class="selectedLocation == room.id || takenLocations.includes(room.id) ? 'text-white' : 'text-gray-800'" x-text="room.label"></span>

                                        {{-- Badge Status --}}
                                        <span x-show="takenLocations.includes(room.id)" class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                                    </button>
                                </template>
                            </div>

                            <div class="mt-6 text-xs text-gray-400 flex flex-col gap-2 w-full px-4">
                                <div class="flex items-center gap-2"><div class="w-3 h-3 bg-gray-200 rounded"></div> Kosong</div>
                                <div class="flex items-center gap-2"><div class="w-3 h-3 bg-green-500 rounded"></div> Pilihan Anda</div>
                                <div class="flex items-center gap-2"><div class="w-3 h-3 bg-blue-600 rounded"></div> Terisi</div>
                            </div>
                        </div>

                        <div class="w-full md:w-2/3">
                            <form action="{{ route('rooms.store') }}" method="POST">
                                @csrf

                                {{-- Input Hidden untuk Code 1-10 --}}
                                <input type="hidden" name="location_code" :value="selectedLocation">

                                <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                    <h4 class="font-bold text-blue-800 mb-2">Instruksi:</h4>
                                    <p class="text-sm text-blue-600">Klik salah satu kotak pada denah di sebelah kiri untuk mengisi Nomor Kamar secara otomatis.</p>
                                </div>

                                {{-- INPUT NOMOR KAMAR (READONLY) --}}
                                <div class="mb-4">
                                    <label class="block font-medium text-sm text-gray-700">Nomor Kamar</label>

                                    {{-- x-model mengikat input ke variable selectedLabel --}}
                                    <input type="text" name="number" x-model="selectedLabel"
                                           readonly
                                           class="bg-gray-100 cursor-not-allowed border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full mt-1 font-bold text-lg text-gray-600"
                                           placeholder="Pilih lokasi di denah..." required>

                                    @error('number') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    @error('location_code') <span class="text-red-500 text-sm block mt-1">Wajib memilih lokasi pada denah!</span> @enderror
                                </div>

                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label class="block font-medium text-sm text-gray-700">Kapasitas (Orang)</label>
                                        <input type="number" name="capacity" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full mt-1" required min="1">
                                    </div>
                                    <div>
                                        <label class="block font-medium text-sm text-gray-700">Harga (Rp)/per orang</label>
                                        <input type="number" name="price" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full mt-1" required min="0">
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="block font-medium text-sm text-gray-700">Keterangan (Opsional)</label>
                                    <textarea name="description" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full mt-1"></textarea>
                                </div>

                                <div class="flex justify-end mt-8">
                                    <div class="block mt-4">
                                        <label for="is_exclusive" class="inline-flex items-center">
                                            <input id="is_exclusive" type="checkbox" name="is_exclusive" value="1"
                                                {{ old('is_exclusive', isset($room) ? $room->is_exclusive : false) ? 'checked' : '' }}
                                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                            <span class="ms-2 text-sm text-gray-600 font-bold">Booking Satu Kamar (Exclusive)</span>
                                        </label>
                                        <p class="text-xs text-gray-500 mt-1 ml-6">Jika dicentang, kamar dianggap PENUH berapapun jumlah penghuninya.</p>
                                    </div>

                                    <a href="{{ route('rooms.index') }}" class="mr-4 py-2 px-4 bg-gray-200 rounded text-gray-700">Batal</a>

                                    {{-- Tombol mati jika belum pilih lokasi --}}
                                    <button type="submit" :disabled="!selectedLocation"
                                            :class="!selectedLocation ? 'opacity-50 cursor-not-allowed bg-gray-400' : 'bg-blue-600 hover:bg-blue-700'"
                                            class="py-2 px-4 text-white rounded transition-colors">
                                        Simpan Kamar
                                    </button>
                                </div>
                            </form>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
