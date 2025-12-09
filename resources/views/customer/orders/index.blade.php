@extends('layouts.dashboard')
@section('title','Pesanan Saya')

@section('content')
<div class="container-fluid pt-4 px-4">

    <div class="bg-secondary rounded h-100 p-4">
        <h6 class="mb-3">Pesanan Saya</h6>
        <table class="table table-bordered text-white align-middle">
            <thead>
                <tr>
                <th>#</th>
                <th>Produk</th>
                <th>Jumlah</th>
                <th>Harga Satuan</th>
                <th>Total Harga</th>
                <th>Status</th>
                <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $order->product->name }}</td>
                <td>{{ $order->jumlah }}</td>
                <td>Rp {{ number_format($order->product->price) }}</td>
                <td>Rp {{ number_format($order->jumlah * $order->product->price) }}</td>
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
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-3">{{ $orders->links() }}</div>
    </div>
</div>
@endsection
