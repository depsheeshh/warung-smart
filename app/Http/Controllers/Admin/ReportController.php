<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    private function getReportData(string $periode = 'monthly', ?string $date = null): array
    {
        $date = $date ?? now()->toDateString();
        $d = \Carbon\Carbon::parse($date);

        $query = Order::query();

        // Filter periode
        if ($periode === 'daily') {
            $query->whereDate('created_at', $d->toDateString());
        } elseif ($periode === 'monthly') {
            $query->whereYear('created_at', $d->year)->whereMonth('created_at', $d->month);
        } elseif ($periode === 'yearly') {
            $query->whereYear('created_at', $d->year);
        }

        // Ringkasan pesanan
        $totalOrders    = (clone $query)->count();
        $pendingOrders  = (clone $query)->where('status','pending')->count();
        $acceptedOrders = (clone $query)->where('status','accepted')->count();
        $rejectedOrders = (clone $query)->where('status','rejected')->count();

        // Ringkasan produk
        $totalProducts   = Product::count();
        $activeProducts  = Product::where('status','active')->count();
        $pendingProducts = Product::where('status','pending')->count();

        // Ambil accepted untuk kalkulasi revenue & discount
        $accepted = (clone $query)->where('status','accepted')->with('product')->get();

        // Total revenue: quantity × price_final (snapshot)
        $totalRevenue = $accepted->sum(function($o) {
            $base  = $o->product->price ?? 0;
            $final = $o->price_snapshot ?? $base;
            return $o->quantity * $final;
        });

        // Total discount: selisih harga × quantity
        $totalDiscount = $accepted->sum(function($o) {
            $base  = $o->product->price ?? 0;
            $final = $o->price_snapshot ?? $base;
            $diff  = max(0, $base - $final);
            return $o->quantity * $diff;
        });

        // Pesanan per supplier/admin
        $ordersPerSupplier = (clone $query)->with('product.supplier')->get()
            ->groupBy(function($o) {
                return $o->product->supplier->name ?? $o->product->created_by?->name ?? 'Admin';
            })
            ->map(function($group) {
                $accepted = $group->where('status','accepted');

                $revenue = $accepted->sum(function($o){
                    $base  = $o->product->price ?? 0;
                    $final = $o->price_snapshot ?? $base;
                    return $o->quantity * $final;
                });

                $discount = $accepted->sum(function($o){
                    $base  = $o->product->price ?? 0;
                    $final = $o->price_snapshot ?? $base;
                    $diff  = max(0, $base - $final);
                    return $o->quantity * $diff;
                });

                return [
                    'orders'    => $group->count(),
                    'accepted'  => $accepted->count(),
                    'rejected'  => $group->where('status','rejected')->count(),
                    'qty_total' => $group->sum('quantity'),
                    'qty_sold'  => $accepted->sum('quantity'),
                    'revenue'   => $revenue,
                    'discount'  => $discount,
                ];
            });

        // Tren bulanan
        $monthlyOrders = Order::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(quantity) as qty_total')
            )
            ->groupBy('year','month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Produk per supplier/admin
        $productsPerSupplier = Product::with('supplier')->get()
            ->groupBy(fn($p) => $p->supplier->name ?? $p->created_by?->name ?? 'Admin')
            ->map(fn($group) => [
                'active'  => $group->where('status','active')->count(),
                'pending' => $group->where('status','pending')->count(),
                'total'   => $group->count(),
            ]);

        // Detail orders: kirim sebagai Eloquent (no map to array)
        $orders = (clone $query)->with(['customer','product'])->get();

        return compact(
            'periode','date',
            'totalOrders','pendingOrders','acceptedOrders','rejectedOrders',
            'totalProducts','activeProducts','pendingProducts',
            'totalRevenue','totalDiscount','ordersPerSupplier','monthlyOrders','productsPerSupplier',
            'orders'
        );
    }

    public function index(Request $request)
    {
        $periode = $request->get('periode','monthly');
        $date    = $request->get('date', now()->toDateString());

        return view('admin.reports.index', $this->getReportData($periode,$date));
    }

    public function exportPdf(Request $request)
    {
        $periode = $request->get('periode','monthly');
        $date    = $request->get('date', now()->toDateString());

        $pdf = Pdf::loadView('admin.reports.pdf', $this->getReportData($periode,$date));
        return $pdf->download('laporan-transaksi.pdf');
    }
}
