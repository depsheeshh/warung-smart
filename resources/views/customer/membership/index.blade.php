@extends('layouts.dashboard')
@section('title','Membership')

@section('content')
<div class="container-fluid pt-4 px-4">
  <div class="bg-secondary rounded h-100 p-4">

    {{-- Header status --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h6 class="mb-0">Membership Customer</h6>
      @if(auth()->user()->isPremium())
        <span class="badge bg-warning text-dark">Premium Aktif</span>
      @else
        <span class="badge bg-light text-dark">Basic</span>
      @endif
    </div>

    {{-- Alert --}}
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Status subscription terakhir --}}
    <div class="card bg-dark text-white p-3 mb-4">
      <h6 class="mb-2">Status Membership Saat Ini</h6>
      @if($subs)
        <p><strong>Status:</strong> {{ ucfirst($subs->status) }}</p>
        <p><strong>Mulai:</strong> {{ $subs->starts_at ? $subs->starts_at->format('d M Y') : '—' }}</p>
        <p><strong>Berakhir:</strong> {{ $subs->ends_at ? $subs->ends_at->format('d M Y') : '—' }}</p>
      @else
        <p class="text-muted">Belum ada pengajuan membership.</p>
      @endif
    </div>

    {{-- Ajukan membership --}}
    @if(!$subs || in_array($subs->status, ['cancelled','expired']))
      <div class="card bg-dark text-white p-3">
        <h6 class="mb-2">Ajukan Membership Premium</h6>
        <p class="text-muted mb-3">Pengajuan akan diverifikasi admin. Durasi awal: 1 bulan.</p>

        <form method="POST" action="{{ route('customer.membership.subscribe') }}">
          @csrf
          <button class="btn btn-primary">Ajukan Membership</button>
        </form>
      </div>

    @elseif($subs && $subs->status === 'pending')
      <div class="card bg-warning text-dark p-3">
        <h6 class="mb-2">Pengajuan Pending</h6>
        <p>Menunggu verifikasi admin.</p>

        {{-- ✅ WhatsApp muncul di status pending dengan pesan otomatis --}}
        <div class="mt-3">
          <p class="text-muted mb-1">Butuh konfirmasi langsung?</p>
          <a href="https://wa.me/6281234567890?text=Halo%20Admin,%20saya%20{{ auth()->user()->name }}%20sudah%20ajukan%20membership%20Premium%20dan%20status%20saya%20pending.%20Mohon%20konfirmasi."
             class="btn btn-success">
             Hubungi Admin via WhatsApp
          </a>
          <p class="mt-2">Nomor Admin (Pa Usman): <strong>+62 812-3456-7890</strong></p>
        </div>
      </div>

    @elseif($subs && $subs->status === 'active')
      <div class="card bg-success text-white p-3">
        <h6 class="mb-2">Membership Aktif</h6>
        <p>Selamat! Membership Premium aktif sampai {{ $subs->ends_at->format('d M Y') }}.</p>
      </div>
    @endif

  </div>
</div>
@endsection
