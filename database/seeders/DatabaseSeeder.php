<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['role_name' => 'superadmin', 'role_description' => 'Super Administrator / Developer'],
            ['role_name' => 'admin',      'role_description' => 'Administrator / Business Owner'],
            ['role_name' => 'planner',    'role_description' => 'Production Planner / Supervisor'],
            ['role_name' => 'inventory',  'role_description' => 'Inventory Staff'],
            ['role_name' => 'operator',   'role_description' => 'Production Operator'],
            ['role_name' => 'qc',         'role_description' => 'Quality Control Staff'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['role_name' => $role['role_name']], $role);
        }

        $adminRole = Role::where('role_name', 'admin')->first();

        User::firstOrCreate(['email' => 'admin@inkforge.com'], [
            'name'     => 'Admin',
            'password' => Hash::make('password'),
            'role_id'  => $adminRole->id,
        ]);

        // User requested account
        $superadminRole = Role::where('role_name', 'superadmin')->first();
        User::updateOrCreate(['email' => 'admin@inkforge.local'], [
            'name'     => 'InkForge Admin',
            'password' => Hash::make('admin123'),
            'role_id'  => $superadminRole->id,
        ]);
    }
}
