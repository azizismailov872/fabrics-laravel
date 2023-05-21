<?php

namespace Database\Factories\Fabrics;

use App\Models\Colors\Color;
use App\Models\Fabrics\Fabric;
use App\Models\Mateirals\Material;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class FabricFactory extends Factory
{   

    protected $count = 1;
    protected $model = Fabric::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {   

        return [
            //'model' => '#'.fake()->unique()->numberBetween(1,300),
            'model' => $this->count < 10 ? '#00'.$this->count++ : '#0'.$this->count++,
            'quantity' => fake()->randomNumber(),
            'weight' =>  fake()->randomNumber(),
            'material_id' => Material::inRandomOrder()->first()->id,
            'color_id' =>  Color::inRandomOrder()->first()->id,
        ];
    }
}
