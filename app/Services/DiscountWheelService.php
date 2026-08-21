<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\DiscountWheelSpin;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class DiscountWheelService
{
    /**
     * Segments configuration
     */
    protected array $segments = [
        ['label' => '5% OFF', 'type' => 'percentage', 'value' => 5, 'probability' => 30],
        ['label' => '10% OFF', 'type' => 'percentage', 'value' => 10, 'probability' => 25],
        ['label' => '15% OFF', 'type' => 'percentage', 'value' => 15, 'probability' => 15],
        ['label' => '20% OFF', 'type' => 'percentage', 'value' => 20, 'probability' => 5],
        ['label' => 'Free Shipping', 'type' => 'free_shipping', 'value' => 100, 'probability' => 10],
        ['label' => '৳100 OFF', 'type' => 'fixed', 'value' => 100, 'probability' => 10],
        ['label' => '৳200 OFF', 'type' => 'fixed', 'value' => 200, 'probability' => 3],
        ['label' => 'Better Luck Next Time', 'type' => 'none', 'value' => 0, 'probability' => 2],
    ];

    /**
     * Validate spin eligibility server-side and calculate anti-abuse reward
     */
    public function spin(?int $userId, string $ipAddress): array
    {
        // Rate limiting anti-abuse check: 1 spin per 24 hours per user/IP
        $recentSpin = DiscountWheelSpin::where('ip_address', $ipAddress)
            ->when($userId, fn($q) => $q->orWhere('user_id', $userId))
            ->where('created_at', '>=', Carbon::now()->subHours(24))
            ->first();

        if ($recentSpin) {
            return [
                'success' => false,
                'message' => 'You have already spun the wheel today. Please come back in 24 hours!',
                'already_spun' => true,
                'reward' => [
                    'label' => $recentSpin->reward_label,
                    'coupon_code' => $recentSpin->coupon_code,
                ]
            ];
        }

        // Weighted random selector
        $winningSegment = $this->selectWeightedSegment();

        $couponCode = null;
        if ($winningSegment['type'] !== 'none') {
            $couponCode = 'WHEEL-' . Str::upper(Str::random(6));

            Coupon::create([
                'code' => $couponCode,
                'type' => $winningSegment['type'],
                'value' => $winningSegment['value'],
                'min_order' => 500.00,
                'max_discount' => 500.00,
                'usage_limit' => 1,
                'times_used' => 0,
                'expires_at' => Carbon::now()->addDays(7),
                'target_type' => 'b2c',
                'is_active' => true,
            ]);
        }

        $spinRecord = DiscountWheelSpin::create([
            'user_id' => $userId,
            'ip_address' => $ipAddress,
            'reward_label' => $winningSegment['label'],
            'reward_type' => $winningSegment['type'],
            'reward_value' => $winningSegment['value'],
            'coupon_code' => $couponCode,
            'expires_at' => Carbon::now()->addDays(7),
        ]);

        // Return segment index for UI canvas animation alignment
        $index = array_search($winningSegment, $this->segments);

        return [
            'success' => true,
            'message' => 'Spin successful!',
            'segment_index' => $index,
            'reward' => [
                'label' => $winningSegment['label'],
                'type' => $winningSegment['type'],
                'value' => $winningSegment['value'],
                'coupon_code' => $couponCode,
            ]
        ];
    }

    protected function selectWeightedSegment(): array
    {
        $rand = rand(1, 100);
        $cum = 0;
        foreach ($this->segments as $segment) {
            $cum += $segment['probability'];
            if ($rand <= $cum) {
                return $segment;
            }
        }
        return $this->segments[0];
    }

    public function getSegments(): array
    {
        return array_map(fn($s) => ['label' => $s['label']], $this->segments);
    }
}
