<?php

namespace App\Models;

use App\Models\MatchNumber\MatchNumber;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefereeScoreDetail extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'match_number_id',
        'referee_id',
        'judge_index',
        'scorable_type',
        'scorable_id',
        'details',
        'total_calculated_score',
        'notes',
        'signature',
    ];

    protected $casts = [
        'details' => 'array',
        'total_calculated_score' => 'decimal:2',
    ];

    public function matchNumber()
    {
        return $this->belongsTo(MatchNumber::class);
    }

    public function referee()
    {
        return $this->belongsTo(Referee::class);
    }

    public function scorable()
    {
        return $this->morphTo();
    }
}
