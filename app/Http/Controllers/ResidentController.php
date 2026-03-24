<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use App\Models\Room;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ResidentController extends Controller
{
    public function index()
    {
        $residents = Resident::with('room')->latest()->paginate(10);
        return view('residents.index', compact('residents'));
    }

    public function create()
    {
        $rooms = Room::with('residents')->get();
        return view('residents.create', compact('rooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_id'         => 'required|exists:rooms,id',
            'bed_slot'        => 'required|integer|min:1',
            'name'            => 'required|string|max:255',
            'identity_number' => 'required|unique:residents,identity_number',
            'phone'           => 'nullable|string|regex:/^08[0-9]{5,13}$/|max:15',
            'entry_date'      => 'required|date',
        ], [
            'phone.regex' => 'Nomor HP harus diawali 08 dan hanya boleh berisi angka (minimal 7 digit).',
        ]);

        $room = Room::findOrFail($request->room_id);

        if ($request->bed_slot > $room->capacity) {
            return back()->withInput()->withErrors(['bed_slot' => 'Nomor bed tidak valid untuk kapasitas kamar ini.']);
        }

        $isBedTaken = $room->residents()->where('bed_slot', $request->bed_slot)->exists();
        if ($isBedTaken) {
            return back()->withInput()->withErrors(['bed_slot' => 'Slot bed nomor ' . $request->bed_slot . ' sudah terisi! Silakan pilih bed lain.']);
        }

        Resident::create($request->all());

        return redirect()->route('residents.index')->with('success', 'Data taruna dan alokasi bed berhasil ditambahkan!');
    }

    public function edit(Resident $resident)
    {
        $rooms = Room::with('residents')->get();
        return view('residents.edit', compact('resident', 'rooms'));
    }

    public function update(Request $request, Resident $resident)
    {
        $request->validate([
            'room_id'         => 'required|exists:rooms,id',
            'bed_slot'        => 'required|integer|min:1',
            'name'            => 'required|string|max:255',
            'identity_number' => 'required|unique:residents,identity_number,' . $resident->id,
            'phone'           => 'nullable|string|regex:/^08[0-9]{5,13}$/|max:15',
        ], [
            'phone.regex' => 'Nomor HP harus diawali 08 dan hanya boleh berisi angka (minimal 7 digit).',
        ]);

        $room = Room::findOrFail($request->room_id);

        if ($request->bed_slot > $room->capacity) {
             return back()->withInput()->withErrors(['bed_slot' => 'Nomor bed tidak valid untuk kapasitas kamar ini.']);
        }

        // Cek apakah bed sudah diisi oleh orang LAIN (Abaikan diri sendiri)
        $isBedTaken = $room->residents()
                           ->where('bed_slot', $request->bed_slot)
                           ->where('id', '!=', $resident->id)
                           ->exists();

        if ($isBedTaken) {
            return back()->withInput()->withErrors(['bed_slot' => 'Slot bed nomor ' . $request->bed_slot . ' sudah terisi!']);
        }

        $resident->update($request->only([
            'room_id', 'bed_slot', 'name', 'identity_number', 'phone'
        ]));

        return redirect()->route('residents.index')->with('success', 'Data taruna dan alokasi bed berhasil diperbarui!');
    }

    public function destroy(Resident $resident)
    {
        $resident->delete();
        return redirect()->route('residents.index')->with('success', 'Data taruna berhasil dihapus!');
    }

    // PERPANJANG ASRAMA
    public function extend(Resident $resident)
    {
        return view('residents.extend', compact('resident'));
    }

    public function updateExtension(Request $request, Resident $resident)
    {
        $request->validate([
            'entry_date' => 'required|date',
        ]);

        // 1. Hitung tanggal keluar SAAT INI
        $currentExitDate = $resident->entry_date->copy()->addMonths(4)->addDays(10);

        // 2. Tentukan deadline waktu perpanjangan (H+1 sampai H+6)
        $minDate = $currentExitDate->copy()->addDays(1);
        $maxDate = $currentExitDate->copy()->addDays(6);

        $newEntryDate = \Carbon\Carbon::parse($request->entry_date);

        // 3. Tanggal harus berada di dalam jendela waktu 6 hari tersebut
        if ($newEntryDate->startOfDay() < $minDate->startOfDay() || $newEntryDate->startOfDay() > $maxDate->startOfDay()) {
            return back()->withInput()->withErrors([
                'entry_date' => 'Tanggal perpanjangan tidak valid! Harus berada di antara ' . $minDate->format('d M Y') . ' sampai dengan ' . $maxDate->format('d M Y') . '.'
            ]);
        }
        $resident->update([
            'entry_date' => $request->entry_date
        ]);

        return redirect()->route('residents.index')->with('success', 'Proses perpanjangan asrama berhasil dilakukan!');
    }

    // KELUAR ASRAMA
    public function checkout(Resident $resident)
    {
        return view('residents.checkout', compact('resident'));
    }

    public function processCheckout(Request $request, Resident $resident)
    {
        $expectedExitDate = $resident->entry_date->copy()->addMonths(4)->addDays(10);
        $maxExitDate = $expectedExitDate->copy()->addDays(6);

        $request->validate([
            'exit_date' => 'required|date|after_or_equal:' . $expectedExitDate->format('Y-m-d') . '|before_or_equal:' . $maxExitDate->format('Y-m-d'),
            'reason'    => 'nullable|string|max:500',
        ], [
            'exit_date.after_or_equal' => 'Tanggal keluar aktual tidak boleh mendahului tanggal prakiraan keluar asrama.',
            'exit_date.before_or_equal' => 'Tanggal keluar aktual melewati batas toleransi 6 hari.',
        ]);

        // 1. Simpan/Pindahkan data ke tabel arsip (ResidentOut)
        \App\Models\ResidentOut::create([
            'name'            => $resident->name,
            'identity_number' => $resident->identity_number,
            'phone'           => $resident->phone,
            'room_info'       => 'Kamar ' . ($resident->room->number ?? '-') . ' (Bed #' . $resident->bed_slot . ')',
            'entry_date'      => $resident->entry_date,
            'exit_date'       => $request->exit_date,
            'reason'          => $request->reason,
        ]);

        // 2. Hapus data dari tabel residents aktif (Otomatis membebaskan slot kasur/kamar)
        $resident->delete();

        return redirect()->route('residents.index')->with('success', 'Proses keluar asrama berhasil dilakukan! Data telah dipindahkan ke menu Data Taruna Keluar.');
    }
}
