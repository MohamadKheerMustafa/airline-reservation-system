<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Crew extends Model
{
    use HasFactory;

    protected $fillable = [
        'airline_id',
        'name',
    ];

    public function flights()
    {
        return $this->hasMany(Flight::class);
    }

    public function crewMembers()
    {
        return $this->hasMany(CrewMember::class, 'crews_id', 'id');
    }

    public function airlines()
    {
        return $this->belongsTo(Airline::class, 'airline_id');
    }
}
