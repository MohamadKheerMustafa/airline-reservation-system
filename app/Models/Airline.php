<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Airline extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function planes()
    {
        return $this->hasMany(Plane::class);
    }

    public function flights()
    {
        return $this->hasMany(Flight::class);
    }

    public function crews()
    {
        return $this->hasMany(Crew::class);
    }
}
