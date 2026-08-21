<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class B2BAnalyticsService
{
    /**
     * Compute comprehensive B2B BI Analytics over 1y, 3y, 5y, 10y or custom date range.
     */
    public function getAnalytics(int $userId, string $range = '1y', ?string $startDate = null, ?string $endDate = null): array
    {
        $query = Order::where('user_id', $userId)->where('user_type', 'b2b');

        $now = Carbon::now();
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } else {
            switch ($range) {
                case '1y':
                    $query->where('created_at', '>=', $now->copy()->subYear());
                    break;
                case '3y':
                    $query->where('created_at', '>=', $now->copy()->subYears(3));
                    break;
                case '5y':
                    $query->where('created_at', '>=', $now->copy()->subYears(5));
                    break;
                case '10y':
                default:
                    $query->where('created_at', '>=', $now->copy()->subYears(10));
                    break;
            }
        }

        $orders = (clone $query)->get();

        $totalSpending = (float) $orders->sum('total_amount');
        $totalOrders = $orders->count();
        $avgOrderValue = $totalOrders > 0 ? round($totalSpending / $totalOrders, 2) : 0;
        $totalSavings = (float) $orders->sum('discount_amount');
        $estimatedProfit = round($totalSpending * 0.28, 2); // 28% estimated markup profit margin

        // Historical spending trends by Year
        $spendingByYear = (clone $query)
            ->selectRaw("strftime('%Y', created_at) as year, SUM(total_amount) as total, COUNT(*) as order_count, SUM(discount_amount) as savings")
            ->groupBy('year')
            ->orderBy('year', 'asc')
            ->get();

        // Monthly trends for current year
        $spendingByMonth = (clone $query)
            ->selectRaw("strftime('%Y-%m', created_at) as month, SUM(total_amount) as total")
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        // Category breakdown
        $categoryBreakdown = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('orders.user_id', $userId)
            ->select('categories.name as category', DB::raw('SUM(order_items.subtotal) as total_spent'))
            ->groupBy('categories.name')
            ->orderBy('total_spent', 'desc')
            ->get();

        return [
            'kpis' => [
                'total_purchases' => $totalSpending,
                'total_spending' => $totalSpending,
                'total_orders' => $totalOrders,
                'average_order_value' => $avgOrderValue,
                'total_savings' => $totalSavings,
                'estimated_profit' => $estimatedProfit,
            ],
            'charts' => [
                'spending_by_year' => $spendingByYear,
                'spending_by_month' => $spendingByMonth,
                'category_breakdown' => $categoryBreakdown,
            ]
        ];
    }
}
