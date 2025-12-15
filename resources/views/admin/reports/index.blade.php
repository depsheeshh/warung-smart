@extends('layouts.dashboard')
@section('title','Laporan & Rekap Transaksi')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary rounded h-100 p-4">
        <h6 class="mb-3">
            Ringkasan Transaksi
            @if($mode === 'periode' && $periode)
                (Periode: {{ ucfirst($periode) }} - {{ $date }})
            @elseif($mode === 'date' && $date)
                (Tanggal: {{ $date }})
            @else
                (Belum ada filter)
            @endif
        </h6>

        <a href="{{ route('admin.reports.pdf', request()->all()) }}" class="btn btn-danger mb-3">
            <i class="fa fa-file-pdf me-2"></i>Export PDF
        </a>

        {{-- Filter Form --}}
        <form method="GET" action="{{ route('admin.reports.index') }}" class="mb-3">
            <div class="row">
                <div class="col-md-3">
                    <select name="mode" class="form-select" onchange="toggleFilter(this.value)">
                        <option value="">-- Pilih Mode --</option>
                        <option value="periode" {{ $mode=='periode'?'selected':'' }}>Periode (Harian/Bulanan/Tahunan)</option>
                        <option value="date" {{ $mode=='date'?'selected':'' }}>Tanggal Spesifik</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="periode" id="periodeSelect" class="form-select" {{ $mode!='periode'?'disabled':'' }}>
                        <option value="daily" {{ $periode=='daily'?'selected':'' }}>Harian</option>
                        <option value="monthly" {{ $periode=='monthly'?'selected':'' }}>Bulanan</option>
                        <option value="yearly" {{ $periode=='yearly'?'selected':'' }}>Tahunan</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="date" name="date" id="dateInput" value="{{ $date }}" class="form-control" {{ $mode!='date'?'disabled':'' }}>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">Terapkan</button>
                </div>
            </div>
        </form>

        {{-- Ringkasan Pesanan --}}
        <div class="row mb-4">
            <div class="col-md-3"><div class="card bg-dark text-white p-3"><h6>Total Pesanan</h6><h4>{{ $totalOrders }}</h4></div></div>
            <div class="col-md-3"><div class="card bg-warning text-dark p-3"><h6>Pending</h6><h4>{{ $pendingOrders }}</h4></div></div>
            <div class="col-md-3"><div class="card bg-success text-white p-3"><h6>Diterima</h6><h4>{{ $acceptedOrders }}</h4></div></div>
            <div class="col-md-3"><div class="card bg-danger text-white p-3"><h6>Ditolak</h6><h4>{{ $rejectedOrders }}</h4></div></div>
        </div>

        {{-- Ringkasan Produk --}}
        <h6 class="mb-3">Ringkasan Produk</h6>
        <div class="row mb-4">
            <div class="col-md-4"><div class="card bg-dark text-white p-3"><h6>Total Produk</h6><h4>{{ $totalProducts }}</h4></div></div>
            <div class="col-md-4"><div class="card bg-success text-white p-3"><h6>Aktif</h6><h4>{{ $activeProducts }}</h4></div></div>
            <div class="col-md-4"><div class="card bg-warning text-dark p-3"><h6>Pending</h6><h4>{{ $pendingProducts }}</h4></div></div>
        </div>

        {{-- Ringkasan Keuangan --}}
        <h6 class="mb-3">Ringkasan Keuangan</h6>
        <div class="row mb-4">
        <div class="col-md-3"><div class="card bg-success text-white p-3"><h6>Pendapatan</h6><h4>Rp {{ number_format($totalRevenue,0,',','.') }}</h4></div></div>
        <div class="col-md-3"><div class="card bg-danger text-white p-3"><h6>Biaya</h6><h4>Rp {{ number_format($expenses,0,',','.') }}</h4></div></div>
        <div class="col-md-3"><div class="card bg-warning text-dark p-3"><h6>Kasbon Aktif</h6><h4>Rp {{ number_format($debts,0,',','.') }}</h4></div></div>
        <div class="col-md-3">
            <div class="card {{ $profitLoss >= 0 ? 'bg-info text-dark' : 'bg-danger text-white' }} p-3">
            <h6>Laba/Rugi</h6>
            <h4>Rp {{ number_format($profitLoss,0,',','.') }}</h4>
            </div>
        </div>
        </div>

        {{-- Ringkasan Operasional --}}
        <h6 class="mb-3">Ringkasan Operasional</h6>
        <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-danger text-white p-3">
            <h6>Supplier Telat</h6>
            <h4>{{ $delayedCount }}</h4>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-dark p-3">
            <h6>Harga Naik ≥10%</h6>
            <h4>{{ $recentIncreasesCount }}</h4>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-dark text-white p-3">
            <h6>Produk Stok Rendah (≤10)</h6>
            <h4>{{ $lowStocksCount }}</h4>
            </div>
        </div>
        </div>


        {{-- Detail Pengeluaran --}}
        <h6 class="mb-3">Detail Pengeluaran</h6>
        <div class="table-responsive">
        <table class="table table-bordered text-white align-middle">
        <thead>
            <tr>
            <th>Kategori</th>
            <th>Tanggal</th>
            <th>Nominal</th>
            <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse(\App\Models\Expense::orderByDesc('date')->take(10)->get() as $expense)
            <tr>
                <td>{{ $expense->category }}</td>
                <td>{{ $expense->date->format('d M Y') }}</td>
                <td>Rp {{ number_format($expense->amount,0,',','.') }}</td>
                <td>{{ $expense->notes ?? '—' }}</td>
            </tr>
            @empty
            <tr><td colspan="4" class="text-center text-muted">Belum ada data pengeluaran.</td></tr>
            @endforelse
        </tbody>
        </table>
        </div>

        {{-- Grafik Keuangan --}}
        <h6 class="mb-3">Grafik Keuangan (Pendapatan vs Biaya vs Kasbon)</h6>
        <canvas id="financeChart" height="120"></canvas>

        <div class="card bg-dark text-white p-3 mb-3">
            <h6>Ringkasan Diskon Membership</h6>
            <p>Total Diskon Diberikan: Rp {{ number_format($ordersPerSupplier->sum('discount'),0,',','.') }}</p>
        </div>

        {{-- Revenue --}}
        <div class="mb-4">
            <h6>Total Revenue (Simulatif)</h6>
            <h4>Rp {{ number_format($totalRevenue,0,',','.') }}</h4>
        </div>

        {{-- Pesanan per Supplier/Admin --}}
        <h6 class="mb-3">Pesanan per Supplier/Admin</h6>
        <div class="table-responsive">
        <table class="table table-bordered text-white align-middle">
            <thead>
                <tr>
                    <th>Supplier/Admin</th>
                    <th>Total Pesanan</th>
                    <th>Diterima</th>
                    <th>Ditolak</th>
                    <th>Jumlah Barang Dipesan</th>
                    <th>Barang Terjual</th>
                    <th>Total Revenue</th>
                    <th>Total Diskon</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ordersPerSupplier as $supplier => $data)
                <tr>
                    <td>{{ $supplier }}</td>
                    <td>{{ $data['orders'] }}</td>
                    <td>{{ $data['accepted'] }}</td>
                    <td>{{ $data['rejected'] }}</td>
                    <td>{{ $data['qty_total'] }}</td>
                    <td>{{ $data['qty_sold'] }}</td>
                    <td>Rp {{ number_format($data['revenue'],0,',','.') }}</td>
                    <td>Rp {{ number_format($data['discount'],0,',','.') }}</td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted">Belum ada data pesanan.</td></tr>
                @endforelse
            </tbody>
        </table>
        </div>

        {{-- Produk per Supplier/Admin --}}
        <h6 class="mb-3">Produk per Supplier/Admin</h6>
        <div class="table-responsive">
        <table class="table table-bordered text-white align-middle">
            <thead>
                <tr>
                    <th>Supplier/Admin</th>
                    <th>Produk Aktif</th>
                    <th>Produk Pending</th>
                    <th>Total Produk</th>
                </tr>
            </thead>
            <tbody>
                @forelse($productsPerSupplier as $supplier => $data)
                <tr>
                    <td>{{ $supplier }}</td>
                    <td>{{ $data['active'] }}</td>
                    <td>{{ $data['pending'] }}</td>
                    <td>{{ $data['total'] }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center text-muted">Belum ada data produk.</td></tr>
                @endforelse
            </tbody>
        </table>
        </div>

        {{-- Detail Pesanan --}}
        <h6 class="mb-3">Detail Pesanan</h6>
        <div class="table-responsive">
        <table class="table table-bordered text-white">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Produk</th>
                    <th>Jumlah</th>
                    <th>Harga Asli</th>
                    <th>Harga Final</th>
                    <th>Diskon</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td>{{ $order->customer->name ?? '—' }}</td>
                    <td>{{ $order->product->name ?? '—' }}</td>
                    <td>{{ $order->quantity }}</td>
                    <td>Rp {{ number_format($order->product->price,0,',','.') }}</td>
                    <td>Rp {{ number_format($order->unit_price,0,',','.') }}</td>
                    <td>Rp {{ number_format(max(0, ($order->product->price - $order->unit_price)) * $order->quantity,0,',','.') }}</td>
                    <td>{{ ucfirst($order->status) }}</td>
                    <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>


        {{-- Tren Bulanan --}}
        <div class="mb-4">
            <h6>Tren Pesanan Bulanan</h6>
            <div class="table-responsive">
            <table class="table table-bordered text-white align-middle">
                <thead>
                    <tr>
                        <th>Bulan</th>
                        <th>Total Pesanan</th>
                        <th>Jumlah Barang Dipesan</th>
                        <th>Total Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($monthlyOrders as $row)
                    <tr>
                        <td>{{ \Carbon\Carbon::create($row->year, $row->month)->translatedFormat('F Y') }}</td>
                        <td>{{ $row->total }}</td>
                        <td>{{ $row->qty_total }}</td>
                        <td>Rp {{ number_format($row->revenue,0,',','.') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center text-muted">Belum ada data bulanan.</td></tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div>

        {{-- Chart Section --}}
        <h6 class="mb-3">Visualisasi Data</h6>
        <div class="row">
            <div class="col-md-6"><canvas id="supplierChart" height="120"></canvas></div>
            <div class="col-md-6"><canvas id="monthlyChart" height="120"></canvas></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Chart Pesanan per Supplier
    const supplierLabels = {!! json_encode(array_keys($ordersPerSupplier->toArray())) !!};
    const supplierOrders = {!! json_encode(collect($ordersPerSupplier)->pluck('orders')->values()) !!};
    const supplierRevenue= {!! json_encode(collect($ordersPerSupplier)->pluck('revenue')->values()) !!};
    const supplierDiscount= {!! json_encode(collect($ordersPerSupplier)->pluck('discount')->values()) !!};

    if (supplierLabels.length > 0) {
        new Chart(document.getElementById('supplierChart'), {
            type: 'bar',
            data: {
                labels: supplierLabels,
                datasets: [
                    { label: 'Jumlah Pesanan', data: supplierOrders, backgroundColor: 'rgba(54,162,235,0.7)' },
                    { label: 'Revenue', data: supplierRevenue, backgroundColor: 'rgba(75,192,192,0.7)' },
                    { label: 'Diskon', data: supplierDiscount, backgroundColor: 'rgba(255,206,86,0.7)' }
                ]
            }
        });
    }

    // Chart Tren Bulanan
    const monthlyLabels = {!! json_encode($monthlyOrders->map(fn($row) => \Carbon\Carbon::create($row->year, $row->month)->translatedFormat('F Y'))) !!};
    const monthlyTotals = {!! json_encode($monthlyOrders->pluck('total')) !!};
    const monthlyRevenue= {!! json_encode($monthlyOrders->pluck('revenue')) !!};

    if (monthlyLabels.length > 0) {
        new Chart(document.getElementById('monthlyChart'), {
            type: 'line',
            data: {
                labels: monthlyLabels,
                datasets: [
                    { label: 'Total Pesanan', data: monthlyTotals, borderColor: 'rgba(255,99,132,1)', fill: false },
                    { label: 'Revenue', data: monthlyRevenue, borderColor: 'rgba(75,192,192,1)', fill: false }
                ]
            }
        });
    }

    // Toggle filter input sesuai mode
    function toggleFilter(mode) {
        document.getElementById('periodeSelect').disabled = (mode !== 'periode');
        document.getElementById('dateInput').disabled = (mode !== 'date');
    }
</script>
@endpush

@push('scripts')
<script>
  // Chart Keuangan
  const financeLabels = ['Pendapatan','Biaya','Kasbon'];
  const financeData   = [{{ $totalRevenue }}, {{ $expenses }}, {{ $debts }}];

  new Chart(document.getElementById('financeChart'), {
    type: 'bar',
    data: {
      labels: financeLabels,
      datasets: [{
        label: 'Rp',
        data: financeData,
        backgroundColor: [
          'rgba(75,192,192,0.7)',
          'rgba(255,99,132,0.7)',
          'rgba(255,206,86,0.7)'
        ],
        borderColor: [
          'rgba(75,192,192,1)',
          'rgba(255,99,132,1)',
          'rgba(255,206,86,1)'
        ],
        borderWidth: 1
      }]
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

