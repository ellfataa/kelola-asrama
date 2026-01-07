<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Denah & Daftar Kamar') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-gray-800">Visualisasi Area Asrama</h3>
                    <a href="{{ route('rooms.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 text-sm font-bold shadow">
                        + Tambah Denah Kamar
                    </a>
                </div>

                {{-- GRID KAMAR (Looping langsung dari Database) --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @forelse($rooms as $room)
                        <div class="border rounded-xl p-4 hover:shadow-lg transition bg-white relative group">

                            {{-- Header Kamar --}}
                            <div class="flex justify-between items-center mb-3 pb-2 border-b">
                                <span class="font-bold text-lg text-gray-800">{{ $room->number }}</span>
                                <span class="text-xs font-mono bg-gray-100 px-2 py-1 rounded">Rp {{ number_format($room->price/1000, 0) }}k</span>
                            </div>

                            {{-- VISUALISASI BED DI DALAM KAMAR --}}
                            <div class="grid grid-cols-2 gap-2 mb-2">
                                @for($i = 1; $i <= $room->capacity; $i++)
                                    @php
                                        // Cek apakah bed ini terisi
                                        // Kita pakai helper model yang sudah dibuat: isBedTaken($i)
                                        // Tapi karena $room diloop di view, kita perlu akses relasi residents
                                        // Cara efisien: cek collection residents
                                        $isTaken = $room->residents->contains('bed_slot', $i);
                                    @endphp

                                    <div class="h-8 rounded text-[10px] flex items-center justify-center font-bold border {{ $isTaken ? 'bg-red-500 border-red-600 text-white' : 'bg-green-100 border-green-300 text-green-700' }}"
                                         title="Bed {{ $i }}: {{ $isTaken ? 'Terisi' : 'Kosong' }}">
                                        {{ $i }}
                                    </div>
                                @endfor
                            </div>

                            {{-- Info Sisa --}}
                            <div class="text-center text-xs text-gray-500 mt-2">
                                {{ $room->residents_count }} / {{ $room->capacity }} Bed Terisi
                            </div>

                            {{-- Action Buttons (Muncul saat hover) --}}
                            <div class="absolute inset-0 bg-white/90 flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity rounded-xl">
                                <a href="{{ route('rooms.edit', $room) }}" class="px-3 py-1 bg-yellow-500 text-white rounded text-sm hover:bg-yellow-600">Edit</a>
                                <form action="{{ route('rooms.destroy', $room) }}" method="POST" onsubmit="return confirm('Hapus kamar ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded text-sm hover:bg-red-700">Hapus</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-10 text-gray-400 border-2 border-dashed rounded-xl">
                            Belum ada kamar yang dibuat. Klik tombol Tambah Denah Kamar.
                        </div>
                    @endforelse
                </div>

                <div class="mt-6">
                    {{ $rooms->links() }}
                </div>
            </div>

            {{-- Anda bisa membiarkan tabel detail di bawah jika ingin view list --}}
            {{-- Kode tabel sama seperti sebelumnya, hanya hapus bagian logic location_code --}}

        </div>
    </div>
</x-app-layout>
