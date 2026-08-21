<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Order;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalRevenue = (float) Order::where('payment_status', 'paid')->sum('total_amount');
        $totalOrders = Order::count();
        $totalCustomers = User::where('role', 'b2c')->count();
        $totalB2b = User::where('role', 'b2b')->count();
        $totalProducts = Product::count();
        $pendingB2b = Business::where('status', 'pending')->count();
        $lowStockProducts = Product::whereColumn('stock', '<=', 'low_stock_threshold')->get();
        $recentOrders = Order::with('user')->orderBy('id', 'desc')->take(6)->get();

        return response()->json([
            'success' => true,
            'data' => [
                'stats' => [
                    'total_revenue' => $totalRevenue,
                    'total_orders' => $totalOrders,
                    'total_customers' => $totalCustomers,
                    'total_b2b_users' => $totalB2b,
                    'total_products' => $totalProducts,
                    'pending_b2b_verifications' => $pendingB2b,
                ],
                'low_stock_products' => $lowStockProducts,
                'recent_orders' => $recentOrders,
            ]
        ]);
    }

    public function b2bRequests()
    {
        $businesses = Business::with('user')->orderBy('id', 'desc')->get();
        return response()->json(['success' => true, 'data' => $businesses]);
    }

    public function approveB2B($id)
    {
        $business = Business::findOrFail($id);
        $business->status = 'approved';
        $business->save();

        return response()->json(['success' => true, 'message' => 'B2B Business profile approved.']);
    }

    public function rejectB2B(Request $request, $id)
    {
        $business = Business::findOrFail($id);
        $business->status = 'rejected';
        $business->rejection_reason = $request->get('reason', 'Documents unverified');
        $business->save();

        return response()->json(['success' => true, 'message' => 'B2B Business profile rejected.']);
    }

    public function updateOrderStatus(Request $request, $orderId)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,out_for_delivery,delivered,cancelled,refunded',
        ]);

        $order = Order::findOrFail($orderId);
        $order->status = $validated['status'];
        if ($validated['status'] === 'delivered') {
            $order->payment_status = 'paid';
        }
        $order->save();

        return response()->json(['success' => true, 'message' => "Order status updated to {$order->status}."]);
    }

    public function updateSettings(Request $request)
    {
        $settings = $request->all();
        foreach ($settings as $key => $val) {
            Setting::set($key, is_array($val) ? json_encode($val) : (string) $val);
        }

        return response()->json(['success' => true, 'message' => 'Settings updated successfully.']);
    }
}
