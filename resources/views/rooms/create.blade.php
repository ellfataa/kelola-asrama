<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Kamar & Setup Bed') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- ALPINE JS untuk Preview Bed --}}
                    <div x-data="{
                            capacity: 4, // Default 4
                            roomNumber: '',
                            price: ''
                        }"
                        class="flex flex-col md:flex-row gap-8">

                        <div class="w-full md:w-1/2">
                            <form action="{{ route('rooms.store') }}" method="POST">
                                @csrf

                                <div class="mb-4">
                                    <label class="block font-medium text-sm text-gray-700">Nomor / Nama Kamar</label>
                                    <input type="text" name="number" x-model="roomNumber"
                                           class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full mt-1"
                                           placeholder="Contoh: A-101, Mawar-1" required>
                                    @error('number') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label class="block font-medium text-sm text-gray-700">Jumlah Bed (Kapasitas)</label>
                                        {{-- x-model terhubung ke preview di kanan --}}
                                        <input type="number" name="capacity" x-model="capacity" min="1" max="20"
                                               class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full mt-1" required>
                                    </div>
                                    <div>
                                        <label class="block font-medium text-sm text-gray-700">Harga per Bed (Rp)</label>
                                        <input type="number" name="price" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full mt-1" required>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="block font-medium text-sm text-gray-700">Fasilitas / Keterangan</label>
                                    <textarea name="description" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full mt-1" placeholder="AC, Lemari, WiFi..."></textarea>
                                </div>

                                <div class="flex justify-end mt-6">
                                    <a href="{{ route('rooms.index') }}" class="mr-4 py-2 px-4 bg-gray-200 rounded text-gray-700">Batal</a>
                                    <button type="submit" class="py-2 px-6 bg-blue-600 text-white rounded hover:bg-blue-700 font-bold">Simpan Kamar</button>
                                </div>
                            </form>
                        </div>

                        <div class="w-full md:w-1/2 bg-gray-50 border-2 border-dashed border-gray-300 rounded-xl p-6 flex flex-col items-center justify-center min-h-[300px]">
                            <h3 class="text-gray-500 font-bold mb-4 uppercase tracking-wider text-sm">Preview Denah Kamar</h3>

                            {{-- Judul Kamar Preview --}}
                            <div class="mb-4 px-4 py-2 bg-white shadow-sm border rounded text-lg font-bold text-gray-800" x-text="roomNumber || 'Nama Kamar'"></div>

                            {{-- Grid Bed Preview --}}
                            <div class="grid grid-cols-2 gap-3" :class="capacity > 6 ? 'grid-cols-3' : 'grid-cols-2'">
                                <template x-for="i in parseInt(capacity)">
                                    <div class="w-16 h-20 bg-white border border-green-400 rounded-lg flex flex-col items-center justify-center shadow-sm relative">
                                        <div class="w-8 h-4 bg-green-100 rounded-sm mb-2 border border-green-200"></div>
                                        <div class="w-10 h-8 bg-green-50 border border-green-100 rounded-sm"></div>

                                        <span class="absolute bottom-1 right-1 text-[10px] text-gray-500 font-bold" x-text="i"></span>
                                    </div>
                                </template>
                            </div>

                            <p class="text-xs text-gray-400 mt-4 text-center">
                                Sistem akan otomatis membuat <span x-text="capacity"></span> slot tempat tidur untuk kamar ini.
                            </p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
