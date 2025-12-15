@extends('layouts.dashboard')
@section('title','Manajemen Pesanan')

@section('content')
    <div class="container-fluid pt-4 px-4">

    <div class="bg-secondary rounded h-100 p-4">
    <h6 class="mb-3">Daftar Pesanan</h6>

    <div class="table-responsive">
    <table class="table table-bordered text-white align-middle">
    <thead>
    <tr>
    <th>#</th>
    <th>Produk</th>
    <th>Supplier</th>
    <th>Customer</th>
    <th>Jumlah</th>
    <th>Harga Satuan</th>
    <th>Total Harga</th>
    <th>Status</th>
    <th>Tanggal</th>
    <th>Aksi</th>
    </tr>
    </thead>
    <tbody>
    @foreach($orders as $order)
    <tr>
    <td>{{ $loop->iteration }}</td>
    <td>{{ $order->product?->name ?? '—' }}</td>
    <td>{{ $order->product?->supplier?->name ?? '—' }}</td>
    <td>{{ $order->customer?->name ?? '—' }}</td>
    <td>{{ $order->quantity }}</td>

    {{-- Harga satuan --}}
    <td>
    @if($order->unit_price < ($order->product->price ?? 0))
    <span class="text-decoration-line-through text-muted">
        Rp {{ number_format($order->product->price) }}
    </span><br>
    <span class="text-success fw-bold">Rp {{ number_format($order->unit_price) }}</span>
    <span class="badge bg-info ms-1">
        Diskon {{ round(100 - ($order->unit_price / $order->product->price * 100)) }}%
    </span>
    @else
    Rp {{ number_format($order->unit_price) }}
    @endif
    </td>

    {{-- Total harga --}}
    <td>Rp {{ number_format($order->total_price) }}</td>

    {{-- Status --}}
    <td>
    @if($order->status === 'accepted')
    <span class="badge bg-success">Accepted</span>
    @elseif($order->status === 'rejected')
    <span class="badge bg-danger">Rejected</span>
    @else
    <span class="badge bg-warning text-dark">Pending</span>
    @endif
    </td>

    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>

    {{-- Aksi --}}
    <td>
    @if($order->status === 'pending')
    <form method="POST" action="{{ route('admin.orders.accept', $order) }}" class="d-inline">
        @csrf @method('PATCH')
        <button class="btn btn-success btn-sm">Terima</button>
    </form>
    <form method="POST" action="{{ route('admin.orders.reject', $order) }}" class="d-inline">
        @csrf @method('PATCH')
        <button class="btn btn-danger btn-sm mt-1">Tolak</button>
    </form>
    @else
    <span class="text-muted">—</span>
    @endif
    </td>
    </tr>
    @endforeach
    </tbody>
    </table>
    </div>

    <div class="mt-3">{{ $orders->links('pagination::bootstrap-5') }}</div>
    </div>
    </div>
@endsection
