<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;

class ProductControllerTest extends TestCase
{
  use RefreshDatabase;

  /**
   * Test creating a product with valid data.
   *
   * @return void
   */
  public function test_can_create_product_with_valid_data()
  {
    $data = [
      'name' => 'Test Product',
      'price' => 10.99,
      'description' => 'Test description',
    ];

    $response = $this->json('POST', '/api/products', $data);

    $response->assertStatus(201)
      ->assertJson([
        'name' => 'Test Product',
        'price' => 10.99,
        'description' => 'Test description',
      ]);
  }

  /**
   * Test creating a product with invalid data.
   *
   * @return void
   */
  public function test_cannot_create_product_with_invalid_data()
  {
    $data = [
      'name' => '',
      'price' => 'abc',
      'description' => 'Test description',
    ];

    $response = $this->json('POST', '/api/products', $data);

    $response->assertStatus(422)
      ->assertJsonValidationErrors(['name', 'price']);
  }

  /**
   * Test updating a product with valid data.
   *
   * @return void
   */
  public function test_can_update_product_with_valid_data()
  {
    $product = Product::factory()->create();

    $data = [
      'name' => 'Updated Product',
      'price' => 20.99,
      'description' => 'Updated description',
    ];

    $response = $this->json('PUT', '/api/products/' . $product->id, $data);

    $response->assertStatus(200)
      ->assertJson([
        'name' => 'Updated Product',
        'price' => 20.99,
        'description' => 'Updated description',
      ]);
  }

  /**
   * Test updating a product with invalid data.
   *
   * @return void
   */
  public function test_cannot_update_product_with_invalid_data()
  {
    $product = Product::factory()->create();

    $data = [
      'name' => '',
      'price' => 'abc',
      'description' => 'Updated description',
    ];

    $response = $this->json('PUT', '/api/products/' . $product->id, $data);

    $response->assertStatus(422)
      ->assertJsonValidationErrors(['name', 'price']);
  }

  /**
   * Test listing products.
   *
   * @return void
   */
  public function test_can_list_products()
  {
    Product::factory()->count(5)->create();

    $response = $this->json('GET', '/api/products');

    $response->assertStatus(200)
      ->assertJsonCount(5, 'data');
  }

  /**
   * Test showing an existing product.
   *
   * @return void
   */
  public function test_can_show_existing_product()
  {
    $product = Product::factory()->create();

    $response = $this->json('GET', '/api/products/' . $product->id);

    $response->assertStatus(200)
      ->assertJson($product->toArray());
  }

  /**
   * Test showing a non-existing product returns 404.
   *
   * @return void
   */
  public function test_show_non_existing_product_returns_404()
  {
    $response = $this->json('GET', '/api/products/999');

    $response->assertStatus(404);
  }

  /**
   * Test deleting an existing product.
   *
   * @return void
   */
  public function test_can_delete_existing_product()
  {
    $product = Product::factory()->create();

    $response = $this->json('DELETE', '/api/products/' . $product->id);

    $response->assertStatus(204);
  }

  /**
   * Test deleting a non-existing product returns 404.
   *
   * @return void
   */
  public function test_delete_non_existing_product_returns_404()
  {
    $response = $this->json('DELETE', '/api/products/999');

    $response->assertStatus(404);
  }
}
