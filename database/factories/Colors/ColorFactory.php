<?php

namespace Database\Factories\Colors;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Colors\Color>
 */
class ColorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->colorName(),
            'hex_code' => fake()->hexColor()
        ];
    }
}
