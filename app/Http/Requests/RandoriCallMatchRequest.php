<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RandoriCallMatchRequest extends FormRequest
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
            'node_key' => 'required|string',
            'round_idx' => 'required|integer|min:0',
            'match_idx' => 'required|integer|min:0',
            'bracket' => 'required|string|in:ub,lb,gf',
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
            'node_key' => 'Node Key',
            'round_idx' => 'Ronde',
            'match_idx' => 'Pertandingan',
            'bracket' => 'Bracket',
        ];
    }
}
