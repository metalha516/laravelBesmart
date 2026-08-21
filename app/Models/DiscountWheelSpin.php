<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountWheelSpin extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ip_address',
        'reward_label',
        'reward_type',
        'reward_value',
        'coupon_code',
        'is_used',
        'expires_at',
    ];

    protected $casts = [
        'reward_value' => 'float',
        'is_used' => 'boolean',
        'expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
