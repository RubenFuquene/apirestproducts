<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

    protected $user;

    // Configura el usuario autenticado antes de cada prueba
    protected function setUp(): void
    {
        parent::setUp();

        // Crea un usuario y un token de autenticación
        $this->user = User::factory()->create();
        $this->actingAs($this->user, 'sanctum');
    }

    // Prueba para obtener la lista de productos
    public function test_can_get_products()
    {
        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     '*' => ['id', 'name', 'description', 'price', 'category_id', 'created_at', 'updated_at']
                 ]);
    }

    // Prueba para agregar un nuevo producto
    public function test_can_create_product()
    {
        $category = Category::factory()->create();

        $response = $this->postJson('/api/products', [
            'name' => 'Smartwatch',
            'description' => 'A smartwatch with health monitoring features.',
            'price' => 299.99,
            'category_id' => $category->id
        ]);

        $response->assertStatus(201)
                 ->assertJson([
                     'name' => 'Smartwatch',
                     'description' => 'A smartwatch with health monitoring features.',
                     'price' => 299.99,
                     'category_id' => $category->id
                 ]);
    }

    // Prueba para actualizar un producto existente
    public function test_can_update_product()
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);

        $response = $this->putJson("/api/products/{$product->id}", [
            'name' => 'Updated Product',
            'price' => 399.99
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'name' => 'Updated Product',
                     'price' => 399.99
                 ]);
    }

    // Prueba para eliminar un producto
    public function test_can_delete_product()
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);

        $response = $this->deleteJson("/api/products/{$product->id}");

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Product deleted successfully']);

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}
