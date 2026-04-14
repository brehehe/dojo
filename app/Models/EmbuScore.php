<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmbuScore extends Model
{
    //
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
    ];

    public function matchNumber()
    {
        return $this->belongsTo(MatchNumber\MatchNumber::class);
    }

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }
}
