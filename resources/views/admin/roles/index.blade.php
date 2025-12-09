@extends('layouts.dashboard')
@section('title','Manajemen Role')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary rounded h-100 p-4">
        <h6 class="mb-4">Daftar Role</h6>
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addRoleModal">
            <i class="fa fa-plus me-2"></i>Tambah Role
        </button>

        <div class="table-responsive">
            <table class="table table-hover table-bordered text-white">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Role</th>
                        <th>Permissions</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $index => $role)
                    <tr>
                        <td>{{ $index+1 }}</td>
                        <td>{{ $role->name }}</td>
                        <td>{{ $role->permissions->pluck('name')->implode(', ') }}</td>
                        <td>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editRoleModal{{ $role->id }}">
                                <i class="fa fa-edit"></i>
                            </button>
                            <form action="{{ route('admin.roles.destroy',$role->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus role ini?')">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>

                    <!-- Modal Edit Role -->
                    <div class="modal fade" id="editRoleModal{{ $role->id }}" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <form action="{{ route('admin.roles.update',$role->id) }}" method="POST">
                                @csrf @method('PUT')
                                <div class="modal-content bg-secondary text-white">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Role</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-floating mb-3">
                                            <input type="text" name="name" value="{{ $role->name }}" class="form-control bg-dark text-white" required>
                                            <label>Nama Role</label>
                                        </div>
                                        <div class="mb-3">
                                            <label>Permissions</label><br>
                                            @foreach($permissions as $perm)
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $perm->name }}"
                                                        {{ $role->permissions->contains($perm->id) ? 'checked' : '' }}>
                                                    <label class="form-check-label">{{ $perm->name }}</label>
                                                </div>
                                            @endforeach
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

<!-- Modal Tambah Role -->
<div class="modal fade" id="addRoleModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('admin.roles.store') }}" method="POST">
            @csrf
            <div class="modal-content bg-secondary text-white">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="form-floating mb-3">
                        <input type="text" name="name" class="form-control bg-dark text-white" placeholder="Nama Role" required>
                        <label>Nama Role</label>
                    </div>
                    <div class="mb-3">
                        <label>Permissions</label><br>
                        @foreach($permissions as $perm)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $perm->name }}">
                                <label class="form-check-label">{{ $perm->name }}</label>
                            </div>
                        @endforeach
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
