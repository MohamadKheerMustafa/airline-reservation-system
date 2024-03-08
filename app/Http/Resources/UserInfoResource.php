<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserInfoResource extends JsonResource
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
            'is_admin' => $this->is_admin,
            'is_Employee' => $this->is_Employee,
            'name' => $this->name,
            'phone' => $this->phone,
            'address' => $this->address,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'tickets_count' => $this->tickets_count ? null : $this->tickets_count,
            'media' => $this->getFirstMediaUrl('Profiles')
        ];
    }
}
