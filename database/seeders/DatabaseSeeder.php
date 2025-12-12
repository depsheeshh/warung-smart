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
        // Jalankan seeder modular untuk role & permission dulu
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class,
        ]);

        // Buat user default Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@warungsmart.com'],
            [
                'name' => 'Usman Dembelek',
                'password' => Hash::make('Admin123!'),
                'phone' => '081234567890',
                'address' => 'Cirebon',
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
                'phone' => '081234567891',
                'address' => 'Cirebon',
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
                'phone' => '081234567892',
                'address' => 'Cirebon',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );
        $customer->assignRole('customer');

        // Jalankan OrderSeeder setelah user & produk ada
        $this->call([
            OrderSeeder::class,
        ]);
    }
}
