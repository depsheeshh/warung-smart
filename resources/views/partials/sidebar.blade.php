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
        <img class="rounded-circle" src="{{ asset('img/user.jpg') }}" alt="" style="width: 40px; height: 40px;">
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
        <li class="nav-item">
        <a href="{{ route('admin.dashboard') }}"
            class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fa fa-tachometer-alt me-2"></i>Dashboard
        </a>
        </li>

        <!-- Membership Admin -->
        <li class="nav-item">
          <a href="{{ route('admin.membership.index') }}"
             class="nav-link {{ request()->routeIs('admin.membership.*') ? 'active' : '' }}">
             <i class="fa fa-id-card me-2"></i>Kelola Membership
          </a>
        </li>

        <li class="nav-item">
        <a href="{{ route('admin.membership_discounts.index') }}"
            class="nav-link {{ request()->routeIs('admin.membership_discounts.*') ? 'active' : '' }}">
            <i class="fa fa-percent me-2"></i>Diskon Membership
        </a>
        </li>

        @endrole

        @role('supplier')
        <li class="nav-item">
        <a href="{{ route('supplier.dashboard') }}"
            class="nav-link {{ request()->routeIs('supplier.dashboard') ? 'active' : '' }}">
            <i class="fa fa-tachometer-alt me-2"></i>Dashboard
        </a>
        </li>
        @endrole

        @role('customer')
        <li class="nav-item">
        <a href="{{ route('customer.dashboard') }}"
            class="nav-link {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
            <i class="fa fa-tachometer-alt me-2"></i>Dashboard
        </a>
        </li>
        @endrole


      <!-- Manajemen User Dropdown (Admin only) -->
      @role('admin')
      @canany(['users.view','roles.view','permissions.view'])
        <li class="nav-item dropdown">
          <a href="#"
             class="nav-link dropdown-toggle {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.roles.*') || request()->routeIs('admin.permissions.*') ? 'active' : '' }}"
             data-bs-toggle="dropdown">
             <i class="fa fa-users me-2"></i>Manajemen <br> User
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
        @can('products.view')
        <li class="nav-item">
        <a href="{{ route('admin.products.index') }}"
            class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
            <i class="fa fa-box me-2"></i>Produk
        </a>
        </li>
        @endcan
      @endcanany

      <li class="nav-item">
        <a href="{{ route('admin.orders.index') }}"
            class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
            <i class="fa fa-tasks me-2"></i>Manajemen Pesanan
        </a>
        </li>
      <li class="nav-item">
        <a href="{{ route('admin.reports.index') }}"
            class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
            <i class="fa fa-tasks me-2"></i>Laporan Transaksi dan Produk
        </a>
        </li>
      @endrole

      @role('supplier')
        <li class="nav-item">
        <a href="{{ route('supplier.products.index') }}"
            class="nav-link {{ request()->routeIs('supplier.products.*') ? 'active' : '' }}">
            <i class="fa fa-box me-2"></i>Produk Saya
        </a>
        </li>
        <li class="nav-item">
        <a href="{{ route('supplier.orders.index') }}"
            class="nav-link {{ request()->routeIs('supplier.orders.*') ? 'active' : '' }}">
            <i class="fa fa-clipboard-list me-2"></i>Pesanan Masuk
        </a>
        </li>
    @endrole

    @role('customer')
        <li class="nav-item">
        <a href="{{ route('customer.products.index') }}"
            class="nav-link {{ request()->routeIs('customer.products.*') ? 'active' : '' }}">
            <i class="fa fa-shopping-cart me-2"></i>Produk Tersedia
        </a>
        </li>
        <li class="nav-item">
        <a href="{{ route('customer.orders.index') }}"
            class="nav-link {{ request()->routeIs('customer.orders.*') ? 'active' : '' }}">
            <i class="fa fa-shopping-cart me-2"></i>Pesanan Saya
        </a>
        </li>
         <!-- Membership Customer -->
        <li class="nav-item">
          <a href="{{ route('customer.membership.index') }}"
             class="nav-link {{ request()->routeIs('customer.membership.*') ? 'active' : '' }}">
             <i class="fa fa-star me-2"></i>Membership Saya
          </a>
        </li>
    @endrole


    </ul>
  </nav>
</div>
