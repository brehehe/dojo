<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'type',
        'gender',
        'age_group',
        'weight_class',
        'match_type',
    ];

    public function athletes()
    {
        return $this->belongsToMany(Athlete::class);
    }
}
