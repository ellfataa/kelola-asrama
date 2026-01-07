<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Kamar') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form action="{{ route('rooms.update', $room) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700">Nomor Kamar</label>
                            <input type="text" name="number" value="{{ old('number', $room->number) }}" class="border-gray-300 rounded-md w-full mt-1" required>
                            @error('number') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block font-medium text-sm text-gray-700">Kapasitas</label>
                                <input type="number" name="capacity" value="{{ old('capacity', $room->capacity) }}" class="border-gray-300 rounded-md w-full mt-1" required min="1">
                                <p class="text-[10px] text-red-500 mt-1">*Jangan kurangi di bawah jumlah penghuni saat ini!</p>
                            </div>
                            <div>
                                <label class="block font-medium text-sm text-gray-700">Harga</label>
                                <input type="number" name="price" value="{{ old('price', $room->price) }}" class="border-gray-300 rounded-md w-full mt-1" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700">Keterangan</label>
                            <textarea name="description" class="border-gray-300 rounded-md w-full mt-1">{{ old('description', $room->description) }}</textarea>
                        </div>

                        <div class="flex justify-end">
                            <a href="{{ route('rooms.index') }}" class="mr-4 py-2 px-4 bg-gray-200 rounded text-gray-700">Batal</a>
                            <button type="submit" class="py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-700">Update</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
