<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'name', 'sku', 'price', 'b2b_price', 'stock', 'attributes'];

    protected $casts = [
        'attributes' => 'array',
        'price' => 'float',
        'b2b_price' => 'float',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
