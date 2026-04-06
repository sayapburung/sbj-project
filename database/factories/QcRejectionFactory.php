<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QcRejection>
 */
class QcRejectionFactory extends Factory
{
    public function definition()
    {
        return [
            'rejected_from_stage' => $this->faker->randomElement(['desain', 'press', 'printing']),
            'rejection_reason' => $this->faker->sentence(),
            'rejection_notes' => $this->faker->paragraph(),
            'severity' => $this->faker->randomElement(['low', 'medium', 'critic']),
            'rejected_by' => 1,
            'rejected_at' => now()->subDays(rand(1, 10)),
            'is_resolved' => $this->faker->boolean(),
            'resolved_at' => now(),
        ];
    }
}
