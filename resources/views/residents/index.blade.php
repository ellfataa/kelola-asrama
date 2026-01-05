<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Penghuni Asrama') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-4 flex justify-between items-center">
                <a href="{{ route('residents.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    + Daftar Penghuni Baru
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
                                <th class="p-3">Nama Lengkap</th>
                                <th class="p-3">NIM/KTP</th>
                                <th class="p-3">Kamar</th>
                                <th class="p-3">Tgl Masuk</th>
                                <th class="p-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($residents as $resident)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-3 font-bold">{{ $resident->name }}</td>
                                <td class="p-3">{{ $resident->identity_number }}</td>
                                <td class="p-3">
                                    <span class="bg-indigo-100 text-indigo-800 py-1 px-2 rounded text-sm font-semibold">
                                        {{ $resident->room->number }}
                                    </span>
                                </td>
                                <td class="p-3">{{ $resident->entry_date->format('d M Y') }}</td>
                                <td class="p-3 text-center space-x-2">
                                    <a href="{{ route('residents.edit', $resident) }}" class="text-yellow-600 hover:underline">Edit</a>

                                    <form action="{{ route('residents.destroy', $resident) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data penghuni ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="p-6 text-center text-gray-500">Belum ada penghuni.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $residents->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
