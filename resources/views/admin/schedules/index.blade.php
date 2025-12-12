@extends('layouts.dashboard')
@section('title','Jadwal Supplier')
@section('content')
<div class="container-fluid pt-4 px-4">
  <div class="bg-secondary rounded h-100 p-4">
    <h6 class="mb-3">Tambah Jadwal Supplier</h6>
    <form action="{{ route('admin.schedules.store') }}" method="POST" class="row g-2 mb-4">
      @csrf
      <div class="col-md-4">
        <select name="supplier_id" class="form-select" required>
          @foreach(\App\Models\User::role('supplier')->get() as $sup)
            <option value="{{ $sup->id }}">{{ $sup->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-4">
        <input type="date" name="expected_date" class="form-control" required>
      </div>
      <div class="col-md-4">
        <button class="btn btn-primary">Simpan Jadwal</button>
      </div>
    </form>

    <h6 class="mb-3">Daftar Jadwal Supplier</h6>
    <table class="table table-bordered text-white align-middle">
      <thead>
        <tr><th>Supplier</th><th>Jadwal</th><th>Status</th></tr>
      </thead>
      <tbody>
        @forelse($schedules as $s)
          <tr>
            <td>{{ $s->supplier->name }}</td>
            <td>{{ $s->expected_date->format('d M Y') }}</td>
            <td><span class="badge bg-{{ $s->status=='delayed'?'danger':($s->status=='arrived'?'success':'secondary') }}">{{ ucfirst($s->status) }}</span></td>
          </tr>
        @empty
          <tr><td colspan="3" class="text-center text-muted">Belum ada jadwal.</td></tr>
        @endforelse
      </tbody>
    </table>
    {{ $schedules->links('pagination::bootstrap-5') }}
  </div>
</div>
@endsection
