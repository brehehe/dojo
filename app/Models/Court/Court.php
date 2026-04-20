<?php

namespace App\Models\Court;

use App\Models\DrawingMatchNumber;
use App\Models\MatchNumber\MatchNumber;
use App\Models\Registration;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Court extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'order',
        'active_match_id',
        'active_registration_id',
        'active_bracket_node',
        'active_drawing_id',
    ];

    public function activeMatch()
    {
        return $this->belongsTo(MatchNumber::class, 'active_match_id');
    }

    public function activeRegistration()
    {
        return $this->belongsTo(Registration::class, 'active_registration_id');
    }

    /**
     * The specific DrawingMatchNumber slot currently active on this court.
     * Provides full context: pool, session_time, rundown, registration, contingent, draft_type.
     */
    public function activeDrawing()
    {
        return $this->belongsTo(DrawingMatchNumber::class, 'active_drawing_id');
    }
}
