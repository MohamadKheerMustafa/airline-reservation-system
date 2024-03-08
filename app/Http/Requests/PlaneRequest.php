<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlaneRequest extends FormRequest
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
            'name' => 'required|unique:planes,name',
            'code' => 'required|unique:planes,code',
            'capacity' => 'required',
            'airline_id' => 'required|exists:airlines,id',
            'manufacturer' => 'required',
            'year_manufactured' => 'required|integer',
        ];
    }
}
