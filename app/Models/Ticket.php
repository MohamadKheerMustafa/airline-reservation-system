<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $guarded = [];

    // protected $with = ['passenger', 'user', 'reservation'];

    public function getStatusAttribute($value)
    {
        switch ($value) {
            case 0:
                return "pending";
                break;
            case 1:
                return "approve";
                break;

            case 2:
                return "cancele";
                break;

            default:
                return "not defined";
                break;
        }
    }

    public function passenger()
    {
        return $this->belongsTo(Passenger::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function payment()
    {
        return $this->hasMany(Payment::class);
    }

    public function luggage()
    {
        return $this->hasMany(Luggage::class);
    }
}
