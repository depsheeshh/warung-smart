@extends('layouts.auth')
@section('title','Register')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
    <h3 class="text-primary"><i class="fa fa-user-edit me-2"></i>WarungSmart</h3>
    <h3>Register</h3>
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
    <div class="form-floating mb-4">
        <input type="password" name="password" class="form-control bg-dark text-white" placeholder="Password" required>
        <label>Password</label>
    </div>
    <div class="form-floating mb-4">
        <input type="password" name="password_confirmation" class="form-control bg-dark text-white" placeholder="Konfirmasi Password" required>
        <label>Konfirmasi Password</label>
    </div>
    <button type="submit" class="btn btn-primary py-3 w-100 mb-4">Register</button>
    <p class="text-center mb-0">Already have an Account? <a href="{{ route('login') }}">Login</a></p>
</form>
@endsection
