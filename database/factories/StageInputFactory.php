<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StageInput>
 */
class StageInputFactory extends Factory
{
    public function definition()
    {
        return [
            'stage_name' => $this->faker->randomElement(['desain', 'press', 'printing']),
            'meteran_desain' => $this->faker->numberBetween(10, 300),
            'kiloan' => $this->faker->numberBetween(1, 50),
            'meteran_press' => $this->faker->numberBetween(10, 300),
            'meteran_printing' => $this->faker->numberBetween(10, 300),
        ];
    }
}
