<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function checkout(Request $request, PaymentService $paymentService)
    {
        $validated = $request->validate([
            'shipping_address' => 'required|array',
            'shipping_address.name' => 'required|string',
            'shipping_address.phone' => 'required|string',
            'shipping_address.address' => 'required|string',
            'shipping_address.city' => 'required|string',
            'payment_method' => 'required|string|in:cod,stripe,sslcommerz,bkash',
            'notes' => 'nullable|string',
        ]);

        $user = $request->user();
        $isB2B = $user && $user->isB2B();

        $cart = Cart::with(['items.product.b2bPriceTiers'])
            ->where('user_id', $user->id)
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Your cart is empty.'], 400);
        }

        return DB::transaction(function () use ($cart, $user, $isB2B, $validated, $paymentService) {
            $subtotal = 0;
            $orderItemsData = [];

            foreach ($cart->items as $item) {
                $product = Product::lockForUpdate()->find($item->product_id);

                if (!$product || $product->stock < $item->quantity) {
                    throw new \Exception("Insufficient stock for product {$product?->name}");
                }

                if ($isB2B) {
                    $unitPrice = $product->getB2BUnitPrice($item->quantity);
                } else {
                    $unitPrice = (float) ($product->sale_price ?? $product->price);
                }

                $itemSubtotal = round($unitPrice * $item->quantity, 2);
                $subtotal += $itemSubtotal;

                // Reserve stock
                $product->stock -= $item->quantity;
                $product->save();

                $orderItemsData[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'sku' => $product->sku,
                    'unit_price' => $unitPrice,
                    'quantity' => $item->quantity,
                    'subtotal' => $itemSubtotal,
                ];
            }

            $discount = 0;
            if ($cart->coupon_code) {
                $coupon = \App\Models\Coupon::where('code', $cart->coupon_code)->first();
                if ($coupon) {
                    $discount = $coupon->type === 'percentage'
                        ? round(($subtotal * $coupon->value) / 100, 2)
                        : (float) $coupon->value;
                    $coupon->increment('times_used');
                }
            }

            $shipping = $subtotal > 2000 ? 0 : 120;
            $totalAmount = max(0, round($subtotal - $discount + $shipping, 2));

            $order = Order::create([
                'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                'user_id' => $user->id,
                'user_type' => $isB2B ? 'b2b' : 'b2c',
                'subtotal' => round($subtotal, 2),
                'discount_amount' => round($discount, 2),
                'shipping_cost' => $shipping,
                'tax_amount' => 0.00,
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => $validated['payment_method'],
                'shipping_address' => $validated['shipping_address'],
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($orderItemsData as $itemData) {
                $itemData['order_id'] = $order->id;
                OrderItem::create($itemData);
            }

            // Clear Cart
            $cart->items()->delete();
            $cart->coupon_code = null;
            $cart->save();

            // Process payment gateway
            $paymentResult = $paymentService->processPayment($order, $validated['payment_method']);

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'data' => [
                    'order' => $order->load('items'),
                    'payment' => $paymentResult,
                ]
            ], 201);
        });
    }
}
