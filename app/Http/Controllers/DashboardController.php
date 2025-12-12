<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Debt;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user->getRoleNames()->first(); // ambil role utama

        // Variabel default
        $monthlyOrders = collect();
        $data = [];

        if ($role === 'admin') {
            // --- Pesanan bulanan ---
            $monthlyOrders = Order::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(quantity) as qty_total'),
                DB::raw('SUM(total_price) as revenue')
            )
            ->groupBy('year','month')
            ->orderBy('year')->orderBy('month')
            ->get();

            // --- Ringkasan umum ---
            $data = [
                'totalOrders'    => Order::count(),
                'totalProducts'  => Product::count(),
                'totalCustomers' => User::role('customer')->count(),
                'totalSuppliers' => User::role('supplier')->count(),
            ];

            // --- Ringkasan kasbon ---
            $totalDebts = Debt::whereIn('status',['unpaid','overdue'])->sum('amount');

            $topCustomers = User::role('customer')
                ->withSum(['debts' => function($q){
                    $q->whereIn('status',['unpaid','overdue']);
                }], 'amount')
                ->orderByDesc('debts_sum_amount')
                ->take(5)
                ->get();

            // --- Data untuk chart kasbon bulanan ---
            $monthlyDebts = Debt::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(amount) as total_debt')
            )
            ->whereIn('status',['unpaid','overdue'])
            ->groupBy('year','month')
            ->orderBy('year')->orderBy('month')
            ->get();

            // Supplier telat (7 hari terakhir)
            $delayedSchedules = \App\Models\SupplierSchedule::with('supplier')
                ->where('status', 'delayed')
                ->where('expected_date', '>=', now()->subDays(7))
                ->orderByDesc('expected_date')
                ->take(10)
                ->get();

            // Harga naik signifikan (bandingkan entry terbaru per supplier-product vs sebelumnya)
            $recentPriceIncreases = \App\Models\SupplierPrice::with(['supplier','product'])
                ->orderByDesc('date')
                ->get()
                ->groupBy(function($p){ return $p->supplier_id.'-'.$p->product_id; })
                ->map(function($group){
                    $latest = $group->sortByDesc('date')->first();
                    $previous = $group->sortByDesc('date')->skip(1)->first();
                    if ($latest && $previous && $latest->price > $previous->price) {
                        $percent = (($latest->price - $previous->price) / max($previous->price, 1)) * 100;
                        return $percent >= 10 ? [
                            'product' => $latest->product->name,
                            'supplier'=> $latest->supplier->name,
                            'previous_price' => $previous->price,
                            'current_price'  => $latest->price,
                            'percent_increase' => round($percent, 2),
                            'date' => $latest->date->format('d M Y'),
                        ] : null;
                    }
                    return null;
                })
                ->filter()
                ->values()
                ->take(10);

            // Stok rendah (misal threshold <= 10)
            $lowStocks = Product::with('supplier')
                ->where('stock', '<=', 10)
                ->orderBy('stock')
                ->take(10)
                ->get();

            return view('admin.dashboard', compact(
                'user',
                'monthlyOrders',
                'totalDebts',
                'topCustomers',
                'monthlyDebts',
                'delayedSchedules',
                'recentPriceIncreases',
                'lowStocks'
            ) + $data);
        }

        if ($role === 'supplier') {
            $monthlyOrders = Order::whereHas('product', fn($q) => $q->where('supplier_id', $user->id))
                ->select(DB::raw('YEAR(created_at) as year'), DB::raw('MONTH(created_at) as month'),
                         DB::raw('COUNT(*) as total'), DB::raw('SUM(quantity) as qty_total'),
                         DB::raw('SUM(total_price) as revenue'))
                ->groupBy('year','month')
                ->orderBy('year')->orderBy('month')
                ->get();

            $data = [
                'activeProducts' => Product::where('supplier_id',$user->id)->where('status','active')->count(),
                'acceptedOrders' => Order::whereHas('product', fn($q) => $q->where('supplier_id',$user->id))
                                         ->where('status','accepted')->count(),
                'totalRevenue'   => Order::whereHas('product', fn($q) => $q->where('supplier_id',$user->id))
                                         ->where('status','accepted')->sum('total_price'),
            ];

            return view('supplier.dashboard', compact('user','monthlyOrders') + $data);
        }

        if ($role === 'customer') {
            $monthlyOrders = Order::where('customer_id', $user->id)
                ->select(DB::raw('YEAR(created_at) as year'),
                        DB::raw('MONTH(created_at) as month'),
                        DB::raw('COUNT(*) as total'),
                        DB::raw('SUM(quantity) as qty_total'))
                ->groupBy('year','month')
                ->orderBy('year')->orderBy('month')
                ->get();

            $data = [
                'myOrders'        => Order::where('customer_id',$user->id)->count(),
                'myAcceptedOrders'=> Order::where('customer_id',$user->id)->where('status','accepted')->count(),
                'myDebts'         => $user->debts()->whereIn('status',['unpaid','overdue'])->sum('amount'),
                'myDebtsPaid'     => $user->debts()->where('status','paid')->sum('amount'),
            ];

            $monthlyDebts = $user->debts()
                ->select(DB::raw('YEAR(created_at) as year'),
                        DB::raw('MONTH(created_at) as month'),
                        DB::raw('SUM(amount) as total_debt'))
                ->whereIn('status',['unpaid','overdue'])
                ->groupBy('year','month')
                ->orderBy('year')->orderBy('month')
                ->get();

            // Ambil notifikasi terbaru (misalnya 5 terakhir)
            $notifications = $user->notifications()->latest()->take(5)->get();

            return view('customer.dashboard', compact(
                'user','monthlyOrders','monthlyDebts','notifications'
            ) + $data);
        }

        // fallback
        return redirect()->route('home');
    }
}

