<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resident extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'entry_date' => 'date',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
