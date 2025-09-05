<ul class="sidebar-menu">

    {{-- Dashboard: semua role bisa lihat --}}
    <li>
        <a href="{{ route('dashboard') }}">
            <i class="fas fa-home"></i> Dashboard
        </a>
    </li>

    {{-- Menu khusus Admin --}}
    @if(auth()->user()->hasRole('admin'))
        <li class="menu-header">Admin Menu</li>

        @if(auth()->user()->hasPermission('manage_users'))
            <li>
                <a href="{{ route('users.index') }}">
                    <i class="fas fa-users"></i> Kelola User
                </a>
            </li>
        @endif

        @if(auth()->user()->hasPermission('system_settings'))
            <li>
                <a href="{{ route('settings.index') }}">
                    <i class="fas fa-cog"></i> Pengaturan Sistem
                </a>
            </li>
        @endif
    @endif

    {{-- Menu khusus Operator --}}
    @if(auth()->user()->hasRole('operator'))
        <li class="menu-header">Operator Menu</li>

        @if(auth()->user()->hasPermission('manage_rentals'))
            <li>
                <a href="{{ route('rentals.index') }}">
                    <i class="fas fa-box"></i> Kelola Peminjaman
                </a>
            </li>
        @endif

        @if(auth()->user()->hasPermission('view_reports'))
            <li>
                <a href="{{ route('reports.index') }}">
                    <i class="fas fa-chart-line"></i> Laporan
                </a>
            </li>
        @endif
    @endif

    {{-- Menu khusus User --}}
    @if(auth()->user()->hasRole('user'))
        <li class="menu-header">User Menu</li>

        @if(auth()->user()->hasPermission('rent_items'))
            <li>
                <a href="{{ route('items.index') }}">
                    <i class="fas fa-shopping-cart"></i> Sewa Barang
                </a>
            </li>
        @endif

        @if(auth()->user()->hasPermission('view_own'))
            <li>
                <a href="{{ route('myrentals.index') }}">
                    <i class="fas fa-list"></i> Peminjaman Saya
                </a>
            </li>
        @endif
    @endif

</ul>
