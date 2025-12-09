@extends('layouts.dashboard')
@section('title','Laporan & Rekap Transaksi')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary rounded h-100 p-4">
        <h6 class="mb-3">Ringkasan Transaksi (Periode: {{ ucfirst($periode) }} - {{ $date }})</h6>

        <a href="{{ route('admin.reports.pdf') }}" class="btn btn-danger mb-3">
            <i class="fa fa-file-pdf me-2"></i>Export PDF
        </a>

        <form method="GET" action="{{ route('admin.reports.index') }}" class="mb-3">
        <div class="row">
            <div class="col-md-3">
                <select name="periode" class="form-select">
                    <option value="daily" {{ $periode=='daily'?'selected':'' }}>Harian</option>
                    <option value="monthly" {{ $periode=='monthly'?'selected':'' }}>Bulanan</option>
                    <option value="yearly" {{ $periode=='yearly'?'selected':'' }}>Tahunan</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="date" name="date" value="{{ $date }}" class="form-control">
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
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted">Belum ada data pesanan.</td></tr>
                @endforelse
            </tbody>
        </table>

        {{-- Produk per Supplier/Admin --}}
        <h6 class="mb-3">Produk per Supplier/Admin</h6>
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

        <h6 class="mb-3">Detail Pesanan</h6>
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
            <td>{{ $order->product->name ?? $order->product->nama_barang ?? '—' }}</td>
            <td>{{ $order->quantity }}</td>
            <td>Rp {{ number_format($order->product->price,0,',','.') }}</td>
            <td>Rp {{ number_format($order->price_snapshot ?? $order->product->price,0,',','.') }}</td>
            <td>Rp {{ number_format(max(0, ($order->product->price - ($order->price_snapshot ?? $order->product->price))) * $order->quantity,0,',','.') }}</td>
            <td>{{ ucfirst($order->status) }}</td>
            <td>{{ $order->created_at->format('d M Y H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
        </table>

        {{-- Tren Bulanan --}}
        <div class="mb-4">
            <h6>Tren Pesanan Bulanan</h6>
            <table class="table table-bordered text-white align-middle">
                <thead>
                    <tr>
                        <th>Bulan</th>
                        <th>Total Pesanan</th>
                        <th>Jumlah Barang Dipesan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($monthlyOrders as $row)
                    <tr>
                        <td>{{ \Carbon\Carbon::create($row->year, $row->month)->translatedFormat('F Y') }}</td>
                        <td>{{ $row->total }}</td>
                        <td>{{ $row->qty_total }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="text-center text-muted">Belum ada data bulanan.</td></tr>
                    @endforelse
                </tbody>
            </table>
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

    if (supplierLabels.length > 0) {
        new Chart(document.getElementById('supplierChart'), {
            type: 'bar',
            data: {
                labels: supplierLabels,
                datasets: [
                    { label: 'Jumlah Pesanan', data: supplierOrders, backgroundColor: 'rgba(54,162,235,0.7)' },
                    { label: 'Revenue', data: supplierRevenue, backgroundColor: 'rgba(75,192,192,0.7)' }
                ]
            }
        });
    }

    // Chart Tren Bulanan
    const monthlyLabels = {!! json_encode($monthlyOrders->map(fn($row) => \Carbon\Carbon::create($row->year, $row->month)->translatedFormat('F Y'))) !!};
    const monthlyTotals = {!! json_encode($monthlyOrders->pluck('total')) !!};

    if (monthlyLabels.length > 0) {
        new Chart(document.getElementById('monthlyChart'), {
            type: 'line',
            data: {
                labels: monthlyLabels,
                datasets: [
                    { label: 'Total Pesanan', data: monthlyTotals, borderColor: 'rgba(255,99,132,1)', fill: false }
                ]
            }
        });
    }
</script>
@endpush
