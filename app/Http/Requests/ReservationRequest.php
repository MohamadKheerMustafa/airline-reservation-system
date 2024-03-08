<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class ReservationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'flight_id' => 'required|exists:flights,id',
            // 'reservation_date' => 'required|date|after_or_equal:' . Carbon::now()->toDateString(),
            'seat_preference' => 'nullable',
            'special_requests' => 'nullable',
            'addtionalNotes' => 'nullable'
        ];
    }
}
