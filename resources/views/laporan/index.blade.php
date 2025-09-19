@extends('layouts.app')

@section('title', 'Laporan Lengkap')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Laporan Lengkap</h4>
        <div>
            <a href="{{ route('laporan.export.pdf') }}" class="btn btn-danger btn-sm">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
            <a href="{{ route('laporan.export.excel') }}" class="btn btn-success btn-sm">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
        </div>

        <div class="mb-3 no-print">
        <a href="#" onclick="window.print()" class="btn btn-primary">
        🖨️ Print Laporan
    </a>
</div>

    </div>

    {{-- ================= USERS ================= --}}
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">Data Users</div>
        <div class="card-body">
            <table class="table table-bordered table-striped table-sm">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Dibuat</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $u)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $u->id }}</td>
                        <td>{{ $u->name }}</td>
                        <td>{{ $u->email }}</td>
                        <td>{{ $u->created_at ? $u->created_at->format('d-m-Y') : '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- ================= BARANG ================= --}}
    <div class="card mb-4">
        <div class="card-header bg-success text-white">Data Barang</div>
        <div class="card-body">
            <table class="table table-bordered table-striped table-sm">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>ID</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                        <th>Total Dipinjam</th>
                        <th>Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($barang as $b)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $b->id }}</td>
                        <td>{{ $b->nama_barang }}</td>
                        <td>{{ $b->kategori->nama_kategori ?? '-' }}</td>
                        <td>{{ $b->stok_tersedia }}</td>
                        <td>{{ $b->total_dipinjam ?? 0 }}</td>
                        <td>Rp{{ number_format($b->pendapatan ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- ================= KATEGORI ================= --}}
    <div class="card mb-4">
        <div class="card-header bg-warning">Data Kategori</div>
        <div class="card-body">
            <table class="table table-bordered table-striped table-sm">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>ID</th>
                        <th>Nama Kategori</th>
                        <th>Jumlah Barang</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($kategori as $k)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $k->id }}</td>
                        <td>{{ $k->nama_kategori }}</td>
                        <td>{{ $k->barang->count() }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- ================= PEMINJAMAN ================= --}}
    <div class="card mb-4">
        <div class="card-header bg-info text-white">Data Peminjaman</div>
        <div class="card-body">
            <table class="table table-bordered table-striped table-sm">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>ID</th>
                        <th>Peminjam</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Kembali</th>
                        <th>Total Bayar</th>
                        <th>Total Denda</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($peminjaman as $p)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $p->id }}</td>
                        <td>{{ $p->peminjam->nama_peminjam ?? '-' }}</td>
                        <td>{{ $p->tanggal_pinjam ? \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d-m-Y') : '-' }}</td>
                       <td>
                            {{ $p->tanggal_kembali_aktual 
                                ? $p->tanggal_kembali_aktual->format('d-m-Y') 
                                : ($p->tanggal_kembali_rencana 
                                    ? $p->tanggal_kembali_rencana->format('d-m-Y') 
                                    : '-') }}
                        </td>
                        <td>Rp{{ number_format($p->total_bayar ?? 0, 0, ',', '.') }}</td>
                        <td>Rp{{ number_format($p->total_denda ?? 0, 0, ',', '.') }}</td>
                        <td>{{ $p->status }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- ================= TRANSAKSI ================= --}}
    <div class="card mb-4">
        <div class="card-header bg-dark text-white">Data Transaksi Keuangan</div>
        <div class="card-body">
            <table class="table table-bordered table-striped table-sm">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>ID</th>
                        <th>Tanggal</th>
                        <th>Jenis</th>
                        <th>Jumlah</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaksi as $t)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $t->id }}</td>
                        <td>{{ $t->tanggal_transaksi ? \Carbon\Carbon::parse($t->tanggal_transaksi)->format('d-m-Y') : '-' }}</td>
                        <td>{{ ucfirst($t->jenis_transaksi) }}</td>
                        <td>Rp{{ number_format($t->jumlah, 0, ',', '.') }}</td>
                        <td>
    {{ $t->keterangan 
        ? $t->keterangan 
        : ($t->peminjaman ? "Transaksi dari Peminjaman #{$t->peminjaman->id}" : '-') }}
</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
