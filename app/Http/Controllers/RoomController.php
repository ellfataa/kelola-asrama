<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    // Menampilkan daftar kamar
    public function index()
    {
        $rooms = Room::latest()->paginate(10); // 10 data per halaman
        return view('rooms.index', compact('rooms'));
    }

    // Form tambah kamar
    public function create()
    {
        return view('rooms.create');
    }

    // Simpan data kamar baru
    public function store(Request $request)
    {
        $request->validate([
            'number' => 'required|unique:rooms,number|max:10',
            'capacity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        Room::create($request->all());

        return redirect()->route('rooms.index')->with('success', 'Kamar berhasil ditambahkan.');
    }

    // Form edit kamar
    public function edit(Room $room)
    {
        return view('rooms.edit', compact('room'));
    }

    // Update data kamar
    public function update(Request $request, Room $room)
    {
        $request->validate([
            'number' => 'required|max:10|unique:rooms,number,' . $room->id, // Abaikan ID ini saat cek unik
            'capacity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $room->update($request->all());

        return redirect()->route('rooms.index')->with('success', 'Data kamar diperbarui.');
    }

    // Hapus kamar
    public function destroy(Room $room)
    {
        $room->delete();
        return redirect()->route('rooms.index')->with('success', 'Kamar berhasil dihapus.');
    }
}
