<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'User Panel - AsetPinjam')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --sidebar-width: 250px;
            --header-height: 60px;
            --primary-color: #1cc88a;
        }
        body { font-size: 0.875rem; background-color: #f8f9fc; }
        .main-header { background: var(--primary-color); color: white; height: var(--header-height); position: fixed; top: 0; left: 0; right: 0; z-index: 1030; }
        .main-header .navbar-brand { font-weight: bold; color: white !important; font-size: 1.1rem; }
        .sidebar { position: fixed; top: var(--header-height); left: 0; width: var(--sidebar-width); height: calc(100vh - var(--header-height)); background: white; box-shadow: 0 0 35px rgba(0,0,0,0.1); transform: translateX(-100%); transition: transform 0.3s ease; z-index: 1020; overflow-y: auto; }
        .sidebar.show { transform: translateX(0); }
        .sidebar-nav { padding: 1rem 0; }
        .sidebar-nav .nav-link { padding: 0.75rem 1.5rem; color: #5a5c69; display: flex; align-items: center; transition: all 0.3s ease; }
        .sidebar-nav .nav-link:hover { background-color: #f8f9fc; color: var(--primary-color); padding-left: 2rem; }
        .sidebar-nav .nav-link.active { background-color: var(--primary-color); color: white; border-left: 3px solid #fff; }
        .sidebar-nav .nav-link i { width: 20px; margin-right: 0.75rem; }
        .sidebar-divider { border-top: 1px solid #e3e6f0; margin: 1rem 0; }
        .sidebar-heading { font-size: 0.65rem; font-weight: 800; color: #b7b9cc; text-transform: uppercase; letter-spacing: 0.1rem; padding: 0.75rem 1.5rem 0.25rem; }
        .main-content { margin-left: 0; margin-top: var(--header-height); padding: 1.5rem; min-height: calc(100vh - var(--header-height)); transition: margin-left 0.3s ease; }
        @media (min-width: 768px) { .sidebar { transform: translateX(0); } .main-content { margin-left: var(--sidebar-width); } .sidebar-toggle { display: none; } }
    </style>
</head>
<body>
    <!-- Header -->
    <nav class="navbar navbar-expand main-header">
        <div class="container-fluid px-3">
            <button class="btn btn-link text-white me-3 sidebar-toggle d-md-none" type="button"><i class="fas fa-bars"></i></button>
            <a class="navbar-brand" href="{{ route('user.dashboard') }}"><i class="fas fa-user-circle me-2"></i>User Panel</a>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown user-dropdown">
                    <a class="nav-link text-white dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-2"></i>{{ auth()->user()->name }}
                    </a>
                    <div class="dropdown-menu dropdown-menu-end shadow">
                        <a class="dropdown-item" href="{{ route('settings.profile') }}"><i class="fas fa-user me-2"></i>Profil</a>
                        <a class="dropdown-item" href="{{ route('settings.account') }}"><i class="fas fa-cog me-2"></i>Pengaturan</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt me-2"></i>Keluar</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <ul class="nav flex-column sidebar-nav">
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}" href="{{ route('user.dashboard') }}"><i class="fas fa-tachometer-alt"></i>Dashboard</a></li>
            <div class="sidebar-divider"></div>
            <div class="sidebar-heading">Penyewaan</div>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('user.cari.barang') ? 'active' : '' }}" href="{{ route('user.cari.barang') }}"><i class="fas fa-search"></i>Cari Barang</a></li>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('user.peminjaman') ? 'active' : '' }}" href="{{ route('user.peminjaman') }}"><i class="fas fa-handshake"></i>Ajukan Peminjaman</a></li>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('user.riwayat.sewa') ? 'active' : '' }}" href="{{ route('user.riwayat.sewa') }}"><i class="fas fa-history"></i>Riwayat Sewa</a></li>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('user.peminjam') ? 'active' : '' }}" href="{{ route('user.peminjam') }}"><i class="fas fa-address-book"></i>Data Peminjam</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.querySelector('.sidebar-toggle');
            const sidebar = document.getElementById('sidebar');
            function toggleSidebar() { sidebar.classList.toggle('show'); }
            if (sidebarToggle) sidebarToggle.addEventListener('click', toggleSidebar);
        });
    </script>

    @stack('scripts')
</body>
</html>
