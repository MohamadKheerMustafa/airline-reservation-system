<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plane extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $with = ['airline:id,name'];

    public function flights()
    {
        return $this->hasMany(Flight::class);
    }

    public function airline()
    {
        return $this->belongsTo(Airline::class);
    }
}
