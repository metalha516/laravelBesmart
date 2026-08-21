<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'sku',
        'price',
        'b2b_price',
        'sale_price',
        'stock',
        'reserved_stock',
        'moq',
        'weight',
        'dimensions',
        'category_id',
        'brand_id',
        'short_description',
        'description',
        'specifications',
        'tags',
        'status',
        'is_featured',
        'is_flash_sale',
        'low_stock_threshold',
        'view_count',
    ];

    protected $casts = [
        'specifications' => 'array',
        'tags' => 'array',
        'price' => 'float',
        'b2b_price' => 'float',
        'sale_price' => 'float',
        'is_featured' => 'boolean',
        'is_flash_sale' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function b2bPriceTiers()
    {
        return $this->hasMany(B2BPriceTier::class)->orderBy('min_quantity');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Calculate dynamic B2B quantity unit price based on tiers
     */
    public function getB2BUnitPrice(int $quantity): float
    {
        $tiers = $this->b2bPriceTiers;
        foreach ($tiers as $tier) {
            if ($quantity >= $tier->min_quantity && ($tier->max_quantity === null || $quantity <= $tier->max_quantity)) {
                return (float) $tier->unit_price;
            }
        }
        return (float) ($this->b2b_price ?? $this->price);
    }
}
