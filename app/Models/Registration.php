<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        'contingent_id',
        'total_cost',
        'final_amount',
        'unique_code',
        'payment_method',
        'referral_code',
        'status',
        'transfer_proof_path',
        'sim_perkemi_confirm',
    ];

    /**
     * Get the contingent that owns the registration.
     */
    public function contingent(): BelongsTo
    {
        return $this->belongsTo(Contingent::class);
    }

    /**
     * Get the athletes for the registration.
     */
    public function athletes(): BelongsToMany
    {
        return $this->belongsToMany(Athlete::class, 'registration_athlete')
            ->withPivot(['weight', 'kyu', 'age_group', 'rank', 'match_type', 'dojo_origin', 'city', 'age'])
            ->withTimestamps();
    }

    /**
     * Get the officials for the registration.
     */
    public function officials(): BelongsToMany
    {
        return $this->belongsToMany(Official::class, 'registration_official')
            ->withPivot(['role'])
            ->withTimestamps();
    }

    public function embuScores()
    {
        return $this->hasMany(EmbuScore::class);
    }
}
