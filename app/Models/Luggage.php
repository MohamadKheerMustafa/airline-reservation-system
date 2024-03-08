<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Luggage extends Model
{
    use HasFactory;
    protected $table = 'luggages';

    protected $fillable = [
        'ticket_id',
        'standard_quantity',
        'additional_quantity',
        'additional_price',
    ];
    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id', 'id');
    }
}
