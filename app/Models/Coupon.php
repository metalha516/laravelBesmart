<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'value',
        'min_order',
        'max_discount',
        'usage_limit',
        'times_used',
        'expires_at',
        'target_type',
        'is_active',
    ];

    protected $casts = [
        'value' => 'float',
        'min_order' => 'float',
        'max_discount' => 'float',
        'is_active' => 'boolean',
        'expires_at' => 'datetime',
    ];
}
