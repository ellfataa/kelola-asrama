<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoomController extends Controller
{
    public function index()
    {
        // Ambil semua kamar, urutkan dari yang terbaru atau berdasarkan nama
        // withCount('residents') untuk menghitung hunian saat ini
        $rooms = Room::with('residents')->withCount('residents')->orderBy('number', 'asc')->paginate(12);

        return view('rooms.index', compact('rooms'));
    }

    public function create()
    {
        return view('rooms.create');
    }

    public function store(Request $request)
    {
        // Bersihkan format harga
        $request->merge([ 'price' => str_replace('.', '', $request->price) ]);

        $request->validate([
            'number' => 'required|unique:rooms,number|max:10',
            'capacity' => 'required|integer|min:1|max:20', // Batasi maks bed per kamar agar UI tidak rusak
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        Room::create($request->all());

        return redirect()->route('rooms.index')->with('success', 'Kamar dan Slot Tempat Tidur berhasil dibuat.');
    }

    public function edit(Room $room)
    {
        return view('rooms.edit', compact('room'));
    }

    public function update(Request $request, Room $room)
    {
        $request->merge([ 'price' => str_replace('.', '', $request->price) ]);

        $request->validate([
            'number' => 'required|max:10|unique:rooms,number,' . $room->id,
            'capacity' => 'required|integer|min:1|max:20',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        // 1. VALIDASI JUMLAH PENGHUNI
        // Kita hanya mencegah jika kapasitas baru < JUMLAH ORANG.
        // Posisi bed tidak masalah, nanti kita atur ulang.
        $totalResidents = $room->residents()->count();

        if ($request->capacity < $totalResidents) {
            return back()
                ->withInput()
                ->withErrors([
                    'capacity' => "Tidak bisa mengurangi kapasitas menjadi {$request->capacity}! Saat ini ada {$totalResidents} penghuni. Minimal kapasitas harus {$totalResidents}."
                ]);
        }

        // Gunakan Transaction agar data aman
        DB::transaction(function () use ($request, $room) {

            // 2. LOGIC RE-SLOTTING (PEMADATAN BED)
            // Jika kapasitas dikurangi, cek apakah ada penghuni yang "tertinggal" di bed nomor tinggi
            if ($request->capacity < $room->capacity) {

                // Ambil penghuni yang posisinya di luar kapasitas baru (Misal: Kapasitas baru 2, ambil penghuni di bed 3, 4, 5...)
                $strandedResidents = $room->residents()
                                          ->where('bed_slot', '>', $request->capacity)
                                          ->orderBy('bed_slot', 'asc')
                                          ->get();

                if ($strandedResidents->count() > 0) {
                    // Cari slot kosong di dalam range kapasitas baru (Misal: 1 sampai 2)

                    // Ambil bed yang SUDAH terisi di range aman (1-2)
                    $takenSlots = $room->residents()
                                       ->where('bed_slot', '<=', $request->capacity)
                                       ->pluck('bed_slot')
                                       ->toArray();

                    // Cari angka 1 sampai X yang tidak ada di takenSlots
                    $emptySlots = [];
                    for ($i = 1; $i <= $request->capacity; $i++) {
                        if (!in_array($i, $takenSlots)) {
                            $emptySlots[] = $i;
                        }
                    }

                    // Pindahkan penghuni terlantar ke slot kosong
                    foreach ($strandedResidents as $index => $resident) {
                        if (isset($emptySlots[$index])) {
                            $resident->update([
                                'bed_slot' => $emptySlots[$index]
                            ]);
                        }
                    }
                }
            }

            // 3. Update Data Kamar
            $room->update($request->all());
        });

        return redirect()->route('rooms.index')->with('success', 'Data kamar diperbarui. Struktur bed telah disesuaikan otomatis.');
    }

    public function destroy(Room $room)
    {
        // CEK: Apakah kamar ini masih ada penghuninya?
        if ($room->residents()->count() > 0) {
            // Jika ada, batalkan proses dan kembalikan pesan error
            return redirect()->route('rooms.index')
                ->with('error', 'Gagal menghapus! Kamar ini masih ditempati oleh penghuni. Silakan pindahkan atau hapus penghuni terlebih dahulu.');
        }

        // Jika kosong, baru boleh dihapus
        $room->delete();

        return redirect()->route('rooms.index')->with('success', 'Kamar berhasil dihapus.');
    }
}
