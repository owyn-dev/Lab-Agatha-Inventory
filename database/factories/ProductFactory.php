<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\VariantProduct;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
final class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => $this->faker->word(),
            'name' => $this->faker->name(),
            'image' => 'cheese_sagoo.jpg',
            'variant' => $this->faker->randomElement(VariantProduct::cases()),
            'price' => $this->faker->randomFloat(2, 50000, 150000),
            'expired_day' => $this->faker->numberBetween(1, 30),
            'stock' => 0,
        ];
    }
}
