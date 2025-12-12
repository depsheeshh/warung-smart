@extends('layouts.dashboard')
@section('title','Kasbon Saya')

@section('content')
<div class="container-fluid pt-4 px-4">
  <div class="bg-secondary rounded h-100 p-4">
    <h6 class="mb-4">Riwayat Kasbon</h6>
    <table class="table table-hover table-bordered text-white align-middle">
      <thead>
        <tr>
          <th>#</th>
          <th>Produk</th>
          <th>Nominal</th>
          <th>Status</th>
          <th>Jatuh Tempo</th>
          <th>Catatan</th>
        </tr>
      </thead>
      <tbody>
        @foreach($debts as $index => $debt)
        <tr>
          <td>{{ $debts->firstItem() + $index }}</td>
          <td>{{ $debt->product->name ?? '-' }}</td>
          <td>Rp {{ number_format($debt->amount,0,',','.') }}</td>
          <td>
            <span class="badge bg-{{ $debt->status == 'paid' ? 'success' : ($debt->status == 'overdue' ? 'danger' : 'warning') }}">
              {{ ucfirst($debt->status) }}
            </span>
          </td>
          <td>{{ $debt->due_date ? \Carbon\Carbon::parse($debt->due_date)->format('d/m/Y') : '-' }}</td>
          <td>{{ $debt->notes }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
    {{ $debts->links('pagination::bootstrap-5') }}
  </div>
</div>
@endsection
