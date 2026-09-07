<?php

namespace App\Http\Requests\Monitor;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SaveRefereeAssignmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'court_id' => 'required',
            'rundown_id' => 'required',
            'session_time_id' => 'required',
            'referees' => 'required|array|min:5|max:5',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'referees.min' => 'Wajib memilih 5 wasit untuk 5 posisi juri',
            'referees.max' => 'Wajib memilih 5 wasit untuk 5 posisi juri',
        ];
    }
}
