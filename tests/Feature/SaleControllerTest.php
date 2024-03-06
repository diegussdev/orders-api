<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Sale;

class SaleControllerTest extends TestCase
{
  use RefreshDatabase;

  /**
   * Test creating a new sale.
   *
   * @return void
   */
  public function test_can_create_sale_with_valid_data()
  {
    Product::factory()->count(2)->create();

    $data = [
      'amount' => 100,
      'products' => [
        ['id' => 1],
        ['id' => 2],
      ]
    ];

    $response = $this->postJson('/api/sales', $data);

    $response->assertStatus(201)
      ->assertJsonStructure([
        'id',
        'amount',
        'status',
        'products',
      ]);
  }

  /**
   * Test validation when creating a sale with invalid data.
   *
   * @return void
   */
  public function test_validation_when_creating_sale_with_invalid_data()
  {
    $data = [
      'amount' => -100,
      'products' => []
    ];

    $response = $this->postJson('/api/sales', $data);

    $response->assertStatus(422)
      ->assertJsonValidationErrors([
        'amount',
        'products',
      ]);
  }

  /**
   * Test listing sales.
   *
   * @return void
   */
  public function test_can_list_sales()
  {
    Sale::factory()->count(5)->create();

    $response = $this->getJson('/api/sales');

    $response->assertStatus(200)
      ->assertJsonCount(5, 'data');
  }

  /**
   * Test showing a specific sale.
   *
   * @return void
   */
  public function test_can_show_sale()
  {
    $sale = Sale::factory()->create();

    $response = $this->getJson("/api/sales/{$sale->id}");

    $response->assertStatus(200)
      ->assertJson($sale->toArray());
  }

  /**
   * Test showing a non-existing sale returns 404.
   *
   * @return void
   */
  public function test_showing_non_existent_sale_returns_404()
  {
    $response = $this->getJson('/api/sales/999');

    $response->assertStatus(404);
  }

  /**
   * Test cancelling a sale.
   *
   * @return void
   */
  public function test_can_cancel_sale()
  {
    $sale = Sale::factory()->create();

    $response = $this->putJson("/api/sales/{$sale->id}/cancel");

    $response->assertStatus(200)
      ->assertJson([
        'id' => $sale->id,
        'status' => Sale::STATUS_CANCELED,
      ]);
  }

  /**
   * Test cancelling a non-existing sale returns 404.
   *
   * @return void
   */
  public function test_cancelling_non_existent_sale_returns_404()
  {
    $response = $this->putJson('/api/sales/999/cancel');

    $response->assertStatus(404);
  }
}
