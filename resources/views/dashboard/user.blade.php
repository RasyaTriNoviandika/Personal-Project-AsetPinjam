@extends('layouts.user')

@section('title', 'Dashboard - User Panel')

@section('content')
<div class="container-fluid">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient-primary text-white shadow">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-2">Selamat Datang, {{ auth()->user()->name }}!</h2>
                            <p class="mb-0 opacity-75">Kelola peminjaman barang Anda dengan mudah</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <i class="fas fa-user-circle fa-5x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Peminjaman
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['total_peminjaman'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Sedang Dipinjam
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['sedang_dipinjam'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hourglass-half fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Menunggu Persetujuan
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['menunggu_persetujuan'] }}
                            </div>
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
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Dikembalikan
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['sudah_dikembalikan'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert for Overdue Items -->
    @if($overduePeminjaman->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-danger shadow">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                    <div>
                        <h5 class="alert-heading mb-1">Perhatian! Ada {{ $overduePeminjaman->count() }} item terlambat</h5>
                        <p class="mb-2">Segera kembalikan item yang sudah melewati batas waktu untuk menghindari denda.</p>
                        <a href="{{ route('user.peminjaman.index') }}?status=dipinjam" class="btn btn-outline-danger btn-sm">
                            <i class="fas fa-eye me-1"></i>Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Main Content Row -->
    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-8">
            <!-- Quick Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bolt me-2"></i>Aksi Cepat
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('user.peminjaman.create') }}" class="btn btn-success btn-block h-100 d-flex flex-column justify-content-center align-items-center text-decoration-none">
                                <i class="fas fa-plus-circle fa-2x mb-2"></i>
                                <span>Ajukan Peminjaman</span>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('user.cari.barang') }}" class="btn btn-info btn-block h-100 d-flex flex-column justify-content-center align-items-center text-decoration-none">
                                <i class="fas fa-search fa-2x mb-2"></i>
                                <span>Cari Barang</span>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('user.peminjaman.index') }}" class="btn btn-warning btn-block h-100 d-flex flex-column justify-content-center align-items-center text-decoration-none">
                                <i class="fas fa-history fa-2x mb-2"></i>
                                <span>Riwayat Sewa</span>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('user.peminjam.index') }}" class="btn btn-primary btn-block h-100 d-flex flex-column justify-content-center align-items-center text-decoration-none">
                                <i class="fas fa-address-book fa-2x mb-2"></i>
                                <span>Data Peminjam</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Borrowings -->
            @if($activePeminjaman->count() > 0)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-hourglass-half me-2"></i>Sedang Dipinjam ({{ $activePeminjaman->count() }})
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($activePeminjaman as $peminjaman)
                        <div class="col-md-6 mb-3">
                            <div class="card border-warning">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            @if($peminjaman->detailPeminjaman->first()->barang->gambar)
                                                <img src="{{ asset('storage/' . $peminjaman->detailPeminjaman->first()->barang->gambar) }}" 
                                                     class="rounded" width="60" height="60" style="object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                     style="width: 60px; height: 60px;">
                                                    <i class="fas fa-box fa-2x text-muted"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ $peminjaman->detailPeminjaman->first()->barang->nama_barang }}</h6>
                                            <small class="text-muted">Peminjam: {{ $peminjaman->peminjam->nama }}</small>
                                            <br><small class="text-muted">Kembali: {{ $peminjaman->tanggal_kembali_rencana->format('d M Y') }}</small>
                                            @if($peminjaman->tanggal_kembali_rencana->isPast())
                                                <br><span class="badge bg-danger">Terlambat</span>
                                            @endif
                                        </div>
                                        <div>
                                            <a href="{{ route('user.peminjaman.show', $peminjaman->id) }}" class="btn btn-sm btn-outline-primary">
                                                Detail
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="text-center">
                        <a href="{{ route('user.peminjaman.index') }}?status=dipinjam" class="btn btn-warning">
                            Lihat Semua
                        </a>
                    </div>
                </div>
            </div>
            @endif

            <!-- Recent Activity -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-clock me-2"></i>Aktivitas Terbaru
                    </h6>
                </div>
                <div class="card-body">
                    @if($recentPeminjaman->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tbody>
                                    @foreach($recentPeminjaman as $peminjaman)
                                    <tr>
                                        <td width="60">
                                            @if($peminjaman->detailPeminjaman->first()->barang->gambar)
                                                <img src="{{ asset('storage/' . $peminjaman->detailPeminjaman->first()->barang->gambar) }}" 
                                                     class="rounded" width="40" height="40" style="object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                     style="width: 40px; height: 40px;">
                                                    <i class="fas fa-box text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $peminjaman->detailPeminjaman->first()->barang->nama_barang }}</strong>
                                            <br><small class="text-muted">{{ $peminjaman->created_at->diffForHumans() }}</small>
                                        </td>
                                        <td width="120">
                                            @if($peminjaman->status == 'pending')
                                                <span class="badge bg-info">Menunggu</span>
                                            @elseif($peminjaman->status == 'dipinjam')
                                                <span class="badge bg-warning">Dipinjam</span>
                                            @elseif($peminjaman->status == 'dikembalikan')
                                                <span class="badge bg-success">Selesai</span>
                                            @endif
                                        </td>
                                        <td width="80">
                                            <a href="{{ route('user.peminjaman.show', $peminjaman->id) }}" class="btn btn-sm btn-outline-primary">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center">
                            <a href="{{ route('user.peminjaman.index') }}" class="btn btn-primary">
                                Lihat Semua Riwayat
                            </a>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-3x mb-3"></i>
                            <h5>Belum ada aktivitas</h5>
                            <p>Mulai dengan mengajukan peminjaman pertama Anda</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
            <!-- Chart -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-area me-2"></i>Grafik Peminjaman (6 Bulan)
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="borrowingChart"></canvas>
                </div>
            </div>

            <!-- Available Items -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-box me-2"></i>Barang Tersedia
                    </h6>
                </div>
                <div class="card-body">
                    @if($availableBarang->count() > 0)
                        @foreach($availableBarang->take(5) as $barang)
                        <div class="d-flex align-items-center mb-3">
                            <div class="me-3">
                                @if($barang->gambar)
                                    <img src="{{ asset('storage/' . $barang->gambar) }}" 
                                         class="rounded" width="40" height="40" style="object-fit: cover;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                         style="width: 40px; height: 40px;">
                                        <i class="fas fa-box text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <div class="font-weight-bold">{{ $barang->nama_barang }}</div>
                                <small class="text-muted">{{ $barang->kategori->nama ?? '-' }}</small>
                            </div>
                            <div>
                                <span class="badge bg-success">{{ $barang->stok_tersedia }}</span>
                            </div>
                        </div>
                        @endforeach
                        <div class="text-center">
                            <a href="{{ route('user.cari.barang') }}" class="btn btn-success btn-sm">
                                Lihat Semua
                            </a>
                        </div>
                    @else
                        <div class="text-center text-muted py-3">
                            <i class="fas fa-box-open fa-2x mb-2"></i>
                            <p>Tidak ada barang tersedia</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- User Info -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user me-2"></i>Info Pengguna
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <i class="fas fa-user-circle fa-5x text-muted mb-3"></i>
                        <h5>{{ auth()->user()->name }}</h5>
                        <p class="text-muted">{{ auth()->user()->email }}</p>
                        <small class="text-muted">Data Peminjam: {{ $myPeminjam }}</small>
                        <br><small class="text-muted">Bergabung: {{ auth()->user()->created_at->format('d M Y') }}</small>
                    </div>
                    <div class="d-grid mt-3">
                        <a href="{{ route('settings.profile') }}" class="btn btn-outline-primary">
                            <i class="fas fa-edit me-2"></i>Edit Profil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.btn-block {
    width: 100%;
    min-height: 100px;
}

.bg-gradient-primary {
    background: linear-gradient(45deg, #4e73df, #224abe);
}

.text-xs {
    font-size: 0.7rem;
}

.font-weight-bold {
    font-weight: 700;
}

.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}
</style>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Borrowing Chart
    const ctx = document.getElementById('borrowingChart').getContext('2d');
    const monthlyData = @json($monthlyData);
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: monthlyData.map(item => item.month),
            datasets: [{
                label: 'Peminjaman',
                data: monthlyData.map(item => item.count),
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78, 115, 223, 0.1)',
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });
});
</script>
@endpush