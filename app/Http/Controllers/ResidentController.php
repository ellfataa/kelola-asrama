<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use App\Models\Room;
use Illuminate\Http\Request;

class ResidentController extends Controller
{
    public function index()
    {
        // Ambil data penghuni beserta data kamarnya (Eager Loading) agar hemat query
        $residents = Resident::with('room')->latest()->paginate(10);
        return view('residents.index', compact('residents'));
    }

    public function create()
    {
        // Kita butuh data kamar untuk ditampilkan di Dropdown (Select Option)
        // Opsional: Bisa difilter hanya kamar yang belum penuh, tapi untuk sekarang semua saja.
        $rooms = Room::all();
        return view('residents.create', compact('rooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'name' => 'required|string|max:255',
            'identity_number' => 'required|unique:residents,identity_number', // NIK/NIM harus unik
            'phone' => 'nullable|string|max:15',
            'entry_date' => 'required|date',
        ]);

        // LOGIC VALIDASI KAPASITAS
        $room = Room::findOrFail($request->room_id);
        $currentResidents = $room->residents()->count();

        if ($currentResidents >= $room->capacity) {
            return back()
                ->withInput() // Kembalikan inputan user
                ->withErrors(['room_id' => 'Kamar ' . $room->number . ' sudah penuh! (Kapasitas: ' . $room->capacity . ')']);
        }

        Resident::create($request->all());

        return redirect()->route('residents.index')->with('success', 'Penghuni berhasil didaftarkan.');
    }

    public function edit(Resident $resident)
    {
        $rooms = Room::all();
        return view('residents.edit', compact('resident', 'rooms'));
    }

    public function update(Request $request, Resident $resident)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'name' => 'required|string|max:255',
            'identity_number' => 'required|unique:residents,identity_number,' . $resident->id,
            'phone' => 'nullable|string|max:15',
            'entry_date' => 'required|date',
        ]);

        // LOGIC VALIDASI KAPASITAS SAAT PINDAH KAMAR
        if ($request->room_id != $resident->room_id) {
            $newRoom = Room::findOrFail($request->room_id);
            if ($newRoom->residents()->count() >= $newRoom->capacity) {
                return back()
                    ->withInput()
                    ->withErrors(['room_id' => 'Kamar tujuan penuh!']);
            }
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
