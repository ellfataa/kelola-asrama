<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 w-full">
            <h2 class="font-bold text-xl text-slate-800 leading-tight flex items-center gap-2.5">
                <div class="p-2 bg-indigo-50 text-indigo-600 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                {{ __('Data Taruna Terdaftar Asrama') }}
            </h2>
            <div class="w-full sm:w-auto flex justify-end">
                <a href="{{ route('residents.create') }}" class="w-full sm:w-auto justify-center inline-flex items-center px-5 py-2.5 bg-indigo-600 text-white rounded-xl font-bold text-sm shadow-md hover:bg-indigo-700 hover:shadow-indigo-500/30 transition-all duration-200 transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Taruna
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6 pb-12">

        {{-- Notifikasi --}}
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition
                 class="bg-emerald-50 text-emerald-700 p-4 rounded-2xl border border-emerald-200 flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="p-1.5 bg-emerald-100 rounded-lg"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
                    <span class="font-bold text-sm">{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="text-emerald-500 hover:text-emerald-800 transition-colors">&times;</button>
            </div>
        @endif

        {{-- Info bar total data taruna --}}
        <div class="flex items-center justify-between text-sm text-slate-500 px-2">
            <p>Menampilkan <span class="font-bold text-slate-800">{{ $residents->count() }}</span> dari <span class="font-bold text-slate-800">{{ $residents->total() }}</span> total taruna</p>
        </div>

        {{-- Tabel --}}
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-[11px] uppercase font-extrabold text-slate-400 tracking-wider">
                            <th class="p-5 pl-6">Profil Taruna</th>
                            <th class="p-5">NIM/KTP</th>
                            <th class="p-5">Alokasi Kamar</th>
                            <th class="p-5">Masuk Asrama</th>
                            <th class="p-5">Keluar Asrama</th>
                            <th class="p-5 text-center">Formulir Asrama</th>
                            <th class="p-5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($residents as $resident)
                        <tr class="hover:bg-slate-50/80 transition-colors duration-150 group">
                            {{-- Profil Taruna --}}
                            <td class="p-4 pl-6">
                                <div class="flex items-center gap-3.5">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-red-500 to-orange-500 text-white flex items-center justify-center font-bold text-sm shadow-sm ring-2 ring-white">
                                        {{ substr($resident->name, 0, 1) }}
                                    </div>
                                    <div class="flex flex-col">
                                        <div class="font-extrabold text-slate-900 group-hover:text-indigo-600 transition-colors text-sm">{{ $resident->name }}</div>
                                        <div class="text-[11px] font-medium text-slate-500 mt-0.5 flex items-center gap-1">
                                            <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                            {{ $resident->phone ?? 'Tidak ada data HP' }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- NIM/KTP --}}
                            <td class="p-4 text-sm font-bold text-slate-600">
                                {{ $resident->identity_number }}
                            </td>

                            {{-- Alokasi Kamar --}}
                            <td class="p-4">
                                <div class="flex flex-col gap-1.5">
                                    <span class="inline-flex items-center w-fit gap-1.5 px-3 py-1.5 rounded-lg bg-indigo-50 border border-indigo-100 text-indigo-700 text-xs font-bold shadow-sm">
                                        <svg class="w-3.5 h-3.5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                        Kamar {{ $resident->room->number ?? 'Tidak Diketahui' }}
                                    </span>
                                    <span class="inline-flex items-center w-fit gap-1.5 px-2.5 py-1 rounded-md bg-emerald-50 border border-emerald-100 text-emerald-700 text-[10px] font-extrabold shadow-sm">
                                        <svg class="w-3 h-3 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 14a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H9a2 2 0 01-2-2v-4z M7 14V6a2 2 0 012-2h8a2 2 0 012 2v8 M7 14h10 M7 10h10 M7 6h10"></path></svg>
                                        Bed #{{ $resident->bed_slot ?? '-' }}
                                    </span>
                                </div>
                            </td>

                            {{-- Tanggal Masuk --}}
                            <td class="p-4 text-xs font-medium text-slate-500">
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    {{ $resident->entry_date->format('d M Y') }}
                                </div>
                            </td>

                            {{-- Tanggal Keluar (Perkiraan) --}}
                            <td class="p-4 text-xs font-bold text-amber-600">
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ $resident->entry_date->copy()->addMonths(4)->addDays(10)->format('d M Y') }}
                                </div>
                            </td>

                            {{-- Formulir Perpanjangan/Keluar --}}
                            <td class="p-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('residents.extend', $resident) }}" class="px-3 py-1.5 bg-emerald-50 text-emerald-600 hover:bg-emerald-100 hover:text-emerald-700 rounded-lg text-[10px] font-extrabold border border-emerald-200 transition-all shadow-sm">
                                        Perpanjang
                                    </a>
                                    <a href="{{ route('residents.checkout', $resident) }}" class="px-3 py-1.5 bg-orange-50 text-orange-600 hover:bg-orange-100 hover:text-orange-700 rounded-lg text-[10px] font-extrabold border border-orange-200 transition-all shadow-sm">
                                        Keluar
                                    </a>
                                </div>
                            </td>

                            {{-- Aksi --}}
                            <td class="p-4 pr-6 text-right">
                                <div class="flex items-center justify-end gap-2.5">
                                    <a href="{{ route('residents.edit', $resident) }}" class="p-2 bg-slate-50 text-slate-400 border border-slate-200 rounded-xl hover:bg-amber-50 hover:text-amber-600 hover:border-amber-200 transition-all shadow-sm" title="Edit Data">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </a>

                                    <form action="{{ route('residents.destroy', $resident) }}" method="POST" onsubmit="return confirm('⚠️ KONFIRMASI HAPUS\n\nApakah Anda yakin ingin menghapus data Taruna {{ $resident->name }}?\nData yang dihapus tidak dapat dikembalikan.');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 bg-slate-50 text-slate-400 border border-slate-200 rounded-xl hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 transition-all shadow-sm" title="Hapus Data">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        {{-- Jika tidak ada data taruna --}}
                        @empty
                        <tr>
                            <td colspan="7" class="p-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-slate-50 border border-slate-100 rounded-2xl flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    </div>
                                    <h3 class="text-slate-800 font-bold text-lg">Belum ada taruna</h3>
                                    <span class="text-sm font-medium text-slate-500 mt-1 mb-5">Sistem belum memiliki data taruna yang terdaftar.</span>
                                    <a href="{{ route('residents.create') }}" class="px-5 py-2.5 bg-indigo-50 text-indigo-700 font-bold rounded-xl hover:bg-indigo-100 transition-colors text-sm border border-indigo-200">
                                        Tambahkan data taruna pertama
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($residents->hasPages())
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
                    {{ $residents->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
