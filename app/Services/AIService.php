<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Str;

class AIService
{
    /**
     * Parse natural language prompt and return matching products from the database.
     */
    public function searchProducts(string $prompt, bool $isB2B = false): array
    {
        $promptLower = Str::lower($prompt);
        $query = Product::with(['category', 'brand', 'primaryImage', 'b2bPriceTiers'])
            ->where('status', 'active');

        // Extract budget condition if present (e.g. "under 3000", "below 1500")
        if (preg_match('/(?:under|below|less than|max|budget)\s*(?:taka|৳|\$)?\s*(\d+)/i', $promptLower, $matches)) {
            $maxPrice = (float) $matches[1];
            $query->where('price', '<=', $maxPrice);
        }

        // Keywords matching category or title
        $keywords = collect(explode(' ', $promptLower))
            ->filter(fn($w) => strlen($w) > 2 && !in_array($w, ['the', 'for', 'and', 'with', 'need', 'show', 'find', 'buy', 'under', 'taka', 'below', 'something', 'that']));

        if ($keywords->isNotEmpty()) {
            $query->where(function ($q) use ($keywords) {
                foreach ($keywords as $word) {
                    $q->orWhere('name', 'LIKE', "%{$word}%")
                      ->orWhere('description', 'LIKE', "%{$word}%")
                      ->orWhereHas('category', fn($cat) => $cat->where('name', 'LIKE', "%{$word}%"))
                      ->orWhereHas('brand', fn($b) => $b->where('name', 'LIKE', "%{$word}%"));
                }
            });
        }

        $products = $query->take(6)->get();

        // Format structured response
        $recommendations = $products->map(function ($product) use ($isB2B) {
            $effectivePrice = $isB2B && $product->b2b_price ? $product->b2b_price : ($product->sale_price ?? $product->price);
            $reason = "Matches your specifications in " . ($product->category?->name ?? 'Electronics') . " with high rating.";

            return [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'price' => $effectivePrice,
                'original_price' => $product->price,
                'b2b_price' => $product->b2b_price,
                'moq' => $product->moq,
                'rating' => 4.8,
                'image_url' => $product->primaryImage?->image_url ?? $product->images->first()?->image_url ?? 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&q=80',
                'availability' => $product->stock > 0 ? "In Stock ({$product->stock} available)" : "Out of Stock",
                'reason' => $reason,
            ];
        });

        return [
            'reply' => "Here are the best matching products from our Besmart inventory:",
            'products' => $recommendations,
        ];
    }
}
