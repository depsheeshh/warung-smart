<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@warungsmart.com'],
            [
                'name' => 'Admin Utama',
                'password' => bcrypt('password'),
                'phone' => '081234567890',
                'address' => 'Cirebon'
            ]
        );
        $admin->assignRole('admin');

        $supplier = User::firstOrCreate(
            ['email' => 'supplier@warungsmart.com'],
            [
                'name' => 'Supplier Default',
                'password' => bcrypt('password'),
                'phone' => '081234567891',
                'address' => 'Cirebon'
            ]
        );
        $supplier->assignRole('supplier');

        $customer = User::firstOrCreate(
            ['email' => 'customer@warungsmart.com'],
            [
                'name' => 'Customer Default',
                'password' => bcrypt('password'),
                'phone' => '081234567892',
                'address' => 'Cirebon'
            ]
        );
        $customer->assignRole('customer');
    }
}
