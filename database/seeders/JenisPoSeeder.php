<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JenisPO;

class JenisPoSeeder extends Seeder
{
    public function run(): void
    {
        $data = [

            // ================= PRINTING =================
            ['kategori' => 'Printing', 'kode' => 'PR-01', 'nama' => 'Print Only'],
            ['kategori' => 'Printing', 'kode' => 'PR-02', 'nama' => 'Print Press'],
            ['kategori' => 'Printing', 'kode' => 'PR-03', 'nama' => 'Print Press + Bahan'],
            ['kategori' => 'Printing', 'kode' => 'PR-04', 'nama' => 'Print Press + Laser'],
            ['kategori' => 'Printing', 'kode' => 'PR-05', 'nama' => 'Print Press + Bahan + Laser'],

            // ================= FULL ORDER =================
            ['kategori' => 'Full Order', 'kode' => 'FO-01', 'nama' => 'Full Order Jaket'],
            ['kategori' => 'Full Order', 'kode' => 'FO-02', 'nama' => 'Full Order Atasan'],
            ['kategori' => 'Full Order', 'kode' => 'FO-03', 'nama' => 'Full Order Stelan Pendek'],
            ['kategori' => 'Full Order', 'kode' => 'FO-04', 'nama' => 'Full Order Stelan Panjang'],
            ['kategori' => 'Full Order', 'kode' => 'FO-05', 'nama' => 'Full Order Stelan Panjang dan Pendek'],
            ['kategori' => 'Full Order', 'kode' => 'FO-06', 'nama' => 'Full Order Stelan & Atasan'],
            ['kategori' => 'Full Order', 'kode' => 'FO-07', 'nama' => 'Full Order Celana Pendek'],
            ['kategori' => 'Full Order', 'kode' => 'FO-08', 'nama' => 'Full Order Celana Panjang'],
        ];

        foreach ($data as $item) {
            \App\Models\JenisPo::updateOrCreate(
                ['kode' => $item['kode']],
                [
                    'kategori' => $item['kategori'],
                    'nama' => $item['nama'],
                    'is_active' => 1
                ]
            );
        }
    }
}