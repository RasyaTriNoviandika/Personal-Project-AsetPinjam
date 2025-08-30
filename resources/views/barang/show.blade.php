@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-eye me-2"></i>
        Detail Barang
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('barang.edit', $barang) }}" class="btn btn-warning">
                <i class="fas fa-edit me-1"></i>
                Edit
            </a>
        </div>
        <a href="{{ route('barang.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>
            Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        {{-- Info Barang --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Barang</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        @if($barang->gambar)
                            <img src="{{ asset('storage/' . $barang->gambar) }}" class="img-fluid rounded mb-3" alt="{{ $barang->nama_barang }}">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center rounded mb-3" style="height: 200px;">
                                <i class="fas fa-image fa-3x text-muted"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-8">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Nama Barang:</strong></td>
                                <td>{{ $barang->nama_barang }}</td>
                            </tr>
                            <tr>
                                <td><strong>Kategori:</strong></td>
                                <td>{{ $barang->kategori->nama_kategori ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Stok:</strong></td>
                                <td>
                                    <span class="badge bg-{{ $barang->stok_tersedia <= 3 ? 'warning' : 'success' }}">
                                        {{ $barang->stok_tersedia }} / {{ $barang->stok_total }} unit tersedia
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Harga Sewa/Hari:</strong></td>
                                <td><strong class="text-primary">Rp {{ number_format($barang->harga_sewa_per_hari, 0, ',', '.') }}</strong></td>
                            </tr>
                            <tr>
                                <td><strong>Denda/Hari:</strong></td>
                                <td><strong class="text-danger">Rp {{ number_format($barang->denda_per_hari, 0, ',', '.') }}</strong></td>
                            </tr>
                            <tr>
                                <td><strong>Kondisi:</strong></td>
                                <td>
                                    @php
                                        $kondisiClass = [
                                            'baik' => 'bg-success',
                                            'rusak_ringan' => 'bg-warning',
                                            'rusak_berat' => 'bg-danger'
                                        ];
                                    @endphp
                                    <span class="badge {{ $kondisiClass[$barang->kondisi] ?? 'bg-secondary' }}">
                                        {{ ucfirst(str_replace('_', ' ', $barang->kondisi)) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>
                                    <span class="badge bg-{{ $barang->status == 'aktif' ? 'success' : 'danger' }}">
                                        {{ ucfirst($barang->status) }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                @if($barang->deskripsi)
                <div class="mt-3">
                    <strong>Deskripsi:</strong>
                    <p class="mt-2">{{ $barang->deskripsi }}</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Riwayat Peminjaman --}}
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-history me-2"></i>Riwayat Peminjaman (10 Terakhir)</h5>
            </div>
            <div class="card-body">
                @if($barang->detailPeminjaman->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Peminjam</th>
                                    <th>Jumlah</th>
                                    <th>Status</th>
                                    <th>Kondisi Kembali</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($barang->detailPeminjaman()->with('peminjaman.peminjam')->latest()->take(10)->get() as $detail)
                                <tr>
                                    <td>{{ $detail->peminjaman->tanggal_pinjam->format('d/m/Y') }}</td>
                                    <td>{{ $detail->peminjaman->peminjam->nama_peminjam ?? '-' }}</td>
                                    <td>{{ $detail->jumlah }}</td>
                                    <td>
                                        <span class="badge bg-{{ $detail->peminjaman->status == 'dikembalikan' ? 'success' : 'primary' }}">
                                            {{ ucfirst($detail->peminjaman->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($detail->kondisi_kembali)
                                            <span class="badge bg-{{ $detail->kondisi_kembali == 'baik' ? 'success' : 'warning' }}">
                                                {{ ucfirst(str_replace('_', ' ', $detail->kondisi_kembali)) }}
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted mb-0">Belum ada riwayat peminjaman</p>
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
                    <span>Total Dipinjam:</span>
                    <span><strong>{{ $barang->detailPeminjaman->sum('jumlah') }} kali</strong></span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span>Pendapatan:</span>
                    <span><strong>Rp {{ number_format($barang->detailPeminjaman->sum('subtotal_sewa'), 0, ',', '.') }}</strong></span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span>Peminjaman Aktif:</span>
                    <span><strong>{{ $barang->detailPeminjaman()->whereHas('peminjaman', function($q) { $q->whereIn('status', ['dipinjam', 'terlambat']); })->sum('jumlah') }}</strong></span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Tingkat Kerusakan:</span>
                    <span>
                        @php
                            $totalKembali = $barang->detailPeminjaman()->whereNotNull('kondisi_kembali')->count();
                            $rusak = $barang->detailPeminjaman()->whereIn('kondisi_kembali', ['rusak_ringan', 'rusak_berat', 'hilang'])->count();
                            $persentase = $totalKembali > 0 ? round(($rusak / $totalKembali) * 100, 1) : 0;
                        @endphp
                        <strong class="text-{{ $persentase > 20 ? 'danger' : ($persentase > 10 ? 'warning' : 'success') }}">{{ $persentase }}%</strong>
                    </span>
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
                    <a href="{{ route('barang.edit', $barang) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-1"></i>Edit Barang
                    </a>
                    @if($barang->status == 'aktif' && $barang->stok_tersedia > 0)
                        <a href="{{ route('peminjaman.create') }}?barang_id={{ $barang->id }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>Buat Peminjaman
                        </a>
                    @endif
                    <button class="btn btn-info" onclick="window.print()">
                        <i class="fas fa-print me-1"></i>Print Detail
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection