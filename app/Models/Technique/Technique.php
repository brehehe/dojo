<?php

namespace App\Models\Technique;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Technique extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'name',
        'order',
    ];
}
