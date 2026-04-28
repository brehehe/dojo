<?php

namespace App\Models\PaymentMethod;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'name',
        'account_number',
        'bank',
        'logo',
        'order',
        'is_active',
    ];
}
