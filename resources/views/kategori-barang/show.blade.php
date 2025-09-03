@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-eye me-2"></i>
        Detail Kategori Barang
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('kategori-barang.edit', $kategori) }}" class="btn btn-warning">
                <i class="fas fa-edit me-1"></i>
                Edit
            </a>
        </div>
        <a href="{{ route('kategori-barang.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>
            Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        {{-- Info Kategori --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Kategori</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td><strong>Nama Kategori:</strong></td>
                        <td>{{ $kategori->nama_kategori }}</td>
                    </tr>
                    <tr>
                        <td><strong>Deskripsi:</strong></td>
                        <td>{{ $kategori->deskripsi ?: '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Jumlah Barang:</strong></td>
                        <td>
                            <span class="badge bg-primary">{{ $kategori->barang_count }} barang</span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Dibuat:</strong></td>
                        <td>{{ $kategori->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Diperbarui:</strong></td>
                        <td>{{ $kategori->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Daftar Barang dalam Kategori --}}
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-boxes me-2"></i>Daftar Barang dalam Kategori</h5>
            </div>
            <div class="card-body">
                @if($kategori->barang->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Gambar</th>
                                    <th>Nama Barang</th>
                                    <th>Stok</th>
                                    <th>Harga Sewa/Hari</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kategori->barang as $barang)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        @if($barang->gambar)
                                            <img src="{{ Storage::url($barang->gambar) }}" class="img-thumbnail" style="width:40px; height:40px;">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center" style="width:40px; height:40px;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>{{ $barang->nama_barang }}</td>
                                    <td>
                                        <span class="badge {{ $barang->stok_tersedia <= 3 ? 'bg-warning' : 'bg-success' }}">
                                            {{ $barang->stok_tersedia }}/{{ $barang->stok_total }}
                                        </span>
                                    </td>
                                    <td>Rp {{ number_format($barang->harga_sewa_per_hari, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge {{ $barang->status == 'aktif' ? 'bg-success' : 'bg-danger' }}">
                                            {{ ucfirst($barang->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('barang.show', $barang) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('barang.edit', $barang) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted mb-0">Belum ada barang dalam kategori ini</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        {{-- Statistik --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Statistik</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <span>Total Barang:</span>
                    <span><strong>{{ $kategori->barang_count ?? 0 }}</strong></span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span>Barang Aktif:</span>
                    <span><strong>{{ $kategori->barang->where('status', 'aktif')->count() }}</strong></span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span>Total Stok:</span>
                    <span><strong>{{ $kategori->barang->sum('stok_total') }}</strong></span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Stok Tersedia:</span>
                    <span><strong>{{ $kategori->barang->sum('stok_tersedia') }}</strong></span>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('kategori-barang.edit', $kategori) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-1"></i>Edit Kategori
                    </a>
                    <a href="{{ route('barang.create') }}?kategori_id={{ $kategori->id }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Tambah Barang Baru
                    </a>
                    <button class="btn btn-info" onclick="window.print()">
                        <i class="fas fa-print me-1"></i>Print Detail
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection