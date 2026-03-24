<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('rooms.index') }}" class="p-2 text-slate-400 hover:text-indigo-600 bg-white rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="font-bold text-xl text-slate-800 leading-tight">
                {{ __('Tambah Data Kamar') }}
            </h2>
        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto pb-12 px-4 sm:px-6 lg:px-8">
        <div x-data="{
                capacity: 4,
                roomNumber: '',
                price: ''
            }"
            class="flex flex-col lg:flex-row gap-8 lg:gap-10 items-start">

            {{-- LEFT: FORM INPUT --}}
            <div class="w-full lg:w-7/12 bg-white overflow-hidden shadow-sm rounded-3xl border border-slate-100">
                <div class="p-6 sm:p-8">

                    <div class="border-b border-slate-100 pb-5 mb-6">
                        <h3 class="text-lg font-extrabold text-slate-900 tracking-tight">Detail Informasi Kamar</h3>
                        <p class="text-sm text-slate-500 font-medium mt-1">Lengkapi data dan kapasitas untuk kamar baru.</p>
                    </div>

                    <form action="{{ route('rooms.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <div>
                            <label class="block font-bold text-xs text-slate-500 uppercase tracking-wider mb-2">Nomor/Nama Kamar</label>
                            <input type="text" name="number" x-model="roomNumber"
                                class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 py-3 px-4 transition-all duration-200 text-slate-900 font-semibold"
                                placeholder="Contoh: A-101" required>
                            @error('number') <span class="text-rose-500 text-xs font-medium mt-1.5 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="block font-bold text-xs text-slate-500 uppercase tracking-wider mb-2">Kapasitas (Bed)</label>
                                <div class="relative">
                                    <input type="number" name="capacity" x-model="capacity" min="1" max="20"
                                        class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 py-3 pl-4 pr-12 transition-all duration-200 font-extrabold text-slate-900 text-lg" required>
                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block font-bold text-xs text-slate-500 uppercase tracking-wider mb-2">Harga per Semester</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <span class="text-slate-400 font-bold text-sm">Rp</span>
                                    </div>
                                    <input type="number" name="price"
                                        class="block w-full rounded-xl border-slate-200 bg-slate-50 pl-12 pr-4 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 py-3 transition-all duration-200 font-bold text-slate-900" required placeholder="0">
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block font-bold text-xs text-slate-500 uppercase tracking-wider mb-2">Fasilitas/Keterangan Tambahan</label>
                            <textarea name="description" rows="3"
                                class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 py-3 px-4 transition-all duration-200 text-sm"
                                placeholder="Misal: AC, Kamar Mandi Dalam, WiFi..."></textarea>
                        </div>

                        <div class="pt-4 flex flex-col sm:flex-row items-center gap-3 border-t border-slate-100">
                            <button type="submit" class="w-full sm:w-auto flex-1 py-3.5 px-6 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 font-bold shadow-lg hover:shadow-indigo-500/30 transition-all duration-200 transform hover:-translate-y-0.5 focus:ring-4 focus:ring-indigo-500/30">
                                Simpan Data Kamar
                            </button>
                            <a href="{{ route('rooms.index') }}" class="w-full sm:w-auto text-center py-3.5 px-6 bg-red-500 border border-white text-white rounded-xl hover:bg-red-600 font-bold transition-colors">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- RIGHT: PREVIEW STICKY --}}
            <div class="w-full lg:w-5/12 lg:sticky lg:top-28">
                <div class="bg-slate-50 border border-slate-200 rounded-3xl p-6 relative overflow-hidden min-h-[380px] flex flex-col shadow-sm">
                    <div class="absolute inset-0 opacity-[0.04] pointer-events-none" style="background-image: radial-gradient(#0f172a 1px, transparent 1px); background-size: 24px 24px;"></div>

                    <div class="relative z-10 flex flex-col h-full w-full max-w-sm mx-auto">
                        <div class="flex items-center justify-center gap-2 mb-6">
                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            <h3 class="text-slate-500 font-bold uppercase tracking-widest text-[10px]">Preview Tampilan Kamar</h3>
                        </div>

                        <div class="flex-1 flex flex-col">
                            {{-- Card Simulasi --}}
                            <div class="bg-white w-full rounded-2xl shadow-xl shadow-indigo-100/50 border border-slate-100 overflow-hidden transform transition-all duration-300">

                                {{-- Header Card Simulasi --}}
                                <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
                                    <div class="w-10 h-10 shrink-0 rounded-xl bg-indigo-600 flex items-center justify-center text-white font-extrabold text-lg shadow-sm">
                                        <span x-text="roomNumber ? roomNumber.charAt(0).toUpperCase() : '?'"></span>
                                    </div>
                                    <div class="flex flex-col truncate">
                                        <span class="font-extrabold text-lg text-slate-900 leading-tight truncate" x-text="roomNumber || 'Nomor/Nama Kamar'"></span>
                                        <div class="flex items-center gap-1 mt-0.5 text-emerald-500">
                                            <span class="text-[10px] font-bold tracking-wider">Rp xxx.xxx</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Body Card Simulasi (Bed) --}}
                                <div class="p-5">
                                    <div class="grid gap-2.5" :class="capacity > 6 ? 'grid-cols-3' : 'grid-cols-2'">
                                        <template x-for="i in parseInt(capacity || 0)">
                                            <div class="h-12 rounded-xl bg-emerald-50 border-2 border-emerald-100 flex flex-col items-center justify-center relative">
                                                <svg class="w-4 h-4 mb-0.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 14a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H9a2 2 0 01-2-2v-4z M7 14V6a2 2 0 012-2h8a2 2 0 012 2v8 M7 14h10 M7 10h10 M7 6h10"></path>
                                                </svg>
                                                <span class="absolute top-1 right-1.5 text-[8px] font-bold text-emerald-500" x-text="'#'+i"></span>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                {{-- Footer Card Simulasi --}}
                                <div class="px-5 py-2.5 bg-slate-50 border-t border-slate-100 flex items-center justify-between text-xs">
                                    <div class="flex items-center gap-1.5">
                                        <span class="relative flex h-2 w-2">
                                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                          <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                        </span>
                                        <span class="font-bold text-slate-600">Kamar Terisi</span>
                                    </div>
                                    <span class="text-[10px] font-extrabold bg-slate-200 text-slate-600 px-2 py-0.5 rounded-md">
                                        0/<span x-text="capacity"></span> Bed
                                    </span>
                                </div>
                            </div>

                            <p class="text-[11px] text-red-400 mt-5 text-center font-medium">
                                *Preview otomatis menyesuaikan nama dan jumlah bed yang Anda atur.
                            </p>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
