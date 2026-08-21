<?php

namespace App\Services;

class ChinaImportCalculatorService
{
    /**
     * Compute full landed cost breakdown for bulk imports from China
     */
    public function calculate(array $data): array
    {
        $unitPrice = (float) ($data['unit_price'] ?? 0);
        $quantity = (int) ($data['quantity'] ?? 1);
        $weightKg = (float) ($data['weight_kg'] ?? 0.5);
        $shippingMethod = $data['shipping_method'] ?? 'air'; // air or sea

        $exchangeRate = (float) ($data['exchange_rate'] ?? 120.0); // BDT per USD
        $dutyRate = (float) ($data['customs_duty_rate'] ?? 15.0) / 100; // %
        $vatRate = (float) ($data['vat_rate'] ?? 15.0) / 100; // %
        $otherCosts = (float) ($data['other_costs'] ?? 0);
        $desiredMarginRate = (float) ($data['desired_margin_rate'] ?? 30.0) / 100; // %

        $fobProductCostUsd = $unitPrice * $quantity;
        $fobProductCostBdt = $fobProductCostUsd * $exchangeRate;

        // Shipping calculation: Air = $8/kg, Sea = $2/kg
        $ratePerKg = $shippingMethod === 'sea' ? 2.5 : 8.5;
        $shippingCostUsd = $weightKg * $quantity * $ratePerKg;
        $shippingCostBdt = $shippingCostUsd * $exchangeRate;

        $cifBaseBdt = $fobProductCostBdt + $shippingCostBdt;

        $customsDutyBdt = $cifBaseBdt * $dutyRate;
        $vatBdt = ($cifBaseBdt + $customsDutyBdt) * $vatRate;

        $totalLandedCostBdt = $cifBaseBdt + $customsDutyBdt + $vatBdt + $otherCosts;
        $costPerUnitBdt = $quantity > 0 ? $totalLandedCostBdt / $quantity : 0;

        $suggestedSellingPriceBdt = $costPerUnitBdt * (1 + $desiredMarginRate);
        $expectedRevenueBdt = $suggestedSellingPriceBdt * $quantity;
        $expectedProfitBdt = $expectedRevenueBdt - $totalLandedCostBdt;

        return [
            'summary' => [
                'total_investment_bdt' => round($totalLandedCostBdt, 2),
                'cost_per_unit_bdt' => round($costPerUnitBdt, 2),
                'suggested_selling_price_bdt' => round($suggestedSellingPriceBdt, 2),
                'expected_revenue_bdt' => round($expectedRevenueBdt, 2),
                'expected_profit_bdt' => round($expectedProfitBdt, 2),
                'profit_margin_percent' => round($desiredMarginRate * 100, 2),
                'break_even_units' => ceil($totalLandedCostBdt / ($suggestedSellingPriceBdt > 0 ? $suggestedSellingPriceBdt : 1)),
            ],
            'breakdown' => [
                'fob_product_cost_bdt' => round($fobProductCostBdt, 2),
                'shipping_cost_bdt' => round($shippingCostBdt, 2),
                'customs_duty_bdt' => round($customsDutyBdt, 2),
                'vat_bdt' => round($vatBdt, 2),
                'other_costs_bdt' => round($otherCosts, 2),
            ]
        ];
    }
}
