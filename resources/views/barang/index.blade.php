@extends('layouts.app')

@section('content')
<nav class="navbar navbar-expand-lg navbar-light bg-light d-lg-none">
    <div class="container">
        <a class="navbar-brand" href="#">Data Barang</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="{{ route('barang.index') }}">Data Barang</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('peminjam.index') }}">Data Peminjam</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('peminjaman.index') }}">Peminjaman</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('laporan.index') }}">Laporan</a></li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('barang.create') }}">
                        <i class="fas fa-plus me-1"></i> Tambah Barang
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom d-none d-lg-flex">
    <h1 class="h2"><i class="fas fa-boxes me-2"></i>Data Barang</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('barang.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Tambah Barang
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Gambar</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                        <th>Harga Sewa / Hari</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($barang as $item)
                        <tr>
                            {{-- Nomor urut dengan pagination --}}
                            <td>{{ $loop->iteration + ($barang->currentPage() - 1) * $barang->perPage() }}</td>

                            {{-- Gambar --}}
                            <td>
                                @if($item->gambar)
                                    <img src="{{ Storage::url($item->gambar) }}" class="img-thumbnail" style="width:50px; height:50px; object-fit: cover;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center rounded" style="width:50px; height:50px;">
                                        <i class="fas fa-image text-muted fa-lg"></i>
                                    </div>
                                @endif
                            </td>

                            {{-- Nama Barang --}}
                            <td>{{ $item->nama_barang }}</td>

                            {{-- Kategori --}}
                            <td>{{ $item->kategori->nama_kategori ?? '-' }}</td>

                            {{-- Stok --}}
                            <td>
                                <span class="badge {{ $item->stok_tersedia <= 3 ? 'bg-danger' : ($item->stok_tersedia <= 5 ? 'bg-warning' : 'bg-success') }}">
                                    {{ $item->stok_tersedia }}/{{ $item->stok_total }}
                                </span>
                            </td>

                            {{-- Harga Sewa --}}
                            <td>Rp {{ number_format($item->harga_sewa_per_hari, 0, ',', '.') }}</td>

                            {{-- Status --}}
                            <td>
                                <span class="badge {{ $item->status == 'aktif' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>

                            {{-- Aksi --}}
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('barang.show', $item) }}" class="btn btn-sm btn-info" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('barang.edit', $item) }}" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Barang">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('barang.destroy', $item) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus barang ini?')" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus Barang">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="fas fa-box-open fa-2x text-muted mb-2"></i>
                                <p class="text-muted">Tidak ada data barang yang tersedia.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-center mt-3">
            {{ $barang->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
@endpush