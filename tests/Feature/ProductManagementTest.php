<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_store_new_product_successfully()
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $category = Category::create([
            'name' => 'Laptops & Computers',
            'slug' => 'laptops-computers',
        ]);

        $payload = [
            'name' => 'UltraBook Pro 14 OLED',
            'price' => 120000.00,
            'sale_price' => 105000.00,
            'b2b_price' => 92000.00,
            'stock' => 25,
            'moq' => 2,
            'category_id' => $category->id,
            'description' => 'Flagship OLED laptop with AI processor.',
            'image_url' => 'https://images.unsplash.com/photo-1603302576837-37561b2e2302?w=500&q=80',
            'is_featured' => true,
            'is_flash_sale' => false,
        ];

        $response = $this->actingAs($admin)
            ->postJson('/api/v1/admin/products', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Product created successfully',
            ]);

        $this->assertDatabaseHas('products', [
            'name' => 'UltraBook Pro 14 OLED',
            'price' => 120000.00,
            'stock' => 25,
            'category_id' => $category->id,
        ]);

        $product = Product::where('name', 'UltraBook Pro 14 OLED')->first();
        $this->assertNotNull($product);
        $this->assertNotNull($product->primaryImage);
        $this->assertEquals($payload['image_url'], $product->primaryImage->image_url);
    }

    public function test_store_product_fails_without_required_fields()
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin2@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)
            ->postJson('/api/v1/admin/products', [
                'name' => '', // Empty name
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'price', 'stock']);
    }

    public function test_guest_cannot_store_product()
    {
        $response = $this->postJson('/api/v1/admin/products', [
            'name' => 'Unauthorized Laptop',
            'price' => 50000,
            'stock' => 10,
        ]);

        $response->assertStatus(401);
    }
}
