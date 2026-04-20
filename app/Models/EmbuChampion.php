<?php

namespace App\Models;

use App\Models\MatchNumber\MatchNumber;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmbuChampion extends Model
{
    protected $fillable = [
        'match_number_id',
        'registration_id',
        'rank',
        'penyisihan_score',
        'final_score',
        'accumulated_score',
    ];

    public function matchNumber(): BelongsTo
    {
        return $this->belongsTo(MatchNumber::class);
    }

    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }
}
