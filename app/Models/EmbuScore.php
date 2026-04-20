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

    /** Get the effective score for ranking (nilai_akhir if set, else total_score). */
    public function getEffectiveScoreAttribute(): float
    {
        return $this->nilai_akhir > 0 ? $this->nilai_akhir : $this->total_score;
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
