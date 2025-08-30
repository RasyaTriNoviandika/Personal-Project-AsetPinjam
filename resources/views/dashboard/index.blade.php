@extends('layouts.app')

@section('content')
<nav class="navbar navbar-expand-lg navbar-light bg-light d-lg-none">
    <div class="container">
        <a class="navbar-brand" href="#">Dashboard</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('barang.index') }}">Data Barang</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('peminjam.index') }}">Data Peminjam</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('peminjaman.index') }}">Peminjaman</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('laporan.index') }}">Laporan</a>
                </li>
                <li>
                    <li class="nav-item">
    <form action="{{ route('logout') }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="nav-link btn btn-link text-danger" style="text-decoration:none;">
            Logout
        </button>
    </form>
</li>

                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-tachometer-alt me-2"></i>Dashboard Sistem Peminjaman</h4>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Barang</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalBarang }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-boxes fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Peminjam</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalPeminjam }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-users fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Peminjaman Aktif</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalPeminjamanAktif }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-exchange-alt fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pendapatan Bulan Ini</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($totalPendapatanBulanIni, 0, ',', '.') }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="alert alert-info" role="alert">
                                <h5><i class="fas fa-info-circle me-2"></i>Selamat Datang di Sistem Peminjaman!</h5>
                                <p class="mb-0">Sistem ini membantu Anda mengelola peminjaman barang dengan fitur:</p>
                                <ul class="mt-2 mb-0">
                                    <li>Manajemen Data Barang & Kategori</li>
                                    <li>Manajemen Data Peminjam</li>
                                    <li>Pencatatan Peminjaman & Pengembalian</li>
                                    <li>Sistem Keuangan & Laporan</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6><i class="fas fa-plus me-2"></i>Quick Actions</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('barang.create') }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-plus me-1"></i>Tambah Barang
                                        </a>
                                        <a href="{{ route('peminjam.create') }}" class="btn btn-success btn-sm">
                                            <i class="fas fa-user-plus me-1"></i>Tambah Peminjam
                                        </a>
                                        <a href="{{ route('peminjaman.create') }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-exchange-alt me-1"></i>Buat Peminjaman
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h6><i class="fas fa-chart-line me-2"></i>Pendapatan 6 Bulan Terakhir</h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="pendapatanChart" style="height: 200px;"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<style>
.border-left-primary { border-left: 0.25rem solid #4e73df !important; }
.border-left-success { border-left: 0.25rem solid #1cc88a !important; }
.border-left-info { border-left: 0.25rem solid #36b9cc !important; }
.border-left-warning { border-left: 0.25rem solid #f6c23e !important; }
</style>

<script>
// Chart Pendapatan
const ctx = document.getElementById('pendapatanChart').getContext('2d');
const pendapatanData = @json($pendapatanBulanan);

new Chart(ctx, {
    type: 'line',
    data: {
        labels: pendapatanData.map(item => item.bulan),
        datasets: [{
            label: 'Pendapatan (Rp)',
            data: pendapatanData.map(item => item.pendapatan),
            borderColor: '#667eea',
            backgroundColor: 'rgba(102, 126, 234, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'Rp ' + value.toLocaleString('id-ID');
                    }
                }
            }
        }
    }
});
</script>
@endsection