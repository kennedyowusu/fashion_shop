<?php

namespace Database\Factories;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cart>
 */
class CartFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'image' => $this->faker->imageUrl(),
            'price' => $this->faker->randomFloat(2, 0, 1000),
            'quantity' => $this->faker->randomDigit,
            'user_id' => null,
            'product_id' => null,
        ];
    }

    /**
     * Indicate that the cart belongs to a user.
     *
     * @param \App\Models\User $user
     * @return self
     */
    public function ownedBy(User $user): self
    {
        return $this->state(function (array $attributes) use ($user) {
            return [
                'user_id' => $user->id,
            ];
        });
    }

    /**
     * Indicate that the cart belongs to a product.
     *
     * @param \App\Models\Product $product
     * @return self
     */
    public function forProduct(Product $product): self
    {
        return $this->state(function (array $attributes) use ($product) {
            return [
                'product_id' => $product->id,
            ];
        });
    }
}

