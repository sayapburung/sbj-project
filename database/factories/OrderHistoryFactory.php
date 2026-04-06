<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderHistory>
 */
class OrderHistoryFactory extends Factory
{
    public function definition()
    {
        $stage = ['desain', 'press', 'printing', 'qc', 'selesai'];

        return [
            'from_stage' => $this->faker->randomElement($stage),
            'to_stage' => $this->faker->randomElement($stage),
            'from_status' => 'PROCESS',
            'to_status' => $this->faker->randomElement(['PROCESS', 'DONE']),
            'notes' => $this->faker->sentence(),
            'user_id' => 1,
            'created_at' => now(),
        ];
    }
}
