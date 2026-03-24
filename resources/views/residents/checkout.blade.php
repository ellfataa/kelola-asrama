<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('residents.index') }}" class="p-2 text-slate-400 hover:text-rose-600 bg-white rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="font-bold text-xl text-slate-800 leading-tight">
                {{ __('Proses Keluar Asrama') }}
            </h2>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto pb-12 px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm rounded-3xl border border-slate-200">
            <div class="p-6 sm:p-10">

                @php
                    $expectedExitDate = $resident->entry_date->copy()->addMonths(4)->addDays(10);
                    // Menghitung deadline toleransi 6 hari
                    $maxExitDate = $expectedExitDate->copy()->addDays(6);
                @endphp

                <form action="{{ route('residents.processCheckout', $resident) }}" method="POST" class="space-y-8">
                    @csrf

                    <div class="border-b border-slate-100 pb-5 flex justify-between items-center">
                        <div>
                            <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">Formulir Keluar</h3>
                            <p class="text-sm text-slate-500 font-medium mt-1">Tentukan tanggal aktual keluar dan alasan terminasi taruna.</p>
                        </div>
                        <div class="hidden sm:flex w-12 h-12 rounded-2xl bg-rose-100 text-rose-600 items-center justify-center font-bold text-xl shadow-md border border-rose-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        </div>
                    </div>

                    {{-- Data Diri (Readonly) --}}
                    <div class="bg-slate-50 border border-slate-100 rounded-2xl p-6 relative overflow-hidden">
                        <div class="absolute right-0 top-0 w-32 h-32 bg-slate-200/50 rounded-bl-full -mr-10 -mt-10 pointer-events-none"></div>
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4 relative z-10">Informasi Taruna</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 relative z-10">
                            <div>
                                <label class="block font-bold text-xs text-slate-500 mb-1">Nama Lengkap</label>
                                <div class="font-semibold text-slate-800">{{ $resident->name }}</div>
                            </div>
                            <div>
                                <label class="block font-bold text-xs text-slate-500 mb-1">NIM/KTP</label>
                                <div class="font-mono text-slate-800">{{ $resident->identity_number }}</div>
                            </div>
                            <div>
                                <label class="block font-bold text-xs text-slate-500 mb-1">Riwayat Kamar & Bed</label>
                                <div class="font-semibold text-indigo-600">Unit {{ $resident->room->number ?? '-' }} (Bed #{{ $resident->bed_slot }})</div>
                            </div>
                            <div>
                                <label class="block font-bold text-xs text-slate-500 mb-1">Tanggal Masuk Asrama</label>
                                <div class="font-semibold text-slate-800">{{ $resident->entry_date->format('d F Y') }}</div>
                            </div>
                            <div>
                                <label class="block font-bold text-xs text-slate-500 mb-1">Tanggal Keluar Asrama</label>
                                <div class="font-semibold text-slate-800">{{ $expectedExitDate->format('d F Y') }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- Input Data Keluar --}}
                    <div class="bg-rose-50/40 border border-rose-100 rounded-3xl p-6 sm:p-8">
                        <h4 class="text-sm font-extrabold text-rose-600 tracking-wide mb-6 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            DETAIL PROSES KELUAR
                        </h4>

                        <div class="space-y-6">
                            <div>
                                 <label class="block font-bold text-xs text-slate-700 uppercase tracking-wider mb-2">Tanggal Keluar Aktual</label>
                                 <input type="date" name="exit_date" value="{{ old('exit_date', $expectedExitDate->format('Y-m-d')) }}"
                                        min="{{ $expectedExitDate->format('Y-m-d') }}"
                                        max="{{ $maxExitDate->format('Y-m-d') }}"
                                        class="block w-full sm:w-1/2 rounded-xl border-rose-200 bg-white focus:bg-white focus:border-rose-500 focus:ring-4 focus:ring-rose-500/10 py-3.5 px-4 transition-all duration-200 font-bold text-rose-700 shadow-sm" required>

                                 <p class="text-[11px] font-medium text-rose-500 mt-2">
                                     *Batas pengisian hanya diizinkan antara <b>{{ $expectedExitDate->format('d M Y') }} s.d {{ $maxExitDate->format('d M Y') }}</b>.
                                 </p>
                                 @error('exit_date') <span class="text-rose-500 text-xs font-bold mt-1.5 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                 <label class="block font-bold text-xs text-slate-700 uppercase tracking-wider mb-2">Alasan/Keterangan Keluar Asrama (Opsional)</label>
                                 <textarea name="reason" rows="3"
                                           class="block w-full rounded-xl border-rose-200 bg-white focus:bg-white focus:border-rose-500 focus:ring-4 focus:ring-rose-500/10 py-3 px-4 transition-all duration-200 text-sm shadow-sm"
                                           placeholder="Misal: Lulus studi, pindah domisili, dsb...">{{ old('reason') }}</textarea>
                                 @error('reason') <span class="text-rose-500 text-xs font-bold mt-1.5 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="mt-6 flex items-start gap-2 bg-rose-100/50 border border-rose-200 text-rose-700 p-3.5 rounded-xl shadow-sm">
                            <svg class="w-5 h-5 flex-shrink-0 mt-0.5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            <span class="text-xs font-bold leading-relaxed">
                                Setelah diproses, data taruna ini akan dihapus dari daftar aktif dan dipindahkan ke riwayat <b>Data Taruna Keluar</b>. Kapasitas dan slot kasur kamar akan otomatis kosong kembali.
                            </span>
                        </div>
                    </div>

                    <div class="pt-4 flex flex-col sm:flex-row items-center justify-start gap-3 border-t border-slate-100">
                        <button type="submit" class="w-full sm:w-auto py-3.5 px-8 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 shadow-lg hover:shadow-emerald-500/30 transform hover:-translate-y-0.5 font-bold transition-all duration-200 focus:ring-4 focus:ring-emerald-500/30">
                            Konfirmasi Keluar Asrama
                        </button>
                        <a href="{{ route('residents.index') }}" class="w-full sm:w-auto text-center py-3.5 px-6 bg-red-500 border border-white text-white rounded-xl hover:bg-red-800 font-bold transition-colors">
                            Batal
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>
