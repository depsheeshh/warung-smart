@extends('layouts.dashboard')

@section('title','Dashboard Admin')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary rounded h-100 p-4">
        <h6 class="mb-4">Dashboard Admin</h6>
        <p class="text-white">Selamat datang, {{ Auth::user()->name }} 👋</p>
        <p class="text-white">Anda login sebagai <strong>Admin</strong>.</p>
    </div>
</div>
@endsection
