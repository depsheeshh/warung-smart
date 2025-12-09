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

    <!-- User dropdown -->
    <div class="navbar-nav align-items-center ms-auto">
        <div class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                <img class="rounded-circle me-lg-2" src="{{ asset('img/user.jpg') }}" alt="" style="width: 40px; height: 40px;">
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
                <a href="#" class="dropdown-item">My Profile</a>
                <a href="#" class="dropdown-item">Settings</a>
                {{-- Tambahan menu Membership khusus customer --}}
                @role('customer')
                  <a href="{{ route('customer.membership.index') }}" class="dropdown-item">
                      <i class="fa fa-star me-2"></i> Membership Saya
                  </a>
                @endrole
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="dropdown-item">Log Out</button>
                </form>
            </div>
        </div>
    </div>
</nav>
