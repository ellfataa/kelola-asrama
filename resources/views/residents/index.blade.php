<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 leading-tight flex items-center gap-2">
            <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            {{ __('Daftar Penghuni Asrama') }}
        </h2>
    </x-slot>

    <div class="space-y-6">

        {{-- Top Action Bar --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Manajemen Penghuni</h3>
                <p class="text-sm text-slate-500">Total terdaftar: <span class="font-bold text-indigo-600">{{ $residents->count() }}</span> orang</p>
            </div>

            <a href="{{ route('residents.create') }}" class="inline-flex items-center px-5 py-2.5 bg-indigo-600 text-white rounded-xl font-bold text-sm shadow-md hover:bg-indigo-700 hover:shadow-lg transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                Tambah Penghuni Baru
            </a>
        </div>

        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                 class="bg-emerald-50 text-emerald-700 p-4 rounded-xl border border-emerald-200 flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="text-emerald-500 hover:text-emerald-800">&times;</button>
            </div>
        @endif

        {{-- Table Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-xs uppercase font-bold text-slate-500 tracking-wider">
                            <th class="p-4 pl-6">Nama Lengkap</th>
                            <th class="p-4">Identitas (NIM/KTP)</th>
                            <th class="p-4">Kamar & Tempat Tidur</th>
                            <th class="p-4">Tgl Masuk</th>
                            <th class="p-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($residents as $resident)
                        <tr class="hover:bg-slate-50/80 transition-colors duration-150 group">
                            <td class="p-4 pl-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-sm shadow-sm border border-white ring-2 ring-indigo-50">
                                        {{ substr($resident->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-800">{{ $resident->name }}</div>
                                        <div class="text-xs text-slate-500">{{ $resident->phone ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4 text-sm font-mono text-slate-600">
                                {{ $resident->identity_number }}
                            </td>
                            <td class="p-4">
                                <div class="flex items-center gap-2">
                                    <span class="px-2.5 py-1 rounded-lg bg-slate-100 border border-slate-200 text-slate-700 text-xs font-bold">
                                        Unit {{ $resident->room->number }}
                                    </span>
                                    <span class="px-2 py-1 rounded-lg bg-blue-50 border border-blue-100 text-blue-600 text-xs font-semibold flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 14a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H9a2 2 0 01-2-2v-4z M7 14V6a2 2 0 012-2h8a2 2 0 012 2v8 M7 14h10 M7 10h10 M7 6h10"></path></svg>
                                        Tempat Tidur ke - {{ $resident->bed_slot }}
                                    </span>
                                </div>
                            </td>
                            <td class="p-4 text-sm text-slate-500">
                                {{ $resident->entry_date->format('d M Y') }}
                            </td>
                            <td class="p-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('residents.edit', $resident) }}" class="p-2 bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-100 transition" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </a>
                                    
                                    {{-- Form Delete dengan Confirm JS --}}
                                    <form action="{{ route('residents.destroy', $resident) }}" method="POST" onsubmit="return confirm('⚠️ KONFIRMASI HAPUS\n\nApakah Anda yakin ingin menghapus data Sdr/i {{ $resident->name }}?\nData yang dihapus tidak dapat dikembalikan.');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-100 transition" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="p-12 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-400">
                                    <svg class="w-12 h-12 mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    <span class="font-medium">Belum ada penghuni yang terdaftar.</span>
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
