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

    {{-- Ajukan / Batalkan Membership --}}
    @if(!$subs || in_array($subs->status, ['cancelled','expired']))
      <div class="card bg-dark text-white p-3">
        <h6 class="mb-2">Ajukan Membership Premium</h6>
        <p class="text-muted mb-3">Pengajuan akan diverifikasi admin. Durasi awal: 1 bulan.</p>

        {{-- Tombol membuka modal step-by-step --}}
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#membershipModal">
          Ajukan Membership
        </button>
      </div>

    @elseif($subs && $subs->status === 'pending')
      <div class="card bg-warning text-dark p-3">
        <h6 class="mb-2">Pengajuan Pending</h6>
        <p>Menunggu verifikasi admin.</p>

        {{-- WhatsApp --}}
        <div class="mt-3">
          <p class="text-muted mb-1">Butuh konfirmasi langsung?</p>
          <a href="https://wa.me/6281234567890?text=Halo%20Admin,%20saya%20{{ auth()->user()->name }}%20sudah%20ajukan%20membership%20Premium%20dan%20status%20saya%20pending.%20Mohon%20konfirmasi."
             class="btn btn-success">
             Hubungi Admin via WhatsApp
          </a>
          <p class="mt-2">Nomor Admin (Pa Usman): <strong>+62 812-3456-7890</strong></p>
        </div>

        {{-- Tombol Batalkan --}}
        <form method="POST" action="{{ route('customer.membership.cancel', $subs->id) }}" class="mt-3">
          @csrf
          <button class="btn btn-outline-danger">Batalkan Pengajuan</button>
        </form>
      </div>

    @elseif($subs && $subs->status === 'active')
      <div class="card bg-success text-white p-3">
        <h6 class="mb-2">Membership Aktif</h6>
        <p>Selamat! Membership Premium aktif sampai {{ $subs->ends_at->format('d M Y') }}.</p>
      </div>
    @endif

  </div>
</div>

{{-- Modal Multi-Step Membership --}}
<div class="modal fade" id="membershipModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content bg-secondary text-white">
      <div class="modal-header">
        <h5 class="modal-title">Langkah Pendaftaran Membership</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">

        {{-- Progress bar --}}
        <div class="progress mb-4">
          <div id="progressBar" class="progress-bar bg-warning" role="progressbar" style="width: 33%">
            Step 1 dari 3
          </div>
        </div>

        {{-- Step 1 --}}
        <div id="step1">
          <h6><i class="fa fa-scroll me-2"></i> Syarat & Ketentuan</h6>
          <ul>
            <li>Membership berlaku 30 hari sejak aktivasi.</li>
            <li>Tidak dapat dipindahtangankan.</li>
            <li>Diskon berlaku sesuai kategori produk.</li>
          </ul>
          <div class="form-check mt-3">
            <input class="form-check-input" type="checkbox" id="agreeCheck">
            <label class="form-check-label" for="agreeCheck">Saya setuju</label>
          </div>
        </div>

        {{-- Step 2 --}}
        <div id="step2" class="d-none">
          <h6><i class="fa fa-star me-2"></i> Pilih Paket Membership</h6>
          <div class="row">
            <div class="col-md-6">
              <div class="card bg-dark text-white p-3">
                <h5>Basic</h5>
                <p>Tanpa diskon tambahan</p>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card bg-warning text-dark p-3">
                <h5>Premium</h5>
                <p>Dapatkan diskon berikut:</p>
                <ul>
                    @forelse($membershipDiscounts as $discount)
                        <li>
                        Diskon: {{ rtrim(rtrim(number_format($discount->discount_percent, 2), '0'), '.') }}%
                        <br>
                        Berlaku: {{ $discount->starts_at->format('d M Y') }} – {{ $discount->ends_at->format('d M Y') }}
                        </li>
                    @empty
                        <li class="text-muted">Belum ada diskon aktif saat ini.</li>
                    @endforelse
                    </ul>
              </div>
            </div>
          </div>
        </div>

        {{-- Step 3 --}}
        <div id="step3" class="d-none">
          <h6><i class="fa fa-check-circle me-2"></i> Konfirmasi</h6>
          <p>Anda akan berlangganan <strong>Premium</strong>. Klik tombol di bawah untuk aktivasi.</p>
          <form action="{{ route('customer.membership.subscribe') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-success">
              <i class="fa fa-check me-2"></i> Aktifkan Membership
            </button>
          </form>
        </div>

      </div>
      <div class="modal-footer">
        <button id="prevBtn" class="btn btn-light d-none">Kembali</button>
        <button id="nextBtn" class="btn btn-warning">Lanjut</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
let step = 1;
const progressBar = document.getElementById('progressBar');
const nextBtn = document.getElementById('nextBtn');
const prevBtn = document.getElementById('prevBtn');

function showStep(n) {
  // sembunyikan semua step
  document.querySelectorAll('#step1,#step2,#step3').forEach(el => el.classList.add('d-none'));
  // tampilkan step sesuai n
  document.getElementById('step'+n).classList.remove('d-none');

  // update progress bar
  progressBar.style.width = (n*33) + '%';
  progressBar.innerText = 'Step ' + n + ' dari 3';

  // toggle tombol
  prevBtn.classList.toggle('d-none', n===1);
  nextBtn.classList.toggle('d-none', n===3);
}

// tombol Next
nextBtn.addEventListener('click', () => {
  if(step===1 && !document.getElementById('agreeCheck').checked) {
    alert('Anda harus menyetujui syarat & ketentuan terlebih dahulu.');
    return;
  }
  step++;
  showStep(step);
});

// tombol Prev
prevBtn.addEventListener('click', () => {
  step--;
  showStep(step);
});

// inisialisasi step pertama
showStep(step);
</script>
@endpush
