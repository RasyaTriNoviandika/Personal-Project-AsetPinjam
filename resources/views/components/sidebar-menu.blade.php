@php
    use App\Helpers\RoleHelper;
    $userRole = auth()->user()->role ?? null;
    // $menus = RoleHelper::getMenuByRole($userRole);
@endphp

<nav>
    <ul>
        <li><a href="{{ route('dashboard') }}">Dashboard</a></li>

        @if($role === 'admin')
            <li><a href="{{ route('laporan.index') }}">Laporan</a></li>
            <li><a href="{{ route('user.index') }}">User  </a></li>
            <li><a href="{{ route('barang.index') }}">Barang</a></li>
        @endif

        @if($role === 'operator')
            <li><a href="{{ route('peminjaman.index') }}">Peminjaman</a></li>
        @endif

        @if($role === 'user')
            <li><a href="{{ route('peminjaman.status') }}">Status Peminjaman</a></li>
        @endif

        <li>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" style="background:none;border:none;padding:0;color:#007bff;cursor:pointer;">Logout</button>
            </form>
        </li>
    </ul>
    
</nav>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('dashboard') }}" class="brand-link">
        <img src="{{ asset('admin/dist/img/AdminLTELogo.png') }}" alt="Logo" class="brand-image img-circle elevation-3">
        <span class="brand-text font-weight-light">Rental System</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- User Panel -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('admin/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="{{ route('settings.profile') }}" class="d-block">
                    {{ auth()->user()->name }}
                    <small class="badge badge-{{ RoleHelper::getRoleColor($userRole) }} ml-1">
                        {{ RoleHelper::getRoleName($userRole) }}
                    </small>
                </a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                @foreach($menus as $key => $menu)
                    @if(strpos($key, 'divider_') === 0)
                        {{-- Divider --}}
                        <li class="nav-header">{{ $menu }}</li>
                    @else
                        @php
                            [$label, $icon, $route] = $menu;
                            $isActive = request()->routeIs($route . '*');
                        @endphp
                        
                        <li class="nav-item">
                            <a href="{{ route($route) }}" class="nav-link {{ $isActive ? 'active' : '' }}">
                                <i class="nav-icon {{ $icon }}"></i>
                                <p>{{ $label }}</p>
                                @if($isActive)
                                    <i class="right fas fa-angle-left"></i>
                                @endif
                            </a>
                        </li>
                    @endif
                @endforeach

                {{-- Logout --}}
                <li class="nav-header">SISTEM</li>
                <li class="nav-item">
                    <a href="{{ route('logout') }}" class="nav-link"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </nav>
    </div>
</aside>