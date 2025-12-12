@extends('layouts.dashboard')
@section('title','Dashboard Supplier')

@section('content')
<div class="container-fluid pt-4 px-4">
  <div class="bg-secondary rounded h-100 p-4">
    <h6 class="mb-4">Dashboard Supplier</h6>
    <p class="text-white">Halo {{ Auth::user()->name }} 👋</p>
    <p class="text-white">Anda login sebagai <strong>Supplier</strong>.</p>

    {{-- Indikator ringkasan --}}
    <div class="row mb-4">
      <div class="col-md-4"><div class="card bg-dark text-white p-3"><h6>Produk Aktif</h6><h4>{{ $activeProducts ?? 0 }}</h4></div></div>
      <div class="col-md-4"><div class="card bg-success text-white p-3"><h6>Pesanan Diterima</h6><h4>{{ $acceptedOrders ?? 0 }}</h4></div></div>
      <div class="col-md-4"><div class="card bg-info text-dark p-3"><h6>Total Revenue</h6><h4>Rp {{ number_format($totalRevenue ?? 0,0,',','.') }}</h4></div></div>
    </div>

    {{-- Chart penjualan --}}
    <h6 class="mb-3">Penjualan Bulanan</h6>
    <canvas id="supplierChart" height="120"></canvas>
  </div>
</div>
@endsection

@push('scripts')
<script>
  const supplierLabels = {!! json_encode($monthlyOrders->map(fn($row) => \Carbon\Carbon::create($row->year,$row->month)->translatedFormat('F Y'))) !!};
  const supplierRevenue = {!! json_encode($monthlyOrders->pluck('revenue')) !!};

  new Chart(document.getElementById('supplierChart'), {
    type: 'bar',
    data: {
      labels: supplierLabels,
      datasets: [
        { label: 'Revenue', data: supplierRevenue, backgroundColor: 'rgba(54,162,235,0.7)' }
      ]
    }
  });
</script>
@endpush
