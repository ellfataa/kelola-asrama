<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Registrasi Penghuni Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form action="{{ route('residents.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700">Nama Lengkap</label>
                            <input type="text" name="name" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full mt-1" required>
                            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block font-medium text-sm text-gray-700">NIM / No. KTP</label>
                                <input type="text" name="identity_number" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full mt-1" required>
                                @error('identity_number') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block font-medium text-sm text-gray-700">No. HP (Opsional)</label>
                                <input type="text" name="phone" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full mt-1">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700">Pilih Kamar</label>
                            <select name="room_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full mt-1">
                                <option value="">-- Pilih Kamar --</option>
                                @foreach($rooms as $room)
                                    @php
                                        // Gunakan residents_count dari controller (lebih cepat)
                                        $filled = $room->residents_count;
                                        $sisa = $room->capacity - $filled;

                                        // Logic Status: Penuh jika sisa 0 ATAU Exclusive
                                        $isFull = $sisa <= 0 || $room->is_exclusive;

                                        // Label Text
                                        if ($room->is_exclusive) {
                                            $statusText = 'EXCLUSIVE (FULL)';
                                        } elseif ($sisa <= 0) {
                                            $statusText = 'PENUH';
                                        } else {
                                            $statusText = 'Sisa ' . $sisa . ' slot';
                                        }
                                    @endphp

                                    <option value="{{ $room->id }}" {{ $isFull ? 'disabled' : '' }} class="{{ $isFull ? 'text-gray-400 bg-gray-100' : '' }}">
                                        Kamar {{ $room->number }}
                                        ({{ $statusText }})
                                        - Rp {{ number_format($room->price, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                            @error('room_id') <span class="text-red-500 text-sm font-bold">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700">Tanggal Masuk</label>
                            <input type="date" name="entry_date" value="{{ date('Y-m-d') }}" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full mt-1" required>
                        </div>

                        <div class="flex justify-end mt-6">
                            <a href="{{ route('residents.index') }}" class="mr-4 py-2 px-4 bg-gray-200 rounded text-gray-700">Batal</a>
                            <button type="submit" class="py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan Data</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
