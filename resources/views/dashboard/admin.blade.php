@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
    <div>
        <h1 class="h2 text-gray-800">
            <i class="fas fa-tachometer-alt text-primary me-2"></i>Dashboard Admin
        </h1>
        <p class="text-muted mb-0">Selamat datang kembali, {{ auth()->user()->name }}!</p>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('users.index') }}" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-users me-1"></i>Kelola User
            </a>
            <a href="{{ route('laporan.index') }}" class="btn btn-outline-success btn-sm">
                <i class="fas fa-chart-bar me-1"></i>Laporan
            </a>
        </div>
    </div>
</div>


        <div class="mb-3 no-print">
        <a href="#" onclick="window.print()" class="btn btn-primary">
        🖨️ Print Laporan
    </a>

<!-- Stats Cards Row -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm border-left-primary h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col me-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Users
                        </div>
                        <div class="h4 mb-0 font-weight-bold text-gray-800">
                            {{ number_format($totalUsers) }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="icon-circle bg-primary">
                            <i class="fas fa-users text-white"></i>
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
                            Total Barang
                        </div>
                        <div class="h4 mb-0 font-weight-bold text-gray-800">
                            {{ number_format($totalBarang) }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="icon-circle bg-success">
                            <i class="fas fa-boxes text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm border-left-info h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col me-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Peminjaman Aktif
                        </div>
                        <div class="h4 mb-0 font-weight-bold text-gray-800">
                            {{ number_format($totalPeminjamanAktif) }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="icon-circle bg-info">
                            <i class="fas fa-exchange-alt text-white"></i>
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
                            Pendapatan Bulan Ini
                        </div>
                        <div class="h4 mb-0 font-weight-bold text-gray-800">
                            Rp {{ number_format($totalPendapatanBulanIni, 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="icon-circle bg-warning">
                            <i class="fas fa-money-bill-wave text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content Row -->
<div class="row g-4">
    <!-- Chart Section -->
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-chart-line me-2"></i>Pendapatan 6 Bulan Terakhir
                </h6>
           <div class="dropdown">
    <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
        <i class="fas fa-download me-1"></i>Export
    </button>
    <div class="dropdown-menu">
        <a class="dropdown-item" href="{{ route('laporan.export.keuangan.excel') }}">
            <i class="fas fa-file-excel me-2"></i>Excel
        </a>
        <a class="dropdown-item" href="{{ route('laporan.export.keuangan.pdf') }}">
            <i class="fas fa-file-pdf me-2"></i>Detail Report
        </a>
    </div>
</div>
            </div>
            <div class="card-body">
                <div class="chart-container position-relative" style="height: 300px;">
                    <canvas id="pendapatanChart"></canvas>
                </div>
                <hr class="my-4">
                <div class="row text-center">
                    <div class="col-md-4">
                        <div class="border-end">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Pendapatan
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-success">
                                Rp {{ number_format($totalPendapatanBulanIni, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border-end">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Total Pengeluaran
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-danger">
                                Rp {{ number_format($totalPengeluaranBulanIni, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Profit Bersih
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-primary">
                            Rp {{ number_format($totalPendapatanBulanIni - $totalPengeluaranBulanIni, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="col-xl-4 col-lg-5">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-clock me-2"></i>Transaksi Terakhir
                </h6>
                <a href="{{ route('transaksi-keuangan.index') }}" class="btn btn-sm btn-outline-primary">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body p-0" style="max-height: 350px; overflow-y: auto;">
                @if($transaksiTerakhir->count() > 0)
                    @foreach($transaksiTerakhir as $transaksi)
                    <div class="d-flex align-items-center p-3 border-bottom">
                        <div class="me-3">
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
                        <div class="flex-grow-1 min-width-0">
                            <h6 class="mb-1 text-truncate">{{ ucfirst($transaksi->kategori_transaksi ?? '-') }}</h6>
<p class="mb-1 small text-muted text-truncate">{{ $transaksi->keterangan ?? '-' }}</p>
<div class="small text-muted">
    {{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d M Y') }}
</div>

                        </div>
                        <div class="text-end">
                            <span class="font-weight-bold {{ $transaksi->jenis_transaksi == 'masuk' ? 'text-success' : 'text-danger' }}">
                                {{ $transaksi->jenis_transaksi == 'masuk' ? '+' : '-' }}Rp {{ number_format($transaksi->jumlah, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                        <p class="text-muted mb-0">Belum ada transaksi</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Management Tables Row -->
<div class="row g-4 mt-4">
    <!-- Late Returns -->
    <div class="col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="m-0 font-weight-bold text-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Peminjaman Terlambat 
                    @if($peminjamanTerlambat->count() > 0)
                        <span class="badge bg-warning ms-2">{{ $peminjamanTerlambat->count() }}</span>
                    @endif
                </h6>
            </div>
            <div class="card-body p-0">
                @if($peminjamanTerlambat->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0">Kode</th>
                                    <th class="border-0">Peminjam</th>
                                    <th class="border-0">Terlambat</th>
                                    <th class="border-0">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($peminjamanTerlambat->take(5) as $pinjam)
                                <tr>
                                    <td class="align-middle">
                                        <small class="font-monospace">{{ $pinjam->kode_peminjaman }}</small>
                                    </td>
                                    <td class="align-middle">{{ $pinjam->peminjam->nama_peminjam }}</td>
                                    <td class="align-middle">
                                        <span class="badge bg-danger">
                                            {{ \Carbon\Carbon::now()->diffInDays($pinjam->tanggal_kembali_rencana) }} hari
                                        </span>
                                    </td>
                                    <td class="align-middle">
                                        <a href="{{ route('peminjaman.show', $pinjam->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3 bg-light text-center">
                        <a href="{{ route('peminjaman.index') }}?status=terlambat" class="btn btn-warning btn-sm">
                            <i class="fas fa-list me-1"></i>Lihat Semua Terlambat
                        </a>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <p class="text-muted mb-0">Tidak ada peminjaman terlambat</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Low Stock Items -->
    <div class="col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="m-0 font-weight-bold text-info">
                    <i class="fas fa-boxes me-2"></i>
                    Stok Menipis
                    @if($stokMenipis->count() > 0)
                        <span class="badge bg-info ms-2">{{ $stokMenipis->count() }}</span>
                    @endif
                </h6>
            </div>
            <div class="card-body p-0">
                @if($stokMenipis->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0">Barang</th>
                                    <th class="border-0">Kategori</th>
                                    <th class="border-0">Stok</th>
                                    <th class="border-0">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stokMenipis->take(5) as $barang)
                                <tr>
                                    <td class="align-middle">{{ $barang->nama_barang }}</td>
                                    <td class="align-middle">
                                        <span class="badge bg-secondary">{{ $barang->kategori->nama_kategori ?? '-' }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <span class="badge {{ $barang->stok_tersedia <= 1 ? 'bg-danger' : 'bg-warning' }}">
                                            {{ $barang->stok_tersedia }}/{{ $barang->stok_total }}
                                        </span>
                                    </td>
                                    <td class="align-middle">
                                        <a href="{{ route('barang.edit', $barang->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                   <div class="p-3 bg-light text-center">
    <a href="{{ route('barang.create') }}" class="btn btn-success btn-sm me-2">
        <i class="fas fa-plus me-1"></i>Tambah Barang
    </a>
    <a href="{{ route('barang.index') }}" class="btn btn-info btn-sm">
        <i class="fas fa-boxes me-1"></i>Kelola Stok Barang
    </a>
</div>

                @else
                    <div class="text-center py-4">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <p class="text-muted mb-0">Semua barang stoknya aman</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Popular Items Section -->
@if($barangPopuler->count() > 0)
<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-trophy me-2"></i>Barang Paling Populer
                </h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    @foreach($barangPopuler as $index => $barang)
                    <div class="col-lg-4 col-md-6">
                        <div class="d-flex align-items-center p-3 bg-light rounded">
                            <div class="me-3">
                                <div class="icon-circle {{ $index == 0 ? 'bg-warning' : ($index == 1 ? 'bg-info' : 'bg-secondary') }}">
                                    <span class="text-white font-weight-bold">{{ $index + 1 }}</span>
                                </div>
                            </div>
                            <div class="flex-grow-1 min-width-0">
                                <h6 class="mb-1 text-truncate">{{ $barang->nama_barang }}</h6>
                                <small class="text-muted">{{ $barang->total_dipinjam }} kali dipinjam</small>
                            </div>
                            <div class="ms-2">
                                <div class="progress" style="width: 60px; height: 8px;">
                                    <div class="progress-bar bg-primary" 
                                         style="width: {{ ($barang->total_dipinjam / $barangPopuler->max('total_dipinjam')) * 100 }}%">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@section('scripts')
<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0">Grafik Pendapatan & Pengeluaran 6 Bulan Terakhir</h5>
    </div>
    <div class="card-body">
        <canvas id="pendapatanChart" height="120"></canvas>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const grafikBulanan = @json($grafikBulanan);

    const ctx = document.getElementById('pendapatanChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: grafikBulanan.map(item => item.bulan),
            datasets: [
                {
                    label: 'Pendapatan',
                    data: grafikBulanan.map(item => item.pendapatan),
                    borderColor: 'green',
                    backgroundColor: 'rgba(0,128,0,0.1)',
                    fill: true,
                    tension: 0.3
                },
                {
                    label: 'Pengeluaran',
                    data: grafikBulanan.map(item => item.pengeluaran),
                    borderColor: 'red',
                    backgroundColor: 'rgba(255,0,0,0.1)',
                    fill: true,
                    tension: 0.3
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
                tooltip: { mode: 'index', intersect: false }
            },
            interaction: {
                mode: 'nearest',
                axis: 'x',
                intersect: false
            },
            scales: {
                y: {
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
@endpush

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Data dari Controller
    const grafikBulanan = @json($grafikBulanan);

    // Target canvas yang sudah ada di card
    const ctx = document.getElementById('pendapatanChart').getContext('2d');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: grafikBulanan.map(item => item.bulan),
            datasets: [
                {
                    label: 'Pendapatan (Rp)',
                    data: grafikBulanan.map(item => item.pendapatan),
                    borderColor: '#4e73df',
                    backgroundColor: 'rgba(78, 115, 223, 0.15)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 4,
                    pointBackgroundColor: '#4e73df',
                },
                {
                    label: 'Pengeluaran (Rp)',
                    data: grafikBulanan.map(item => item.pengeluaran),
                    borderColor: '#e74a3b',
                    backgroundColor: 'rgba(231, 74, 59, 0.15)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 4,
                    pointBackgroundColor: '#e74a3b',
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let val = context.raw || 0;
                            return context.dataset.label + ': Rp ' + val.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
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
