@extends('layouts.app')

@section('title', 'Dashboard User')

@section('content')
{{-- User Header --}}
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <div>
        <h1 class="h2"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</h1>
        <p class="text-muted mb-0">Selamat datang, {{ auth()->user()->name }}!</p>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('barang.index') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-search me-1"></i>Cari Barang
            </a>
            <a href="{{ route('peminjaman.user') }}" class="btn btn-sm btn-outline-info">
                <i class="fas fa-history me-1"></i>Riwayat Sewa
            </a>
        </div>
    </div>
</div>

{{-- User Stats Cards --}}
<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Peminjaman</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalPeminjaman }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-handshake fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Sedang Dipinjam</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $peminjamanAktif }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clock fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Selesai</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $peminjamanSelesai }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Terlambat</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $peminjamanTerlambat }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Main Content Row --}}
<div class="row">
    {{-- Rental Information --}}
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Informasi Penyewaan</h6>
                <a href="{{ route('barang.index') }}" class="btn btn-sm btn-outline-primary">Lihat Katalog</a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <i class="fas fa-boxes fa-3x text-primary mb-3"></i>
                                <h5 class="card-title">{{ $barangTersedia }}</h5>
                                <p class="card-text">Barang Tersedia</p>
                                <a href="{{ route('barang.index') }}" class="btn btn-primary btn-sm">Lihat Barang</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <i class="fas fa-tags fa-3x text-info mb-3"></i>
                                <h5 class="card-title">{{ $kategoriTersedia }}</h5>
                                <p class="card-text">Kategori Tersedia</p>
                                <a href="{{ route('barang.index') }}" class="btn btn-info btn-sm">Jelajahi Kategori</a>
                            </div>
                        </div>
                    </div>
                </div>

                @if($peminjamanTerlambat > 0)
                <div class="alert alert-danger mt-3" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Perhatian!</strong> Anda memiliki {{ $peminjamanTerlambat }} peminjaman yang terlambat. 
                    Silakan segera kembalikan untuk menghindari denda tambahan.
                    <a href="{{ route('peminjaman.user') }}?status=terlambat" class="alert-link">Lihat Detail</a>
                </div>
                @endif

                @if($peminjamanMendatang->count() > 0)
                <div class="alert alert-info mt-3" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Pengingat!</strong> Anda memiliki {{ $peminjamanMendatang->count() }} peminjaman yang akan jatuh tempo dalam 7 hari ke depan.
                </div>
                @endif
            </div>
        </div>

        {{-- Upcoming Returns --}}
        @if($peminjamanMendatang->count() > 0)
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-warning">
                    <i class="fas fa-calendar-alt me-2"></i>Jadwal Pengembalian
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Kode Peminjaman</th>
                                <th>Jumlah Barang</th>
                                <th>Tanggal Kembali</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($peminjamanMendatang as $peminjaman)
                            <tr>
                                <td>{{ $peminjaman->kode_peminjaman }}</td>
                                <td>{{ $peminjaman->detailPeminjaman->count() }} items</td>
                                <td>{{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali_rencana)->format('d M Y') }}</td>
                                <td>
                                    @php
                                        $days = \Carbon\Carbon::now()->diffInDays($peminjaman->tanggal_kembali_rencana, false);
                                    @endphp
                                    @if($days == 0)
                                        <span class="badge bg-warning">Hari ini</span>
                                    @elseif($days == 1)
                                        <span class="badge bg-info">Besok</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $days }} hari lagi</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('peminjaman.show', $peminjaman->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- Recent Activity Sidebar --}}
    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Aktivitas Terbaru</h6>
            </div>
            <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                @if($riwayatPeminjaman->count() > 0)
                    @foreach($riwayatPeminjaman as $riwayat)
                    <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                        <div class="mr-3">
                            @if($riwayat->status == 'dikembalikan')
                                <div class="icon-circle bg-success">
                                    <i class="fas fa-check text-white"></i>
                                </div>
                            @elseif($riwayat->status == 'dipinjam')
                                <div class="icon-circle bg-primary">
                                    <i class="fas fa-handshake text-white"></i>
                                </div>
                            @elseif($riwayat->status == 'terlambat')
                                <div class="icon-circle bg-danger">
                                    <i class="fas fa-exclamation-triangle text-white"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ $riwayat->kode_peminjaman }}</h6>
                            <p class="mb-1 small">{{ $riwayat->detailPeminjaman->count() }} barang</p>
                            <div class="small text-muted">{{ $riwayat->created_at->diffForHumans() }}</div>
                        </div>
                        <div class="text-right">
                            <span class="badge bg-{{ 
                                $riwayat->status == 'dikembalikan' ? 'success' : 
                                ($riwayat->status == 'dipinjam' ? 'primary' : 
                                ($riwayat->status == 'terlambat' ? 'danger' : 'secondary')) 
                            }}">
                                {{ ucfirst($riwayat->status) }}
                            </span>
                            <div class="mt-1">
                                <a href="{{ route('peminjaman.show', $riwayat->id) }}" class="btn btn-xs btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    
                    <div class="text-center mt-3">
                        <a href="{{ route('peminjaman.user') }}" class="btn btn-sm btn-outline-primary">
                            Lihat Semua Riwayat
                        </a>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-history fa-3x text-muted mb-3"></i>
                        <h6 class="text-muted">Belum Ada Aktivitas</h6>
                        <p class="text-muted small">Mulai menyewa barang untuk melihat riwayat di sini</p>
                        <a href="{{ route('barang.index') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-search me-1"></i>Cari Barang
                        </a>
                    </div>
                @endif
            </div>
        </div>

        {{-- Quick Actions Card --}}
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Aksi Cepat</h6>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <a href="{{ route('barang.index') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="fas fa-search text-primary me-3"></i>
                        <div>
                            <h6 class="mb-1">Cari Barang</h6>
                            <small class="text-muted">Temukan barang yang ingin Anda sewa</small>
                        </div>
                    </a>
                    
                    <a href="{{ route('peminjaman.user') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="fas fa-history text-info me-3"></i>
                        <div>
                            <h6 class="mb-1">Riwayat Peminjaman</h6>
                            <small class="text-muted">Lihat semua peminjaman Anda</small>
                        </div>
                    </a>
                    
                    @if($peminjamanAktif > 0)
                    <a href="{{ route('peminjaman.user') }}?status=dipinjam" class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="fas fa-clock text-warning me-3"></i>
                        <div>
                            <h6 class="mb-1">Peminjaman Aktif</h6>
                            <small class="text-muted">{{ $peminjamanAktif }} peminjaman sedang berlangsung</small>
                        </div>
                    </a>
                    @endif
                    
                    @if($peminjamanTerlambat > 0)
                    <a href="{{ route('peminjaman.user') }}?status=terlambat" class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle text-danger me-3"></i>
                        <div>
                            <h6 class="mb-1">Peminjaman Terlambat</h6>
                            <small class="text-muted">{{ $peminjamanTerlambat }} peminjaman terlambat</small>
                        </div>
                    </a>
                    @endif
                    
                    <a href="{{ route('settings.profile') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="fas fa-user text-secondary me-3"></i>
                        <div>
                            <h6 class="mb-1">Profil Saya</h6>
                            <small class="text-muted">Kelola informasi akun Anda</small>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Info Cards for New Users --}}
@if($totalPeminjaman == 0)
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4 border-left-primary">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h5 class="text-primary mb-2">
                            <i class="fas fa-star me-2"></i>Selamat Datang di Sistem Penyewaan!
                        </h5>
                        <p class="mb-3">
                            Mulai menyewa barang dengan mudah. Jelajahi katalog kami dan temukan barang yang Anda butuhkan.
                        </p>
                        <div class="mb-2">
                            <small class="text-muted">
                                <i class="fas fa-check text-success me-1"></i> Pencarian mudah berdasarkan kategori
                            </small>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">
                                <i class="fas fa-check text-success me-1"></i> Proses peminjaman yang simple
                            </small>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted">
                                <i class="fas fa-check text-success me-1"></i> Tracking status peminjaman real-time
                            </small>
                        </div>
                        <a href="{{ route('barang.index') }}" class="btn btn-primary">
                            <i class="fas fa-rocket me-1"></i>Mulai Menyewa Sekarang
                        </a>
                    </div>
                    <div class="col-md-4 text-center">
                        <i class="fas fa-handshake fa-5x text-primary opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@section('scripts')
<style>
.border-left-primary { border-left: 0.25rem solid #4e73df !important; }
.border-left-success { border-left: 0.25rem solid #1cc88a !important; }
.border-left-info { border-left: 0.25rem solid #36b9cc !important; }
.border-left-warning { border-left: 0.25rem solid #f6c23e !important; }
.border-left-danger { border-left: 0.25rem solid #e74a3b !important; }

.icon-circle { 
    width: 2.5rem; 
    height: 2.5rem; 
    border-radius: 100%; 
    display: flex; 
    align-items: center; 
    justify-content: center; 
}

.btn-xs { 
    padding: 0.25rem 0.5rem; 
    font-size: 0.75rem; 
    line-height: 1.5; 
    border-radius: 0.25rem; 
}

.opacity-50 { opacity: 0.5; }

.list-group-item-action:hover {
    background-color: #f8f9fc;
    transform: translateX(5px);
    transition: all 0.3s ease;
}
</style>

<script>
// Auto-refresh notifications untuk user
function checkNotifications() {
    // Optional: implementasi real-time notifications
    // fetch('/notifications/count')...
}

// Set interval untuk check notifications
setInterval(checkNotifications, 60000); // 1 menit

// Highlight terlambat dengan animasi
@if($peminjamanTerlambat > 0)
    setInterval(function() {
        $('.border-left-danger').fadeOut(500).fadeIn(500);
    }, 2000);
@endif
</script>
@endsection