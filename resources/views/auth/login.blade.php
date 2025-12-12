@extends('layouts.auth')
@section('title','Login')

@section('content')
<div class="text-center mb-4">
  <h3 class="text-info fw-bold"><i class="fa fa-user-edit me-2"></i>WarungSmart</h3>
  <h4 class="fw-light">Login</h4>
</div>

<form method="POST" action="{{ route('login') }}">
  @csrf
  <div class="form-floating mb-3">
    <input type="email" name="email" class="form-control bg-dark text-white" placeholder="Email" required>
    <label>Email address</label>
  </div>
  <div class="form-floating mb-4 position-relative">
    <input type="password" name="password" id="loginPassword" class="form-control bg-dark text-white" placeholder="Password" required>
    <label>Password</label>
    <span class="position-absolute top-50 end-0 translate-middle-y me-3 text-white"
          onclick="togglePassword('loginPassword', this)" style="cursor: pointer;">
      <i class="fa fa-eye"></i>
    </span>
  </div>

  <button type="submit" class="btn btn-info py-3 w-100 mb-3">Login</button>
  <a href="{{ url('/') }}" class="btn btn-outline-light w-100 mb-3"><i class="fa fa-arrow-left me-2"></i>Kembali</a>

  <p class="text-center mb-0">Belum punya akun? <a href="{{ route('register') }}" class="text-warning">Register</a></p>
</form>
@endsection
