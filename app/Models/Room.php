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

    // Helper untuk cek apakah kamar penuh
    // Akan mengembalikan true jika jumlah penghuni >= kapasitas
    public function isFull()
    {
        // Kamar penuh jika:
        // 1. Jumlah penghuni >= Kapasitas
        // 2. ATAU Statusnya Exclusive (Booking 1 kamar)
        if ($this->is_exclusive) {
            return true;
        }

        return $this->residents()->count() >= $this->capacity;
    }
}
