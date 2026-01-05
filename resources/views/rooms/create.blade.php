<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Kamar Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form action="{{ route('rooms.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700">Nomor Kamar</label>
                            <input type="text" name="number" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full mt-1" required placeholder="Contoh: A-101">
                            @error('number') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block font-medium text-sm text-gray-700">Kapasitas (Orang)</label>
                                <input type="number" name="capacity" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full mt-1" required min="1">
                            </div>
                            <div>
                                <label class="block font-medium text-sm text-gray-700">Harga (Rp)</label>
                                <input type="number" name="price" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full mt-1" required min="0">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700">Keterangan (Opsional)</label>
                            <textarea name="description" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full mt-1"></textarea>
                        </div>

                        <div class="flex justify-end">
                            <a href="{{ route('rooms.index') }}" class="mr-4 py-2 px-4 bg-gray-200 rounded text-gray-700">Batal</a>
                            <button type="submit" class="py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
