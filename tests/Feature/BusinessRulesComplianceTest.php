<?php

namespace Tests\Feature;

use App\Models\B2BPriceTier;
use App\Models\Business;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\AIService;
use App\Services\B2BAnalyticsService;
use App\Services\ChinaImportCalculatorService;
use App\Services\DiscountWheelService;
use App\Services\PaymentService;
use App\Services\RecommendationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BusinessRulesComplianceTest extends TestCase
{
    use RefreshDatabase;

    /** Rule 1: B2C Normal Pricing vs B2B Volume Tier Pricing */
    public function test_rule_b2c_vs_b2b_pricing_tiers()
    {
        $category = Category::create(['name' => 'Laptops', 'slug' => 'laptops']);
        $product = Product::create([
            'name' => 'Business Laptop 15',
            'slug' => 'business-laptop-15',
            'sku' => 'LAP-15',
            'price' => 50000.00,
            'b2b_price' => 42000.00,
            'stock' => 100,
            'moq' => 5,
            'category_id' => $category->id,
            'status' => 'active',
        ]);

        B2BPriceTier::create(['product_id' => $product->id, 'min_quantity' => 1, 'max_quantity' => 9, 'unit_price' => 42000.00]);
        B2BPriceTier::create(['product_id' => $product->id, 'min_quantity' => 10, 'max_quantity' => null, 'unit_price' => 38000.00]);

        $this->assertEquals(50000.00, $product->price);
        $this->assertEquals(42000.00, $product->getB2BUnitPrice(5));
        $this->assertEquals(38000.00, $product->getB2BUnitPrice(20));
    }

    /** Rule 2: China Landed Cost Import Calculator Math */
    public function test_rule_china_import_landed_cost_calculations()
    {
        $calculator = new ChinaImportCalculatorService();
        $result = $calculator->calculate([
            'unit_price' => 10.00, // USD
            'quantity' => 100,
            'weight_kg' => 0.5,
            'shipping_method' => 'air',
            'exchange_rate' => 120.0,
            'customs_duty_rate' => 15.0,
            'vat_rate' => 15.0,
            'desired_margin_rate' => 30.0,
        ]);

        $this->assertGreaterThan(0, $result['summary']['total_investment_bdt']);
        $this->assertGreaterThan(0, $result['summary']['cost_per_unit_bdt']);
        $this->assertGreaterThan($result['summary']['cost_per_unit_bdt'], $result['summary']['suggested_selling_price_bdt']);
        $this->assertGreaterThan(0, $result['summary']['break_even_units']);
    }

    /** Rule 3: Besmart AI Natural Language Search Only Returns Real DB Products */
    public function test_rule_ai_shopping_assistant_db_search()
    {
        $category = Category::create(['name' => 'Audio', 'slug' => 'audio']);
        $product = Product::create([
            'name' => 'Pro Wireless Earbuds',
            'slug' => 'pro-wireless-earbuds',
            'sku' => 'EAR-01',
            'price' => 2500.00,
            'stock' => 50,
            'category_id' => $category->id,
            'status' => 'active',
        ]);

        $aiService = new AIService();
        $result = $aiService->searchProducts('earbuds under 3000 taka');

        $this->assertNotEmpty($result['products']);
        $this->assertEquals('Pro Wireless Earbuds', $result['products'][0]['name']);
        $this->assertEquals(2500.00, $result['products'][0]['price']);
    }

    /** Rule 4: Discount Wheel Anti-Abuse Rate Limiting */
    public function test_rule_discount_wheel_server_rate_limiting()
    {
        $wheel = new DiscountWheelService();
        $ip = '192.168.1.10';

        $spin1 = $wheel->spin(null, $ip);
        $this->assertTrue($spin1['success']);

        $spin2 = $wheel->spin(null, $ip);
        $this->assertFalse($spin2['success']);
        $this->assertEquals('You have already spun the wheel today. Please come back in 24 hours!', $spin2['message']);
    }

    /** Rule 5: 10-Year B2B BI Analytics Aggregation */
    public function test_rule_10_year_b2b_analytics_aggregations()
    {
        $user = User::create([
            'name' => 'Wholesale User',
            'email' => 'wholesale@test.com',
            'password' => bcrypt('password'),
            'role' => 'b2b',
        ]);

        Order::create([
            'order_number' => 'ORD-10Y-TEST',
            'user_id' => $user->id,
            'user_type' => 'b2b',
            'subtotal' => 100000.00,
            'discount_amount' => 5000.00,
            'total_amount' => 95000.00,
            'status' => 'delivered',
            'payment_status' => 'paid',
            'shipping_address' => ['city' => 'Dhaka'],
            'created_at' => now()->subYears(2),
        ]);

        $analyticsService = new B2BAnalyticsService();
        $analytics = $analyticsService->getAnalytics($user->id, '10y');

        $this->assertEquals(95000.00, $analytics['kpis']['total_spending']);
        $this->assertEquals(1, $analytics['kpis']['total_orders']);
    }

    /** Rule 6: Payment Gateway Abstraction Layer */
    public function test_rule_payment_gateway_abstraction()
    {
        $user = User::create([
            'name' => 'Buyer',
            'email' => 'buyer@test.com',
            'password' => bcrypt('password'),
            'role' => 'b2c',
        ]);

        $order = Order::create([
            'order_number' => 'ORD-PAY-TEST',
            'user_id' => $user->id,
            'user_type' => 'b2c',
            'subtotal' => 5000.00,
            'total_amount' => 5000.00,
            'status' => 'pending',
            'payment_status' => 'pending',
            'shipping_address' => ['city' => 'Dhaka'],
        ]);

        $paymentService = new PaymentService();
        $result = $paymentService->processPayment($order, 'cod');

        $this->assertTrue($result['success']);
        $this->assertStringStartsWith('COD-', $result['transaction_id']);
    }
}
