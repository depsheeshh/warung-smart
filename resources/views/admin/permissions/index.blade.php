@extends('layouts.dashboard')
@section('title','Manajemen Permission')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary rounded h-100 p-4">
        <h6 class="mb-4">Daftar Permission</h6>
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addPermissionModal">
            <i class="fa fa-plus me-2"></i>Tambah Permission
        </button>

        <div class="table-responsive">
            <table class="table table-hover table-bordered text-white">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Permission</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($permissions as $index => $permission)
                    <tr>
                        <td>{{ $index+1 }}</td>
                        <td>{{ $permission->name }}</td>
                        <td>
                            <!-- Tombol Edit -->
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editPermissionModal{{ $permission->id }}">
                                <i class="fa fa-edit"></i>
                            </button>

                            <!-- Tombol Hapus -->
                            <form action="{{ route('admin.permissions.destroy',$permission->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus permission ini?')">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>

                    <!-- Modal Edit Permission -->
                    <div class="modal fade" id="editPermissionModal{{ $permission->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <form action="{{ route('admin.permissions.update',$permission->id) }}" method="POST">
                                @csrf @method('PUT')
                                <div class="modal-content bg-secondary text-white">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Permission</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-floating mb-3">
                                            <input type="text" name="name" value="{{ $permission->name }}" class="form-control bg-dark text-white" required>
                                            <label>Nama Permission</label>
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
    </div>
</div>

<!-- Modal Tambah Permission -->
<div class="modal fade" id="addPermissionModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('admin.permissions.store') }}" method="POST">
            @csrf
            <div class="modal-content bg-secondary text-white">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Permission</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="form-floating mb-3">
                        <input type="text" name="name" class="form-control bg-dark text-white" placeholder="Nama Permission" required>
                        <label>Nama Permission</label>
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
