<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Kamar Asrama') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-800">Denah & Status Kamar</h3>

                    {{-- Legend / Keterangan Warna --}}
                    <div class="flex gap-4 text-xs">
                        <div class="flex items-center gap-1"><div class="w-3 h-3 bg-gray-200 rounded"></div> Slot Kosong</div>
                        <div class="flex items-center gap-1"><div class="w-3 h-3 bg-green-500 rounded"></div> Tersedia</div>
                        <div class="flex items-center gap-1"><div class="w-3 h-3 bg-red-500 rounded"></div> Penuh</div>
                    </div>
                </div>

                {{-- GRID LAYOUT (Responsive: 2 kolom di HP, 5 kolom di Desktop) --}}
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                    @foreach($roomMap as $map)
                        @php
                            // Cek apakah ada kamar di lokasi ini
                            $roomData = $mappedRooms->get($map['id']);

                            // Logic Warna Status
                            $bgColor = 'bg-gray-100 border-gray-200'; // Default Kosong
                            $textColor = 'text-gray-400';

                            if ($roomData) {
                                $isFull = $roomData->residents_count >= $roomData->capacity;
                                $bgColor = $isFull ? 'bg-red-50 border-red-200' : 'bg-green-50 border-green-200';
                                $borderColor = $isFull ? 'border-red-500' : 'border-green-500';
                                $textColor = 'text-gray-800';
                            }
                        @endphp

                        <div class="relative border-2 rounded-xl p-3 flex flex-col items-center justify-center transition hover:shadow-md {{ $roomData ? $borderColor : 'border-dashed border-gray-300' }} {{ $bgColor }}" style="min-height: 100px;">

                            @if($roomData)
                                {{-- JIKA KAMAR SUDAH DIBUAT --}}
                                <div class="font-bold text-lg {{ $textColor }}">{{ $roomData->number }}</div>

                                {{-- Info Penghuni --}}
                                <div class="text-xs font-semibold mt-1 px-2 py-1 rounded-full {{ $roomData->residents_count >= $roomData->capacity ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                                    {{ $roomData->residents_count }} / {{ $roomData->capacity }} Orang
                                </div>

                                {{-- Link Edit Cepat --}}
                                <a href="{{ route('rooms.edit', $roomData) }}" class="absolute inset-0 z-10" title="Klik untuk edit"></a>

                            @else
                                {{-- JIKA SLOT KOSONG --}}
                                <div class="text-xs font-bold text-gray-300 mb-1">{{ $map['label'] }}</div>
                                <span class="text-xs text-gray-400 italic">(Belum Setup)</span>

                                {{-- Link Create Cepat (Opsional: Nanti bisa diarahkan ke create auto select) --}}
                                <a href="{{ route('rooms.create') }}" class="absolute inset-0 z-10" title="Klik untuk tambah"></a>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 overflow-x-auto">

                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-800">Tabel Rincian</h3>
                        <a href="{{ route('rooms.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 text-sm">
                            + Tambah Kamar Manual
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif

                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b bg-gray-50">
                                <th class="p-3 text-sm font-semibold text-gray-600">Lokasi</th>
                                <th class="p-3 text-sm font-semibold text-gray-600">No. Kamar</th>
                                <th class="p-3 text-sm font-semibold text-gray-600">Hunian</th>
                                <th class="p-3 text-sm font-semibold text-gray-600">Harga</th>
                                <th class="p-3 text-sm font-semibold text-gray-600 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rooms as $room)
                            <tr class="border-b hover:bg-gray-50 transition">
                                <td class="p-3 text-gray-500 text-sm">
                                    {{-- Mencari Label berdasarkan ID Mapping --}}
                                    @php
                                        $label = collect($roomMap)->firstWhere('id', $room->location_code)['label'] ?? 'Unknown';
                                    @endphp
                                    <span class="bg-gray-200 px-2 py-1 rounded text-xs font-bold">{{ $label }}</span>
                                </td>
                                <td class="p-3 font-bold text-gray-800">{{ $room->number }}</td>
                                <td class="p-3">
                                    {{-- Progress Bar Mini --}}
                                    <div class="flex items-center gap-2">
                                        <div class="w-24 bg-gray-200 rounded-full h-2.5">
                                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ ($room->residents_count / $room->capacity) * 100 }}%"></div>
                                        </div>
                                        <span class="text-xs text-gray-600">{{ $room->residents_count }}/{{ $room->capacity }}</span>
                                    </div>
                                </td>
                                <td class="p-3 font-mono text-sm">Rp {{ number_format($room->price, 0, ',', '.') }}</td>
                                <td class="p-3 text-center space-x-2">
                                    <a href="{{ route('rooms.edit', $room) }}" class="text-yellow-600 hover:text-yellow-800 text-sm font-semibold">Edit</a>
                                    <form action="{{ route('rooms.destroy', $room) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus kamar ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="p-6 text-center text-gray-500 italic">Belum ada data kamar yang diinput.</td>
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
