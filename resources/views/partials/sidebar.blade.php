<div class="sidebar pe-4 pb-3">
  <nav class="navbar bg-secondary navbar-dark">
    <!-- Brand sesuai role -->
    @role('admin')
      <a href="{{ route('admin.dashboard') }}" class="navbar-brand mx-4 mb-3">
        <h3 class="text-primary"><i class="fa fa-user-edit me-2"></i>WarungSmart</h3>
      </a>
    @endrole
    @role('supplier')
      <a href="{{ route('supplier.dashboard') }}" class="navbar-brand mx-4 mb-3">
        <h3 class="text-primary"><i class="fa fa-user-edit me-2"></i>WarungSmart</h3>
      </a>
    @endrole
    @role('customer')
      <a href="{{ route('customer.dashboard') }}" class="navbar-brand mx-4 mb-3">
        <h3 class="text-primary"><i class="fa fa-user-edit me-2"></i>WarungSmart</h3>
      </a>
    @endrole

    <!-- User Info -->
    <div class="d-flex align-items-center ms-4 mb-4">
      <div class="position-relative">
        {{-- Foto profil bulat --}}
                @if(Auth::user()->avatar)
                    <img class="rounded-circle me-lg-2" src="{{ asset('storage/'.Auth::user()->avatar) }}" alt="Avatar" style="width: 40px; height: 40px;">
                @else
                    <img class="rounded-circle me-lg-2" src="{{ asset('img/user.jpg') }}" alt="Avatar" style="width: 40px; height: 40px;">
                @endif
        <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
      </div>
      <div class="ms-3">
        <h6 class="mb-0">{{ Auth::user()->name }}</h6>
        <span>{{ Auth::user()->roles->pluck('name')->implode(', ') }}</span>
      </div>
    </div>

    <ul class="navbar-nav w-100">
      <!-- Dashboard -->
      @role('admin')
        <!-- Dashboard -->
      <li class="nav-item">
        <a href="{{ route('admin.dashboard') }}"
           class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
           <i class="fa fa-tachometer-alt me-2"></i>Dashboard
        </a>
      </li>

      <!-- Membership Section -->
      <li class="nav-item mt-3 text-uppercase text-muted small px-3">
        <i class="fa fa-id-card me-2"></i>Membership
      </li>
      <li class="nav-item">
        <a href="{{ route('admin.membership.index') }}"
           class="nav-link {{ request()->routeIs('admin.membership.*') ? 'active' : '' }}">
           <i class="fa fa-users me-2"></i>Kelola Membership
        </a>
      </li>
      <li class="nav-item">
        <a href="{{ route('admin.membership_discounts.index') }}"
           class="nav-link {{ request()->routeIs('admin.membership_discounts.*') ? 'active' : '' }}">
           <i class="fa fa-percent me-2"></i>Diskon Membership
        </a>
      </li>

      <!-- Transaksi Jual Beli Section -->
      <li class="nav-item mt-3 text-uppercase text-muted small px-3">
        <i class="fa fa-shopping-cart me-2"></i>Transaksi Jual Beli
      </li>
      @can('products.view')
      <li class="nav-item">
        <a href="{{ route('admin.products.index') }}"
           class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
           <i class="fa fa-box me-2"></i>Produk
        </a>
      </li>
      @endcan
      <li class="nav-item">
        <a href="{{ route('admin.orders.index') }}"
           class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
           <i class="fa fa-tasks me-2"></i>Manajemen Pesanan
        </a>
      </li>

       <!-- Forecast Section -->
      <li class="nav-item mt-3 text-uppercase text-muted small px-3">
        <i class="fa fa-chart-bar me-2"></i>Forecast
      </li>
      <li class="nav-item">
        <a href="{{ route('admin.forecast.summary') }}"
           class="nav-link {{ request()->routeIs('admin.forecast.summary') ? 'active' : '' }}">
           <i class="fa fa-chart-pie me-2"></i>Ringkasan Forecast
        </a>
      </li>

      <!-- Jadwal Supplier Section -->
        <li class="nav-item mt-3 text-uppercase text-muted small px-3">
        <i class="fa fa-truck me-2"></i>Supplier
        </li>
        <li class="nav-item">
        <a href="{{ route('admin.schedules.index') }}"
            class="nav-link {{ request()->routeIs('admin.schedules.*') ? 'active' : '' }}">
            <i class="fa fa-calendar-alt me-2"></i>Jadwal Supplier
        </a>
        </li>

        <!-- Kasbon Section -->
        <li class="nav-item mt-3 text-uppercase text-muted small px-3">
        <i class="fa fa-money-bill me-2"></i>Kasbon
        </li>
        <li class="nav-item">
        <a href="{{ route('admin.debts.index') }}"
            class="nav-link {{ request()->routeIs('admin.debts.*') ? 'active' : '' }}">
            <i class="fa fa-wallet me-2"></i>Manajemen Kasbon
        </a>
        </li>

      <!-- Laporan Section -->
      <li class="nav-item mt-3 text-uppercase text-muted small px-3">
        <i class="fa fa-file-alt me-2"></i>Laporan
      </li>
      <li class="nav-item">
        <a href="{{ route('admin.reports.index') }}"
           class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
           <i class="fa fa-chart-line me-2"></i>Laporan
        </a>
      </li>

      <!-- Manajemen User Section -->
      <li class="nav-item mt-3 text-uppercase text-muted small px-3">
        <i class="fa fa-users me-2"></i>Manajemen User
      </li>
      @canany(['users.view','roles.view','permissions.view'])
      <li class="nav-item dropdown">
        <a href="#"
           class="nav-link dropdown-toggle {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.roles.*') || request()->routeIs('admin.permissions.*') ? 'active' : '' }}"
           data-bs-toggle="dropdown">
           <i class="fa fa-user-cog me-2"></i>User & Role
        </a>
        <div class="dropdown-menu bg-transparent border-0
                    {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.roles.*') || request()->routeIs('admin.permissions.*') ? 'show' : '' }}">
            @can('users.view')
            <a href="{{ route('admin.users.index') }}" class="dropdown-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
              <i class="fa fa-user me-2"></i>Data User
            </a>
            @endcan
            @can('roles.view')
            <a href="{{ route('admin.roles.index') }}" class="dropdown-item {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
              <i class="fa fa-id-badge me-2"></i>Role
            </a>
            @endcan
            @can('permissions.view')
            <a href="{{ route('admin.permissions.index') }}" class="dropdown-item {{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}">
              <i class="fa fa-key me-2"></i>Permission
            </a>
            @endcan
        </div>
      </li>
      @endcanany
      @endrole

      @role('supplier')
        <!-- Dashboard -->
      <li class="nav-item">
        <a href="{{ route('supplier.dashboard') }}" class="nav-link {{ request()->routeIs('supplier.dashboard') ? 'active' : '' }}">
          <i class="fa fa-tachometer-alt me-2"></i>Dashboard
        </a>
      </li>

      <!-- Produk Section -->
      <li class="nav-item mt-3 text-uppercase text-muted small px-3">
        <i class="fa fa-box me-2"></i>Produk
      </li>
      <li class="nav-item">
        <a href="{{ route('supplier.products.index') }}" class="nav-link {{ request()->routeIs('supplier.products.*') ? 'active' : '' }}">
          <i class="fa fa-box-open me-2"></i>Produk Saya
        </a>
      </li>

      <!-- Pesanan Section -->
      <li class="nav-item mt-3 text-uppercase text-muted small px-3">
        <i class="fa fa-clipboard-list me-2"></i>Pesanan
      </li>
      <li class="nav-item">
        <a href="{{ route('supplier.orders.index') }}" class="nav-link {{ request()->routeIs('supplier.orders.*') ? 'active' : '' }}">
          <i class="fa fa-clipboard-check me-2"></i>Pesanan Masuk
        </a>
      </li>

      <!-- Harga Produk Section -->
    <li class="nav-item mt-3 text-uppercase text-muted small px-3">
    <i class="fa fa-tags me-2"></i>Harga Produk
    </li>
    <li class="nav-item">
    <a href="{{ route('supplier.prices.index') }}"
        class="nav-link {{ request()->routeIs('supplier.prices.*') ? 'active' : '' }}">
        <i class="fa fa-money-bill-wave me-2"></i>Histori Harga
    </a>
    </li>

    @endrole

    @role('customer')
    <!-- Dashboard -->
      <li class="nav-item">
        <a href="{{ route('customer.dashboard') }}" class="nav-link {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
          <i class="fa fa-tachometer-alt me-2"></i>Dashboard
        </a>
      </li>
        <!-- Produk Section -->
      <li class="nav-item mt-3 text-uppercase text-muted small px-3">
        <i class="fa fa-shopping-cart me-2"></i>Produk
      </li>
      <li class="nav-item">
        <a href="{{ route('customer.products.index') }}" class="nav-link {{ request()->routeIs('customer.products.*') ? 'active' : '' }}">
          <i class="fa fa-store me-2"></i>Produk Tersedia
        </a>
      </li>

      <!-- Pesanan Section -->
      <li class="nav-item mt-3 text-uppercase text-muted small px-3">
        <i class="fa fa-clipboard-list me-2"></i>Pesanan
      </li>
      <li class="nav-item">
        <a href="{{ route('customer.orders.index') }}" class="nav-link {{ request()->routeIs('customer.orders.*') ? 'active' : '' }}">
          <i class="fa fa-shopping-basket me-2"></i>Pesanan Saya
        </a>
      </li>

      <!-- Membership Section -->
      <li class="nav-item mt-3 text-uppercase text-muted small px-3">
        <i class="fa fa-star me-2"></i>Membership
      </li>
      <li class="nav-item">
        <a href="{{ route('customer.membership.index') }}" class="nav-link {{ request()->routeIs('customer.membership.*') ? 'active' : '' }}">
          <i class="fa fa-star-half-alt me-2"></i>Membership Saya
        </a>
      </li>

      <!-- Kasbon Section -->
  <li class="nav-item mt-3 text-uppercase text-muted small px-3">
    <i class="fa fa-money-bill me-2"></i>Kasbon
  </li>
  <li class="nav-item">
    <a href="{{ route('customer.debts.index') }}" class="nav-link {{ request()->routeIs('customer.debts.*') ? 'active' : '' }}">
      <i class="fa fa-wallet me-2"></i>Kasbon Saya
    </a>
  </li>
    @endrole


    </ul>
  </nav>
</div>
