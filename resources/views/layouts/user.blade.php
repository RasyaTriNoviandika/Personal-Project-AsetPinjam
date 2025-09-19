{{-- resources/views/layouts/user.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | AsetPinjam User</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Nunito', sans-serif; background-color: #f4f6f9; }
        .sidebar { background: #4e73df; min-height: 100vh; color: white; }
        .sidebar a { color: white; text-decoration: none; display: block; padding: 10px 15px; border-radius: 5px; }
        .sidebar a:hover, .sidebar a.active { background: #2e59d9; }
        .navbar { background: white; border-bottom: 1px solid #e3e6f0; }
    </style>
    @yield('head')
</head>6
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar p-3">
            <h4 class="mb-4"><i class="fas fa-handshake me-2"></i>AsetPinjam</h4>
            <a href="{{ route('user.dashboard') }}" class="{{ request()->is('user/dashboard') ? 'active' : '' }}">
                <i class="fas fa-home me-2"></i> Dashboard
            </a>
            <a href="{{ route('barang.index') }}" class="{{ request()->is('user/barang*') ? 'active' : '' }}">
                <i class="fas fa-search me-2"></i> Cari Barang
            </a>
            <a href="{{ route('peminjaman.user') }}" class="{{ request()->is('user/peminjaman*') ? 'active' : '' }}">
                <i class="fas fa-history me-2"></i> Riwayat Sewa
            </a>
            <hr>
            <a href="{{ route('settings.profile') }}">
                <i class="fas fa-user me-2"></i> Profil
            </a>
            <a href="{{ route('logout') }}" 
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt me-2"></i> Keluar
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>

        <!-- Content -->
        <div class="flex-grow-1">
            <!-- Top Navbar -->
            <nav class="navbar navbar-expand navbar-light px-3">
                <div class="ms-auto d-flex align-items-center">
                    <span class="me-3">{{ auth()->user()->name }}</span>
                    <i class="fas fa-bell text-muted"></i>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="p-4">
                @yield('content')
            </main>
        </div>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    @yield('scripts')
</body>
</html>
