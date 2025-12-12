@extends('layouts.dashboard')
@section('title','Manajemen Kasbon')

@section('content')
<div class="container-fluid pt-4 px-4">
  <div class="bg-secondary rounded h-100 p-4">
    <h6 class="mb-4">Daftar Kasbon Pelanggan</h6>

    <!-- Form tambah kasbon -->
    <form action="{{ route('admin.debts.store') }}" method="POST" class="mb-4">
      @csrf
      <div class="row">
        <div class="col-md-3">
          <select name="customer_id" class="form-control bg-dark text-white" required>
            <option value="">Pilih Customer</option>
            @foreach(\App\Models\User::role('customer')->get() as $cust)
              <option value="{{ $cust->id }}">{{ $cust->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-2">
          <input type="number" name="amount" class="form-control bg-dark text-white" placeholder="Nominal" required>
        </div>
        <div class="col-md-3">
          <input type="date" name="due_date" class="form-control bg-dark text-white">
        </div>
        <div class="col-md-2">
          <input type="text" name="notes" class="form-control bg-dark text-white" placeholder="Catatan">
        </div>
        <div class="col-md-2">
          <button type="submit" class="btn btn-success">Tambah Kasbon</button>
        </div>
      </div>
    </form>

    <!-- Tabel kasbon -->
    <table class="table table-hover table-bordered text-white align-middle">
      <thead>
        <tr>
          <th>#</th>
          <th>Pelanggan</th>
          <th>Nominal</th>
          <th>Status</th>
          <th>Jatuh Tempo</th>
          <th>Catatan</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @foreach($debts as $index => $debt)
        <tr>
          <td>{{ $debts->firstItem() + $index }}</td>
          <td>{{ $debt->customer->name }}</td>
          <td>Rp {{ number_format($debt->amount,0,',','.') }}</td>
          <td>
            <span class="badge bg-{{ $debt->status == 'paid' ? 'success' : ($debt->status == 'overdue' ? 'danger' : 'warning') }}">
              {{ ucfirst($debt->status) }}
            </span>
          </td>
          <td>{{ $debt->due_date ? \Carbon\Carbon::parse($debt->due_date)->format('d/m/Y') : '-' }}</td>
          <td>{{ $debt->notes ?? '-' }}</td>
          <td>
            @if(in_array($debt->status,['unpaid','overdue']))
              <form action="{{ route('admin.debts.markAsPaid',$debt->id) }}" method="POST">
                @csrf @method('PATCH')
                <button class="btn btn-sm btn-success">Lunasi</button>
              </form>
            @else
              <span class="text-muted">Sudah Lunas</span>
            @endif
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>

    {{ $debts->links('pagination::bootstrap-5') }}
  </div>
</div>
@endsection
