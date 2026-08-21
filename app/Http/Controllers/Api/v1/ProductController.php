<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Services\RecommendationService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand', 'primaryImage', 'b2bPriceTiers'])
            ->where('status', 'active');

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('sku', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'newest':
                default:
                    $query->orderBy('id', 'desc');
                    break;
            }
        } else {
            $query->orderBy('id', 'desc');
        }

        $products = $query->paginate($request->get('per_page', 12));

        return response()->json([
            'success' => true,
            'message' => 'Products retrieved successfully',
            'data' => $products,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'b2b_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'moq' => 'nullable|integer|min:1',
            'category_id' => 'nullable|integer',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'image_url' => 'nullable|string',
            'is_featured' => 'nullable|boolean',
            'is_flash_sale' => 'nullable|boolean',
        ]);

        $categoryId = $validated['category_id'] ?? null;
        if (!$categoryId) {
            $category = Category::firstOrCreate(
                ['slug' => 'general'],
                ['name' => 'General Electronics']
            );
            $categoryId = $category->id;
        }

        $slug = Str::slug($validated['name']) . '-' . Str::random(5);
        $sku = 'SKU-' . strtoupper(Str::random(8));

        $product = Product::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'sku' => $sku,
            'price' => $validated['price'],
            'sale_price' => $validated['sale_price'] ?? null,
            'b2b_price' => $validated['b2b_price'] ?? null,
            'stock' => $validated['stock'],
            'moq' => $validated['moq'] ?? 1,
            'category_id' => $categoryId,
            'short_description' => $validated['short_description'] ?? null,
            'description' => $validated['description'] ?? null,
            'status' => 'active',
            'is_featured' => $validated['is_featured'] ?? false,
            'is_flash_sale' => $validated['is_flash_sale'] ?? false,
        ]);

        $imageUrl = $validated['image_url'] ?? 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&q=80';
        ProductImage::create([
            'product_id' => $product->id,
            'image_url' => $imageUrl,
            'is_primary' => true,
            'sort_order' => 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully',
            'data' => $product->load(['category', 'primaryImage']),
        ], 201);
    }

    public function show($id, RecommendationService $recommendationService)
    {
        $product = Product::with(['category', 'brand', 'images', 'variants', 'b2bPriceTiers', 'reviews.user'])
            ->findOrFail($id);

        $product->increment('view_count');

        $bundle = $recommendationService->getBundleForProduct($product->id);
        $fomo = $recommendationService->getFOMOStats($product);

        return response()->json([
            'success' => true,
            'data' => [
                'product' => $product,
                'bundle' => $bundle,
                'fomo' => $fomo,
            ]
        ]);
    }

    public function featured()
    {
        $featured = Product::with(['primaryImage', 'category', 'b2bPriceTiers'])
            ->where('status', 'active')
            ->where('is_featured', true)
            ->take(8)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $featured,
        ]);
    }

    public function flashSales()
    {
        $flashSales = Product::with(['primaryImage', 'category'])
            ->where('status', 'active')
            ->where('is_flash_sale', true)
            ->take(6)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $flashSales,
        ]);
    }
}
