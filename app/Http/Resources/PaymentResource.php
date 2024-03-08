<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'reservation_id' => $this->reservation,
            'user_id' => $this->reservation->user,
            'payment_amount' => $this->payment_amount,
            'payment_date' => $this->payment_date,
            'payment_method' => $this->payment_method,
            'type' => $this->type,
            'ticket_id' => $this->ticket,
            'done' => $this->done,
            'notes' => $this->notes,
            'Employee' => $this->Employee,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
