<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ResidentOut extends Model
{
    protected $guarded = ['id'];
    protected $casts = [
        'entry_date' => 'date',
        'exit_date' => 'date',
    ];
}
