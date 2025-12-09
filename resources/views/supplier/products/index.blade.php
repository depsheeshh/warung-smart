@extends('layouts.dashboard')
@section('title','Produk Saya (Supplier)')

@section('content')
<div class="container-fluid pt-4 px-4">

    <div class="bg-secondary rounded h-100 p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="mb-0">Produk Saya</h6>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                <i class="fa fa-plus me-2"></i>Tambah Produk
            </button>
        </div>

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
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $index => $product)
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
                        <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                        <td>{{ $product->stock }}</td>
                        <td>
                            @if($product->status === 'active')
                                <span class="badge bg-success"><i class="fa fa-check-circle me-1"></i>Aktif</span>
                            @else
                                <span class="badge bg-warning text-dark"><i class="fa fa-hourglass-half me-1"></i>Pending</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">Belum ada produk. Tambahkan sekarang.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $products->links() }}
        </div>
    </div>
</div>

{{-- Modal Tambah Produk --}}
<div class="modal fade" id="addProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('supplier.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content bg-secondary text-white">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Produk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="form-floating mb-3">
                        <input type="text" name="name" class="form-control bg-dark text-white" placeholder="Nama Produk" required>
                        <label>Nama Produk</label>
                    </div>
                    <div class="form-floating mb-3">
                        <textarea name="description" class="form-control bg-dark text-white" placeholder="Deskripsi"></textarea>
                        <label>Deskripsi</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="number" name="price" class="form-control bg-dark text-white" placeholder="Harga" min="0" required>
                        <label>Harga</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="number" name="stock" class="form-control bg-dark text-white" placeholder="Stok" min="0" required>
                        <label>Stok</label>
                    </div>

                    {{-- Style input file sesuai permintaan --}}
                    <div class="mb-3">
                        <label for="supplierFormFile" class="form-label">Gambar Produk</label>
                        <input class="form-control bg-dark text-white" type="file" id="supplierFormFile" name="image" accept=".jpg,.jpeg,.png">
                        <small class="text-muted d-block mt-1">Maks 2MB, format: JPG, JPEG, PNG.</small>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-success" type="submit">
                        <i class="fa fa-save me-2"></i>Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
