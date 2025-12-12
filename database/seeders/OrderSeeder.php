<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class OrderSeeder extends Seeder
{
    public function run()
    {
        // Ambil produk pertama
        $product = Product::first();
        // Ambil user dengan role customer (Spatie Permission)
        $customer = User::role('customer')->first();

        if (!$product || !$customer) {
            return;
        }

        // Seed data penjualan 12 periode (misal 12 bulan)
        $salesData = [20, 25, 22, 30, 28, 35, 40, 38, 45, 50, 48, 55];

        foreach ($salesData as $i => $qty) {
            Order::create([
                'customer_id'     => $customer->id,
                'product_id'      => $product->id,
                'quantity'        => $qty,
                'status'          => 'completed',
                'unit_price'      => $product->price,
                'total_price'     => $product->price * $qty,
                'discount_percent'=> 0,
                'created_at'      => now()->subMonths(12 - $i),
            ]);
        }
    }
}
