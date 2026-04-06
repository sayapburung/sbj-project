<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PurchaseOrder>
 */
class PurchaseOrderFactory extends Factory
{
    public function definition()
    {
        $jenisPO = ['Sablon', 'Printing', 'Cutting', 'Jahit'];
        $stage = ['DESAIN', 'PRESS', 'PRINTING', 'QC', 'SELESAI'];

        return [
            'po_number' => 'PO-' . strtoupper($this->faker->bothify('####??')),
            'nama_konsumen' => $this->faker->name(),
            'nama_po' => 'Order ' . $this->faker->word(),
            'jenis_po_id' => $this->faker->numberBetween(1, 4),
            'jenis_po' => $this->faker->randomElement($jenisPO),
            'file' => 'file_' . $this->faker->randomNumber() . '.pdf',
            'jumlah' => $this->faker->numberBetween(10, 500),
            'meteran' => $this->faker->numberBetween(50, 1000),
            'tanggal_order' => now()->subDays(rand(1, 30)),
            'deadline' => now()->addDays(rand(1, 30)),
            'jenis_bahan' => $this->faker->randomElement(['Katun', 'Polyester', 'Drifit']),
            'current_stage' => $this->faker->randomElement($stage),
            'stage_status' => $this->faker->randomElement(['WAITING', 'PROCESS', 'DONE']),
            'active' => 1,
            'created_by' => 1,
        ];
    }
}
