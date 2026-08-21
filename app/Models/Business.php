<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_name',
        'trade_license',
        'vat_number',
        'business_type',
        'address',
        'city',
        'country',
        'status',
        'rejection_reason',
        'credit_limit',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
