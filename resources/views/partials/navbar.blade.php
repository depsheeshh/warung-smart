<nav class="navbar navbar-expand bg-secondary navbar-dark sticky-top px-4 py-0">
    <!-- Brand link sesuai role -->
    @role('admin')
      <a href="{{ route('admin.dashboard') }}" class="navbar-brand d-flex d-lg-none me-4">
          <h2 class="text-primary mb-0"><i class="fa fa-user-edit"></i></h2>
      </a>
    @endrole
    @role('supplier')
      <a href="{{ route('supplier.dashboard') }}" class="navbar-brand d-flex d-lg-none me-4">
          <h2 class="text-primary mb-0"><i class="fa fa-user-edit"></i></h2>
      </a>
    @endrole
    @role('customer')
      <a href="{{ route('customer.dashboard') }}" class="navbar-brand d-flex d-lg-none me-4">
          <h2 class="text-primary mb-0"><i class="fa fa-user-edit"></i></h2>
      </a>
    @endrole

    <!-- Sidebar toggler -->
    <a href="#" class="sidebar-toggler flex-shrink-0">
        <i class="fa fa-bars"></i>
    </a>

    <!-- Search -->
    <form class="d-none d-md-flex ms-4">
        <input class="form-control bg-dark border-0" type="search" placeholder="Search">
    </form>

    <!-- Navbar kanan -->
    <div class="navbar-nav align-items-center ms-auto">

        {{-- Notifikasi --}}
        <div class="nav-item dropdown me-3">
            <a href="#" class="nav-link dropdown-toggle" id="notifDropdown" data-bs-toggle="dropdown">
                <i class="fa fa-bell"></i>
                @if(Auth::user()->unreadNotifications->count() > 0)
                    <span class="badge bg-danger">
                        {{ Auth::user()->unreadNotifications->count() }}
                    </span>
                @endif
            </a>

            <div class="dropdown-menu dropdown-menu-end bg-secondary border-0 rounded-0 rounded-bottom m-0 notif-dropdown"
                aria-labelledby="notifDropdown">

                <h6 class="dropdown-header text-white">Notifikasi</h6>

                @forelse(Auth::user()->unreadNotifications as $notification)
                    <a href="#" class="dropdown-item notif-item">
                        <i class="fa fa-info-circle me-2"></i>
                        {{ $notification->data['message'] }}
                        <small class="d-block text-muted">
                            {{ $notification->created_at->diffForHumans() }}
                        </small>
                    </a>
                @empty
                    <span class="dropdown-item text-muted">
                        Tidak ada notifikasi baru
                    </span>
                @endforelse

                <div class="dropdown-divider"></div>

                <form action="{{ route('notifications.read') }}" method="POST" class="text-center px-3 pb-2">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-light w-100">
                        Tandai semua dibaca
                    </button>
                </form>
            </div>
        </div>

        {{-- User dropdown --}}
        <div class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                {{-- Foto profil bulat --}}
                @if(Auth::user()->avatar)
                    <img class="rounded-circle me-lg-2" src="{{ asset('storage/'.Auth::user()->avatar) }}" alt="Avatar" style="width: 40px; height: 40px;">
                @else
                    <img class="rounded-circle me-lg-2" src="{{ asset('img/user.jpg') }}" alt="Avatar" style="width: 40px; height: 40px;">
                @endif

                <span class="d-none d-lg-inline-flex">
                    {{ Auth::user()->name }}
                    {{-- Badge khusus role customer --}}
                    @role('customer')
                        @if(Auth::user()->isPremium())
                            <span class="badge bg-warning text-dark ms-2">Premium</span>
                        @else
                            <span class="badge bg-light text-dark ms-2">Basic</span>
                        @endif
                    @endrole
                </span>
            </a>
            <div class="dropdown-menu dropdown-menu-end bg-secondary border-0 rounded-0 rounded-bottom m-0">
                <a href="{{ route('profile.index') }}" class="dropdown-item">
                    <i class="fa fa-user me-2"></i> My Profile
                </a>
                @role('customer')
                  <a href="{{ route('customer.membership.index') }}" class="dropdown-item">
                      <i class="fa fa-star me-2"></i> Membership Saya
                  </a>
                @endrole
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="dropdown-item">
                        <i class="fa fa-sign-out-alt me-2"></i> Log Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
