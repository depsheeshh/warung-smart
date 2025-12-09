<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Jalankan seeder modular
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class,
        ]);

        // Buat user default Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@warungsmart.com'],
            [
                'name' => 'Ilham Admin',
                'password' => Hash::make('Admin123!'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );
        $admin->assignRole('admin');

        // Buat user default Supplier
        $supplier = User::firstOrCreate(
            ['email' => 'supplier@warungsmart.com'],
            [
                'name' => 'Yanto Supplier',
                'password' => Hash::make('Supplier123!'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );
        $supplier->assignRole('supplier');

        // Buat user default Customer
        $customer = User::firstOrCreate(
            ['email' => 'customer@warungsmart.com'],
            [
                'name' => 'Bahlil Customer',
                'password' => Hash::make('Customer123!'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );
        $customer->assignRole('customer');
    }
}
