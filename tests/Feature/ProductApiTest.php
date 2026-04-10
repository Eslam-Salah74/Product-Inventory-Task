<?php

namespace Tests\Feature;

use App\Enums\ProductStatus;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_can_list_products()
    {
        Product::factory()->count(15)->create();

        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
            ->assertJsonCount(10, 'data') // Pagination default
            ->assertJsonStructure([
                'success',
                'message',
                'data',
                'meta' => ['current_page', 'last_page', 'per_page', 'total']
            ]);
    }

    public function test_can_create_product()
    {
        $data = [
            'sku' => 'TEST-SKU-1',
            'name' => 'Test Product',
            'description' => 'Test Description',
            'price' => 99.99,
            'stock_quantity' => 100,
            'low_stock_threshold' => 5,
            'status' => ProductStatus::ACTIVE->value,
        ];

        $response = $this->postJson('/api/products', $data);

        $response->assertStatus(201)
            ->assertJsonPath('data.sku', 'TEST-SKU-1');

        $this->assertDatabaseHas('products', ['sku' => 'TEST-SKU-1']);
    }

    public function test_can_show_product()
    {
        $product = Product::factory()->create();

        $response = $this->getJson("/api/products/{$product->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $product->id);
    }

    public function test_can_update_product()
    {
        $product = Product::factory()->create(['name' => 'Old Name']);

        $response = $this->putJson("/api/products/{$product->id}", [
            'name' => 'New Name'
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.name', 'New Name');

        $this->assertDatabaseHas('products', ['id' => $product->id, 'name' => 'New Name']);
    }

    public function test_can_soft_delete_product()
    {
        $product = Product::factory()->create();

        $response = $this->deleteJson("/api/products/{$product->id}");

        $response->assertStatus(204);

        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }

    public function test_can_adjust_stock()
    {
        $product = Product::factory()->create(['stock_quantity' => 10]);

        $response = $this->postJson("/api/products/{$product->id}/stock", [
            'amount' => 5
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.stock_quantity', 15);
    }

    public function test_can_list_low_stock_products()
    {
        Product::factory()->create(['stock_quantity' => 20, 'low_stock_threshold' => 10]);
        Product::factory()->create(['stock_quantity' => 5, 'low_stock_threshold' => 10]);

        $response = $this->getJson('/api/products/low-stock');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }
}
