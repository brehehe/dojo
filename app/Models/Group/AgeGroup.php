<?php

namespace App\Models\Group;

use App\Models\MatchNumber\MatchNumber;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgeGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'order',
        'price',
    ];

    public function matchNumbers()
    {
        return $this->hasMany(MatchNumber::class);
    }
}
