<?php

namespace Tests\Feature;

use App\Models\B2BPriceTier;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class B2BPricingTest extends TestCase
{
    use RefreshDatabase;

    public function test_b2b_quantity_pricing_calculates_tier_discounts()
    {
        $category = Category::create(['name' => 'Tech', 'slug' => 'tech']);

        $product = Product::create([
            'name' => 'Bulk SSD 1TB',
            'slug' => 'bulk-ssd-1tb',
            'sku' => 'SSD-100',
            'price' => 10000.00,
            'b2b_price' => 8500.00,
            'stock' => 500,
            'moq' => 5,
            'category_id' => $category->id,
            'status' => 'active',
        ]);

        B2BPriceTier::create(['product_id' => $product->id, 'min_quantity' => 1, 'max_quantity' => 9, 'unit_price' => 8500.00]);
        B2BPriceTier::create(['product_id' => $product->id, 'min_quantity' => 10, 'max_quantity' => 49, 'unit_price' => 7500.00]);
        B2BPriceTier::create(['product_id' => $product->id, 'min_quantity' => 50, 'max_quantity' => null, 'unit_price' => 6500.00]);

        $this->assertEquals(8500.00, $product->getB2BUnitPrice(5));
        $this->assertEquals(7500.00, $product->getB2BUnitPrice(20));
        $this->assertEquals(6500.00, $product->getB2BUnitPrice(100));
    }
}
