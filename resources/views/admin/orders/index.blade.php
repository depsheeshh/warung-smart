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
            <th>Alasan Penolakan</th>
            <th>Tanggal</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($orders as $order)
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
                <span class="badge bg-danger">Rejected</span><br>
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

            {{-- Aksi --}}
            <td>
              @if($order->status === 'pending')
                <form method="POST" action="{{ route('admin.orders.accept', $order) }}" class="d-inline">
                  @csrf @method('PATCH')
                  <button class="btn btn-success btn-sm">Terima</button>
                </form>

                <!-- Tombol Tolak membuka modal -->
                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $order->id }}">
                  Tolak
                </button>

                <!-- Modal khusus order ini -->
                <div class="modal fade" id="rejectModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content bg-secondary text-white">
                      <div class="modal-header">
                        <h5 class="modal-title">Alasan Penolakan Pesanan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                      </div>
                      <form method="POST" action="{{ route('admin.orders.reject', $order->id) }}">
                        @csrf
                        @method('PATCH')
                        <div class="modal-body">
                          <div class="mb-3">
                            <label for="reason{{ $order->id }}" class="form-label">Tuliskan alasan</label>
                            <textarea name="rejection_reason" id="reason{{ $order->id }}" class="form-control" rows="3" required></textarea>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                          <button type="submit" class="btn btn-danger">Tolak Pesanan</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              @else
                <span class="text-muted">—</span>
              @endif
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="10" class="text-center text-muted">Belum ada pesanan.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="mt-3">{{ $orders->links('pagination::bootstrap-5') }}</div>
  </div>
</div>
@endsection
