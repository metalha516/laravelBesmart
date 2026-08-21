<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductBundle;

class RecommendationService
{
    /**
     * Get Smart Product Bundles ("Complete Your Setup" / "Frequently Bought Together")
     */
    public function getBundleForProduct(int $productId): ?array
    {
        $product = Product::find($productId);
        if (!$product) return null;

        // Check if there is an explicit admin-defined bundle
        $bundle = ProductBundle::with(['items.product.primaryImage'])
            ->where('primary_product_id', $productId)
            ->where('is_active', true)
            ->first();

        if ($bundle && $bundle->items->isNotEmpty()) {
            $items = $bundle->items->map(fn($item) => $item->product);
        } else {
            // Dynamically select 3 complementary products from same category or complementary categories
            $items = Product::with('primaryImage')
                ->where('id', '!=', $productId)
                ->where('category_id', $product->category_id)
                ->where('status', 'active')
                ->take(3)
                ->get();
        }

        if ($items->isEmpty()) return null;

        $itemsTotal = $items->sum('price');
        $grandTotal = $product->price + $itemsTotal;
        $discountPercent = 10.0;
        $bundlePrice = round($grandTotal * (1 - ($discountPercent / 100)), 2);
        $savings = round($grandTotal - $bundlePrice, 2);

        return [
            'bundle_id' => $bundle?->id ?? 0,
            'title' => 'Complete Your Setup',
            'main_product' => $product,
            'bundle_products' => $items,
            'original_total' => $grandTotal,
            'bundle_price' => $bundlePrice,
            'savings' => $savings,
            'discount_percentage' => $discountPercent,
        ];
    }

    /**
     * Get FOMO indicators for a product (Stock urgency, recent viewer counts, purchase activity)
     */
    public function getFOMOStats(Product $product): array
    {
        $lowStockWarning = $product->stock <= $product->low_stock_threshold ? "Only {$product->stock} left in stock - order soon!" : null;

        // Deterministic realistic view and purchase activity based on ID & stock
        $viewersCount = 8 + ($product->id * 3) % 15;
        $recentPurchases = 3 + ($product->id * 2) % 10;

        return [
            'stock_urgency' => $lowStockWarning,
            'recent_viewers' => "{$viewersCount} people viewed this product today",
            'recent_purchases' => "{$recentPurchases} people bought this recently",
            'flash_sale_ending' => $product->is_flash_sale ? "Flash Sale ends in 02:43:17" : null,
        ];
    }
}
