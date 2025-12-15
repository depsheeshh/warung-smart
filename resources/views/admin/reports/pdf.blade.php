<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan & Rekap Transaksi</title>

<style>
@page { margin: 32px 40px; }

body {
    font-family: "DejaVu Sans", Helvetica, Arial, sans-serif;
    font-size: 11.5px;
    color: #2b2b2b;
    line-height: 1.6;
}

/* ===== HEADER ===== */
.header {
    border-bottom: 3px solid #1f3d7a;
    padding-bottom: 14px;
    margin-bottom: 22px;
}

.header table { width: 100%; }

.header h1 {
    margin: 0;
    font-size: 18px;
    letter-spacing: 1px;
    color: #1f3d7a;
}

.header p {
    margin: 2px 0 0;
    font-size: 12.5px;
    color: #555;
}

/* ===== META ===== */
.meta {
    font-size: 11px;
    margin-bottom: 18px;
}

.meta strong { color: #1f3d7a; }

/* ===== SECTION ===== */
.section { margin-top: 28px; }

.section-title {
    font-size: 14px;
    font-weight: bold;
    color: #1f3d7a;
    margin-bottom: 10px;
    border-left: 4px solid #1f3d7a;
    padding-left: 8px;
}

/* ===== SUMMARY BOX ===== */
.summary-box {
    background: #f4f7fb;
    border: 1px solid #d9e2ef;
    padding: 12px 14px;
    margin-bottom: 14px;
}

.summary-line { margin-bottom: 4px; }

/* ===== TABLE ===== */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 8px;
    font-size: 11px;
}

thead th {
    background: #1f3d7a;
    color: #fff;
    padding: 7px 6px;
    text-align: left;
}

tbody td {
    padding: 6px;
    border-bottom: 1px solid #e2e2e2;
}

tbody tr:nth-child(even) {
    background: #fafafa;
}

/* ===== FOOTER ===== */
.footer {
    margin-top: 50px;
    text-align: right;
    font-size: 11px;
}

.signature {
    margin-top: 42px;
    font-weight: bold;
    text-decoration: underline;
}
</style>
</head>

<body>

<!-- HEADER -->
<div class="header">
<table>
<tr>
<td style="width:80px;">
    <img src="{{ public_path('img/logo.png') }}" style="width:70px;">
</td>
<td style="text-align:center;">
    <h1>LAPORAN & REKAP TRANSAKSI</h1>
    <p>WarungSmart</p>
</td>
</tr>
</table>
</div>

<!-- META -->
<div class="meta">
<strong>Filter Laporan:</strong>
@if($mode === 'periode' && $periode)
    Periode {{ ucfirst($periode) }} ({{ $date }})
@elseif($mode === 'date' && $date)
    Tanggal {{ $date }}
@else
    Tanpa filter
@endif
</div>

<!-- RINGKASAN PESANAN -->
<div class="section">
<div class="section-title">Ringkasan Pesanan</div>
<div class="summary-box">
    <div class="summary-line"><strong>Total Pesanan:</strong> {{ $totalOrders }}</div>
    <div class="summary-line">
        Pending: {{ $pendingOrders }} |
        Diterima: {{ $acceptedOrders }} |
        Ditolak: {{ $rejectedOrders }}
    </div>
</div>
</div>

<!-- RINGKASAN PRODUK -->
<div class="section">
<div class="section-title">Ringkasan Produk</div>
<div class="summary-box">
    <div class="summary-line"><strong>Total Produk:</strong> {{ $totalProducts }}</div>
    <div class="summary-line">
        Aktif: {{ $activeProducts }} |
        Pending: {{ $pendingProducts }}
    </div>
</div>
</div>

<!-- KEUANGAN -->
<div class="section">
<div class="section-title">Ringkasan Keuangan</div>
<div class="summary-box">
    <div class="summary-line"><strong>Pendapatan:</strong> Rp {{ number_format($totalRevenue,0,',','.') }}</div>
    <div class="summary-line"><strong>Biaya:</strong> Rp {{ number_format($expenses,0,',','.') }}</div>
    <div class="summary-line"><strong>Kasbon Aktif:</strong> Rp {{ number_format($debts,0,',','.') }}</div>
    <div class="summary-line"><strong>Laba / Rugi:</strong> Rp {{ number_format($profitLoss,0,',','.') }}</div>
</div>
</div>

<!-- OPERASIONAL -->
<div class="section">
<div class="section-title">Ringkasan Operasional</div>
<div class="summary-box">
    <div class="summary-line">Supplier Telat: {{ $delayedCount }}</div>
    <div class="summary-line">Harga Naik ≥10%: {{ $recentIncreasesCount }}</div>
    <div class="summary-line">Produk Stok Rendah (≤10): {{ $lowStocksCount }}</div>
</div>
</div>

<!-- PESANAN PER SUPPLIER -->
<div class="section">
<div class="section-title">Pesanan per Supplier / Admin</div>
<table>
<thead>
<tr>
<th>Supplier</th>
<th>Total</th>
<th>Diterima</th>
<th>Ditolak</th>
<th>Qty</th>
<th>Terjual</th>
<th>Revenue</th>
<th>Diskon</th>
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
<tr><td colspan="8" style="text-align:center;">Tidak ada data</td></tr>
@endforelse
</tbody>
</table>
</div>

<!-- PRODUK PER SUPPLIER -->
<div class="section">
<div class="section-title">Produk per Supplier / Admin</div>
<table>
<thead>
<tr>
<th>Supplier</th>
<th>Aktif</th>
<th>Pending</th>
<th>Total</th>
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
<tr><td colspan="4" style="text-align:center;">Tidak ada data</td></tr>
@endforelse
</tbody>
</table>
</div>

<!-- DETAIL PESANAN -->
<div class="section">
<div class="section-title">Detail Pesanan</div>
<table>
<thead>
<tr>
<th>Customer</th>
<th>Produk</th>
<th>Qty</th>
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

<!-- TREN BULANAN -->
<div class="section">
<div class="section-title">Tren Pesanan Bulanan</div>
<table>
<thead>
<tr>
<th>Bulan</th>
<th>Total Pesanan</th>
<th>Jumlah Barang</th>
<th>Revenue</th>
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
<tr><td colspan="4" style="text-align:center;">Tidak ada data</td></tr>
@endforelse
</tbody>
</table>
</div>

<!-- FOOTER -->
<div class="footer">
Dicetak pada {{ now()->format('d M Y H:i') }}<br>
<span class="signature">{{ auth()->user()->name }}</span>
</div>

</body>
</html>
