@extends('layouts.dashboard')
@section('title','Manajemen User')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary rounded h-100 p-4">
        <h6 class="mb-4">Daftar User</h6>

        <!-- Tombol Tambah User -->
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="fa fa-plus me-2"></i>Tambah User
        </button>

        <!-- Tabel User -->
        <div class="table-responsive">
            <table class="table table-hover table-bordered text-white">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Telepon</th>
                        <th>Alamat</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $index => $user)
                    <tr>
                        <td>{{ $index+1 }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone }}</td>
                        <td>{{ $user->address }}</td>
                        <td>{{ $user->roles->pluck('name')->implode(', ') }}</td>
                        <td>
                            <!-- Tombol Edit -->
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}">
                                <i class="fa fa-edit"></i>
                            </button>

                            <!-- Tombol Hapus -->
                            <form action="{{ route('admin.users.destroy',$user->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus user ini?')">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>

                    <!-- Modal Edit User -->
                    <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <form action="{{ route('admin.users.update',$user->id) }}" method="POST">
                                @csrf @method('PUT')
                                <div class="modal-content bg-secondary text-white">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit User</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-floating mb-3">
                                            <input type="text" name="name" value="{{ $user->name }}" class="form-control bg-dark text-white" required>
                                            <label>Nama Lengkap</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="email" name="email" value="{{ $user->email }}" class="form-control bg-dark text-white" required>
                                            <label>Email</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="text" name="phone" value="{{ $user->phone }}" class="form-control bg-dark text-white">
                                            <label>Nomor Telepon</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="text" name="address" value="{{ $user->address }}" class="form-control bg-dark text-white">
                                            <label>Alamat</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <select name="role" class="form-select bg-dark text-white" required>
                                                @foreach($roles as $role)
                                                    <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                                        {{ ucfirst($role->name) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <label>Role</label>
                                        </div>
                                        <hr>
                                        <div class="form-floating mb-3 position-relative">
                                            <input type="password" name="old_password" id="oldPassword{{ $user->id }}" class="form-control bg-dark text-white" placeholder="Password Lama">
                                            <label>Password Lama</label>

                                            <span class="position-absolute top-50 end-0 translate-middle-y me-3 text-white"
                                                onclick="togglePassword('oldPassword{{ $user->id }}', this)"
                                                style="cursor: pointer;">
                                                <i class="fa fa-eye"></i>
                                            </span>
                                        </div>
                                        <div class="form-floating mb-3 position-relative">
                                            <input type="password" name="new_password" id="newPassword{{ $user->id }}" class="form-control bg-dark text-white" placeholder="Password Baru">
                                            <label>Password Baru</label>

                                            <span class="position-absolute top-50 end-0 translate-middle-y me-3 text-white"
                                                onclick="togglePassword('newPassword{{ $user->id }}', this)"
                                                style="cursor: pointer;">
                                                <i class="fa fa-eye"></i>
                                            </span>
                                        </div>
                                        <div class="form-floating mb-3 position-relative">
                                            <input type="password" name="new_password_confirmation" id="newPasswordConfirm{{ $user->id }}" class="form-control bg-dark text-white" placeholder="Konfirmasi Password Baru">
                                            <label>Konfirmasi Password Baru</label>

                                            <span class="position-absolute top-50 end-0 translate-middle-y me-3 text-white"
                                                onclick="togglePassword('newPasswordConfirm{{ $user->id }}', this)"
                                                style="cursor: pointer;">
                                                <i class="fa fa-eye"></i>
                                            </span>
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

<!-- Modal Tambah User -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <div class="modal-content bg-secondary text-white">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="form-floating mb-3">
                        <input type="text" name="name" class="form-control bg-dark text-white" placeholder="Nama Lengkap" required>
                        <label>Nama Lengkap</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="email" name="email" class="form-control bg-dark text-white" placeholder="Email" required>
                        <label>Email</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" name="password" class="form-control bg-dark text-white" placeholder="Password" required>
                        <label>Password</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" name="phone" class="form-control bg-dark text-white" placeholder="Nomor Telepon">
                        <label>Nomor Telepon</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" name="address" class="form-control bg-dark text-white" placeholder="Alamat">
                        <label>Alamat</label>
                    </div>
                    <div class="form-floating mb-3">
                        <select name="role" class="form-select bg-dark text-white" required>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                            @endforeach
                        </select>
                        <label>Role</label>
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
