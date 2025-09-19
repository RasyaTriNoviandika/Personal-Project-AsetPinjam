@extends('layouts.app')

@section('title', 'Dashboard User')

@section('content')
<div class="container">
    <h4 class="mb-4">Dashboard User</h4>

    {{-- Cek apakah user punya peminjaman --}}
    @if($totalPeminjaman == 0)
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="mb-3">Belum ada peminjaman</h5>
                        <p class="text-muted">
                            Silakan mulai dengan memilih barang untuk dipinjam.
                        </p>
                        <a href="{{ route('barang.index') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i> Mulai Peminjaman
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row">
            {{-- Statistik peminjaman --}}
            <div class="col-md-3">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <h5>Total Peminjaman</h5>
                        <h3>{{ $totalPeminjaman }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <h5>Sedang Dipinjam</h5>
                        <h3>{{ $sedangDipinjam }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <h5>Dikembalikan</h5>
                        <h3>{{ $dikembalikan }}</h3>
                    </div>
                </div>
            </div>
        </div>

        {{-- List peminjaman terakhir --}}
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5>Peminjaman Terakhir</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Barang</th>
                                    <th>Tanggal Pinjam</th>
                                    <th>Tanggal Kembali</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($peminjamanTerakhir as $p)
                                    <tr>
                                        <td>{{ $p->barang->nama_barang }}</td>
                                        <td>{{ $p->tanggal_pinjam }}</td>
                                        <td>{{ $p->tanggal_kembali ?? '-' }}</td>
                                        <td>
                                            @if($p->status == 'dipinjam')
                                                <span class="badge bg-warning text-dark">Dipinjam</span>
                                            @elseif($p->status == 'dikembalikan')
                                                <span class="badge bg-success">Dikembalikan</span>
                                            @else
                                                <span class="badge bg-secondary">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <a href="{{ route('peminjaman.index') }}" class="btn btn-primary btn-sm mt-3">
                            <i class="fas fa-list me-1"></i> Lihat Semua
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
