<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class FlightRequest extends FormRequest
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
            'airline_id' => 'required|exists:airlines,id',
            'plane_id' => 'required|exists:planes,id',
            'origin_id' => 'required|exists:airports,id',
            'destination_id' => 'required|exists:airports,id',
            'departure' => 'required|date_format:Y-m-d H:i:s|after_or_equal:' . Carbon::now(),
            'arrival' => 'required|date_format:Y-m-d H:i:s|after_or_equal:' . Carbon::now(),
            'price' => 'required',
            'gate_number' => 'required|unique:flights,gate_number',
            'crew_id' => 'required|exists:crews,id',
        ];
    }
}
