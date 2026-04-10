<?php

namespace App\Models\MatchNumber;

use App\Models\Group\AgeGroup;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchNumber extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'name',
        'order',
    ];

    public function ageGroup()
    {
        return $this->belongsTo(AgeGroup::class);
    }
}
