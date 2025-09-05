@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
{{-- Admin Header --}}
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-tachometer-alt me-2"></i>Dashboard Admin</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-users me-1"></i>Kelola User
            </a>
            <a href="{{ route('laporan.index') }}" class="btn btn-sm btn-outline-success">
                <i class="fas fa-chart-bar me-1"></i>Laporan
            </a>
        </div>
    </div>
</div>

{{-- Admin Stats Cards --}}
<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Users</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalUsers }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
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
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Barang</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalBarang }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-boxes fa-2x text-gray-300"></i>
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

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pendapatan Bulan Ini</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($totalPendapatanBulanIni) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Financial Overview Row --}}
<div class="row">
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Pendapatan 6 Bulan Terakhir</h6>
                <a href="{{ route('laporan.keuangan') }}" class="btn btn-sm btn-outline-primary">Detail Laporan</a>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="pendapatanChart" style="height: 300px;"></canvas>
                </div>
                <hr>
                <div class="row text-center">
                    <div class="col-md-4">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Pendapatan</div>
                        <div class="h6 mb-0 font-weight-bold text-success">Rp {{ number_format($totalPendapatanBulanIni) }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total Pengeluaran</div>
                        <div class="h6 mb-0 font-weight-bold text-danger">Rp {{ number_format($totalPengeluaranBulanIni) }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Profit Bersih</div>
                        <div class="h6 mb-0 font-weight-bold text-info">Rp {{ number_format($totalPendapatanBulanIni - $totalPengeluaranBulanIni) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Transaksi Terakhir</h6>
            </div>
            <div class="card-body" style="max-height: 350px; overflow-y: auto;">
                @if($transaksiTerakhir->count() > 0)
                    @foreach($transaksiTerakhir as $transaksi)
                    <div class="d-flex align-items-center mb-3">
                        <div class="mr-3">
                            @if($transaksi->jenis_transaksi == 'masuk')
                                <div class="icon-circle bg-success">
                                    <i class="fas fa-arrow-up text-white"></i>
                                </div>
                            @else
                                <div class="icon-circle bg-danger">
                                    <i class="fas fa-arrow-down text-white"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ ucfirst($transaksi->kategori) }}</h6>
                            <small class="text-muted">{{ $transaksi->deskripsi }}</small>
                            <div class="small text-muted">{{ $transaksi->tanggal_transaksi->format('d M Y') }}</div>
                        </div>
                        <div class="text-right">
                            <span class="font-weight-bold {{ $transaksi->jenis_transaksi == 'masuk' ? 'text-success' : 'text-danger' }}">
                                {{ $transaksi->jenis_transaksi == 'masuk' ? '+' : '-' }}Rp {{ number_format($transaksi->jumlah) }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                    <div class="text-center">
                        <a href="{{ route('transaksi-keuangan.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                    </div>
                @else
                    <p class="text-muted mb-0">Belum ada transaksi</p>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Management Tables Row --}}
<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>Peminjaman Terlambat
                </h6>
            </div>
            <div class="card-body">
                @if($peminjamanTerlambat->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Peminjam</th>
                                    <th>Terlambat</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($peminjamanTerlambat->take(5) as $pinjam)
                                <tr>
                                    <td>{{ $pinjam->kode_peminjaman }}</td>
                                    <td>{{ $pinjam->peminjam->nama_peminjam }}</td>
                                    <td>
                                        <span class="badge bg-danger">
                                            {{ \Carbon\Carbon::now()->diffInDays($pinjam->tanggal_kembali_rencana) }} hari
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning">{{ ucfirst($pinjam->status) }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-2">
                        <a href="{{ route('peminjaman.index') }}?status=terlambat" class="btn btn-sm btn-outline-warning">Lihat Semua</a>
                    </div>
                @else
                    <p class="text-muted mb-0">Tidak ada peminjaman terlambat</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-info">
                    <i class="fas fa-boxes me-2"></i>Stok Menipis
                </h6>
            </div>
            <div class="card-body">
                @if($stokMenipis->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Barang</th>
                                    <th>Kategori</th>
                                    <th>Stok</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stokMenipis->take(5) as $barang)
                                <tr>
                                    <td>{{ $barang->nama_barang }}</td>
                                    <td>{{ $barang->kategori->nama_kategori ?? '-' }}</td>
                                    <td>
                                        <span class="badge {{ $barang->stok_tersedia <= 1 ? 'bg-danger' : 'bg-warning' }}">
                                            {{ $barang->stok_tersedia }}/{{ $barang->stok_total }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ ucfirst($barang->status) }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-2">
                        <a href="{{ route('barang.index') }}" class="btn btn-sm btn-outline-info">Lihat Semua</a>
                    </div>
                @else
                    <p class="text-muted mb-0">Semua barang stoknya aman</p>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Popular Items Row --}}
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Barang Paling Populer</h6>
            </div>
            <div class="card-body">
                @if($barangPopuler->count() > 0)
                    <div class="row">
                        @foreach($barangPopuler as $barang)
                        <div class="col-lg-4 col-md-6 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">{{ $barang->nama_barang }}</h6>
                                    <small class="text-muted">{{ $barang->total_dipinjam }} kali dipinjam</small>
                                </div>
                                <div class="progress ml-3" style="width: 80px; height: 8px;">
                                    <div class="progress-bar bg-primary" style="width: {{ ($barang->total_dipinjam / $barangPopuler->max('total_dipinjam')) * 100 }}%"></div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted mb-0">Belum ada data peminjaman</p>
                @endif
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
.icon-circle { width: 2.5rem; height: 2.5rem; border-radius: 100%; display: flex; align-items: center; justify-content: center; }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Chart Pendapatan Admin
const ctx = document.getElementById('pendapatanChart').getContext('2d');
const pendapatanData = @json($pendapatanBulanan);

new Chart(ctx, {
    type: 'line',
    data: {
        labels: pendapatanData.map(item => item.bulan),
        datasets: [{
            label: 'Pendapatan (Rp)',
            data: pendapatanData.map(item => item.pendapatan),
            borderColor: '#4e73df',
            backgroundColor: 'rgba(78, 115, 223, 0.1)',
            tension: 0.3,
            fill: true,
            pointBackgroundColor: '#4e73df',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointRadius: 4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { 
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                    }
                }
            }
        },
        scales: {
            x: { grid: { display: false } },
            y: { 
                beginAtZero: true,
                grid: { borderDash: [3,3] },
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