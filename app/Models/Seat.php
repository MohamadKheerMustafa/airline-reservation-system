<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    use HasFactory;

    protected $fillable =[
        'flight_id',
        'seat_number',
        'class',
        'availability'
    ];

    public function flight()
    {
        return $this->belongsTo(Flight::class);
    }
}
