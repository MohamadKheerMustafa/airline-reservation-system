<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FlightResource extends JsonResource
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
            "flight_number" => $this->flight_number,
            "airline_id" => $this->airline,
            "plane_id" => [
                'id' => $this->plane->id,
                'name' => $this->plane->name,
            ],
            "origin_id" => [
                'id' => $this->origin->id,
                'name' => $this->origin->name,
                'city' => $this->origin->city->name,
            ],
            "destination_id" => [
                'id' => $this->destination->id,
                'name' => $this->destination->name,
                'city' => $this->destination->city->name,
            ],
            "departure" => $this->departure,
            "arrival" => $this->arrival,
            "seats" => $this->seats,
            "remain_seats" => $this->remain_seats,
            "status" => $this->status,
            "price" => $this->price,
            "gate_number" => $this->gate_number,
            "crew_id" => $this->crew,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }
}
