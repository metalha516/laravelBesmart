<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FullApiEndpointTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_catalog_endpoints_return_success()
    {
        $category = Category::create(['name' => 'Laptops', 'slug' => 'laptops']);
        Product::create([
            'name' => 'Test Laptop',
            'slug' => 'test-laptop',
            'sku' => 'LAP-TEST',
            'price' => 50000.00,
            'stock' => 10,
            'category_id' => $category->id,
            'status' => 'active',
            'is_featured' => true,
        ]);

        $response = $this->getJson('/api/v1/products');
        $response->assertStatus(200)->assertJson(['success' => true]);

        $featuredRes = $this->getJson('/api/v1/products/featured');
        $featuredRes->assertStatus(200)->assertJson(['success' => true]);

        $categoriesRes = $this->getJson('/api/v1/categories');
        $categoriesRes->assertStatus(200)->assertJson(['success' => true]);
    }

    public function test_user_authentication_flow()
    {
        $regResponse = $this->postJson('/api/v1/auth/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'role' => 'b2c',
        ]);
        $regResponse->assertStatus(201)->assertJson(['success' => true]);

        $loginResponse = $this->postJson('/api/v1/auth/login', [
            'email' => 'john@example.com',
            'password' => 'password123',
        ]);
        $loginResponse->assertStatus(200)->assertJson(['success' => true]);
    }

    public function test_cart_and_checkout_flow()
    {
        $category = Category::create(['name' => 'Audio', 'slug' => 'audio']);
        $product = Product::create([
            'name' => 'Earphones Pro',
            'slug' => 'earphones-pro',
            'sku' => 'EAR-PRO',
            'price' => 1500.00,
            'stock' => 20,
            'category_id' => $category->id,
            'status' => 'active',
        ]);

        $user = User::create([
            'name' => 'Buyer User',
            'email' => 'buyeruser@test.com',
            'password' => bcrypt('password'),
            'role' => 'b2c',
        ]);

        $addRes = $this->actingAs($user)->postJson('/api/v1/cart/items', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);
        $addRes->assertStatus(200)->assertJson(['success' => true]);

        $checkoutRes = $this->actingAs($user)->postJson('/api/v1/checkout', [
            'shipping_address' => [
                'name' => 'Buyer User',
                'phone' => '+8801700000000',
                'address' => 'Banani Road 11',
                'city' => 'Dhaka',
            ],
            'payment_method' => 'cod',
        ]);
        $checkoutRes->assertStatus(201)->assertJson(['success' => true]);
    }
}
