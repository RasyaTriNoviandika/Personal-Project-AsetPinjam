{{-- resources/views/transaksi-keuangan/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Daftar Transaksi Keuangan')

@section('content')
<div class="container">
    <h4 class="mb-3">Daftar Transaksi Keuangan</h4>

    {{-- Flash message --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Ringkasan --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-bg-success shadow-sm">
                <div class="card-body">
                    <h6>Total Masuk</h6>
                    <h4>Rp {{ number_format($totalMasuk, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-bg-danger shadow-sm">
                <div class="card-body">
                    <h6>Total Keluar</h6>
                    <h4>Rp {{ number_format($totalKeluar, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-bg-primary shadow-sm">
                <div class="card-body">
                    <h6>Saldo</h6>
                    <h4>Rp {{ number_format($saldo, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <form method="GET" action="{{ route('transaksi-keuangan.index') }}" class="row g-2 mb-3">
        <div class="col-md-3">
            <select name="jenis" class="form-control">
                <option value="">-- Semua Jenis --</option>
                <option value="masuk" {{ request('jenis') == 'masuk' ? 'selected' : '' }}>Masuk</option>
                <option value="keluar" {{ request('jenis') == 'keluar' ? 'selected' : '' }}>Keluar</option>
            </select>
        </div>
        <div class="col-md-3">
            <input type="date" name="tanggal_mulai" class="form-control" value="{{ request('tanggal_mulai') }}">
        </div>
        <div class="col-md-3">
            <input type="date" name="tanggal_selesai" class="form-control" value="{{ request('tanggal_selesai') }}">
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('transaksi-keuangan.index') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    {{-- Tombol tambah --}}
    <div class="mb-3">
        <a href="{{ route('transaksi-keuangan.create') }}" class="btn btn-success">+ Tambah Transaksi</a>
    </div>

    {{-- Tabel --}}
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Tanggal</th>
                    <th>Jenis</th>
                    <th>Kategori</th>
                    <th>Nominal</th>
                    <th>Status</th>
                    <th>Bukti</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksi as $t)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $t->kode_transaksi }}</td>
                        <td>{{ $t->nama }}</td>
                        <td>{{ $t->tanggal_transaksi ? $t->tanggal_transaksi->format('d-m-Y') : '-' }}</td>
                        <td>
                            <span class="badge {{ $t->jenis_transaksi == 'masuk' ? 'bg-success' : 'bg-danger' }}">
                                {{ ucfirst($t->jenis_transaksi) }}
                            </span>
                        </td>
                        <td>{{ $t->kategori_transaksi ?? '-' }}</td>
                        <td>Rp {{ number_format($t->jumlah, 0, ',', '.') }}</td>
                        <td>
                            @if($t->status == 'berhasil')
                                <span class="badge bg-success">Berhasil</span>
                            @elseif($t->status == 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @else
                                <span class="badge bg-danger">Gagal</span>
                            @endif
                        </td>
                        <td>
                            @if($t->bukti_transaksi)
                                <a href="{{ asset('storage/' . $t->bukti_transaksi) }}" target="_blank">Lihat</a>
                            @else
                                -
                            @endif
                        </td>
                        <td>
    <a href="{{ route('transaksi-keuangan.show', $t->id) }}" class="btn btn-sm btn-info">Show</a>
    <a href="{{ route('transaksi-keuangan.edit', $t->id) }}" class="btn btn-sm btn-warning">Edit</a>
    <form action="{{ route('transaksi-keuangan.destroy', $t->id) }}" method="POST" class="d-inline"
          onsubmit="return confirm('Yakin ingin menghapus data ini?')">
        @csrf
        @method('DELETE')
        <button class="btn btn-sm btn-danger">Hapus</button>
    </form>
</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center">Belum ada data transaksi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-3">
        {{ $transaksi->links() }}
    </div>
</div>
@endsection
