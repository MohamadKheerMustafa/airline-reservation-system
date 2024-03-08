<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CrewMemberRequest extends FormRequest
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
            'crews_id' => 'required|exists:crews,id',
            'first_name' => 'required',
            'last_name' => 'required',
            'position' => 'required',
            'date_of_birth' => 'required|date',
            'nationality' => 'required',
        ];
    }
}
