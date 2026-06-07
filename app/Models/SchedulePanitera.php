<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SchedulePanitera extends Model
{
    use HasFactory;

    protected $fillable = [
        'rundown_id',
        'session_time_id',
        'court_id',
        'user_id',
        'role_type',
        'slot_index',
    ];

    /**
     * Get the user assigned as officer.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the rundown associated with the assignment.
     */
    public function rundown(): BelongsTo
    {
        return $this->belongsTo(Rundown\Rundown::class);
    }

    /**
     * Get the session time associated with the assignment.
     */
    public function sessionTime(): BelongsTo
    {
        return $this->belongsTo(SessionTime::class);
    }

    /**
     * Get the court associated with the assignment.
     */
    public function court(): BelongsTo
    {
        return $this->belongsTo(Court\Court::class);
    }
}
