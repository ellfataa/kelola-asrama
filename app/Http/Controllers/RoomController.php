<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoomController extends Controller
{
    public function index()
    {
        // Ambil semua kamar beserta penghuninya, urutkan berdasarkan nama/nomor kamar
        $rooms = Room::with('residents')->withCount('residents')->orderBy('number', 'asc')->paginate(12);

        return view('rooms.index', compact('rooms'));
    }

    public function create()
    {
        return view('rooms.create');
    }

    public function store(Request $request)
    {
        $request->merge([ 'price' => str_replace('.', '', $request->price) ]);

        $request->validate([
            'number' => 'required|unique:rooms,number|max:255',
            'capacity' => 'required|integer|min:1|max:20',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $data = $request->all();

        // Suntikan nilai default untuk mencegah error pada kolom database yang memerlukan angka
        $data['location_code'] = 1;

        Room::create($data);

        return redirect()->route('rooms.index')->with('success', 'Data Kamar berhasil dibuat!');
    }

    public function edit(Room $room)
    {
        return view('rooms.edit', compact('room'));
    }

    public function update(Request $request, Room $room)
    {
        $request->merge([ 'price' => str_replace('.', '', $request->price) ]);

        $request->validate([
            'number' => 'required|max:255|unique:rooms,number,' . $room->id,
            'capacity' => 'required|integer|min:1|max:20',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        // 1. Validasi: Kapasitas baru tidak boleh lebih kecil dari jumlah TOTAL penghuni saat ini
        $totalResidents = $room->residents()->count();
        if ($request->capacity < $totalResidents) {
            return back()
                ->withInput()
                ->withErrors([
                    'capacity' => "Tidak bisa mengurangi kapasitas kamar menjadi {$request->capacity}! Saat ini masih ada {$totalResidents} taruna di dalam kamar."
                ]);
        }

        // Gunakan Transaction agar jika terjadi error, database tidak berantakan
        DB::transaction(function () use ($request, $room) {
            $newCapacity = $request->capacity;

            // 2. Logika Pemadatan Bed
            if ($newCapacity < $room->capacity) {
                // Cari taruna yang posisinya tergusur (Misal: Kasur no 3, padahal kapasitas baru hanya sampai 2)
                $strandedResidents = $room->residents()
                                          ->where('bed_slot', '>', $newCapacity)
                                          ->orderBy('bed_slot', 'asc')
                                          ->get();

                if ($strandedResidents->count() > 0) {
                    // Cari tahu kasur nomor berapa saja yang sudah ADA ISINYA di area yang aman (1 s.d Kapasitas Baru)
                    $takenSlots = $room->residents()
                                       ->where('bed_slot', '<=', $newCapacity)
                                       ->pluck('bed_slot')
                                       ->toArray();

                    // Kumpulkan sisa kasur yang KOSONG di area aman tersebut
                    $emptySlots = [];
                    for ($i = 1; $i <= $newCapacity; $i++) {
                        if (!in_array($i, $takenSlots)) {
                            $emptySlots[] = $i;
                        }
                    }

                    // Pindahkan taruna yang tergusur ke kasur kosong yang sudah disiapkan
                    foreach ($strandedResidents as $index => $resident) {
                        if (isset($emptySlots[$index])) {
                            $resident->bed_slot = $emptySlots[$index];
                            $resident->save();
                        }
                    }
                }
            }

            // 3. Simpan perubahan utama kamar
            $data = $request->only(['number', 'capacity', 'price', 'description']);
            $data['location_code'] = $room->location_code ?? 1;

            $room->update($data);
        });

        return redirect()->route('rooms.index')->with('success', 'Data kamar berhasil diperbarui!');
    }

    public function destroy(Room $room)
    {
        if ($room->residents()->count() > 0) {
            return redirect()->route('rooms.index')
                ->with('error', 'Gagal menghapus! Kamar ini masih ditempati oleh taruna. Silakan pindahkan taruna terlebih dahulu.');
        }

        $room->delete();

        return redirect()->route('rooms.index')->with('success', 'Data kamar berhasil dihapus!');
    }
}
