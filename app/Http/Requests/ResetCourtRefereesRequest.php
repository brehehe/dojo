<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ResetCourtRefereesRequest extends FormRequest
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
            'court_id' => 'required|exists:courts,id',
            'rundown_id' => 'required|exists:rundowns,id',
            'session_time_id' => 'required|exists:session_times,id',
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
            'court_id' => 'Lapangan',
            'rundown_id' => 'Rundown',
            'session_time_id' => 'Sesi',
        ];
    }
}
