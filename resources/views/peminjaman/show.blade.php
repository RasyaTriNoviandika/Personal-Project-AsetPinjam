@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-hand-holding fa-fw me-2"></i>Detail Peminjaman</h1>
    <a href="{{ route('peminjaman.index') }}" class="btn btn-secondary">Kembali</a>
</div>
<div class="card">
    <div class="card-body">
        <table class="table table-bordered table-responsive">
            <tr>
                <th>Peminjam</th>
                <td>{{ $peminjaman->peminjam->nama_lengkap ?? '-' }}</td>
            </tr>
            <tr>
                <th>Barang</th>
                <td>{{ $peminjaman->barang->nama_barang ?? '-' }}</td>
            </tr>
            <tr>
                <th>Jumlah</th>
                <td>{{ $peminjaman->jumlah }}</td>
            </tr>
            <tr>
                <th>Tanggal Pinjam</th>
                <td>{{ $peminjaman->tanggal_pinjam }}</td>
            </tr>
            <tr>
                <th>Tanggal Kembali</th>
                <td>{{ $peminjaman->tanggal_kembali }}</td>
            </tr>
        </table>
        <div class="mt-3">
            <a href="{{ route('peminjaman.edit', $peminjaman) }}" class="btn btn-warning"><i class="fas fa-edit me-1"></i> Edit</a>
            <form action="{{ route('peminjaman.destroy', $peminjaman) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus?')"><i class="fas fa-trash me-1"></i> Hapus</button>
            </form>
            <a href="{{ route('peminjaman.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
</div>
