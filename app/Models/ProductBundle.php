<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductBundle extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'primary_product_id', 'discount_percentage', 'is_active'];

    protected $casts = [
        'discount_percentage' => 'float',
        'is_active' => 'boolean',
    ];

    public function primaryProduct()
    {
        return $this->belongsTo(Product::class, 'primary_product_id');
    }

    public function items()
    {
        return $this->hasMany(ProductBundleItem::class, 'bundle_id');
    }
}
