<?php

// database/seeders/RoleSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            [
                'name' => 'Admin',
                'permissions' => ['dashboard', 'purchase_order', 'desain', 'printing', 'press', 'qc', 'pengiriman', 'kanban']
            ],
            [
                'name' => 'Desain',
                'permissions' => ['dashboard', 'desain', 'kanban']
            ],
            [
                'name' => 'Printing',
                'permissions' => ['dashboard', 'printing', 'kanban']
            ],
            [
                'name' => 'Press',
                'permissions' => ['dashboard', 'press', 'kanban']
            ],
            [
                'name' => 'QC',
                'permissions' => ['dashboard', 'qc', 'kanban']
            ],
            [
                'name' => 'Pengiriman',
                'permissions' => ['dashboard', 'pengiriman', 'kanban']
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}