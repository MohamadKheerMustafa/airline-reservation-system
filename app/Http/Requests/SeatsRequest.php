<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SeatsRequest extends FormRequest
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
        $array = [
            'economy',
            'VIP',
            'business'
        ];
        return [
            'flight_id' => 'required|exists:flights,id',
            'class' => 'required',
            'availability' => 'required|in:0,1',
        ];
    }
}
