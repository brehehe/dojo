<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RandoriSubmitScoringRequest extends FormRequest
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
            'bracket' => 'required|string|in:ub,lb,gf',
            'round' => 'required|integer|min:0',
            'match' => 'required|integer|min:0',
            'score_red' => 'required|integer|min:0',
            'score_blue' => 'required|integer|min:0',
            'scoring_aka' => 'nullable',
            'scoring_shiro' => 'nullable',
            'signatures' => 'required|array',
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
            'bracket' => 'Bracket',
            'round' => 'Ronde',
            'match' => 'Pertandingan',
            'score_red' => 'Skor Merah',
            'score_blue' => 'Skor Putih',
            'scoring_aka' => 'Scoring Aka',
            'scoring_shiro' => 'Scoring Shiro',
            'signatures' => 'Tanda Tangan',
        ];
    }
}
