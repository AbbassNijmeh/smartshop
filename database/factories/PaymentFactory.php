<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Foundation\Auth\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => Order::query()->inRandomOrder()->first()?->id ?? Order::factory(),
            'user_id' => User::query()->inRandomOrder()->first()?->id ?? User::factory(),
            'total_amount' => $this->faker->randomFloat(2, 10, 500),
            'payment_method' => $this->faker->randomElement(['credit_card', 'paypal', 'cash']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
