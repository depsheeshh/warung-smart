@extends('layouts.dashboard')
@section('title','Diskon Membership')

@section('content')
<div class="container-fluid pt-4 px-4">
  <div class="bg-secondary rounded h-100 p-4">
    <h6 class="mb-3">Daftar Diskon Membership</h6>

    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Tombol tambah diskon -->
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createDiscountModal">
      Tambah Diskon
    </button>

    <table class="table table-bordered text-white align-middle">
      <thead>
        <tr>
          <th>Diskon (%)</th>
          <th>Berlaku</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($discounts as $d)
        <tr>
          <td>{{ $d->discount_percent }}%</td>
          <td>{{ $d->starts_at->format('d M Y') }} - {{ $d->ends_at->format('d M Y') }}</td>
          <td class="d-flex gap-2">
            <!-- Tombol edit -->
            <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                    data-bs-target="#editDiscountModal{{ $d->id }}">Edit</button>
            <!-- Tombol hapus -->
            <form method="POST" action="{{ route('admin.membership_discounts.destroy',$d) }}">
              @csrf @method('DELETE')
              <button class="btn btn-danger btn-sm">Hapus</button>
            </form>
          </td>
        </tr>

        <!-- Modal Edit -->
        <div class="modal fade" id="editDiscountModal{{ $d->id }}" tabindex="-1">
          <div class="modal-dialog">
            <div class="modal-content bg-dark text-white">
              <div class="modal-header">
                <h5 class="modal-title">Edit Diskon</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <form method="POST" action="{{ route('admin.membership_discounts.update',$d) }}" class="needs-validation" novalidate>
                @csrf @method('PUT')
                <div class="modal-body">
                  <div class="mb-3">
                    <label>Diskon (%)</label>
                    <input type="number" step="0.01" name="discount_percent" class="form-control"
                           value="{{ $d->discount_percent }}" required min="0" max="100">
                    <div class="invalid-feedback">Masukkan persentase diskon antara 0–100.</div>
                  </div>
                  <div class="mb-3">
                    <label>Mulai</label>
                    <input type="date" name="starts_at" class="form-control"
                           value="{{ $d->starts_at->format('Y-m-d') }}" required>
                  </div>
                  <div class="mb-3">
                    <label>Berakhir</label>
                    <input type="date" name="ends_at" class="form-control"
                           value="{{ $d->ends_at->format('Y-m-d') }}" required>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="submit" class="btn btn-success">Simpan</button>
                </div>
              </form>
            </div>
          </div>
        </div>
        @empty
        <tr><td colspan="3" class="text-center text-muted">Belum ada diskon.</td></tr>
        @endforelse
      </tbody>
    </table>
    {{ $discounts->links() }}
  </div>
</div>

<!-- Modal Create -->
<div class="modal fade" id="createDiscountModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content bg-dark text-white">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Diskon Membership</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST" action="{{ route('admin.membership_discounts.store') }}" class="needs-validation" novalidate>
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label>Diskon (%)</label>
            <input type="number" name="discount_percent" class="form-control" required min="0" max="100">
            <div class="invalid-feedback">Masukkan persentase diskon antara 0–100.</div>
          </div>
          <div class="mb-3">
            <label>Mulai</label>
            <input type="date" name="starts_at" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Berakhir</label>
            <input type="date" name="ends_at" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  (function () {
    'use strict'
    const forms = document.querySelectorAll('.needs-validation')
    Array.from(forms).forEach(form => {
      form.addEventListener('submit', event => {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }
        form.classList.add('was-validated')
      }, false)
    })
  })()
</script>
@endsection
