<?php

namespace Database\Factories;

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
            'name' => $this->faker->name,
            'description' => $this->faker->text,
            'price' => $this->faker->randomFloat(2, 0, 100),
            'stock' => $this->faker->randomNumber(2),
            'image' => $this->faker->imageUrl(),
            'is_new' => $this->faker->boolean ? 'yes' : 'no',
            'is_featured' => $this->faker->boolean ? 'yes' : 'no',
            'is_popular' => $this->faker->boolean ? 'yes' : 'no',
            'category_id' => $this->faker->numberBetween(1, 5),
        ];
    }
}
