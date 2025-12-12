@extends('layouts.dashboard')

@section('title','Ringkasan Forecast')

@section('content')
<div class="container-fluid pt-4 px-4">
  <div class="bg-secondary rounded h-100 p-4">
    <h6 class="mb-4">Ringkasan Forecast Semua Produk</h6>

    <table class="table table-hover table-bordered text-white align-middle">
      <thead>
        <tr>
          <th>#</th>
          <th>Nama Produk</th>
          <th>Stok</th>
          <th>Forecast Bulan Depan</th>
          <th>Detail</th>
        </tr>
      </thead>
      <tbody>
        @foreach($products as $index => $product)
        <tr>
          <td>{{ $products->firstItem() + $index }}</td>
          <td>{{ $product->name }}</td>
          <td>{{ $product->stock }}</td>
          <td>
            {{ optional($product->forecastResults->last())->forecast ?? '-' }}
          </td>
          <td>
            <a href="{{ route('admin.forecast.dashboard',$product->id) }}" class="btn btn-sm btn-primary">
              Detail
            </a>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>

    {{ $products->links('pagination::bootstrap-5') }}
  </div>
</div>
@endsection
