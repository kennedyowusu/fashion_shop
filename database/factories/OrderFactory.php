<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{

    protected $model = Order::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status' => $this->faker->randomElement(['pending', 'processing', 'shipped', 'delivered', 'cancelled']),
            'total_price' => $this->faker->randomFloat(2, 0, 1000),
            'shipping_address' => $this->faker->address,
            'billing_address' => $this->faker->address,
            'user_id' => User::factory(),
        ];
    }
}
