<?php

namespace App\Models;

use App\Models\MatchNumber\MatchNumber;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TournamentResult extends Model
{
    protected $fillable = [
        'match_number_id',
        'draft_type',
        'rank',
        'registration_id',
        'athlete_names',
        'contingent_name',
        'penyisihan_score',
        'final_score',
        'accumulated_score',
        'bracket_section',
        'generated_by',
        'confirmed_at',
    ];

    protected $casts = [
        'confirmed_at' => 'datetime',
        'penyisihan_score' => 'decimal:2',
        'final_score' => 'decimal:2',
        'accumulated_score' => 'decimal:2',
    ];

    public function matchNumber(): BelongsTo
    {
        return $this->belongsTo(MatchNumber::class);
    }

    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }

    public function getRankLabelAttribute(): string
    {
        if ($this->rank == 3 || $this->rank == 4) {
            $count = static::where('match_number_id', $this->match_number_id)->count();
            if ($count === 3) {
                return 'Juara 3 Bersama';
            } elseif ($count >= 4) {
                return $this->rank == 3 ? 'Juara 3 Bersama 1' : 'Juara 3 Bersama 2';
            }
        }

        return match ($this->rank) {
            1 => 'Juara 1',
            2 => 'Juara 2',
            3 => 'Juara 3',
            4 => 'Juara 3 Bersama',
            default => 'Peringkat '.$this->rank,
        };
    }
}
