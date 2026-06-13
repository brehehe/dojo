<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class EmbuSaveScoreRequest extends FormRequest
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
            'registration_id' => 'required|exists:registrations,id',
            'round' => 'string|in:Penyisihan,Final',
            'scores' => 'required|array',
            'scores.judge_1' => 'numeric|min:0|max:10',
            'scores.judge_2' => 'numeric|min:0|max:10',
            'scores.judge_3' => 'numeric|min:0|max:10',
            'scores.judge_4' => 'numeric|min:0|max:10',
            'scores.judge_5' => 'numeric|min:0|max:10',
            'denda' => 'numeric|min:0',
            'drawing_id' => 'nullable|exists:drawing_match_numbers,id',
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
            'registration_id' => 'Peserta',
            'round' => 'Babak',
            'scores' => 'Nilai',
            'scores.judge_1' => 'Nilai Juri 1',
            'scores.judge_2' => 'Nilai Juri 2',
            'scores.judge_3' => 'Nilai Juri 3',
            'scores.judge_4' => 'Nilai Juri 4',
            'scores.judge_5' => 'Nilai Juri 5',
            'denda' => 'Denda',
            'drawing_id' => 'Drawing',
        ];
    }
}
