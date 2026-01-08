<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 leading-tight flex items-center gap-2">
            <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            {{ __('Edit Kamar') }}
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-slate-100">
            <div class="p-8">

                <div class="flex items-center justify-between mb-6 border-b border-slate-100 pb-4">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Update Informasi Kamar</h3>
                        <p class="text-sm text-slate-500">Ubah data kapasitas atau harga.</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-lg">
                        {{ substr($room->number, 0, 1) }}
                    </div>
                </div>

                <form action="{{ route('rooms.update', $room) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block font-bold text-xs text-slate-500 uppercase tracking-wider mb-2">Nomor Kamar</label>
                        <input type="text" name="number" value="{{ old('number', $room->number) }}"
                               class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-3 transition-all duration-200" required>
                        @error('number') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    {{-- Warning Box for Capacity --}}
                    <div class="bg-amber-50 border-l-4 border-amber-400 p-4 rounded-r-xl">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-amber-700">
                                    Hati-hati saat mengurangi kapasitas! Pastikan jumlah bed baru tidak kurang dari jumlah penghuni saat ini.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-5">
                        <div>
                            <label class="block font-bold text-xs text-slate-500 uppercase tracking-wider mb-2">Kapasitas</label>
                            <input type="number" name="capacity" value="{{ old('capacity', $room->capacity) }}"
                                   class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-3 transition-all duration-200 font-bold" required min="1">
                        </div>
                        <div>
                            <label class="block font-bold text-xs text-slate-500 uppercase tracking-wider mb-2">Harga</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">Rp</span>
                                </div>
                                <input type="number" name="price" value="{{ old('price', $room->price) }}"
                                       class="block w-full rounded-xl border-slate-200 bg-slate-50 pl-10 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-3 transition-all duration-200" required>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-xs text-slate-500 uppercase tracking-wider mb-2">Keterangan</label>
                        <textarea name="description" rows="3"
                                  class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-3 transition-all duration-200">{{ old('description', $room->description) }}</textarea>
                    </div>

                    <div class="flex justify-end gap-4 pt-4 border-t border-slate-100">
                        <a href="{{ route('rooms.index') }}" class="py-3 px-6 bg-white border border-slate-200 text-slate-600 rounded-xl hover:bg-red-400 hover:text-white font-bold transition-colors">Batal</a>
                        <button type="submit" class="py-3 px-6 bg-green-500 text-white rounded-xl hover:bg-green-800 font-bold shadow-lg hover:shadow-xl transition-all duration-200">Simpan Perubahan</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
