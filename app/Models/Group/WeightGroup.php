<?php

namespace App\Models\Group;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeightGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'order',
    ];
}
