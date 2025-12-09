@extends('layouts.dashboard')
@section('title','Kelola Membership')

@section('content')
<div class="container-fluid pt-4 px-4">
  <div class="bg-secondary rounded h-100 p-4">
    <h6 class="mb-3">Pengajuan Membership</h6>

    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Pending list --}}
    <h6 class="mb-2">Pending</h6>
    <table class="table table-bordered text-white align-middle">
      <thead>
        <tr>
          <th>Customer</th>
          <th>Status</th>
          <th>Diajukan</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($pending as $sub)
        <tr>
          <td>{{ $sub->user->name }}</td>
          <td><span class="badge bg-warning text-dark">Pending</span></td>
          <td>{{ $sub->created_at->format('d M Y H:i') }}</td>
          <td class="d-flex flex-column gap-2">
            <div class="d-flex gap-2">
              <form method="POST" action="{{ route('admin.membership.approve',$sub) }}">
                @csrf
                <button class="btn btn-success btn-sm">Approve</button>
              </form>
              <form method="POST" action="{{ route('admin.membership.cancel',$sub) }}">
                @csrf
                <button class="btn btn-danger btn-sm">Cancel</button>
              </form>
            </div>
            {{-- ✅ Tambahkan kontak WA customer dengan pesan otomatis --}}
            <a href="https://wa.me/{{ $sub->user->phone ?? '6281234567890' }}?text=Halo%20{{ $sub->user->name }},%20pengajuan%20membership%20Premium%20Anda%20saat%20ini%20berstatus%20pending.%20Admin%20akan%20segera%20memverifikasi."
               class="btn btn-success btn-sm mt-2">
               Hubungi Customer via WhatsApp
            </a>
          </td>
        </tr>
        @empty
        <tr><td colspan="4" class="text-center text-muted">Tidak ada pengajuan pending.</td></tr>
        @endforelse
      </tbody>
    </table>

    {{-- Active list --}}
    <h6 class="mt-4 mb-2">Aktif</h6>
    <table class="table table-bordered text-white align-middle">
      <thead>
        <tr>
          <th>Customer</th>
          <th>Mulai</th>
          <th>Berakhir</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($active as $sub)
        <tr>
          <td>{{ $sub->user->name }}</td>
          <td>{{ $sub->starts_at ? $sub->starts_at->format('d M Y') : '—' }}</td>
          <td>{{ $sub->ends_at ? $sub->ends_at->format('d M Y') : '—' }}</td>
          <td class="d-flex gap-2">
            <form method="POST" action="{{ route('admin.membership.downgrade',$sub->user_id) }}">
              @csrf
              <button class="btn btn-warning btn-sm">Downgrade</button>
            </form>
            {{-- ✅ Opsional: kontak WA customer aktif --}}
            <a href="https://wa.me/{{ $sub->user->phone ?? '6281234567890' }}?text=Halo%20{{ $sub->user->name }},%20membership%20Premium%20Anda%20aktif%20hingga%20{{ $sub->ends_at?->format('d M Y') }}."
               class="btn btn-success btn-sm">
               WhatsApp Customer
            </a>
          </td>
        </tr>
        @empty
        <tr><td colspan="4" class="text-center text-muted">Belum ada membership aktif.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
