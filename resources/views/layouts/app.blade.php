<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Sistem Penyewaan Barang')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --sidebar-width: 250px;
            --header-height: 60px;
            --primary-color: #4e73df;
            --secondary-color: #858796;
            --success-color: #1cc88a;
            --danger-color: #e74a3b;
            --warning-color: #f6c23e;
            --info-color: #36b9cc;
        }

        body {
            font-size: 0.875rem;
            background-color: #f8f9fc;
        }

        /* Header */
        .main-header {
            background: linear-gradient(135deg, var(--primary-color), #6f42c1);
            color: white;
            height: var(--header-height);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .main-header .navbar-brand {
            font-weight: bold;
            color: white !important;
            font-size: 1.1rem;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: var(--header-height);
            left: 0;
            width: var(--sidebar-width);
            height: calc(100vh - var(--header-height));
            background: white;
            box-shadow: 0 0 35px rgba(0,0,0,0.1);
            transform: translateX(-100%);
            transition: transform 0.3s ease;
            z-index: 1020;
            overflow-y: auto;
        }

        .sidebar.show { transform: translateX(0); }
        .sidebar-nav { padding: 1rem 0; }
        .sidebar-nav .nav-link {
            padding: 0.75rem 1.5rem;
            color: #5a5c69;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
        }
        .sidebar-nav .nav-link:hover {
            background-color: #f8f9fc;
            color: var(--primary-color);
            padding-left: 2rem;
        }
        .sidebar-nav .nav-link.active {
            background-color: var(--primary-color);
            color: white;
            border-left: 3px solid #fff;
        }
        .sidebar-nav .nav-link i {
            width: 20px;
            margin-right: 0.75rem;
        }
        .sidebar-divider { border-top: 1px solid #e3e6f0; margin: 1rem 0; }
        .sidebar-heading {
            font-size: 0.65rem;
            font-weight: 800;
            color: #b7b9cc;
            text-transform: uppercase;
            letter-spacing: 0.1rem;
            padding: 0.75rem 1.5rem 0.25rem;
        }

        /* Main Content */
        .main-content {
            margin-left: 0;
            margin-top: var(--header-height);
            padding: 1.5rem;
            min-height: calc(100vh - var(--header-height));
            transition: margin-left 0.3s ease;
        }

        /* Mobile overlay */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1010;
            display: none;
        }
        .sidebar-overlay.show { display: block; }

        /* Desktop styles */
        @media (min-width: 768px) {
            .sidebar { transform: translateX(0); }
            .main-content { margin-left: var(--sidebar-width); }
            .sidebar-toggle { display: none; }
            .sidebar-overlay { display: none !important; }
        }

        /* Cards */
        .card { border: none; box-shadow: 0 0.15rem 1.75rem 0 rgba(58,59,69,0.15); border-radius: 0.5rem; }
        .card-header { background-color: #f8f9fc; border-bottom: 1px solid #e3e6f0; }

        /* Buttons */
        .btn { border-radius: 0.35rem; font-size: 0.875rem; }
        .btn-primary { background: var(--primary-color); border-color: var(--primary-color); }

        /* Stats cards */
        .border-left-primary { border-left: 0.25rem solid var(--primary-color) !important; }
        .border-left-success { border-left: 0.25rem solid var(--success-color) !important; }
        .border-left-info { border-left: 0.25rem solid var(--info-color) !important; }
        .border-left-warning { border-left: 0.25rem solid var(--warning-color) !important; }
        .border-left-danger { border-left: 0.25rem solid var(--danger-color) !important; }

        /* User dropdown */
        .user-dropdown .dropdown-toggle::after { display: none; }
        .user-dropdown .dropdown-menu { box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15); border: none; border-radius: 0.5rem; }

        /* Notification badge */
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--danger-color);
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Responsive table */
        .table-responsive { border-radius: 0.5rem; }
        .spinner-border-sm { width: 1rem; height: 1rem; }
        .text-gray-800 { color: #5a5c69 !important; }
        .text-gray-300 { color: #dddfeb !important; }
        .bg-gray-100 { background-color: #f8f9fc !important; }
    </style>
</head>
<body>
    <!-- Header -->
    <nav class="navbar navbar-expand main-header">
        <div class="container-fluid px-3">
            <button class="btn btn-link text-white me-3 sidebar-toggle d-md-none" type="button">
                <i class="fas fa-bars"></i>
            </button>
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="fas fa-cube me-2"></i>AsetPinjam
            </a>
            <ul class="navbar-nav ms-auto">
                <!-- Notifications -->
                <li class="nav-item dropdown">
                    <a class="nav-link text-white position-relative" href="#" id="notificationDropdown" 
                       role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge" id="notificationCount" style="display: none;">0</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end shadow" style="width: 300px;">
                        <h6 class="dropdown-header"><i class="fas fa-bell me-2"></i>Notifikasi</h6>
                        <div id="notificationList" class="max-height-200 overflow-auto">
                            <div class="dropdown-item-text text-center text-muted py-3">
                                <i class="fas fa-check-circle me-2"></i>Tidak ada notifikasi baru
                            </div>
                        </div>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-center small" href="{{ route('notifications.index') }}">
                            Lihat Semua Notifikasi
                        </a>
                    </div>
                </li>

                <!-- User Dropdown -->
                @if(auth()->check())
                <li class="nav-item dropdown user-dropdown">
                    <a class="nav-link text-white dropdown-toggle" href="#" id="userDropdown" 
                       role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle me-2"></i>
                        <span class="d-none d-sm-inline">{{ optional(auth()->user())->name ?? '' }}</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end shadow">
                        <div class="dropdown-header">
                            <strong>{{ optional(auth()->user())->name ?? '' }}</strong><br>
                            <small class="text-muted">{{ optional(auth()->user())->role ? ucfirst(auth()->user()->role) : '' }}</small>
                        </div>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('settings.profile') }}"><i class="fas fa-user me-2"></i>Profil</a>
                        <a class="dropdown-item" href="{{ route('settings.account') }}"><i class="fas fa-cog me-2"></i>Pengaturan</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#" 
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt me-2"></i>Keluar
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                    </div>
                </li>
                @endif
            </ul>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <ul class="nav flex-column sidebar-nav">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i>Dashboard
                </a>
            </li>

            @if(auth()->user()->role === 'admin')
            <div class="sidebar-divider"></div>
            <div class="sidebar-heading">Management</div>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}"><i class="fas fa-users"></i>Kelola User</a></li>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('barang.*') ? 'active' : '' }}" href="{{ route('barang.index') }}"><i class="fas fa-boxes"></i>Kelola Barang</a></li>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('kategori-barang.*') ? 'active' : '' }}" href="{{ route('kategori-barang.index') }}"><i class="fas fa-tags"></i>Kategori Barang</a></li>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('peminjam.*') ? 'active' : '' }}" href="{{ route('peminjam.index') }}"><i class="fas fa-address-book"></i>Data Peminjam</a></li>
            <div class="sidebar-divider"></div>
            <div class="sidebar-heading">Transaksi</div>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('peminjaman.*') ? 'active' : '' }}" href="{{ route('peminjaman.index') }}"><i class="fas fa-handshake"></i>Kelola Peminjaman</a></li>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('transaksi-keuangan.*') ? 'active' : '' }}" href="{{ route('transaksi-keuangan.index') }}"><i class="fas fa-money-bill-wave"></i>Transaksi Keuangan</a></li>
            <div class="sidebar-divider"></div>
            <div class="sidebar-heading">Laporan</div>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}" href="{{ route('laporan.index') }}"><i class="fas fa-chart-bar"></i>Laporan</a></li>

            @elseif(auth()->user()->role === 'user')
            <div class="sidebar-divider"></div>
            <div class="sidebar-heading">Penyewaan</div>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('barang.*') ? 'active' : '' }}" href="{{ route('barang.index') }}"><i class="fas fa-search"></i>Cari Barang</a></li>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('peminjam.*') ? 'active' : '' }}" href="{{ route('peminjam.index') }}"><i class="fas fa-address-book"></i>Peminjam</a></li>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('peminjaman.user') ? 'active' : '' }}" href="{{ route('peminjaman.user') }}"><i class="fas fa-history"></i>Riwayat Sewa</a></li>
            @endif

            <div class="sidebar-divider"></div>
            <div class="sidebar-heading">Akun</div>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}" href="{{ route('settings.profile') }}"><i class="fas fa-user-cog"></i>Pengaturan</a></li>
            <li class="nav-item"><a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();"><i class="fas fa-sign-out-alt"></i>Keluar</a></li>
            <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
        </ul>
    </div>

    <!-- Sidebar Overlay (Mobile) -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
       

        <!-- Page Content -->
        @yield('content')
    </div>
<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebarToggle = document.querySelector('.sidebar-toggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const mainContent = document.getElementById('mainContent');

        function toggleSidebar() {
            sidebar.classList.toggle('show');
            sidebarOverlay.classList.toggle('show');
        }
        function closeSidebar() {
            sidebar.classList.remove('show');
            sidebarOverlay.classList.remove('show');
        }

        if (sidebarToggle) sidebarToggle.addEventListener('click', toggleSidebar);
        sidebarOverlay.addEventListener('click', closeSidebar);
        if (window.innerWidth < 768) mainContent.addEventListener('click', closeSidebar);
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 768) closeSidebar();
        });

        loadNotifications();
        setInterval(loadNotifications, 60000);
    });

    function loadNotifications() {
        fetch('{{ route("notifications.count") }}')
            .then(response => response.json())
            .then(data => {
                const badge = document.getElementById('notificationCount');
                if (data.count > 0) {
                    badge.textContent = data.count;
                    badge.style.display = 'flex';
                } else {
                    badge.style.display = 'none';
                }
            })
            .catch(error => console.log('Notification load error:', error));
    }
</script>

{{-- SweetAlert --}}
@include('sweetalert::alert')

    @stack('scripts')
</body>
</html>
