<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => \App\Models\Customer::factory(),
            'user_id' => \App\Models\User::factory(),
            'status' => $this->faker->randomElement(['pending', 'confirmed', 'shipped', 'delivered', 'canceled']),
            'delivery_fee' => 30.00,
            'total_amount' => $this->faker->randomFloat(2, 50, 2000),
            'created_at' => $this->faker->dateTimeBetween('-3 months', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-3 months', 'now'),
        ];
    }
}
