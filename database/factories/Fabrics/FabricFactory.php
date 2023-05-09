<?php

namespace Database\Factories\Fabrics;

use App\Models\Fabrics\Fabric;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class FabricFactory extends Factory
{   
    protected $model = Fabric::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {   
        return [
            'quantity' => fake()->randomNumber(),
            'materials' => 'Лен, Шелк',
        ];
    }
}
