@extends('layouts.app')

@section('content')
<nav class="navbar navbar-expand-lg navbar-light bg-light d-lg-none">
    <div class="container">
        <a class="navbar-brand" href="#">Data Kategori Barang</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('barang.index') }}">Data Barang</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('laporan.index') }}">Laporan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('kategori-barang.create') }}">
                        <i class="fas fa-plus me-1"></i> Tambah Kategori
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom d-none d-lg-flex">
    <h1 class="h2">
        <i class="fas fa-tags me-2"></i>
        Data Kategori Barang
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('kategori-barang.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>
            Tambah Kategori
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
                        <th>Nama Kategori</th>
                        <th>Deskripsi</th>
                        <th>Jumlah Barang</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kategori as $item)
                    <tr>
                        <td>{{ $loop->iteration + ($kategori->currentPage() - 1) * $kategori->perPage() }}</td>
                        <td>{{ $item->nama_kategori }}</td>
                        <td>{{ $item->deskripsi }}</td>
                        <td>{{ $item->barang_count }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('kategori-barang.show', $item) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('kategori-barang.edit', $item) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('kategori-barang.destroy', $item) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" 
                                            onclick="return confirm('Yakin ingin menghapus?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data kategori barang</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $kategori->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 2000
        })
    @elseif(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: '{{ session('error') }}'
        })
    @endif
</script>
@endpush
