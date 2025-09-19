@extends('layouts.user')

@section('title', 'Riwayat Peminjaman Saya')

@section('breadcrumb')
<li class="breadcrumb-item active">Riwayat Peminjaman</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-1 text-primary">
                <i class="fas fa-history me-2"></i> Riwayat Peminjaman Saya
            </h1>
            <p class="text-muted">Kelola dan pantau semua peminjaman Anda</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('user.peminjaman.create') }}" class="btn btn-success btn-lg">
                <i class="fas fa-plus-circle me-2"></i> Ajukan Peminjaman Baru
            </a>
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
                                {{ $peminjamans->count() }}
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
                                {{ $peminjamans->where('status', 'dipinjam')->count() }}
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
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Dikembalikan
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $peminjamans->where('status', 'dikembalikan')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
                                Total Biaya
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($peminjamans->sum('total_bayar'), 0, ',', '.') }}
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

    <!-- Filters and Search -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h6 class="m-0 font-weight-bold text-primary">Filter & Pencarian</h6>
                </div>
                <div class="col-md-6 text-end">
                    <button class="btn btn-sm btn-outline-primary" id="toggleFilters">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body" id="filterSection" style="display: none;">
            <form method="GET" action="{{ route('user.peminjamn') }}">
                <div class="row">
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                            <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="tanggal_dari" class="form-label">Tanggal Dari</label>
                        <input type="date" name="tanggal_dari" id="tanggal_dari" class="form-control" value="{{ request('tanggal_dari') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="tanggal_sampai" class="form-label">Tanggal Sampai</label>
                        <input type="date" name="tanggal_sampai" id="tanggal_sampai" class="form-control" value="{{ request('tanggal_sampai') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="search" class="form-label">Cari Barang</label>
                        <input type="text" name="search" id="search" class="form-control" placeholder="Nama barang..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i> Filter
                        </button>
                        <a href="{{ route('user.peminjaman.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Main Table -->
    <div class="card shadow">
        <div class="card-header py-3">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-list me-2"></i>Daftar Peminjaman
                    </h6>
                </div>
                <div class="col-md-6 text-end">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-sm btn-outline-info" onclick="refreshTable()">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Kode Peminjaman</th>
                            <th>Barang</th>
                            <th>Tanggal Pinjam</th>
                            <th>Rencana Kembali</th>
                            <th>Tanggal Kembali</th>
                            <th>Status</th>
                            <th>Total Biaya</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjamans as $peminjaman)
                            <tr class="{{ $peminjaman->status == 'dipinjam' && $peminjaman->tanggal_kembali_rencana->isPast() ? 'table-warning' : '' }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <strong class="text-primary">{{ $peminjaman->kode_peminjaman ?? 'PM-' . str_pad($peminjaman->id, 4, '0', STR_PAD_LEFT) }}</strong>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            @if($peminjaman->barang->gambar)
                                                <img src="{{ asset('storage/' . $peminjaman->barang->gambar) }}" 
                                                     class="rounded" width="50" height="50" style="object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                     style="width: 50px; height: 50px;">
                                                    <i class="fas fa-box text-muted"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <strong>{{ $peminjaman->barang->nama_barang }}</strong>
                                            <br><small class="text-muted">{{ $peminjaman->barang->kategori->nama ?? '-' }}</small>
                                            @if($peminjaman->jumlah > 1)
                                                <br><span class="badge bg-info">{{ $peminjaman->jumlah }} unit</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $peminjaman->tanggal_pinjam->format('d/m/Y') }}</small>
                                    <br><strong>{{ $peminjaman->tanggal_pinjam->format('d M Y') }}</strong>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $peminjaman->tanggal_kembali_rencana->format('d/m/Y') }}</small>
                                    <br><strong>{{ $peminjaman->tanggal_kembali_rencana->format('d M Y') }}</strong>
                                    @if($peminjaman->status == 'dipinjam' && $peminjaman->tanggal_kembali_rencana->isPast())
                                        <br><span class="badge bg-danger">Terlambat {{ $peminjaman->tanggal_kembali_rencana->diffForHumans() }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($peminjaman->tanggal_kembali)
                                        <small class="text-muted">{{ $peminjaman->tanggal_kembali->format('d/m/Y') }}</small>
                                        <br><strong>{{ $peminjaman->tanggal_kembali->format('d M Y') }}</strong>
                                        @if($peminjaman->tanggal_kembali > $peminjaman->tanggal_kembali_rencana)
                                            <br><span class="badge bg-warning">Terlambat</span>
                                        @endif
                                    @else
                                        <span class="text-muted">Belum dikembalikan</span>
                                    @endif
                                </td>
                                <td>
                                    @if($peminjaman->status == 'pending')
                                        <span class="badge bg-info">Menunggu Persetujuan</span>
                                    @elseif($peminjaman->status == 'dipinjam')
                                        <span class="badge bg-warning text-dark">Sedang Dipinjam</span>
                                    @elseif($peminjaman->status == 'dikembalikan')
                                        <span class="badge bg-success">Sudah Dikembalikan</span>
                                    @elseif($peminjaman->status == 'ditolak')
                                        <span class="badge bg-danger">Ditolak</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($peminjaman->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <strong class="text-success">Rp {{ number_format($peminjaman->total_bayar ?? 0, 0, ',', '.') }}</strong>
                                    @if($peminjaman->denda > 0)
                                        <br><small class="text-danger">Denda: Rp {{ number_format($peminjaman->denda, 0, ',', '.') }}</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('peminjaman.show', $peminjaman->id) }}" 
                                           class="btn btn-sm btn-info" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        @if($peminjaman->status == 'dipinjam')
                                            <button type="button" class="btn btn-sm btn-success" 
                                                    onclick="confirmReturn({{ $peminjaman->id }})" 
                                                    title="Kembalikan">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                        @endif

                                        @if($peminjaman->status == 'dikembalikan')
                                            <button type="button" class="btn btn-sm btn-outline-primary" 
                                                    onclick="printReceipt({{ $peminjaman->id }})" 
                                                    title="Cetak Kwitansi">
                                                <i class="fas fa-print"></i>
                                            </button>
                                        @endif
                                    </div>

                                    @if($peminjaman->status == 'dipinjam')
                                        <form id="returnForm{{ $peminjaman->id }}" 
                                              action="{{ route('user.peminjaman.kembalikan', $peminjaman->id) }}" 
                                              method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <div class="mb-3">
                                        <i class="fas fa-inbox fa-3x text-muted"></i>
                                    </div>
                                    <h5 class="text-muted">Belum ada peminjaman</h5>
                                    <p class="text-muted">Mulai dengan mengajukan peminjaman barang pertama Anda</p>
                                    <a href="{{ route('user.peminjaman.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i>Ajukan Peminjaman
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($peminjamans->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $peminjamans->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<style>
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

.table-warning {
    background-color: rgba(255, 193, 7, 0.1);
}

.btn-group .btn {
    margin-right: 2px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}
</style>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle filter section
    document.getElementById('toggleFilters').addEventListener('click', function() {
        const filterSection = document.getElementById('filterSection');
        const isVisible = filterSection.style.display !== 'none';
        
        filterSection.style.display = isVisible ? 'none' : 'block';
        this.innerHTML = isVisible 
            ? '<i class="fas fa-filter me-1"></i> Filter' 
            : '<i class="fas fa-times me-1"></i> Tutup Filter';
    });

    // Show filters if there are active filters
    @if(request()->hasAny(['status', 'tanggal_dari', 'tanggal_sampai', 'search']))
        document.getElementById('filterSection').style.display = 'block';
        document.getElementById('toggleFilters').innerHTML = '<i class="fas fa-times me-1"></i> Tutup Filter';
    @endif
});

function refreshTable() {
    window.location.reload();
}

function confirmReturn(peminjamanId) {
    Swal.fire({
        title: 'Konfirmasi Pengembalian',
        text: 'Apakah Anda yakin ingin mengembalikan barang ini?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-check me-2"></i>Ya, Kembalikan',
        cancelButtonText: '<i class="fas fa-times me-2"></i>Batal',
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-secondary'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('returnForm' + peminjamanId);
            form.submit();
        }
    });
}

function printReceipt(peminjamanId) {
    // Open receipt in new window
    window.open(`{{ url('/peminjaman') }}/${peminjamanId}/receipt`, '_blank', 'width=800,height=600');
}
</script>
@endpush