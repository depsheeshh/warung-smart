@extends('layouts.auth')
@section('title','Login')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
    <h3 class="text-primary"><i class="fa fa-user-edit me-2"></i>WarungSmart</h3>
    <h3>Login</h3>
</div>

<form method="POST" action="{{ route('login') }}">
    @csrf
    <div class="form-floating mb-3">
        <input type="email" name="email" class="form-control bg-dark text-white" placeholder="Email" required>
        <label>Email address</label>
    </div>
    <div class="form-floating mb-4">
        <input type="password" name="password" class="form-control bg-dark text-white" placeholder="Password" required>
        <label>Password</label>
    </div>
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div class="form-check">
            <input type="checkbox" name="remember" class="form-check-input" id="remember">
            <label class="form-check-label" for="remember">Remember me</label>
        </div>
        <a href="#">Forgot Password</a>
    </div>
    <button type="submit" class="btn btn-primary py-3 w-100 mb-4">Login</button>
    <p class="text-center mb-0">Don't have an Account? <a href="{{ route('register') }}">Register</a></p>
</form>
@endsection
