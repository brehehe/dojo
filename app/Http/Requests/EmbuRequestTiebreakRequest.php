<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class EmbuRequestTiebreakRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'match_id' => 'required|exists:match_numbers,id',
            'registration_ids' => 'required|array',
            'registration_ids.*' => 'exists:registrations,id',
            'round' => 'string',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'match_id' => 'Pertandingan',
            'registration_ids' => 'Peserta',
            'registration_ids.*' => 'Peserta',
            'round' => 'Babak',
        ];
    }
}
