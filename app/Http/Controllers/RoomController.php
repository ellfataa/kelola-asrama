<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    // Menampilkan daftar kamar
    public function index()
    {
        // 1. Ambil Peta Denah
        $roomMap = $this->getRoomMap();

        // 2. Ambil semua kamar untuk divisualisasikan di Peta
        // keyBy('location_code') memudahkan kita memanggil data berdasarkan lokasi: $mappedRooms[1]
        $mappedRooms = Room::withCount('residents')->get()->keyBy('location_code');

        // 3. Ambil data paginasi untuk Tabel di bawah
        $rooms = Room::latest()->paginate(10);

        return view('rooms.index', compact('rooms', 'roomMap', 'mappedRooms'));
    }

    private function getRoomMap()
    {
        return [
            ['id' => 1, 'label' => 'A-101'],
            ['id' => 2, 'label' => 'A-102'],
            ['id' => 3, 'label' => 'A-103'],
            ['id' => 4, 'label' => 'A-104'],
            ['id' => 5, 'label' => 'A-105'],
            ['id' => 6, 'label' => 'B-201'],
            ['id' => 7, 'label' => 'B-202'],
            ['id' => 8, 'label' => 'B-203'],
            ['id' => 9, 'label' => 'B-204'],
            ['id' => 10, 'label' => 'B-205'],
            ['id' => 11, 'label' => 'C-900'],
        ];
    }

    // Form tambah kamar
    public function create()
    {
        $takenLocations = Room::pluck('location_code')->toArray();
        $roomMap = $this->getRoomMap(); // Ambil data map

        return view('rooms.create', compact('takenLocations', 'roomMap'));
    }

    // Simpan data kamar baru
    public function store(Request $request)
    {
        // Kita bersihkan format harga jika ada (logic sebelumnya)
        $request->merge([
            'price' => str_replace('.', '', $request->price),
        ]);

        $request->validate([
            'number' => 'required|unique:rooms,number|max:10',
            'location_code' => 'required|integer|between:1,20|unique:rooms,location_code',
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
        $takenLocations = Room::pluck('location_code')->toArray();
        $roomMap = $this->getRoomMap(); // Ambil data map

        return view('rooms.edit', compact('room', 'takenLocations', 'roomMap'));
    }

    // Update data kamar
    public function update(Request $request, Room $room)
    {
        // Bersihkan format harga
        $request->merge([
            'price' => str_replace('.', '', $request->price),
        ]);

        $request->validate([
            // Validasi unique location_code harus mengecualikan ID kamar ini sendiri
            'location_code' => 'required|integer|between:1,10|unique:rooms,location_code,' . $room->id,
            'number' => 'required|max:10|unique:rooms,number,' . $room->id,
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
