<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RandoriMatchResult extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'match_number_id',
        'bracket_node_index',
        'bracket_node',
        'bracket_section',
        'winner_athlete_id',
        'winner_color',
        'score_red',
        'score_blue',
        'metadata',
    ];

    public function matchNumber()
    {
        return $this->belongsTo(MatchNumber\MatchNumber::class);
    }

    public function winner()
    {
        return $this->belongsTo(Athlete::class, 'winner_athlete_id');
    }
}
