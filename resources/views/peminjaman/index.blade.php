@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-exchange-alt me-2"></i>Data Peminjaman</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('peminjaman.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>
            Tambah Peminjaman
        </a>
    </div>
</div>

{{-- Filter --}}
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                    <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                    <option value="terlambat" {{ request('status') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                    <option value="batal" {{ request('status') == 'batal' ? 'selected' : '' }}>Batal</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" class="form-control" value="{{ request('tanggal_mulai') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Tanggal Selesai</label>
                <input type="date" name="tanggal_selesai" class="form-control" value="{{ request('tanggal_selesai') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-1"></i>Filter
                    </button>
                    <a href="{{ route('peminjaman.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Tabel Peminjaman --}}
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Peminjam</th>
                        <th>Total Barang</th>
                        <th>Tgl Pinjam</th>
                        <th>Tgl Kembali</th>
                        <th>Total Biaya</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peminjaman as $item)
                        <tr>
                            <td>{{ $loop->iteration + ($peminjaman->currentPage() - 1) * $peminjaman->perPage() }}</td>
                            <td>
                                <span class="badge bg-secondary">{{ $item->kode_peminjaman }}</span>
                            </td>
                            <td>
                                <strong>{{ $item->peminjam->nama_peminjam ?? '-' }}</strong><br>
                                <small class="text-muted">{{ $item->peminjam->email ?? '-' }}</small>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $item->detailPeminjaman->count() }} item</span>
                            </td>
                            <td>{{ $item->tanggal_pinjam ? $item->tanggal_pinjam->format('d/m/Y') : '-' }}</td>
                            <td>
                                {{ $item->tanggal_kembali_rencana ? $item->tanggal_kembali_rencana->format('d/m/Y') : '-' }}
                                @if($item->status == 'dipinjam' || $item->status == 'terlambat')
                                    @php
                                        $hariTerlambat = \Carbon\Carbon::now()->diffInDays($item->tanggal_kembali_rencana, false);
                                    @endphp
                                    @if($hariTerlambat > 0)
                                        <br><small class="text-danger">Terlambat {{ $hariTerlambat }} hari</small>
                                    @endif
                                @endif
                            </td>
                            <td>
                                <strong>Rp {{ number_format($item->total_bayar, 0, ',', '.') }}</strong>
                                @if($item->total_denda > 0)
                                    <br><small class="text-danger">Denda: Rp {{ number_format($item->total_denda, 0, ',', '.') }}</small>
                                @endif
                            </td>
                            <td>
                                @php
                                    $statusClass = [
                                        'dipinjam' => 'bg-primary',
                                        'dikembalikan' => 'bg-success',
                                        'terlambat' => 'bg-danger',
                                        'batal' => 'bg-secondary'
                                    ];
                                @endphp
                                <span class="badge {{ $statusClass[$item->status] ?? 'bg-secondary' }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('peminjaman.show', $item) }}" class="btn btn-sm btn-info" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($item->status == 'dipinjam' || $item->status == 'terlambat')
                                        <a href="{{ route('peminjaman.pengembalian', $item) }}" class="btn btn-sm btn-warning" title="Kembalikan">
                                            <i class="fas fa-undo"></i>
                                        </a>
                                    @endif
                                    @if($item->status != 'dipinjam' && $item->status != 'terlambat')
                                        <form action="{{ route('peminjaman.destroy', $item) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                    onclick="return confirm('Yakin ingin menghapus?')" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">Tidak ada data peminjaman</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        {{ $peminjaman->appends(request()->query())->links() }}
    </div>
</div>
@endsection