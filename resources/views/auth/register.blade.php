@extends('layouts.auth')
@section('title','Register')

@section('content')
<div class="text-center mb-4">
  <h3 class="text-info fw-bold"><i class="fa fa-user-edit me-2"></i>WarungSmart</h3>
  <h4 class="fw-light">Register</h4>
</div>

<form method="POST" action="{{ route('register') }}">
  @csrf
  <div class="form-floating mb-3">
    <input type="text" name="name" class="form-control bg-dark text-white" placeholder="Nama Lengkap" required>
    <label>Nama Lengkap</label>
  </div>
  <div class="form-floating mb-3">
    <input type="email" name="email" class="form-control bg-dark text-white" placeholder="Email" required>
    <label>Email address</label>
  </div>
  <div class="form-floating mb-3">
    <input type="text" name="phone" class="form-control bg-dark text-white" placeholder="Nomor Telepon">
    <label>Nomor Telepon</label>
  </div>
  <div class="form-floating mb-3">
    <input type="text" name="address" class="form-control bg-dark text-white" placeholder="Alamat">
    <label>Alamat</label>
  </div>
  <div class="form-floating mb-3 position-relative">
    <input type="password" name="password" id="regPassword" class="form-control bg-dark text-white" placeholder="Password" required>
    <label>Password</label>
    <span class="position-absolute top-50 end-0 translate-middle-y me-3 text-white"
          onclick="togglePassword('regPassword', this)" style="cursor: pointer;">
      <i class="fa fa-eye"></i>
    </span>
  </div>
  <div class="form-floating mb-4 position-relative">
    <input type="password" name="password_confirmation" id="regPasswordConfirm" class="form-control bg-dark text-white" placeholder="Konfirmasi Password" required>
    <label>Konfirmasi Password</label>
    <span class="position-absolute top-50 end-0 translate-middle-y me-3 text-white"
          onclick="togglePassword('regPasswordConfirm', this)" style="cursor: pointer;">
      <i class="fa fa-eye"></i>
    </span>
  </div>

  <button type="submit" class="btn btn-info py-3 w-100 mb-3">Register</button>
  <a href="{{ url('/') }}" class="btn btn-outline-light w-100 mb-3"><i class="fa fa-arrow-left me-2"></i>Kembali</a>

  <p class="text-center mb-0">Sudah punya akun? <a href="{{ route('login') }}" class="text-warning">Login</a></p>
</form>
@endsection
