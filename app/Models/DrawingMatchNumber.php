<?php

namespace App\Models;

use App\Models\Court\Court;
use App\Models\MatchNumber\MatchNumber;
use App\Models\Pool\Pool;
use App\Models\Rundown\Rundown;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DrawingMatchNumber extends Model
{
    use HasFactory;

    protected $fillable = [
        'match_number_id',
        'registration_id',
        'pool_id',
        'court_id',
        'schedule_date',
        'rundown_id',
        'session_time_id',
        'round',
        'sequence_number',
        'draft_type',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    /**
     * Get the match number that owns the drawing.
     */
    public function matchNumber(): BelongsTo
    {
        return $this->belongsTo(MatchNumber::class);
    }

    /**
     * Get the registration associated with the drawing.
     */
    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }

    /**
     * Get the pool assigned to this drawing.
     */
    public function pool(): BelongsTo
    {
        return $this->belongsTo(Pool::class);
    }

    /**
     * Get the court assigned to this drawing.
     */
    public function court(): BelongsTo
    {
        return $this->belongsTo(Court::class);
    }

    /**
     * Get the rundown (session) assigned to this drawing.
     */
    public function rundown(): BelongsTo
    {
        return $this->belongsTo(Rundown::class);
    }

    /**
     * Get the session time assigned to this drawing.
     */
    public function sessionTime(): BelongsTo
    {
        return $this->belongsTo(SessionTime::class);
    }

    public function merge(): BelongsTo
    {
        return $this->belongsTo(MatchNumberMerge::class, 'metadata->merge_id');
    }
}
