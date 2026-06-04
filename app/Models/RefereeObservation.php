<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RefereeObservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'contingent_id',
        'referee_id',
        'observer_name',
        'observation_date',
        'court',
        'round',
        'match_time',
        'referee_number',
        'contingent_away',
        'contingent_home',
        'total_score',
        'category',
        'kepada',
        'dari',
        'tanggal_laporan',
        'kelebihan',
        'area_perbaikan',
        'rekomendasi',
        'data',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'observation_date' => 'date',
            'tanggal_laporan' => 'date',
            'data' => 'array',
        ];
    }

    /**
     * Get the contingent that owns the observation.
     */
    public function contingent(): BelongsTo
    {
        return $this->belongsTo(Contingent::class);
    }

    /**
     * Get the referee that is being observed.
     */
    public function referee(): BelongsTo
    {
        return $this->belongsTo(Referee::class);
    }
}
