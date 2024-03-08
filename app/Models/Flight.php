<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $fillable=[
        'flight_number',
        'airline_id',
        'plane_id',
        'origin_id',
        'destination_id',
        'departure',
        'arrival',
        'seats',
        'remain_seats',
        'status',
        'price',
        'gate_number',
        'crew_id',
    ];

    protected $with = ['airline:id,name', "plane:id,name", 'origin:id,city_id,name', 'destination:id,city_id,name'];

    public function airline()
    {
        return $this->belongsTo(Airline::class);
    }

    public function plane()
    {
        return $this->belongsTo(Plane::class, 'plane_id')->withDefault([
            'code' => 'N/A',
            "name" => "N/A"
        ]);
    }

    public function origin()
    {
        return $this->belongsTo(Airport::class, "origin_id");
    }

    public function destination()
    {
        return $this->belongsTo(Airport::class, "destination_id");
    }

    public function seats()
    {
        return $this->hasMany(Seat::class);
    }

    public function crew()
    {
        return $this->belongsTo(Crew::class);
    }
}
