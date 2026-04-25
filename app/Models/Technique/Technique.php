<?php

namespace App\Models\Technique;

use App\Models\KyuLevel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Technique extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'order',
        'kyu_level_id',
        'description',
    ];

    public function kyuLevel(): BelongsTo
    {
        return $this->belongsTo(KyuLevel::class);
    }
}
