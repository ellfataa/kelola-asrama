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

                    <form action="{{ route('residents.update', $resident) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', $resident->name) }}" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full mt-1" required>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block font-medium text-sm text-gray-700">NIM / No. KTP</label>
                                <input type="text" name="identity_number" value="{{ old('identity_number', $resident->identity_number) }}" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full mt-1" required>
                            </div>
                            <div>
                                <label class="block font-medium text-sm text-gray-700">No. HP</label>
                                <input type="text" name="phone" value="{{ old('phone', $resident->phone) }}" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full mt-1">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700">Pindah Kamar</label>
                            <select name="room_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full mt-1">
                                @foreach($rooms as $room)
                                    @php
                                        $filled = $room->residents_count;
                                        $sisa = $room->capacity - $filled;
                                        $isMyRoom = $resident->room_id == $room->id;

                                        // Kamar disable jika Penuh/Exclusive KECUALI kamar sendiri
                                        $isFull = ($sisa <= 0 || $room->is_exclusive) && !$isMyRoom;

                                        // Label Text Logic
                                        if ($isMyRoom) {
                                            $statusText = 'Saat ini';
                                        } elseif ($room->is_exclusive) {
                                            $statusText = 'EXCLUSIVE (FULL)';
                                        } elseif ($sisa <= 0) {
                                            $statusText = 'PENUH';
                                        } else {
                                            $statusText = 'Sisa ' . $sisa . ' slot';
                                        }
                                    @endphp

                                    <option value="{{ $room->id }}"
                                        {{ $isMyRoom ? 'selected' : '' }}
                                        {{ $isFull ? 'disabled' : '' }}
                                        class="{{ $isFull ? 'text-gray-400 bg-gray-100' : '' }}">
                                        Kamar {{ $room->number }}
                                        ({{ $statusText }})
                                    </option>
                                @endforeach
                            </select>
                            @error('room_id') <span class="text-red-500 text-sm font-bold">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700">Tanggal Masuk</label>
                            <input type="date" name="entry_date" value="{{ old('entry_date', $resident->entry_date->format('Y-m-d')) }}" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full mt-1" required>
                        </div>

                        <div class="flex justify-end mt-6">
                            <a href="{{ route('residents.index') }}" class="mr-4 py-2 px-4 bg-gray-200 rounded text-gray-700">Batal</a>
                            <button type="submit" class="py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-700">Update Data</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
