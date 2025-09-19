@extends('layouts.app')

@section('title', 'Dashboard User')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h4 class="mb-1">Dashboard User</h4>
            <p class="text-muted">Selamat datang, {{ auth()->user()->name }}!</p>
        </div>
        <div class="col-md-4 text-end">
            <div class="btn-group" role="group">
                <a href="{{ route('barang.index') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-search me-1"></i> Cari Barang
                </a>
                @if($totalPeminjaman > 0)
                <a href="{{ route('user.peminjaman.create') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-plus me-1"></i> Pinjam Lagi
                </a>
                @endif
            </div>
        </div>
    </div>

    {{-- Cek apakah user punya peminjaman --}}
    @if($totalPeminjaman == 0)
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center py-5">
                        <div class="mb-4">
                            <i class="fas fa-box-open text-muted" style="font-size: 4rem;"></i>
                        </div>
                        <h5 class="mb-3">Belum ada peminjaman</h5>
                        <p class="text-muted mb-4">
                            Mulai eksplorasi barang-barang yang tersedia untuk dipinjam.<br>
                            Temukan apa yang Anda butuhkan dengan mudah dan cepat.
                        </p>
                        <a href="{{ route('barang.index') }}" class="btn btn-primary btn-lg px-4">
                            <i class="fas fa-search me-2"></i> Mulai Peminjaman
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        {{-- Statistik peminjaman --}}
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Peminjaman</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalPeminjaman }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Sedang Dipinjam</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $sedangDipinjam }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-hourglass-half fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Dikembalikan</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $dikembalikan }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Total Biaya</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    Rp {{ number_format($totalBiaya ?? 0, 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Peminjaman Aktif --}}
            @if($sedangDipinjam > 0)
            <div class="col-md-8 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-exclamation-triangle me-2"></i>Peminjaman Aktif
                        </h6>
                        <div class="dropdown no-arrow">
                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                                <a class="dropdown-item" href="{{ route('user.peminjaman.index') }}">Lihat Semua</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @foreach($peminjamanAktif as $p)
                        <div class="d-flex align-items-center border-bottom py-3">
                            <div class="me-3">
                                <div class="icon-circle bg-warning">
                                    <i class="fas fa-box text-white"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="small text-gray-500">{{ $p->tanggal_pinjam->format('d M Y') }}</div>
                                <strong>{{ $p->barang->nama_barang }}</strong>
                                <div class="small text-muted">
                                    Rencana kembali: {{ $p->tanggal_kembali_rencana->format('d M Y') }}
                                    @if($p->tanggal_kembali_rencana->isPast())
                                        <span class="badge bg-danger ms-2">Terlambat</span>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <a href="{{ route('peminjaman.show', $p->id) }}" class="btn btn-sm btn-outline-primary">
                                    Detail
                                </a>
                            </div>
                        </div>
                        @endforeach
                        
                        @if($peminjamanAktif->count() == 0)
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-info-circle me-2"></i>Tidak ada peminjaman aktif
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            {{-- Quick Actions --}}
            <div class="col-md-{{ $sedangDipinjam > 0 ? '4' : '12' }} mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-bolt me-2"></i>Quick Actions
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <a href="{{ route('barang.index') }}" class="list-group-item list-group-item-action border-0 px-0">
                                <div class="d-flex align-items-center">
                                    <div class="icon-circle bg-primary me-3">
                                        <i class="fas fa-search text-white"></i>
                                    </div>
                                    <div>
                                        <strong>Cari Barang</strong>
                                        <div class="small text-muted">Temukan barang untuk dipinjam</div>
                                    </div>
                                </div>
                            </a>
                            <a href="{{ route('user.peminjaman.create') }}" class="list-group-item list-group-item-action border-0 px-0">
                                <div class="d-flex align-items-center">
                                    <div class="icon-circle bg-success me-3">
                                        <i class="fas fa-plus text-white"></i>
                                    </div>
                                    <div>
                                        <strong>Ajukan Peminjaman</strong>
                                        <div class="small text-muted">Buat peminjaman baru</div>
                                    </div>
                                </div>
                            </a>
                            <a href="{{ route('user.peminjaman.index') }}" class="list-group-item list-group-item-action border-0 px-0">
                                <div class="d-flex align-items-center">
                                    <div class="icon-circle bg-info me-3">
                                        <i class="fas fa-history text-white"></i>
                                    </div>
                                    <div>
                                        <strong>Riwayat Sewa</strong>
                                        <div class="small text-muted">Lihat semua peminjaman</div>
                                    </div>
                                </div>
                            </a>
                            <a href="{{ route('settings.profile') }}" class="list-group-item list-group-item-action border-0 px-0">
                                <div class="d-flex align-items-center">
                                    <div class="icon-circle bg-secondary me-3">
                                        <i class="fas fa-user-cog text-white"></i>
                                    </div>
                                    <div>
                                        <strong>Pengaturan</strong>
                                        <div class="small text-muted">Kelola profil Anda</div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Riwayat Peminjaman Terakhir --}}
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-history me-2"></i>Riwayat Peminjaman Terakhir
                        </h6>
                        <a href="{{ route('user.peminjaman.index') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-list me-1"></i> Lihat Semua
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Barang</th>
                                        <th>Tanggal Pinjam</th>
                                        <th>Tanggal Kembali</th>
                                        <th>Status</th>
                                        <th>Total Biaya</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($peminjamanTerakhir as $p)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <strong>{{ $p->barang->nama_barang }}</strong>
                                                <br><small class="text-muted">{{ $p->barang->kategori->nama ?? '-' }}</small>
                                            </td>
                                            <td>{{ $p->tanggal_pinjam->format('d M Y') }}</td>
                                            <td>
                                                @if($p->tanggal_kembali)
                                                    {{ $p->tanggal_kembali->format('d M Y') }}
                                                @else
                                                    <span class="text-muted">Belum kembali</span>
                                                    @if($p->tanggal_kembali_rencana->isPast())
                                                        <br><span class="badge bg-danger">Terlambat</span>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                @if($p->status == 'dipinjam')
                                                    <span class="badge bg-warning text-dark">Dipinjam</span>
                                                @elseif($p->status == 'dikembalikan')
                                                    <span class="badge bg-success">Dikembalikan</span>
                                                @elseif($p->status == 'pending')
                                                    <span class="badge bg-info">Pending</span>
                                                @else
                                                    <span class="badge bg-secondary">-</span>
                                                @endif
                                            </td>
                                            <td>Rp {{ number_format($p->total_bayar ?? 0, 0, ',', '.') }}</td>
                                            <td>
                                                <a href="{{ route('peminjaman.show', $p->id) }}" 
                                                   class="btn btn-sm btn-info" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">
                                                <i class="fas fa-inbox me-2"></i>Belum ada riwayat peminjaman
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
.icon-circle {
    height: 2.5rem;
    width: 2.5rem;
    border-radius: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.text-xs {
    font-size: 0.7rem;
}

.font-weight-bold {
    font-weight: 700;
}

.border-left-primary {
    border-left: 0.25rem solid var(--primary-color) !important;
}

.border-left-success {
    border-left: 0.25rem solid var(--success-color) !important;
}

.border-left-info {
    border-left: 0.25rem solid var(--info-color) !important;
}

.border-left-warning {
    border-left: 0.25rem solid var(--warning-color) !important;
}
</style>
@endsection
