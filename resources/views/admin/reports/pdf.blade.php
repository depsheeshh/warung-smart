<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi</title>

    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.4;
        }

        /* HEADER */
        .header-table {
            width: 100%;
            margin-bottom: 20px;
        }

        .header-title {
            font-size: 18px;
            font-weight: bold;
            margin: 0;
            letter-spacing: 0.5px;
        }

        .sub-title {
            font-size: 13px;
            margin-top: 3px;
            color: #555;
        }

        .divider {
            width: 100%;
            height: 2px;
            background: #2c3e50;
            margin: 12px 0 20px;
        }

        /* TABLE STYLE */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            font-size: 11.5px;
        }

        th {
            background: #e7f1ff;
            color: #1f3d7a;
            padding: 7px;
            border: 1px solid #cdd8e6;
            text-align: left;
            font-weight: bold;
        }

        td {
            padding: 7px;
            border: 1px solid #dcdcdc;
        }

        tbody tr:nth-child(even) {
            background: #fafafa;
        }

        /* SECTION TITLE */
        h4 {
            margin-top: 25px;
            margin-bottom: 10px;
            color: #1f3d7a;
            font-size: 15px;
        }

        /* FOOTER SIGNATURE */
        .footer-sign {
            margin-top: 50px;
            text-align: right;
            line-height: 1.6;
        }

        .signature {
            margin-top: 45px;
            font-weight: bold;
            font-size: 13px;
            text-decoration: underline;
        }

        /* SMALL TEXT */
        .muted {
            color: #666;
        }
    </style>

</head>
<body>

    {{-- HEADER --}}
    <table class="header-table">
        <tr>
            <td style="width:80px;">
                <img src="{{ public_path('img/logo.png') }}" alt="Logo" style="width:70px;">
            </td>
            <td style="text-align:center;">
                <p class="header-title">LAPORAN & REKAP TRANSAKSI</p>
                <p class="sub-title">WarungSmart</p>
            </td>
        </tr>
    </table>

    <div class="divider"></div>

    {{-- FILTER INFO --}}
    <p>
        <strong>Filter:</strong>
        @if($mode === 'periode' && $periode)
            Periode {{ ucfirst($periode) }} ({{ $date }})
        @elseif($mode === 'date' && $date)
            Tanggal {{ $date }}
        @else
            <span class="muted">Tidak ada filter diterapkan</span>
        @endif
    </p>

    {{-- SUMMARY SECTION --}}
    <h4>Ringkasan Pesanan</h4>
    <p><strong>Total Pesanan:</strong> {{ $totalOrders }}</p>
    <p>
        Pending: {{ $pendingOrders }} |
        Diterima: {{ $acceptedOrders }} |
        Ditolak: {{ $rejectedOrders }}
    </p>

    <h4>Ringkasan Produk</h4>
    <p><strong>Total Produk:</strong> {{ $totalProducts }}</p>
    <p>
        Produk Aktif: {{ $activeProducts }} |
        Produk Pending: {{ $pendingProducts }}
    </p>

    <h4>Diskon & Revenue</h4>
    <p><strong>Total Diskon:</strong> Rp {{ number_format($ordersPerSupplier->sum('discount'),0,',','.') }}</p>
    <p><strong>Total Revenue (Simulatif):</strong> Rp {{ number_format($totalRevenue,0,',','.') }}</p>

    {{-- PESANAN PER SUPPLIER --}}
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
            <tr>
                <td colspan="8" style="text-align:center;" class="muted">Belum ada data pesanan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- PRODUK PER SUPPLIER --}}
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
            <tr>
                <td colspan="4" style="text-align:center;" class="muted">Belum ada data produk.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- DETAIL PESANAN --}}
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
            <td>Rp {{ number_format($order->unit_price,0,',','.') }}</td>
            <td>
                Rp {{ number_format(max(0, ($order->product->price - $order->unit_price)) * $order->quantity,0,',','.') }}
            </td>
            <td>{{ ucfirst($order->status) }}</td>
            <td>{{ $order->created_at->format('d M Y H:i') }}</td>
        </tr>
        @endforeach
        </tbody>
    </table>

    {{-- TREND BULANAN --}}
    <h4>Tren Pesanan Bulanan</h4>

    <table>
        <thead>
            <tr>
                <th>Bulan</th>
                <th>Total Pesanan</th>
                <th>Jumlah Barang</th>
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
            <tr>
                <td colspan="4" style="text-align:center;" class="muted">Tidak ada data tren bulanan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <h4>Detail Pengeluaran</h4>
    <table>
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
        <tr>
            <td colspan="4" style="text-align:center;" class="muted">Belum ada data pengeluaran.</td>
        </tr>
        @endforelse
    </tbody>
    </table>


    <h4>Ringkasan Keuangan</h4>
    <p><strong>Pendapatan:</strong> Rp {{ number_format($totalRevenue,0,',','.') }}</p>
    <p><strong>Biaya:</strong> Rp {{ number_format($expenses,0,',','.') }}</p>
    <p><strong>Kasbon Aktif:</strong> Rp {{ number_format($debts,0,',','.') }}</p>
    <p><strong>Laba/Rugi:</strong> Rp {{ number_format($profitLoss,0,',','.') }}</p>

    <h4>Ringkasan Keuangan</h4>
    <p><strong>Pendapatan:</strong> Rp {{ number_format($totalRevenue,0,',','.') }}</p>
    <p><strong>Biaya:</strong> Rp {{ number_format($expenses,0,',','.') }}</p>
    <p><strong>Kasbon Aktif:</strong> Rp {{ number_format($debts,0,',','.') }}</p>
    <p><strong>Laba/Rugi:</strong> Rp {{ number_format($profitLoss,0,',','.') }}</p>

    <h4>Ringkasan Operasional</h4>
    <p><strong>Supplier Telat:</strong> {{ $delayedCount }}</p>
    <p><strong>Produk dengan Harga Naik ≥10%:</strong> {{ $recentIncreasesCount }}</p>
    <p><strong>Produk Stok Rendah (≤10):</strong> {{ $lowStocksCount }}</p>



    {{-- FOOTER --}}
    <div class="footer-sign">
        Dicetak pada {{ now()->format('d M Y H:i') }}<br>
        <span class="signature">{{ auth()->user()->name }}</span>
    </div>

</body>
</html>
