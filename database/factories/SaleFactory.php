<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sale>
 */
class SaleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'amount' => $this->faker->randomFloat(2, 100, 10000),
            'status' => Sale::STATUS_CONCLUDED,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Sale $sale) {
            $products = Product::inRandomOrder()->take(rand(1, 5))->get();
            $sale->products()->attach($products);
        });
    }
}
