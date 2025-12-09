@extends('layouts.dashboard')

@section('title','Dashboard Customer')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary rounded h-100 p-4">
        <h6 class="mb-4">Dashboard Customer</h6>
        <p class="text-white">Hai {{ Auth::user()->name }} 👋</p>
        <p class="text-white">Anda login sebagai <strong>Customer</strong>.</p>
    </div>
</div>
@endsection
