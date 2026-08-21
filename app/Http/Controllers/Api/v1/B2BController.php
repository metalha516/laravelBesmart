<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\ImportCalculation;
use App\Models\Order;
use App\Services\B2BAnalyticsService;
use App\Services\ChinaImportCalculatorService;
use Illuminate\Http\Request;

class B2BController extends Controller
{
    public function dashboard(Request $request, B2BAnalyticsService $analyticsService)
    {
        $user = $request->user();
        if (!$user->isB2B() && !$user->isAdmin()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized B2B area'], 403);
        }

        $business = Business::where('user_id', $user->id)->first();
        $range = $request->get('range', '10y');
        $analytics = $analyticsService->getAnalytics($user->id, $range);

        $recentOrders = Order::with('items')
            ->where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'business' => $business,
                'analytics' => $analytics,
                'recent_orders' => $recentOrders,
            ]
        ]);
    }

    public function calculateImport(Request $request, ChinaImportCalculatorService $calculatorService)
    {
        $validated = $request->validate([
            'product_name' => 'required|string',
            'unit_price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'weight_kg' => 'required|numeric|min:0.1',
            'shipping_method' => 'required|in:air,sea',
            'customs_duty_rate' => 'nullable|numeric',
            'vat_rate' => 'nullable|numeric',
            'desired_margin_rate' => 'nullable|numeric',
        ]);

        $result = $calculatorService->calculate($validated);

        if ($request->user()) {
            ImportCalculation::create([
                'user_id' => $request->user()->id,
                'product_name' => $validated['product_name'],
                'unit_price' => $validated['unit_price'],
                'quantity' => $validated['quantity'],
                'product_weight' => $validated['weight_kg'],
                'shipping_method' => $validated['shipping_method'],
                'freight_cost' => $result['breakdown']['shipping_cost_bdt'],
                'customs_duty' => $result['breakdown']['customs_duty_bdt'],
                'vat' => $result['breakdown']['vat_bdt'],
                'total_landed_cost' => $result['summary']['total_investment_bdt'],
                'cost_per_unit' => $result['summary']['cost_per_unit_bdt'],
                'target_price' => $result['summary']['suggested_selling_price_bdt'],
                'expected_profit' => $result['summary']['expected_profit_bdt'],
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $result,
        ]);
    }
}
