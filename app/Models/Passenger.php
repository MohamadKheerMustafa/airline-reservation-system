<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Passenger extends Model
{
    use HasFactory;

    // protected $with = ['reservations'];
    protected $fillable = [
        'first_name',
        'last_name',
        'date_of_birth',
        'gender',
        'nationality',
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
