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
        // OPTIMASI: Gunakan withCount untuk menghitung jumlah penghuni langsung dari database
        // Ini mencegah query berulang (N+1 Problem) di dalam loop view
        $rooms = Room::withCount('residents')->get();
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

        // LOGIC VALIDASI KAPASITAS (UPDATE DISINI)
        $room = Room::findOrFail($request->room_id);

        // Gunakan method model isFull() yang sudah kita update
        if ($room->isFull()) {
            return back()
                ->withInput()
                ->withErrors(['room_id' => 'Kamar ' . $room->number . ' sudah penuh atau dibooking eksklusif!']);
        }

        Resident::create($request->all());

        return redirect()->route('residents.index')->with('success', 'Penghuni berhasil didaftarkan.');
    }

    public function edit(Resident $resident)
    {
        $rooms = Room::withCount('residents')->get();
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

        // LOGIC VALIDASI KAPASITAS SAAT PINDAH KAMAR (UPDATE DISINI)
        if ($request->room_id != $resident->room_id) {
            $newRoom = Room::findOrFail($request->room_id);

            // Gunakan method model isFull()
            if ($newRoom->isFull()) {
                return back()
                    ->withInput()
                    ->withErrors(['room_id' => 'Kamar tujuan penuh atau dibooking eksklusif!']);
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
