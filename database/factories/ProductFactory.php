<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => Category::query()->inRandomOrder()->first()?->id ?? Category::factory(),
            'name' => $this->faker->words(3, true), // Generates a product name
            'description' => $this->faker->sentence(12), // Random product description
            'cost_price' => $this->faker->randomFloat(2, 5, 100), // Cost price between 5 and 100
            'price' => fn(array $attrs) => $attrs['cost_price'] * (1 + $this->faker->randomFloat(2, 0.1, 0.5)), // Selling price (10-50% markup)
            'barcode' => $this->faker->unique()->ean13(), // Generates unique 13-digit barcode
            'stock_quantity' => $this->faker->numberBetween(5, 200), // Stock between 5 and 200
            'brand' => $this->faker->company(), // Fake brand name
            'discount' => $this->faker->randomFloat(2, 0, 20), // Discount up to 20%
            'discount_start' => $this->faker->optional(0.5)->dateTimeBetween('-1 month', 'now'), // 50% chance of having a discount start date
            'discount_end' => fn(array $attrs) => $attrs['discount_start'] ? $this->faker->dateTimeBetween($attrs['discount_start'], '+1 month') : null,
            'rating' => $this->faker->randomFloat(1, 3, 5), // Random rating from 3.0 to 5.0
            'reviews_count' => $this->faker->numberBetween(0, 100), // Up to 100 reviews
            'expiration_date' => $this->faker->optional(0.8)->dateTimeBetween('now', '+1 year'), // 80% chance of expiration date
            'weight' => $this->faker->randomFloat(2, 0.1, 10), // Weight in kg
            'dimensions' => $this->faker->randomElement(['10x10x10 cm', '20x15x5 cm', '30x25x10 cm']), // Random size
            'aisle' => $this->faker->randomLetter(), // Aisle as a letter
            'section' => $this->faker->randomDigit(), // Section as a number
            'floor' => $this->faker->numberBetween(1, 3), // Floor level
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
