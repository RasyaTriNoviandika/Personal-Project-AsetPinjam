@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="refreshData()">
                <i class="fas fa-sync-alt me-1"></i>Refresh
            </button>
        </div>
    </div>
</div>

{{-- Stats Cards --}}
<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
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

    <div class="col-xl-3 col-md-6 mb-4">
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

{{-- Charts Row --}}
<div class="row">
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Pendapatan 6 Bulan Terakhir</h6>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="pendapatanChart" style="height: 320px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Barang Populer</h6>
            </div>
            <div class="card-body">
                @if($barangPopuler->count() > 0)
                    @foreach($barangPopuler as $barang)
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-grow-1">
                            <h6 class="mb-0">{{ $barang->nama_barang }}</h6>
                            <small class="text-muted">{{ $barang->total_dipinjam }} kali dipinjam</small>
                        </div>
                        <div class="progress" style="width: 80px; height: 8px;">
                            <div class="progress-bar bg-primary" style="width: {{ ($barang->total_dipinjam / $barangPopuler->max('total_dipinjam')) * 100 }}%"></div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <p class="text-muted mb-0">Belum ada data peminjaman</p>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Tables Row --}}
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
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($peminjamanTerlambat as $pinjam)
                                <tr>
                                    <td>{{ $pinjam->kode_peminjaman }}</td>
                                    <td>{{ $pinjam->peminjam->nama_peminjam }}</td>
                                    <td>
                                        <span class="badge bg-danger">
                                            {{ \Carbon\Carbon::now()->diffInDays($pinjam->tanggal_kembali_rencana) }} hari
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('peminjaman.pengembalian', $pinjam->id) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-undo"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
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
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stokMenipis as $barang)
                                <tr>
                                    <td>{{ $barang->nama_barang }}</td>
                                    <td>{{ $barang->kategori->nama_kategori ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-warning">
                                            {{ $barang->stok_tersedia }}/{{ $barang->stok_total }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('barang.edit', $barang->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted mb-0">Semua barang stoknya aman</p>
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
            borderColor: '#4e73df',
            backgroundColor: 'rgba(78, 115, 223, 0.1)',
            tension: 0.3,
            fill: true,
            pointBackgroundColor: '#4e73df',
            pointBorderColor: '#4e73df',
            pointHoverBackgroundColor: '#4e73df',
            pointHoverBorderColor: '#4e73df'
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
            x: {
                grid: {
                    display: false
                }
            },
            y: {
                beginAtZero: true,
                grid: {
                    borderDash: [3, 3]
                },
                ticks: {
                    callback: function(value) {
                        return 'Rp ' + value.toLocaleString('id-ID');
                    }
                }
            }
        }
    }
});

function refreshData() {
    location.reload();
}
</script>
@endsection
