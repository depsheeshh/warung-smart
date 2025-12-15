@extends('layouts.dashboard')

@section('title','Produk Tersedia')

@section('content')
<div class="container-fluid pt-4 px-4">
  <div class="bg-secondary rounded h-100 p-4">
    <h6 class="mb-3">Produk Tersedia</h6>

    <form method="POST" action="{{ route('customer.products.whatsapp') }}">
      @csrf
      <div class="table-responsive">
        <table class="table table-hover table-bordered text-white align-middle">
          <thead>
            <tr>
              <th>#</th>
              <th>Gambar</th>
              <th>Nama</th>
              <th>Deskripsi</th>
              <th>Harga</th>
              <th>Stok</th>
              <th>Jumlah Pesan</th>
            </tr>
          </thead>
          <tbody>
            @forelse($products as $index => $product)
              @php $price = $product->getPriceForUser(Auth::user()); @endphp
              <tr>
                <td>{{ $products->firstItem() + $index }}</td>
                <td>
                  @if($product->image)
                    <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" width="80" class="rounded">
                  @else
                    <span class="text-muted">Tidak ada gambar</span>
                  @endif
                </td>
                <td>{{ $product->name }}</td>
                <td class="text-wrap">{{ $product->description }}</td>
                <td>
                  @if($price['percent'] > 0)
                    <span class="text-decoration-line-through text-muted">
                      Rp {{ number_format($price['base'],0,',','.') }}
                    </span>
                    <span class="text-success fw-bold ms-2">
                      Rp {{ number_format($price['final'],0,',','.') }}
                    </span>
                    <small class="badge bg-warning text-dark ms-2">
                      Diskon {{ $price['percent'] }}% Premium
                    </small>
                  @else
                    Rp {{ number_format($price['final'],0,',','.') }}
                  @endif
                </td>
                <td>
                  @if($product->stock > 0)
                    <span class="badge bg-info text-dark">Stok: {{ $product->stock }}</span>
                  @else
                    <span class="badge bg-danger">Stok Habis</span>
                  @endif
                </td>
                <td>
                  @if($product->stock > 0)
                    <input type="number" name="orders[{{ $product->id }}]" min="0" max="{{ $product->stock }}" class="form-control form-control-sm w-50" placeholder="0">
                  @else
                    <input type="number" disabled class="form-control form-control-sm w-50" placeholder="0">
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="text-center text-muted">Belum ada produk tersedia.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="mt-3 d-flex justify-content-between align-items-center">
        <small class="text-muted">
          Menampilkan {{ $products->firstItem() }}–{{ $products->lastItem() }} dari {{ $products->total() }} produk
        </small>
        {{ $products->links('pagination::bootstrap-5') }}
      </div>

      {{-- Tombol global pesan --}}
      <div class="mt-4 text-center">
        <button type="submit" class="btn btn-success">
        <i class="fa fa-shopping-cart me-2"></i> Pesan Sekarang
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
