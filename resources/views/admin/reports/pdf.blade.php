<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background: #eee; }
        h3, h4 { margin-bottom: 10px; }
    </style>
</head>
<body>
    {{-- Header dengan logo UPI --}}
    <table style="width:100%; margin-bottom:20px; border:none;">
        <tr>
            <td style="width:80px; border:none;">
                <img src="{{ public_path('images/logo-upi.png') }}" alt="Logo UPI" style="width:70px;">
            </td>
            <td style="border:none; text-align:center;">
                <h3 style="margin:0;">Laporan & Rekap Transaksi</h3>
                <p style="margin:0;">Universitas Pendidikan Indonesia</p>
            </td>
        </tr>
    </table>

    <p><strong>Periode:</strong> {{ ucfirst($periode) }} ({{ $date }})</p>

    {{-- Ringkasan Pesanan --}}
    <p><strong>Total Pesanan:</strong> {{ $totalOrders }}</p>
    <p>Pending: {{ $pendingOrders }} | Diterima: {{ $acceptedOrders }} | Ditolak: {{ $rejectedOrders }}</p>

    {{-- Ringkasan Produk --}}
    <p><strong>Total Produk:</strong> {{ $totalProducts }}</p>
    <p>Aktif: {{ $activeProducts }} | Pending: {{ $pendingProducts }}</p>

    {{-- Ringkasan Diskon Membership --}}
    <p><strong>Total Diskon Diberikan:</strong> Rp {{ number_format($ordersPerSupplier->sum('discount'),0,',','.') }}</p>

    {{-- Revenue --}}
    <p><strong>Total Revenue (Simulatif):</strong> Rp {{ number_format($totalRevenue,0,',','.') }}</p>

    {{-- Pesanan per Supplier/Admin --}}
    <h4>Pesanan per Supplier/Admin</h4>
    <table>
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
            <tr><td colspan="8" style="text-align:center; color:#666;">Belum ada data pesanan.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- Produk per Supplier/Admin --}}
    <h4>Produk per Supplier/Admin</h4>
    <table>
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
            <tr><td colspan="4" style="text-align:center; color:#666;">Belum ada data produk.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- Detail Orders --}}
    <h4>Detail Pesanan</h4>
    <table>
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
    <h4>Tren Pesanan Bulanan</h4>
    <table>
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
            <tr><td colspan="3" style="text-align:center; color:#666;">Belum ada data bulanan.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- Footer --}}
    <p style="text-align:right; margin-top:40px;">
        Dicetak pada {{ now()->format('d M Y H:i') }}<br>
        <strong>Usman</strong>
    </p>
</body>
</html>
