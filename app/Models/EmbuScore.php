<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmbuScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'match_number_id',
        'registration_id',
        'drawing_id',
        'judge_1',
        'judge_2',
        'judge_3',
        'judge_4',
        'judge_5',
        'total_score',
        'rank',
        'tiebreak_round',
        'denda',
        'nilai_akhir',
        'round_label',
    ];

    protected $casts = [
        'judge_1' => 'float',
        'judge_2' => 'float',
        'judge_3' => 'float',
        'judge_4' => 'float',
        'judge_5' => 'float',
        'total_score' => 'float',
        'denda' => 'float',
        'nilai_akhir' => 'float',
        'tiebreak_round' => 'integer',
    ];

    /** Get the effective score for ranking (nilai_akhir if set, else total_score, computed on-the-fly if needed). */
    public function getEffectiveScoreAttribute(): float
    {
        if ($this->nilai_akhir > 0) {
            return $this->nilai_akhir;
        }

        if ($this->total_score > 0) {
            return max(0.0, $this->total_score - (float) $this->denda);
        }

        $rawVals = [
            (float) $this->judge_1,
            (float) $this->judge_2,
            (float) $this->judge_3,
            (float) $this->judge_4,
            (float) $this->judge_5,
        ];

        $scoredCount = count(array_filter($rawVals, fn ($v) => $v > 0));
        if ($scoredCount === 5) {
            sort($rawVals);
            $total = $rawVals[1] + $rawVals[2] + $rawVals[3];
        } else {
            $total = array_sum($rawVals);
        }

        return max(0.0, $total - (float) $this->denda);
    }

    public function matchNumber()
    {
        return $this->belongsTo(MatchNumber\MatchNumber::class);
    }

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }
}
