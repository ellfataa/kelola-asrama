<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use App\Models\Room;
use Illuminate\Http\Request;

class ResidentController extends Controller
{
    public function index()
    {
        // Eager loading room data
        $residents = Resident::with('room')->latest()->paginate(10);
        return view('residents.index', compact('residents'));
    }

    public function create()
    {
        // Ambil data kamar beserta data penghuninya untuk cek bed
        $rooms = Room::with('residents')->get();
        return view('residents.create', compact('rooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'bed_slot' => 'required|integer|min:1',
            'name' => 'required|string|max:255',
            'identity_number' => 'required|unique:residents,identity_number',
            'phone' => 'nullable|string|max:15',
            'entry_date' => 'required|date',
        ]);

        $room = Room::findOrFail($request->room_id);

        // 1. Cek Validitas Nomor Bed (Tidak boleh lebih dari kapasitas)
        if ($request->bed_slot > $room->capacity) {
            return back()->withInput()->withErrors(['bed_slot' => 'Nomor bed tidak valid untuk kapasitas kamar ini.']);
        }

        // 2. Cek Ketersediaan Bed (Apakah bed X di kamar Y sudah ada isinya?)
        $isBedTaken = $room->residents()->where('bed_slot', $request->bed_slot)->exists();

        if ($isBedTaken) {
            return back()->withInput()->withErrors(['bed_slot' => 'Bed nomor ' . $request->bed_slot . ' sudah terisi! Silakan pilih bed lain.']);
        }

        Resident::create($request->all());

        return redirect()->route('residents.index')->with('success', 'Penghuni berhasil didaftarkan.');
    }

    public function edit(Resident $resident)
    {
        $rooms = Room::with('residents')->get();
        return view('residents.edit', compact('resident', 'rooms'));
    }

    public function update(Request $request, Resident $resident)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'bed_slot' => 'required|integer|min:1', // Bed slot wajib diisi dan divalidasi
            'name' => 'required|string|max:255',
            'identity_number' => 'required|unique:residents,identity_number,' . $resident->id,
            'phone' => 'nullable|string|max:15',
            'entry_date' => 'required|date',
        ]);

        $room = Room::findOrFail($request->room_id);

        // 1. Cek Kapasitas Bed Slot (Validasi Umum)
        if ($request->bed_slot > $room->capacity) {
             return back()->withInput()->withErrors(['bed_slot' => 'Nomor bed tidak valid untuk kapasitas kamar ini.']);
        }

        // 2. Cek Tabrakan Bed
        // Cek apakah bed yang dipilih SUDAH ada isinya OLEH ORANG LAIN
        $isBedTaken = $room->residents()
                           ->where('bed_slot', $request->bed_slot)
                           ->where('id', '!=', $resident->id) // Abaikan diri sendiri (jika tidak pindah bed)
                           ->exists();

        if ($isBedTaken) {
            return back()->withInput()->withErrors(['bed_slot' => 'Bed nomor ' . $request->bed_slot . ' sudah terisi!']);
        }

        $resident->update($request->all());

        return redirect()->route('residents.index')->with('success', 'Data penghuni diperbarui.');
    }

    public function destroy(Resident $resident)
    {
        $resident->delete();
        return redirect()->route('residents.index')->with('success', 'Penghuni berhasil dihapus.');
    }
}
