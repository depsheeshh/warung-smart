@extends('layouts.dashboard')
@section('title','Dashboard Admin')

@section('content')
<div class="container-fluid pt-4 px-4">
  <div class="bg-secondary rounded h-100 p-4">
    <h6 class="mb-4">Dashboard Admin</h6>
    <p class="text-white">Selamat datang, {{ Auth::user()->name }} 👋</p>
    <p class="text-white">Anda login sebagai <strong>Admin</strong>.</p>

    {{-- Indikator ringkasan --}}
    <div class="row mb-4">
      <div class="col-md-3"><div class="card bg-dark text-white p-3"><h6>Total Produk</h6><h4>{{ $totalProducts ?? 0 }}</h4></div></div>
      <div class="col-md-3"><div class="card bg-success text-white p-3"><h6>Total Pesanan</h6><h4>{{ $totalOrders ?? 0 }}</h4></div></div>
      <div class="col-md-3"><div class="card bg-info text-dark p-3"><h6>Total Customer</h6><h4>{{ $totalCustomers ?? 0 }}</h4></div></div>
      <div class="col-md-3"><div class="card bg-warning text-dark p-3"><h6>Total Supplier</h6><h4>{{ $totalSuppliers ?? 0 }}</h4></div></div>
    </div>

    {{-- Chart aktivitas pesanan --}}
    <h6 class="mb-3">Aktivitas Pesanan Bulanan</h6>
    <canvas id="adminChart" height="120"></canvas>

    {{-- Ringkasan Kasbon --}}
    <h6 class="mt-5 mb-3">Ringkasan Kasbon</h6>
    <div class="row mb-4">
      <div class="col-md-4">
        <div class="card bg-danger text-white p-3">
          <h6>Total Kasbon Aktif</h6>
          <h4>Rp {{ number_format($totalDebts,0,',','.') }}</h4>
        </div>
      </div>
      <div class="col-md-8">
        <div class="card bg-dark text-white p-3">
          <h6>Top 5 Pelanggan dengan Hutang Terbesar</h6>
          <table class="table table-sm table-bordered text-white align-middle">
            <thead>
              <tr><th>#</th><th>Nama</th><th>Total Hutang</th></tr>
            </thead>
            <tbody>
              @foreach($topCustomers as $index => $cust)
              <tr>
                <td>{{ $index+1 }}</td>
                <td>{{ $cust->name }}</td>
                <td>Rp {{ number_format($cust->debts_sum_amount,0,',','.') }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>

    {{-- Supplier Telat --}}
    <h6 class="mt-5 mb-3">Supplier Telat (7 hari terakhir)</h6>
    <div class="card bg-dark text-white p-3 mb-4">
    <table class="table table-sm table-bordered text-white align-middle mb-0">
        <thead>
        <tr><th>Supplier</th><th>Jadwal</th><th>Datang</th><th>Status</th></tr>
        </thead>
        <tbody>
        @forelse($delayedSchedules as $s)
            <tr>
            <td>{{ $s->supplier->name }}</td>
            <td>{{ $s->expected_date->format('d M Y') }}</td>
            <td>{{ optional($s->actual_date)->format('d M Y') ?? '—' }}</td>
            <td><span class="badge bg-danger">Delayed</span></td>
            </tr>
        @empty
            <tr><td colspan="4" class="text-center text-muted">Tidak ada keterlambatan.</td></tr>
        @endforelse
        </tbody>
    </table>
    </div>

    {{-- Harga Naik Signifikan --}}
    <h6 class="mt-5 mb-3">Harga Naik Signifikan (≥10%)</h6>
    <div class="card bg-dark text-white p-3 mb-4">
    <div class="table-responsive">
    <table class="table table-sm table-bordered text-white align-middle mb-0">
        <thead>
        <tr><th>Produk</th><th>Supplier</th><th>Tanggal</th><th>Harga Lama</th><th>Harga Baru</th><th>Kenaikan</th></tr>
        </thead>
        <tbody>
        @forelse($recentPriceIncreases as $p)
            <tr>
            <td>{{ $p['product'] }}</td>
            <td>{{ $p['supplier'] }}</td>
            <td>{{ $p['date'] }}</td>
            <td>Rp {{ number_format($p['previous_price'],0,',','.') }}</td>
            <td>Rp {{ number_format($p['current_price'],0,',','.') }}</td>
            <td><span class="badge bg-warning text-dark">{{ $p['percent_increase'] }}%</span></td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-center text-muted">Tidak ada kenaikan signifikan.</td></tr>
        @endforelse
        </tbody>
    </table>
    </div>
    </div>

    {{-- Stok Rendah --}}
    <h6 class="mt-5 mb-3">Stok Rendah</h6>
    <div class="card bg-dark text-white p-3 mb-4">
    <table class="table table-sm table-bordered text-white align-middle mb-0">
        <thead>
        <tr><th>Produk</th><th>Supplier</th><th>Stok</th></tr>
        </thead>
        <tbody>
        @forelse($lowStocks as $p)
            <tr>
            <td>{{ $p->name }}</td>
            <td>{{ $p->supplier?->name ?? '—' }}</td>
            <td><span class="badge bg-danger">{{ $p->stock }}</span></td>
            </tr>
        @empty
            <tr><td colspan="3" class="text-center text-muted">Tidak ada stok rendah.</td></tr>
        @endforelse
        </tbody>
    </table>
    </div>


    {{-- Chart Kasbon Bulanan --}}
    <h6 class="mb-3">Grafik Kasbon Bulanan</h6>
    <canvas id="debtChart" height="120"></canvas>
  </div>
</div>
@endsection

@push('scripts')
<script>
  // Chart Pesanan Bulanan
  const adminLabels = {!! json_encode($monthlyOrders->map(fn($row) => \Carbon\Carbon::create($row->year,$row->month)->translatedFormat('F Y'))) !!};
  const adminTotals = {!! json_encode($monthlyOrders->pluck('total')) !!};

  new Chart(document.getElementById('adminChart'), {
    type: 'line',
    data: {
      labels: adminLabels,
      datasets: [
        { label: 'Total Pesanan', data: adminTotals, borderColor: 'rgba(75,192,192,1)', fill: false }
      ]
    }
  });

  // Chart Kasbon Bulanan
  const debtLabels = {!! json_encode($monthlyDebts->map(fn($row) => \Carbon\Carbon::create($row->year,$row->month)->translatedFormat('F Y'))) !!};
  const debtTotals = {!! json_encode($monthlyDebts->pluck('total_debt')) !!};

  new Chart(document.getElementById('debtChart'), {
    type: 'bar',
    data: {
      labels: debtLabels,
      datasets: [
        { label: 'Total Kasbon (Rp)', data: debtTotals, backgroundColor: 'rgba(255,99,132,0.7)', borderColor: 'rgba(255,99,132,1)', borderWidth: 1 }
      ]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            callback: function(value) {
              return 'Rp ' + value.toLocaleString();
            }
          }
        }
      }
    }
  });
</script>
@endpush
