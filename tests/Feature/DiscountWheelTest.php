<?php

namespace Tests\Feature;

use App\Services\DiscountWheelService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DiscountWheelTest extends TestCase
{
    use RefreshDatabase;

    public function test_wheel_spin_validates_anti_abuse_24h_limit()
    {
        $service = new DiscountWheelService();
        $ip = '127.0.0.1';

        $firstSpin = $service->spin(null, $ip);
        $this->assertTrue($firstSpin['success']);

        $secondSpin = $service->spin(null, $ip);
        $this->assertFalse($secondSpin['success']);
        $this->assertTrue($secondSpin['already_spun']);
    }
}
