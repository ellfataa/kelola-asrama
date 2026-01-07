<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    // Izinkan semua kolom diisi (mass assignment)
    protected $guarded = ['id'];

    // Satu kamar punya banyak penghuni
    public function residents()
    {
        return $this->hasMany(Resident::class);
    }

    // Akan mengembalikan true jika jumlah penghuni >= kapasitas
    // Cek apakah kamar penuh (Semua bed terisi)
    public function isFull()
    {
        return $this->residents()->count() >= $this->capacity;
    }

    // Helper untuk cek apakah slot bed tertentu (misal Bed 2) sudah terisi
    public function isBedTaken($slotNumber)
    {
        return $this->residents()->where('bed_slot', $slotNumber)->exists();
    }
}
