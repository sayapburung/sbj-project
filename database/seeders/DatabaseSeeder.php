<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PurchaseOrder;
use App\Models\StageInput;
use App\Models\QcRejection;
use App\Models\OrderHistory;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Seeder default kamu (biarin tetap ada)
        // $this->call([
        //     RoleSeeder::class,
        //     UserSeeder::class,
        // ]);

        // 🔥 TAMBAHAN: Generate 100 data relasi
        PurchaseOrder::factory(100)->create()->each(function ($po) {

            // Stage Input (1-3 data per PO)
            StageInput::factory(rand(1,3))->create([
                'po_id' => $po->id
            ]);

            // Order History (3-6 per PO)
            OrderHistory::factory(rand(3,6))->create([
                'po_id' => $po->id
            ]);

            // QC Rejection (opsional)
            if (rand(0,1)) {
                QcRejection::factory(rand(1,2))->create([
                    'po_id' => $po->id
                ]);
            }

        });
    }
}