<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contingent extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'leader_name',
        'leader_phone',
        'email',
        'address',
        'total_cost',
        'status',
        'referral_code',
        'payment_method',
        'unique_code',
        'final_amount',
        'kab_kota',
        'transfer_proof_path',
        'sim_perkemi_confirm',
    ];

    public function athletes()
    {
        return $this->hasMany(Athlete::class);
    }

    public function officials()
    {
        return $this->hasMany(Official::class);
    }
}
