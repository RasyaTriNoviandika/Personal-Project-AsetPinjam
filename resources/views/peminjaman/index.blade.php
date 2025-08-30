@extends('layouts.app')

@section('content')
<nav class="navbar navbar-expand-lg navbar-light bg-light d-lg-none">
    <div class="container">
        <a class="navbar-brand" href="#">Data Peminjaman</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="{{ route('barang.index') }}">Data Barang</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('peminjam.index') }}">Data Peminjam</a></li>
                <li class="nav-item"><a class="nav-link active" href="{{ route('peminjaman.index') }}">Peminjaman</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('laporan.index') }}">Laporan</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('peminjaman.create') }}">
                    <i class="fas fa-plus me-1"></i> Tambah
                </a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-hand-holding fa-fw me-2"></i>Data Peminjaman</h1>
    <a href="{{ route('peminjaman.create') }}" class="btn btn-primary mb-2 mb-md-0">
        <i class="fas fa-plus me-1"></i> Tambah Peminjaman
    </a>
</div>

<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Peminjam</th>
                    <th>Barang</th>
                    <th>Jumlah</th>
                    <th>Tanggal Pinjam</th>
                    <th>Tanggal Kembali</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peminjaman as $item)
                <tr>
                    <td>{{ $loop->iteration + ($peminjaman->currentPage()-1)*$peminjaman->perPage() }}</td>
                    <td>{{ $item->peminjam->nama_lengkap ?? '-' }}</td>
                    <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                    <td>{{ $item->jumlah }}</td>
                    <td>{{ $item->tanggal_pinjam }}</td>
                    <td>{{ $item->tanggal_kembali }}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="{{ route('peminjaman.show', $item) }}" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('peminjaman.edit', $item) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('peminjaman.destroy', $item) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data peminjaman</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{ $peminjaman->links() }}
    </div>
</div>
@endsection
