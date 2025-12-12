<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\ForecastMetric;
use App\Models\ForecastResult;
use App\Http\Controllers\Controller;

class ForecastController extends Controller
{
    // --- Helper Methods ---
    private function calculateMAD(array $actual, array $forecast)
    {
        $n = min(count($actual), count($forecast));
        if ($n === 0) return null;

        $sum = 0;
        for ($i = 0; $i < $n; $i++) {
            $sum += abs($actual[$i] - $forecast[$i]);
        }
        return $sum / $n;
    }

    private function calculateMAPE(array $actual, array $forecast)
    {
        $n = min(count($actual), count($forecast));
        if ($n === 0) return null;

        $sum = 0;
        for ($i = 0; $i < $n; $i++) {
            if ($actual[$i] != 0) {
                $sum += abs(($actual[$i] - $forecast[$i]) / $actual[$i]);
            }
        }
        return ($sum / $n) * 100;
    }

    // --- Generate Forecast ---
    public function generate(Product $product)
    {
        // Ambil total penjualan bulan ini
        $currentMonthSales = $product->orders()
            ->whereMonth('created_at', now()->month)
            ->sum('quantity');

        if ($currentMonthSales <= 0) {
            return back()->with('error', 'Belum ada data penjualan bulan ini');
        }

        // Forecast bulan depan dengan SES
        $lastForecast = ForecastResult::where('product_id', $product->id)
            ->orderBy('period', 'desc')
            ->first();

        $alpha = 0.3;
        $forecastNext = $lastForecast
            ? ($alpha * $currentMonthSales) + ((1 - $alpha) * $lastForecast->forecast)
            : $currentMonthSales;

        // Simpan forecast untuk bulan depan
        $period = now()->addMonth()->format('Y-m');
        ForecastResult::updateOrCreate(
            ['product_id' => $product->id, 'period' => $period],
            ['forecast' => round($forecastNext, 2), 'actual' => null]
        );

        // Hitung MAD & MAPE hanya jika sudah ada data aktual
        $results = ForecastResult::where('product_id', $product->id)->orderBy('period')->get();
        $actual = $results->pluck('actual')->filter()->toArray();
        $forecast = $results->pluck('forecast')->filter()->toArray();

        if (count($actual) > 1) {
            $mad = $this->calculateMAD($actual, $forecast);
            $mape = $this->calculateMAPE($actual, $forecast);

            ForecastMetric::updateOrCreate(
                ['product_id' => $product->id],
                ['mad' => $mad, 'mape' => $mape]
            );
        }

        return redirect()->route('admin.forecast.dashboard', $product->id)
            ->with('success', "Forecast bulan depan: siapkan stok sekitar " . round($forecastNext) . " unit.");
    }

    // --- Dashboard Forecast ---
    public function dashboard(Product $product)
    {
        $results = ForecastResult::where('product_id', $product->id)->orderBy('period')->get();
        $labels = $results->pluck('period');
        $forecast = $results->pluck('forecast');
        $actual = $results->pluck('actual');

        // Ambil metrics untuk backend evaluasi
        $metrics = ForecastMetric::where('product_id', $product->id)->first();

        // Insight sederhana
        $insight = "Forecast bulan depan: " . ($forecast->last() ?? '-') . " unit";

        return view('admin.forecast.dashboard', compact('product', 'labels', 'actual', 'forecast', 'metrics', 'insight'));
    }

    // --- Summary Forecast ---
    public function summary()
    {
        $products = Product::with(['forecastResults', 'forecastMetric'])->paginate(10);
        return view('admin.forecast.summary', compact('products'));
    }
}
