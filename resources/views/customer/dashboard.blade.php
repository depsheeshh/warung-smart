@extends('layouts.dashboard')
@section('title','Dashboard Customer')

@section('content')
<div class="container-fluid pt-4 px-4">
  <div class="bg-secondary rounded h-100 p-4">
    <h6 class="mb-4">Dashboard Customer</h6>
    <p class="text-white">Hai {{ Auth::user()->name }} 👋</p>
    <p class="text-white">Anda login sebagai <strong>Customer</strong>.</p>

    {{-- Indikator ringkasan --}}
    <div class="row mb-4">
      <div class="col-md-4"><div class="card bg-dark text-white p-3"><h6>Total Pesanan Saya</h6><h4>{{ $myOrders ?? 0 }}</h4></div></div>
      <div class="col-md-4"><div class="card bg-success text-white p-3"><h6>Pesanan Diterima</h6><h4>{{ $myAcceptedOrders ?? 0 }}</h4></div></div>
      <div class="col-md-4">
        @if(Auth::user()->isPremium())
          <div class="card bg-warning text-dark p-3"><h6>Status Membership</h6><h4>Premium</h4></div>
        @else
          <div class="card bg-light text-dark p-3"><h6>Status Membership</h6><h4>Basic</h4></div>
        @endif
      </div>
    </div>

    {{-- Chart riwayat belanja --}}
    <h6 class="mb-3">Riwayat Belanja Bulanan</h6>
    <canvas id="customerChart" height="120"></canvas>

    {{-- Chart kasbon --}}
    <h6 class="mt-5 mb-3">Grafik Kasbon Bulanan</h6>
    <canvas id="customerDebtChart" height="120"></canvas>

    {{-- Panel notifikasi kasbon --}}
    <h6 class="mt-5 mb-3">Notifikasi Pembelian Terbaru</h6>
    <div class="card bg-dark text-white p-3">
    @if($notifications->isEmpty())
        <p class="text-muted">Belum ada notifikasi kasbon.</p>
    @else
        <ul class="list-group list-group-flush">
        @foreach($notifications as $notif)
            <li class="list-group-item bg-dark text-white d-flex justify-content-between align-items-center">
            <span>{{ $notif->data['message'] ?? 'Notifikasi' }}</span>
            <small class="text-muted">{{ $notif->created_at->diffForHumans() }}</small>
            </li>
        @endforeach
        </ul>
    @endif
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  // Chart belanja
  const customerLabels = {!! json_encode($monthlyOrders->map(fn($row) => \Carbon\Carbon::create($row->year,$row->month)->translatedFormat('F Y'))) !!};
  const customerQty = {!! json_encode($monthlyOrders->pluck('qty_total')) !!};

  new Chart(document.getElementById('customerChart'), {
    type: 'line',
    data: {
      labels: customerLabels,
      datasets: [
        { label: 'Jumlah Barang Dipesan', data: customerQty, borderColor: 'rgba(255,99,132,1)', fill: false }
      ]
    }
  });

  // Chart kasbon
  const debtLabels = {!! json_encode($monthlyDebts->map(fn($row) => \Carbon\Carbon::create($row->year,$row->month)->translatedFormat('F Y'))) !!};
  const debtTotals = {!! json_encode($monthlyDebts->pluck('total_debt')) !!};

  new Chart(document.getElementById('customerDebtChart'), {
    type: 'bar',
    data: {
      labels: debtLabels,
      datasets: [
        { label: 'Total Kasbon (Rp)', data: debtTotals, backgroundColor: 'rgba(54,162,235,0.7)', borderColor: 'rgba(54,162,235,1)', borderWidth: 1 }
      ]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            callback: function(value) {
              return 'Rp ' + value.toLocaleString();
            }
          }
        }
      }
    }
  });
</script>
@endpush

