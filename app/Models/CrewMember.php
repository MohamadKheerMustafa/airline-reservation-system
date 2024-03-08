<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrewMember extends Model
{
    use HasFactory;

    protected $table = 'crewsmembers';
    protected $fillable = [
        'crews_id',
        'first_name',
        'last_name',
        'position',
        'date_of_birth',
        'nationality',
    ];

    public function crew()
    {
        return $this->belongsTo(Crew::class, 'crews_id');
    }
}
