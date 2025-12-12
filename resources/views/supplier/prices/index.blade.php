@extends('layouts.dashboard')
@section('title','Histori Harga Produk')
@section('content')
<div class="container-fluid pt-4 px-4">
  <div class="bg-secondary rounded h-100 p-4">
    <h6 class="mb-3">Input Harga Produk</h6>
    <form action="{{ route('supplier.prices.store') }}" method="POST" class="row g-2 mb-4">
      @csrf
      <div class="col-md-4">
        <select name="product_id" class="form-select" required>
            @foreach(\App\Models\Product::where('supplier_id', auth()->id())->get() as $prod)
                <option value="{{ $prod->id }}">{{ $prod->name }}</option>
            @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <input type="number" name="price" class="form-control" placeholder="Harga" min="0" required>
      </div>
      <div class="col-md-3">
        <input type="date" name="date" class="form-control" required>
      </div>
      <div class="col-md-2">
        <button class="btn btn-primary w-100">Simpan</button>
      </div>
    </form>

    <h6 class="mb-3">Histori Harga Produk</h6>
    <table class="table table-bordered text-white align-middle">
      <thead>
        <tr><th>Produk</th><th>Harga</th><th>Tanggal</th></tr>
      </thead>
      <tbody>
        @forelse($prices as $p)
          <tr>
            <td>{{ $p->product->name }}</td>
            <td>Rp {{ number_format((float)$p->price,0,',','.') }}</td>
            <td>{{ $p->date->format('d M Y') }}</td>
          </tr>
        @empty
          <tr><td colspan="3" class="text-center text-muted">Belum ada histori harga.</td></tr>
        @endforelse
      </tbody>
    </table>
    {{ $prices->links('pagination::bootstrap-5') }}
  </div>
</div>
@endsection
