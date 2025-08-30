@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Detail Peminjam</h2>
    <div class="card mb-3">
        <div class="card-body">
            <p><strong>Nama:</strong> {{ $peminjam->nama_lengkap }}</p>
            <p><strong>Alamat:</strong> {{ $peminjam->alamat }}</p>
            <p><strong>No HP:</strong> {{ $peminjam->no_hp }}</p>
        </div>
    </div>

    <h4>Daftar Peminjaman</h4>
    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Barang</th>
                        <th>Jumlah</th>
                        <th>Tgl Pinjam</th>
                        <th>Tgl Kembali</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peminjam->peminjaman as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                            <td>{{ $item->jumlah }}</td>
                            <td>{{ $item->tanggal_pinjam }}</td>
                            <td>{{ $item->tanggal_kembali }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada peminjaman</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
