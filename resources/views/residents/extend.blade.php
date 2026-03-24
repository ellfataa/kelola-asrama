<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('residents.index') }}" class="p-2 text-slate-400 hover:text-indigo-600 bg-white rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="font-bold text-xl text-slate-800 leading-tight">
                {{ __('Proses Perpanjang Asrama') }}
            </h2>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto pb-12 px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm rounded-3xl border border-slate-200">
            <div class="p-6 sm:p-10">

                @php
                    $currentExitDate = $resident->entry_date->copy()->addMonths(4)->addDays(10);
                    $minDate = $currentExitDate->copy()->addDays(1);
                    $maxDate = $currentExitDate->copy()->addDays(6);
                @endphp

                <form action="{{ route('residents.updateExtension', $resident) }}" method="POST"
                      x-data="{
                          // Default value diatur ke tanggal minimal yang diizinkan (H+1)
                          entryDate: '{{ old('entry_date', $minDate->format('Y-m-d')) }}',
                          exitDate: '',

                          calculateExitDate() {
                              if(!this.entryDate) return;
                              let date = new Date(this.entryDate);
                              // Tambah 4 bulan
                              date.setMonth(date.getMonth() + 4);
                              // Tambah 10 hari
                              date.setDate(date.getDate() + 10);

                              let d = date.getDate().toString().padStart(2, '0');
                              let m = (date.getMonth() + 1).toString().padStart(2, '0');
                              let y = date.getFullYear();
                              this.exitDate = y + '-' + m + '-' + d;
                          }
                      }"
                      x-init="calculateExitDate()"
                      class="space-y-8">

                    @csrf
                    @method('PUT')

                    <div class="border-b border-slate-100 pb-5 flex justify-between items-center">
                        <div>
                            <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">Formulir Perpanjangan</h3>
                            <p class="text-sm text-slate-500 font-medium mt-1">Tentukan tanggal masuk yang baru untuk periode asrama selanjutnya.</p>
                        </div>
                        <div class="hidden sm:flex w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-600 items-center justify-center font-bold text-xl shadow-md border border-emerald-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>

                    {{-- Data Diri (Readonly) --}}
                    <div class="bg-slate-50 border border-slate-100 rounded-2xl p-6">
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Informasi Data Taruna</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="block font-bold text-xs text-slate-500 mb-1">Nama Lengkap</label>
                                <div class="font-semibold text-slate-800">{{ $resident->name }}</div>
                            </div>
                            <div>
                                <label class="block font-bold text-xs text-slate-500 mb-1">NIM/KTP</label>
                                <div class="font-mono text-slate-800">{{ $resident->identity_number }}</div>
                            </div>
                            <div>
                                <label class="block font-bold text-xs text-slate-500 mb-1">Kamar & Bed Saat Ini</label>
                                <div class="font-semibold text-indigo-600">Unit {{ $resident->room->number ?? '-' }} (Bed #{{ $resident->bed_slot }})</div>
                            </div>
                            <div>
                                <label class="block font-bold text-xs text-slate-500 mb-1">Tanggal Keluar Asrama Sebelumnya</label>
                                <div class="font-semibold text-rose-600">{{ $currentExitDate->format('d F Y') }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- Input Perpanjangan --}}
                    <div class="bg-emerald-50/50 border border-emerald-100 rounded-2xl p-6">
                        <h4 class="text-xs font-bold text-emerald-600 uppercase tracking-widest mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Periode Perpanjangan
                        </h4>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                 <label class="block font-bold text-xs text-slate-700 uppercase tracking-wider mb-2">Tanggal Masuk Baru</label>
                                 <input type="date" name="entry_date" x-model="entryDate" @change="calculateExitDate()"
                                        min="{{ $minDate->format('Y-m-d') }}" max="{{ $maxDate->format('Y-m-d') }}"
                                        class="block w-full rounded-xl border-emerald-200 bg-white focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 py-3.5 px-4 transition-all duration-200 font-semibold text-slate-900" required>
                                 @error('entry_date') <span class="text-rose-500 text-xs font-bold mt-1.5 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                 <label class="block font-bold text-xs text-emerald-500 uppercase tracking-wider mb-2">Tanggal Keluar Baru</label>
                                 <input type="date" x-model="exitDate" readonly
                                        class="block w-full rounded-xl border-emerald-100 bg-emerald-50/50 text-emerald-700 py-3.5 px-4 transition-all duration-200 font-bold cursor-not-allowed outline-none focus:ring-0">
                            </div>
                        </div>
                        <p class="text-[11px] font-medium text-red-600 mt-4">
                            *Batas waktu perpanjangan adalah <b>6 hari</b> setelah tanggal keluar sebelumnya.<br>Anda hanya bisa memilih rentang tanggal antara <b>{{ $minDate->format('d M Y') }} s.d {{ $maxDate->format('d M Y') }}</b>.
                        </p>
                    </div>

                    <div class="pt-6 flex flex-col sm:flex-row items-center justify-start gap-3 border-t border-slate-100">
                        <button type="submit" class="w-full sm:w-auto py-3.5 px-8 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 shadow-lg hover:shadow-emerald-500/30 transform hover:-translate-y-0.5 font-bold transition-all duration-200 focus:ring-4 focus:ring-emerald-500/30">
                            Proses Perpanjangan
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
