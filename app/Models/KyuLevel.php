<?php

namespace App\Models;

use App\Models\Technique\Technique;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KyuLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'color',
        'order',
    ];

    public function techniques(): HasMany
    {
        return $this->hasMany(Technique::class);
    }
}
