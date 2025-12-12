@extends('layouts.dashboard')
@section('title','Manajemen Produk')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary rounded h-100 p-4">
        <h6 class="mb-4">Daftar Produk</h6>
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addProductModal">
            <i class="fa fa-plus me-2"></i>Tambah Produk
        </button>

        <div class="table-responsive">
            <table class="table table-hover table-bordered text-white align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Deskripsi</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Supplier</th>
                        <th>Status</th>
                        <th>Terima</th>
                        <th>Gambar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $index => $product)
                    <tr>
                        <td>{{ $products->firstItem() + $index }}</td>
                        <td>{{ $product->name }}</td>
                        <td class="text-wrap">{{ $product->description }}</td>
                        <td>Rp {{ number_format($product->price,0,',','.') }}</td>
                        <td>
                            @if($product->stock > 0)
                                <span class="badge bg-info text-dark">Stok: {{ $product->stock }}</span>
                            @else
                                <span class="badge bg-danger">Stok Habis</span>
                            @endif
                        </td>
                        <td>{{ $product->supplier ? $product->supplier->name : 'Admin' }}</td>
                        <td>
                            <span class="badge bg-{{ $product->status == 'active' ? 'success' : 'warning' }}">
                                {{ ucfirst($product->status) }}
                            </span>
                        </td>
                        <td>
                            {{-- Hanya tampilkan tombol approve jika produk dari supplier dan status pending --}}
                            @if($product->status == 'pending' && $product->supplier)
                                <form action="{{ route('admin.products.approve',$product->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-sm btn-success">
                                        <i class="fa fa-check me-1"></i>Setujui
                                    </button>
                                </form>
                            @endif
                        </td>
                        <td>
                            @if($product->image)
                                <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" width="80" class="rounded">
                            @else
                                <span class="text-muted">Tidak ada gambar</span>
                            @endif
                        </td>
                        <td>
                            <!-- Edit -->
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editProductModal{{ $product->id }}">
                                <i class="fa fa-edit"></i>
                            </button>
                            <!-- Delete -->
                            <form action="{{ route('admin.products.destroy',$product->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus produk ini?')">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                            <!-- Forecast -->
                            <a href="{{ route('admin.forecast.generate',$product->id) }}" class="btn btn-sm btn-info">
                                <i class="fa fa-chart-line me-1"></i> Forecast
                            </a>
                        </td>
                    </tr>

                    <!-- Modal Edit -->
                    <div class="modal fade" id="editProductModal{{ $product->id }}" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <form action="{{ route('admin.products.update',$product->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf @method('PUT')
                                <div class="modal-content bg-secondary text-white">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Produk</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-floating mb-3">
                                            <input type="text" name="name" value="{{ $product->name }}" class="form-control bg-dark text-white" required>
                                            <label>Nama Produk</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <textarea name="description" class="form-control bg-dark text-white">{{ $product->description }}</textarea>
                                            <label>Deskripsi</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="number" name="price" value="{{ $product->price }}" class="form-control bg-dark text-white" required>
                                            <label>Harga</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="number" name="stock" value="{{ $product->stock }}" class="form-control bg-dark text-white" required>
                                            <label>Stok</label>
                                        </div>
                                        <div class="mb-3">
                                            <label for="formFileEdit{{ $product->id }}" class="form-label">Update Gambar Produk</label>
                                            <input class="form-control bg-dark text-white" type="file" id="formFileEdit{{ $product->id }}" name="image">
                                            @if($product->image)
                                                <small class="text-muted">Gambar saat ini:</small><br>
                                                <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" width="100" class="mt-2 rounded">
                                            @endif
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success">Update</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="addProductModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content bg-secondary text-white">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Produk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="form-floating mb-3">
                        <input type="text" name="name" class="form-control bg-dark text-white" required>
                        <label>Nama Produk</label>
                    </div>
                    <div class="form-floating mb-3">
                        <textarea name="description" class="form-control bg-dark text-white"></textarea>
                        <label>Deskripsi</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="number" name="price" class="form-control bg-dark text-white" required>
                        <label>Harga</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="number" name="stock" class="form-control bg-dark text-white" required>
                        <label>Stok</label>
                    </div>
                    <div class="mb-3">
                        <label for="formFile" class="form-label">Gambar Produk</label>
                        <input class="form-control bg-dark text-white" type="file" id="formFile" name="image">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
