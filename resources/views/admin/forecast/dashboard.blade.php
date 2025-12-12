@extends('layouts.dashboard')

@section('title','Forecasting Permintaan')

@section('content')
<div class="container-fluid pt-4 px-4">
  <div class="bg-secondary rounded h-100 p-4">
    <h6 class="mb-3">Forecasting Permintaan Produk: {{ $product->name }}</h6>

    {{-- Grafik Actual vs Forecast --}}
    <canvas id="forecastChart" height="100"></canvas>

    {{-- Insight --}}
    <div class="alert alert-info mt-3">
      {{ $insight }}
    </div>

     {{-- Tombol Kembali --}}
    <a href="{{ route('admin.forecast.summary') }}" class="btn btn-light mt-3">
      <i class="fa fa-arrow-left me-2"></i>Kembali ke Ringkasan
    </a>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('forecastChart');
new Chart(ctx, {
  type: 'line',
  data: {
    labels: @json($labels),
    datasets: [
      {
        label: 'Actual',
        data: @json($actual),
        borderColor: 'red',
        backgroundColor: 'rgba(255,0,0,0.2)',
        fill: false,
        tension: 0.3
      },
      {
        label: 'Forecast',
        data: @json($forecast),
        borderColor: 'blue',
        backgroundColor: 'rgba(0,0,255,0.2)',
        fill: false,
        tension: 0.3
      }
    ]
  },
  options: {
    responsive: true,
    plugins: {
      legend: {
        position: 'top',
        labels: { color: '#fff' }
      },
      title: {
        display: true,
        text: 'Grafik Actual vs Forecast',
        color: '#fff'
      }
    },
    scales: {
      x: {
        ticks: { color: '#fff' }
      },
      y: {
        ticks: { color: '#fff' }
      }
    }
  }
});
</script>
@endsection
