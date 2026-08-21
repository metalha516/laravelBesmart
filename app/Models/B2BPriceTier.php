<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class B2BPriceTier extends Model
{
    use HasFactory;

    protected $table = 'b2b_price_tiers';

    protected $fillable = ['product_id', 'min_quantity', 'max_quantity', 'unit_price'];

    protected $casts = [
        'unit_price' => 'float',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
