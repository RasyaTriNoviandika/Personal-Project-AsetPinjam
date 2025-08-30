
@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-users me-2"></i>Data Peminjam</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('peminjam.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>
            Tambah Peminjam
        </a>
    </div>
</div>

{{-- Filter --}}
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Cari nama/email..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="jenis" class="form-select">
                    <option value="">Semua Jenis</option>
                    <option value="individu" {{ request('jenis') == 'individu' ? 'selected' : '' }}>Individu</option>
                    <option value="organisasi" {{ request('jenis') == 'organisasi' ? 'selected' : '' }}>Organisasi</option>
                    <option value="perusahaan" {{ request('jenis') == 'perusahaan' ? 'selected' : '' }}>Perusahaan</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Non Aktif</option>
                </select>
            </div>
            <div class="col-md-3">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-1"></i>Filter
                    </button>
                    <a href="{{ route('peminjam.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama Peminjam</th>
                        <th>Email</th>
                        <th>No. Telepon</th>
                        <th>Jenis</th>
                        <th>Status</th>
                        <th>Total Peminjaman</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peminjam as $item)
                        <tr>
                            <td>{{ $loop->iteration + ($peminjam->currentPage() - 1) * $peminjam->perPage() }}</td>
                            <td>
                                <span class="badge bg-secondary">{{ $item->kode_peminjam }}</span>
                            </td>
                            <td>
                                <strong>{{ $item->nama_peminjam }}</strong>
                                <br><small class="text-muted">{{ $item->no_identitas }}</small>
                            </td>
                            <td>{{ $item->email }}</td>
                            <td>{{ $item->no_telepon }}</td>
                            <td>
                                <span class="badge bg-info">{{ ucfirst($item->jenis_peminjam) }}</span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $item->status == 'aktif' ? 'success' : 'danger' }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $item->peminjaman_count ?? 0 }}</span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('peminjam.show', $item) }}" class="btn btn-sm btn-info" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('peminjam.edit', $item) }}" class="btn btn-sm btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('peminjam.destroy', $item) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" 
                                                onclick="return confirm('Yakin ingin menghapus?')" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">Tidak ada data peminjam</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        {{ $peminjam->appends(request()->query())->links() }}
    </div>
</div>
@endsection
