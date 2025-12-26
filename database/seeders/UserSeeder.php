<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        $admin = Role::where('name', 'Admin')->first();
        $desain = Role::where('name', 'Desain')->first();
        $printing = Role::where('name', 'Printing')->first();
        $press = Role::where('name', 'Press')->first();
        $qc = Role::where('name', 'QC')->first();
        $pengiriman = Role::where('name', 'Pengiriman')->first();

        $users = [
            ['name' => 'Admin User', 'email' => 'admin@workflow.com', 'password' => Hash::make('password'), 'role_id' => $admin->id],
            ['name' => 'Desain User', 'email' => 'desain@workflow.com', 'password' => Hash::make('password'), 'role_id' => $desain->id],
            ['name' => 'Printing User', 'email' => 'printing@workflow.com', 'password' => Hash::make('password'), 'role_id' => $printing->id],
            ['name' => 'Press User', 'email' => 'press@workflow.com', 'password' => Hash::make('password'), 'role_id' => $press->id],
            ['name' => 'QC User', 'email' => 'qc@workflow.com', 'password' => Hash::make('password'), 'role_id' => $qc->id],
            ['name' => 'Pengiriman User', 'email' => 'pengiriman@workflow.com', 'password' => Hash::make('password'), 'role_id' => $pengiriman->id],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
