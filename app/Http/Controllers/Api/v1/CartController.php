<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected function getOrCreateCart(Request $request): Cart
    {
        $userId = $request->user()?->id;
        $sessionId = $request->header('X-Session-ID') ?: $request->header('X-Session-Id');

        if (!$userId && !$sessionId) {
            $sessionId = 'session_' . md5($request->ip() . ($request->userAgent() ?? 'guest'));
        }

        if ($userId) {
            $cart = Cart::firstOrCreate(['user_id' => $userId]);
        } else {
            $cart = Cart::firstOrCreate(['session_id' => $sessionId]);
        }

        return $cart;
    }

    public function getCart(Request $request)
    {
        $cart = $this->getOrCreateCart($request)->load(['items.product.primaryImage', 'items.product.b2bPriceTiers']);
        $user = $request->user();
        $isB2B = $user && $user->isB2B();

        $subtotal = 0;
        $formattedItems = [];

        foreach ($cart->items as $item) {
            $product = $item->product;
            if (!$product) continue;

            if ($isB2B) {
                $unitPrice = $product->getB2BUnitPrice($item->quantity);
            } else {
                $unitPrice = (float) ($product->sale_price ?? $product->price);
            }

            $itemSubtotal = round($unitPrice * $item->quantity, 2);
            $subtotal += $itemSubtotal;

            $formattedItems[] = [
                'id' => $item->id,
                'product_id' => $product->id,
                'name' => $product->name,
                'image_url' => $product->primaryImage?->image_url ?? 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&q=80',
                'sku' => $product->sku,
                'moq' => $product->moq,
                'quantity' => $item->quantity,
                'unit_price' => $unitPrice,
                'subtotal' => $itemSubtotal,
            ];
        }

        $discount = 0;
        if ($cart->coupon_code) {
            $coupon = Coupon::where('code', $cart->coupon_code)->where('is_active', true)->first();
            if ($coupon) {
                if ($coupon->type === 'percentage') {
                    $discount = round(($subtotal * $coupon->value) / 100, 2);
                } elseif ($coupon->type === 'fixed') {
                    $discount = (float) $coupon->value;
                }
            }
        }

        $shipping = $subtotal > 2000 || $subtotal == 0 ? 0 : 120;
        $total = max(0, round($subtotal - $discount + $shipping, 2));

        return response()->json([
            'success' => true,
            'data' => [
                'cart_id' => $cart->id,
                'items' => $formattedItems,
                'subtotal' => round($subtotal, 2),
                'discount' => round($discount, 2),
                'coupon_code' => $cart->coupon_code,
                'shipping' => $shipping,
                'total' => $total,
            ]
        ]);
    }

    public function addItem(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = $this->getOrCreateCart($request);
        $product = Product::findOrFail($validated['product_id']);

        $user = $request->user();
        if ($user && $user->isB2B() && $validated['quantity'] < $product->moq) {
            return response()->json([
                'success' => false,
                'message' => "B2B Minimum Order Quantity for {$product->name} is {$product->moq} units.",
            ], 422);
        }

        $existingItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->first();

        if ($existingItem) {
            $existingItem->quantity += $validated['quantity'];
            $existingItem->save();
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $validated['quantity'],
                'unit_price' => $product->price,
            ]);
        }

        return $this->getCart($request);
    }

    public function updateItem(Request $request, $itemId)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $item = CartItem::with('product')->findOrFail($itemId);
        $item->quantity = $validated['quantity'];
        $item->save();

        return $this->getCart($request);
    }

    public function removeItem(Request $request, $itemId)
    {
        CartItem::destroy($itemId);
        return $this->getCart($request);
    }

    public function applyCoupon(Request $request)
    {
        $validated = $request->validate(['code' => 'required|string']);

        $coupon = Coupon::where('code', $validated['code'])->where('is_active', true)->first();
        if (!$coupon) {
            return response()->json(['success' => false, 'message' => 'Invalid or expired coupon code.'], 422);
        }

        $cart = $this->getOrCreateCart($request);
        $cart->coupon_code = $coupon->code;
        $cart->save();

        return $this->getCart($request);
    }
}
