<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\Product;
use App\Models\Expense; // tambahan untuk biaya operasional
use App\Models\Debt;    // tambahan untuk kasbon
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    private function getReportData(?string $mode = null, ?string $periode = null, ?string $date = null): array
    {
        $query = Order::with(['product','product.supplier','customer']);

        if (!$mode) {
            $query->whereRaw('1=0');
        }

        if ($mode === 'periode' && $periode) {
            $d = \Carbon\Carbon::parse($date ?? now());
            if ($periode === 'daily') {
                $query->whereDate('created_at', $d->toDateString());
            } elseif ($periode === 'monthly') {
                $query->whereYear('created_at', $d->year)->whereMonth('created_at', $d->month);
            } elseif ($periode === 'yearly') {
                $query->whereYear('created_at', $d->year);
            }
        }

        if ($mode === 'date' && $date) {
            $query->whereDate('created_at', \Carbon\Carbon::parse($date)->toDateString());
        }

        // Ringkasan pesanan
        $totalOrders    = (clone $query)->count();
        $pendingOrders  = (clone $query)->where('status','pending')->count();
        $acceptedOrders = (clone $query)->where('status','accepted')->count();
        $rejectedOrders = (clone $query)->where('status','rejected')->count();

        // Ringkasan produk
        $totalProducts  = Product::count();
        $activeProducts = Product::where('status','active')->count();
        $pendingProducts= Product::where('status','pending')->count();

        // Accepted orders untuk revenue & discount
        $accepted = (clone $query)->where('status','accepted')->get();
        $totalRevenue = $accepted->sum('total_price');
        $totalDiscount= $accepted->sum(function($o) {
            $base = $o->product->price ?? 0;
            $diff = max(0, $base - $o->unit_price);
            return $o->quantity * $diff;
        });

        // Pesanan per supplier/admin
        $ordersPerSupplier = (clone $query)->get()
            ->groupBy(fn($o) => $o->product->supplier->name ?? $o->product->created_by?->name ?? 'Admin')
            ->map(function($group) {
                $accepted = $group->where('status','accepted');
                return [
                    'orders'   => $group->count(),
                    'accepted' => $accepted->count(),
                    'rejected' => $group->where('status','rejected')->count(),
                    'qty_total'=> $group->sum('quantity'),
                    'qty_sold' => $accepted->sum('quantity'),
                    'revenue'  => $accepted->sum('total_price'),
                    'discount' => $accepted->sum(function($o){
                        $base = $o->product->price ?? 0;
                        $diff = max(0, $base - $o->unit_price);
                        return $o->quantity * $diff;
                    }),
                ];
            });

        // Tren bulanan
        $monthlyOrders = Order::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as total'),
            DB::raw('SUM(quantity) as qty_total'),
            DB::raw('SUM(total_price) as revenue')
        )
        ->where('status','accepted')
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

        // Detail orders
        $orders = (clone $query)->get();

        // --- Tambahan: Keuangan ---
        $expenses = Expense::sum('amount'); // total biaya
        $debts    = Debt::whereIn('status',['unpaid','overdue'])->sum('amount'); // kasbon aktif
        $profitLoss = $totalRevenue - $expenses; // laba/rugi sederhana

        // --- Tambahan: Operasional ---
        $delayedCount = \App\Models\SupplierSchedule::where('status','delayed')->count();

        $recentIncreasesCount = \App\Models\SupplierPrice::select('supplier_id','product_id')
            ->groupBy('supplier_id','product_id')
            ->get()
            ->filter(function($row){
                $latest = \App\Models\SupplierPrice::latestFor($row->supplier_id,$row->product_id);
                $previous = \App\Models\SupplierPrice::where('supplier_id',$row->supplier_id)
                    ->where('product_id',$row->product_id)
                    ->where('id','<',$latest->id)
                    ->orderByDesc('date')->first();
                return $latest && $previous && $latest->price > $previous->price
                    && (($latest->price - $previous->price)/max($previous->price,1))*100 >= 10;
            })->count();

        $lowStocksCount = Product::where('stock','<=',10)->count();


        return compact(
            'mode','periode','date',
            'totalOrders','pendingOrders','acceptedOrders','rejectedOrders',
            'totalProducts','activeProducts','pendingProducts',
            'totalRevenue','totalDiscount','ordersPerSupplier','monthlyOrders','productsPerSupplier',
            'orders','expenses','debts','profitLoss',
            'delayedCount','recentIncreasesCount','lowStocksCount'
        );
    }

    public function index(Request $request)
    {
        $mode    = $request->get('mode');
        $periode = $request->get('periode');
        $date    = $request->get('date');

        return view('admin.reports.index', $this->getReportData($mode,$periode,$date));
    }

    public function exportPdf(Request $request)
    {
        $mode    = $request->get('mode');
        $periode = $request->get('periode');
        $date    = $request->get('date');

        $pdf = Pdf::loadView('admin.reports.pdf', $this->getReportData($mode,$periode,$date));
        return $pdf->download('laporan-transaksi.pdf');
    }
}
