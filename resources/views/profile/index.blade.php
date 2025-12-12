@extends('layouts.dashboard')
@section('title','My Profile')

@section('content')
<div class="container-fluid pt-4 px-4">
  <div class="bg-secondary rounded h-100 p-4">
    <h6 class="mb-3">Profil Saya</h6>

    {{-- Foto Profil --}}
    <div class="text-center mb-4">
      @if($user->avatar)
        <img src="{{ asset('storage/'.$user->avatar) }}" alt="Avatar" width="120" class="rounded-circle mb-2">
      @else
        <img src="{{ asset('images/default-avatar.png') }}" alt="Avatar" width="120" class="rounded-circle mb-2">
      @endif
    </div>

    {{-- Form Update Profil --}}
    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="mb-4">
      @csrf @method('PUT')

      <div class="form-floating mb-3">
        <input type="text" name="name" value="{{ $user->name }}" class="form-control bg-dark text-white" required>
        <label>Nama</label>
      </div>

      <div class="form-floating mb-3">
        <input type="email" name="email" value="{{ $user->email }}" class="form-control bg-dark text-white" required>
        <label>Email</label>
      </div>

      <div class="form-floating mb-3">
        <input type="text" name="phone" value="{{ $user->phone }}" class="form-control bg-dark text-white">
        <label>No. HP</label>
      </div>

      <div class="form-floating mb-3">
        <input type="text" name="address" value="{{ $user->address }}" class="form-control bg-dark text-white">
        <label>Alamat</label>
      </div>

      {{-- Membership hanya untuk customer, readonly --}}
      @role('customer')
        <div class="form-floating mb-3">
          <input type="text" value="{{ $user->membership_type }}" class="form-control bg-dark text-white" readonly>
          <label>Membership Type</label>
        </div>

        {{-- Badge status membership --}}
        @if($user->isPremium())
          <span class="badge bg-warning text-dark mb-3">
            Premium Aktif sampai {{ optional($user->currentSubscription()?->ends_at)->format('d M Y') }}
          </span>
        @else
          <span class="badge bg-light text-dark mb-3">Basic</span>
        @endif
      @endrole

      <div class="mb-3">
        <label for="avatar" class="form-label">Foto Profil</label>
        <input type="file" name="avatar" class="form-control bg-dark text-white" accept=".jpg,.jpeg,.png">
      </div>

      <button type="submit" class="btn btn-success">
        <i class="fa fa-save me-2"></i>Simpan Perubahan
      </button>
    </form>

    {{-- Form Ganti Password --}}
    <h6 class="mb-3">Ganti Password</h6>
    <form action="{{ route('profile.updatePassword') }}" method="POST">
      @csrf @method('PUT')

      <div class="form-floating mb-3 position-relative">
        <input type="password" name="current_password" id="currentPassword" class="form-control bg-dark text-white" required>
        <label>Password Lama</label>

        <span class="position-absolute top-50 end-0 translate-middle-y me-3 text-white"
            style="cursor: pointer;"
            onclick="togglePassword('currentPassword', this)">
            <i class="fa fa-eye"></i>
        </span>
    </div>

      <div class="form-floating mb-3 position-relative">
        <input type="password" name="new_password" id="newPassword" class="form-control bg-dark text-white" required>
        <label>Password Baru</label>

        <span class="position-absolute top-50 end-0 translate-middle-y me-3 text-white"
            style="cursor: pointer;"
            onclick="togglePassword('newPassword', this)">
            <i class="fa fa-eye"></i>
        </span>
    </div>

      <div class="form-floating mb-3 position-relative">
        <input type="password" name="new_password_confirmation" id="newPasswordConfirm" class="form-control bg-dark text-white" required>
        <label>Konfirmasi Password Baru</label>

        <span class="position-absolute top-50 end-0 translate-middle-y me-3 text-white"
            style="cursor: pointer;"
            onclick="togglePassword('newPasswordConfirm', this)">
            <i class="fa fa-eye"></i>
        </span>
    </div>

      <button type="submit" class="btn btn-warning">
        <i class="fa fa-key me-2"></i>Update Password
      </button>
    </form>
  </div>
</div>
@endsection
