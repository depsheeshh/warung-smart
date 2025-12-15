@extends('layouts.dashboard')
@section('title','Pesanan Saya')

@section('content')
<div class="container-fluid pt-4 px-4">
  <div class="bg-secondary rounded h-100 p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h6 class="mb-0">Pesanan Saya</h6>
      {{-- Badge Membership --}}
      @if(auth()->user()->isPremium())
        <span class="badge bg-warning text-dark">
          Premium Customer
        </span>
      @else
        <span class="badge bg-light text-dark">
          Basic Customer
        </span>
      @endif
    </div>

    <table class="table table-bordered text-white align-middle">
      <thead>
        <tr>
          <th>#</th>
          <th>Produk</th>
          <th>Jumlah</th>
          <th>Harga Satuan</th>
          <th>Total Harga</th>
          <th>Status</th>
          <th>Alasan Penolakan</th>
          <th>Tanggal</th>
        </tr>
      </thead>
      <tbody>
        @foreach($orders as $order)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $order->product->name }}</td>
          <td>{{ $order->quantity }}</td>

          {{-- Harga satuan --}}
          <td>
            @if($order->discount_percent > 0)
              <span class="text-decoration-line-through text-muted">
                Rp {{ number_format($order->product->price, 0, ',', '.') }}
              </span><br>
              <span class="text-success fw-bold">
                Rp {{ number_format($order->unit_price, 0, ',', '.') }}
              </span>
              <span class="badge bg-info ms-1">
                Diskon {{ $order->discount_percent }}%
              </span>
            @else
              Rp {{ number_format($order->unit_price, 0, ',', '.') }}
            @endif
          </td>

          {{-- Total harga --}}
          <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>

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
          <td>
            @if($order->status === 'rejected')
                <small class="text-muted">Alasan: {{ $order->rejection_reason }}</small>
            @else
                <span class="text-muted">—</span>
            @endif
            </td>
          <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>

    <div class="mt-3">{{ $orders->links('pagination::bootstrap-5') }}</div>
  </div>
</div>
@endsection
