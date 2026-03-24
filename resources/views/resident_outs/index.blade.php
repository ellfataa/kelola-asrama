<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 w-full">
            <h2 class="font-bold text-xl text-slate-800 leading-tight flex items-center gap-2.5">
                <div class="p-2 bg-rose-50 text-rose-600 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                </div>
                {{ __('Data Taruna Keluar Asrama') }}
            </h2>
        </div>
    </x-slot>

    <div class="space-y-6 pb-12 px-4 sm:px-6 lg:px-8">

        {{-- INFO BAR --}}
        <div class="flex items-center justify-between text-sm text-slate-500 px-2 mt-4">
            <p>Menampilkan <span class="font-bold text-slate-800">{{ $residentOuts->count() }}</span> dari <span class="font-bold text-slate-800">{{ $residentOuts->total() }}</span> riwayat taruna keluar</p>
        </div>

        {{-- Table Card --}}
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left border-collapse whitespace-nowrap lg:whitespace-normal">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-[11px] uppercase font-extrabold text-slate-400 tracking-wider">
                            <th class="p-5 pl-6 min-w-[200px]">Profil Taruna</th>
                            <th class="p-5 min-w-[150px]">NIM/KTP</th>
                            <th class="p-5 min-w-[160px]">Riwayat Kamar</th>
                            <th class="p-5 min-w-[130px]">Tanggal Masuk</th>
                            <th class="p-5 min-w-[130px]">Tanggal Keluar</th>
                            <th class="p-5 w-full max-w-sm">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($residentOuts as $out)
                        <tr class="hover:bg-slate-50/50 transition-colors duration-150 group grayscale-[30%] hover:grayscale-0">
                            <td class="p-4 pl-6 align-top pt-5">
                                <div class="flex items-start sm:items-center gap-3.5">
                                    <div class="w-10 h-10 shrink-0 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center font-bold text-sm shadow-sm ring-2 ring-white">
                                        {{ substr($out->name, 0, 1) }}
                                    </div>
                                    <div class="flex flex-col">
                                        <div class="font-extrabold text-slate-700 whitespace-normal line-clamp-1" title="{{ $out->name }}">{{ $out->name }}</div>
                                        <div class="text-[11px] font-medium text-slate-400 mt-0.5">{{ $out->phone ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4 text-sm font-bold text-slate-500 align-top pt-6">
                                {{ $out->identity_number }}
                            </td>
                            <td class="p-4 align-top pt-5">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-100 border border-slate-200 text-slate-600 text-xs font-bold shadow-sm whitespace-nowrap">
                                    <svg class="w-3.5 h-3.5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    {{ $out->room_info }}
                                </span>
                            </td>
                            <td class="p-4 text-xs font-medium text-slate-400 align-top pt-6 whitespace-nowrap">
                                {{ $out->entry_date->format('d M Y') }}
                            </td>
                            <td class="p-4 text-xs font-bold text-rose-500 align-top pt-6 whitespace-nowrap">
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                    {{ $out->exit_date->format('d M Y') }}
                                </div>
                            </td>
                            <td class="p-4 text-[11px] text-slate-500 align-top pt-6">
                                <div class="max-w-sm">
                                    <p class="leading-relaxed line-clamp-2 hover:line-clamp-none transition-all duration-300" title="{{ $out->reason }}">
                                        {{ $out->reason ?? '-' }}
                                    </p>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="p-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-slate-50 border border-slate-100 rounded-2xl flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                                    </div>
                                    <h3 class="text-slate-800 font-bold text-lg">Belum ada Riwayat</h3>
                                    <span class="text-sm font-medium text-slate-500 mt-1 mb-5">Belum ada data taruna yang tercatat keluar dari asrama.</span>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($residentOuts->hasPages())
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
                    {{ $residentOuts->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
