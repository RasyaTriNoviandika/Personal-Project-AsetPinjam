@extends('layouts.app')

@section('title', 'Dashboard User')

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
    <div>
        <h1 class="h2 text-gray-800">
            <i class="fas fa-tachometer-alt text-primary me-2"></i>Dashboard
        </h1>
        <p class="text-muted mb-0">Selamat datang, {{ auth()->user()->name }}!</p>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('barang.index') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-search me-1"></i>Cari Barang
            </a>
            <a href="{{ route('peminjaman.user') }}" class="btn btn-outline-info btn-sm">
                <i class="fas fa-history me-1"></i>Riwayat Sewa
            </a>
        </div>
    </div>
</div>

<!-- Alert Messages -->
@if($peminjamanTerlambat > 0)
<div class="alert alert-danger border-left-danger shadow-sm mb-4" role="alert">
    <div class="d-flex align-items-center">
        <div class="me-3">
            <div class="icon-circle bg-danger">
                <i class="fas fa-exclamation-triangle text-white"></i>
            </div>
        </div>
        <div class="flex-grow-1">
            <h6 class="alert-heading mb-2"><strong>Perhatian!</strong></h6>
            <p class="mb-2">Anda memiliki <strong>{{ $peminjamanTerlambat }}</strong> peminjaman yang terlambat.</p>
            <p class="mb-0">Silakan segera kembalikan untuk menghindari denda tambahan.</p>
        </div>
        <div>
            <a href="{{ route('peminjaman.user') }}?status=terlambat" class="btn btn-outline-danger btn-sm">
                <i class="fas fa-eye me-1"></i>Lihat Detail
            </a>
        </div>
    </div>
</div>
@endif

@if($peminjamanMendatang->count() > 0)
<div class="alert alert-info border-left-info shadow-sm mb-4" role="alert">
    <div class="d-flex align-items-center">
        <div class="me-3">
            <div class="icon-circle bg-info">
                <i class="fas fa-info-circle text-white"></i>
            </div>
        </div>
        <div class="flex-grow-1">
            <h6 class="alert-heading mb-2"><strong>Pengingat!</strong></h6>
            <p class="mb-0">Anda memiliki <strong>{{ $peminjamanMendatang->count() }}</strong> peminjaman yang akan jatuh tempo dalam 7 hari ke depan.</p>
        </div>
        <div>
            <a href="{{ route('peminjaman.user') }}" class="btn btn-outline-info btn-sm">
                Lihat Jadwal
            </a>
        </div>
    </div>
</div>
@endif

<!-- Stats Cards Row -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm border-left-primary h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col me-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Peminjaman
                        </div>
                        <div class="h4 mb-0 font-weight-bold text-gray-800">
                            {{ number_format($totalPeminjaman) }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="icon-circle bg-primary">
                            <i class="fas fa-handshake text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm border-left-warning h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col me-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Sedang Dipinjam
                        </div>
                        <div class="h4 mb-0 font-weight-bold text-gray-800">
                            {{ number_format($peminjamanAktif) }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="icon-circle bg-warning">
                            <i class="fas fa-clock text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm border-left-success h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col me-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Selesai
                        </div>
                        <div class="h4 mb-0 font-weight-bold text-gray-800">
                            {{ number_format($peminjamanSelesai) }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="icon-circle bg-success">
                            <i class="fas fa-check-circle text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm border-left-danger h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col me-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Terlambat
                        </div>
                        <div class="h4 mb-0 font-weight-bold text-gray-800">
                            {{ number_format($peminjamanTerlambat) }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="icon-circle bg-danger">
                            <i class="fas fa-exclamation-triangle text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content Row -->
<div class="row g-4">
    <!-- Rental Information -->
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-info-circle me-2"></i>Informasi Penyewaan
                </h6>
                <a href="{{ route('barang.index') }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-external-link-alt me-1"></i>Lihat Katalog
                </a>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card bg-gradient-primary text-white h-100">
                            <div class="card-body text-center d-flex flex-column justify-content-center">
                                <i class="fas fa-boxes fa-3x mb-3"></i>
                                <h3 class="card-title mb-2">{{ number_format($barangTersedia) }}</h3>
                                <p class="card-text mb-3">Barang Tersedia</p>
                                <a href="{{ route('barang.index') }}" class="btn btn-light btn-sm mt-auto">
                                    <i class="fas fa-eye me-1"></i>Lihat Barang
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-gradient-info text-white h-100">
                            <div class="card-body text-center d-flex flex-column justify-content-center">
                                <i class="fas fa-tags fa-3x mb-3"></i>
                                <h3 class="card-title mb-2">{{ number_format($kategoriTersedia) }}</h3>
                                <p class="card-text mb-3">Kategori Tersedia</p>
                                <a href="{{ route('barang.index') }}" class="btn btn-light btn-sm mt-auto">
                                    <i class="fas fa-search me-1"></i>Jelajahi Kategori
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming Returns -->
        @if($peminjamanMendatang->count() > 0)
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="m-0 font-weight-bold text-warning">
                    <i class="fas fa-calendar-alt me-2"></i>Jadwal Pengembalian
                    <span class="badge bg-warning ms-2">{{ $peminjamanMendatang->count() }}</span>
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0">Kode</th>
                                <th class="border-0">Jumlah Item</th>
                                <th class="border-0">Tanggal Kembali</th>
                                <th class="border-0">Status</th>
                                <th class="border-0">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($peminjamanMendatang as $peminjaman)
                            <tr>
                                <td class="align-middle">
                                    <small class="font-monospace">{{ $peminjaman->kode_peminjaman }}</small>
                                </td>
                                <td class="align-middle">{{ $peminjaman->detailPeminjaman->sum('jumlah') }} items</td>
                                <td class="align-middle">{{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali_rencana)->format('d M Y') }}</td>
                                <td class="align-middle">
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
                                <td class="align-middle">
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

    <!-- Recent Activity Sidebar -->
    <div class="col-xl-4 col-lg-5">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-history me-2"></i>Aktivitas Terbaru
                </h6>
                <a href="{{ route('peminjaman.user') }}" class="btn btn-sm btn-outline-primary">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body p-0" style="max-height: 400px; overflow-y: auto;">
                @if($riwayatPeminjaman->count() > 0)
                    @foreach($riwayatPeminjaman as $riwayat)
                    <div class="d-flex align-items-center p-3 border-bottom">
                        <div class="me-3">
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
                            @else
                                <div class="icon-circle bg-secondary">
                                    <i class="fas fa-clock text-white"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-grow-1 min-width-0">
                            <h6 class="mb-1 text-truncate">{{ $riwayat->kode_peminjaman }}</h6>
                            <p class="mb-1 small text-muted text-truncate">{{ $riwayat->detailPeminjaman->sum('jumlah') }} barang</p>
                            <div class="small text-muted">
                                {{ $riwayat->created_at->diffForHumans() }}
                            </div>
                        </div>
                        <div class="text-end">
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
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-history fa-3x text-muted mb-3"></i>
                        <p class="text-muted mb-0">Belum ada aktivitas</p>
                        <a href="{{ route('barang.index') }}" class="btn btn-primary btn-sm mt-2">
                            <i class="fas fa-search me-1"></i>Mulai Menyewa
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Quick Actions Card -->
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-bolt me-2"></i>Aksi Cepat
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <a href="{{ route('barang.index') }}" class="list-group-item list-group-item-action border-0 d-flex align-items-center">
                        <div class="me-3">
                            <div class="icon-circle bg-primary">
                                <i class="fas fa-search text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">Cari Barang</h6>
                            <small class="text-muted">Temukan barang yang ingin Anda sewa</small>
                        </div>
                        <i class="fas fa-chevron-right text-muted"></i>
                    </a>
                    
                    <a href="{{ route('peminjaman.user') }}" class="list-group-item list-group-item-action border-0 d-flex align-items-center">
                        <div class="me-3">
                            <div class="icon-circle bg-info">
                                <i class="fas fa-history text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">Riwayat Peminjaman</h6>
                            <small class="text-muted">Lihat semua peminjaman Anda</small>
                        </div>
                        <i class="fas fa-chevron-right text-muted"></i>
                    </a>
                    
                    @if($peminjamanAktif > 0)
                    <a href="{{ route('peminjaman.user') }}?status=dipinjam" class="list-group-item list-group-item-action border-0 d-flex align-items-center">
                        <div class="me-3">
                            <div class="icon-circle bg-warning">
                                <i class="fas fa-clock text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">Peminjaman Aktif</h6>
                            <small class="text-muted">{{ $peminjamanAktif }} peminjaman sedang berlangsung</small>
                        </div>
                        <span class="badge bg-warning">{{ $peminjamanAktif }}</span>
                    </a>
                    @endif
                    
                    @if($peminjamanTerlambat > 0)
                    <a href="{{ route('peminjaman.user') }}?status=terlambat" class="list-group-item list-group-item-action border-0 d-flex align-items-center">
                        <div class="me-3">
                            <div class="icon-circle bg-danger">
                                <i class="fas fa-exclamation-triangle text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">Peminjaman Terlambat</h6>
                            <small class="text-muted">Segera kembalikan untuk menghindari denda</small>
                        </div>
                        <span class="badge bg-danger">{{ $peminjamanTerlambat }}</span>
                    </a>
                    @endif
                    
                    <a href="{{ route('settings.profile') }}" class="list-group-item list-group-item-action border-0 d-flex align-items-center">
                        <div class="me-3">
                            <div class="icon-circle bg-secondary">
                                <i class="fas fa-user text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">Profil Saya</h6>
                            <small class="text-muted">Kelola informasi akun Anda</small>
                        </div>
                        <i class="fas fa-chevron-right text-muted"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Welcome Card for New Users -->
@if($totalPeminjaman == 0)
<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow-sm border-0 border-left-primary">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex align-items-start">
                            <div class="me-3">
                                <div class="icon-circle bg-primary">
                                    <i class="fas fa-star text-white"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="text-primary mb-2">
                                    Selamat Datang di Sistem Penyewaan!
                                </h5>
                                <p class="mb-3 text-muted">
                                    Mulai menyewa barang dengan mudah. Jelajahi katalog kami dan temukan barang yang Anda butuhkan.
                                </p>
                                <div class="mb-2">
                                    <small class="text-muted">
                                        <i class="fas fa-check text-success me-2"></i>Pencarian mudah berdasarkan kategori
                                    </small>
                                </div>
                                <div class="mb-2">
                                    <small class="text-muted">
                                        <i class="fas fa-check text-success me-2"></i>Proses peminjaman yang simple
                                    </small>
                                </div>
                                <div class="mb-3">
                                    <small class="text-muted">
                                        <i class="fas fa-check text-success me-2"></i>Tracking status peminjaman real-time
                                    </small>
                                </div>
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="{{ route('barang.index') }}" class="btn btn-primary">
                                        <i class="fas fa-rocket me-1"></i>Mulai Menyewa Sekarang
                                    </a>
                                    <a href="{{ route('barang.index') }}" class="btn btn-outline-primary">
                                        <i class="fas fa-info-circle me-1"></i>Pelajari Lebih Lanjut
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-center d-none d-md-block">
                        <i class="fas fa-handshake fa-5x text-primary opacity-25"></i>
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

.opacity-25 { opacity: 0.25; }

.list-group-item-action:hover {
    background-color: #f8f9fc;
    transform: translateX(2px);
    transition: all 0.2s ease;
}

.bg-gradient-primary {
    background: linear-gradient(45deg, #4e73df, #6f7cff);
}

.bg-gradient-info {
    background: linear-gradient(45deg, #36b9cc, #5ce0f5);
}

.card:hover {
    transform: translateY(-2px);
    transition: all 0.3s ease;
}

.text-gray-800 { color: #5a5c69 !important; }
.font-weight-bold { font-weight: 700 !important; }
.text-xs { font-size: 0.7rem; }
</style>

<script>
// Highlight terlambat dengan animasi
@if($peminjamanTerlambat > 0)
    setInterval(function() {
        $('.border-left-danger').fadeOut(1000).fadeIn(1000);
    }, 3000);
@endif

// Add loading states untuk quick actions
document.querySelectorAll('.list-group-item-action').forEach(function(item) {
    item.addEventListener('click', function() {
        const spinner = '<i class="fas fa-spinner fa-spin me-2"></i>';
        const icon = this.querySelector('.fas');
        if(icon && !icon.classList.contains('fa-spinner')) {
            icon.className = 'fas fa-spinner fa-spin';
        }
    });
});
</script>
@endsection