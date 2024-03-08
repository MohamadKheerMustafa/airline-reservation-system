<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "user_id" => $this->user->name,
            "ticket_number" => $this->ticket_number,
            "reservation_id" => $this->reservation,
            'luggages' => $this->luggage,
            "seat_number" => $this->seat_number,
            "status" => $this->status,
            "Employee" => $this->Employee,
            "passenger_id" => $this->passenger,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }
}
