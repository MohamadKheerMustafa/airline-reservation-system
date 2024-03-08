<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $with = ['flights'];

    protected $fillable = [
        'user_id',
        'flight_id',
        'reservation_date',
        'seat_preference',
        'special_requests',
        'addtionalNotes'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function flights()
    {
        return $this->belongsTo(Flight::class, 'flight_id');
    }

}
