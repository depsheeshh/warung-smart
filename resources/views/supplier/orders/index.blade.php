@extends('layouts.dashboard')
@section('title','Pesanan Masuk')

@section('content')
<div class="container-fluid pt-4 px-4">
  @include('partials.alerts')

  <div class="bg-secondary rounded h-100 p-4">
    <h6 class="mb-3">Pesanan Masuk</h6>

    <table class="table table-hover table-bordered text-white align-middle">
      <thead>
        <tr>
          <th>#</th>
          <th>Produk</th>
          <th>Customer</th>
          <th>Jumlah</th>
          <th>Harga Satuan</th>
          <th>Total Harga</th>
          <th>Status</th>
          <th>Tanggal</th>
          <!-- HAPUS kolom Aksi -->
        </tr>
      </thead>
      <tbody>
        @forelse($orders as $index => $order)
        <tr>
          <td>{{ $orders->firstItem() + $index }}</td>
          <td>{{ $order->product->name }}</td>
          <td>{{ $order->customer->name }}</td>
          <td>{{ $order->quantity }}</td>
          <td>Rp {{ number_format($order->product->price) }}</td>
          <td>Rp {{ number_format($order->quantity * $order->product->price) }}</td>
          <td>
            <span class="badge bg-{{ $order->status == 'accepted' ? 'success' : ($order->status == 'rejected' ? 'danger' : 'warning') }}">
              {{ ucfirst($order->status) }}
            </span>
          </td>
          <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
        </tr>
        @empty
        <tr><td colspan="8" class="text-center text-muted">Belum ada pesanan.</td></tr>
        @endforelse
      </tbody>
    </table>

    <div class="mt-3">{{ $orders->links('pagination::bootstrap-5') }}</div>
  </div>
</div>
@endsection
