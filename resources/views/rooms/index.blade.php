<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Kamar Asrama') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-4 flex justify-between items-center">
                <a href="{{ route('rooms.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    + Tambah Kamar
                </a>
                @if(session('success'))
                    <div class="text-green-600 font-bold">{{ session('success') }}</div>
                @endif
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b bg-gray-100">
                                <th class="p-3">No. Kamar</th>
                                <th class="p-3">Kapasitas</th>
                                <th class="p-3">Harga</th>
                                <th class="p-3">Ket</th>
                                <th class="p-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rooms as $room)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-3 font-bold">{{ $room->number }}</td>
                                <td class="p-3">{{ $room->capacity }} Orang</td>
                                <td class="p-3">Rp {{ number_format($room->price, 0, ',', '.') }}</td>
                                <td class="p-3 text-sm text-gray-600">{{ $room->description ?? '-' }}</td>
                                <td class="p-3 text-center space-x-2">
                                    <a href="{{ route('rooms.edit', $room) }}" class="text-yellow-600 hover:underline">Edit</a>

                                    <form action="{{ route('rooms.destroy', $room) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus kamar ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="p-6 text-center text-gray-500">Belum ada data kamar.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $rooms->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
