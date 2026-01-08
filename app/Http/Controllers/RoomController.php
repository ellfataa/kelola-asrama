<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

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

        $room->update($request->all());

        return redirect()->route('rooms.index')->with('success', 'Data kamar diperbarui.');
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
