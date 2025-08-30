
@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-eye me-2"></i>
        Detail Peminjaman
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            @if($peminjaman->status == 'dipinjam' || $peminjaman->status == 'terlambat')
                <a href="{{ route('peminjaman.pengembalian', $peminjaman) }}" class="btn btn-warning">
                    <i class="fas fa-undo me-1"></i>
                    Proses Pengembalian
                </a>
            @endif
            <button onclick="window.print()" class="btn btn-info">
                <i class="fas fa-print me-1"></i>
                Print
            </button>
        </div>
        <a href="{{ route('peminjaman.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>
            Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        {{-- Info Peminjaman --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Peminjaman</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Kode Peminjaman:</strong></td>
                                <td>{{ $peminjaman->kode_peminjaman }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Pinjam:</strong></td>
                                <td>{{ $peminjaman->tanggal_pinjam ? $peminjaman->tanggal_pinjam->format('d F Y') : '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Kembali Rencana:</strong></td>
                                <td>{{ $peminjaman->tanggal_kembali_rencana ? $peminjaman->tanggal_kembali_rencana->format('d F Y') : '-' }}</td>
                            </tr>
                            @if($peminjaman->tanggal_kembali_aktual)
                            <tr>
                                <td><strong>Tanggal Kembali Aktual:</strong></td>
                                <td>{{ $peminjaman->tanggal_kembali_aktual->format('d F Y') }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>
                                    @php
                                        $statusClass = [
                                            'dipinjam' => 'bg-primary',
                                            'dikembalikan' => 'bg-success',
                                            'terlambat' => 'bg-danger',
                                            'batal' => 'bg-secondary'
                                        ];
                                    @endphp
                                    <span class="badge {{ $statusClass[$peminjaman->status] ?? 'bg-secondary' }}">
                                        {{ ucfirst($peminjaman->status) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Durasi:</strong></td>
                                <td>{{ $peminjaman->tanggal_pinjam && $peminjaman->tanggal_kembali_rencana ? $peminjaman->tanggal_pinjam->diffInDays($peminjaman->tanggal_kembali_rencana) + 1 : 0 }} hari</td>
                            </tr>
                            @if($peminjaman->status == 'dipinjam' || $peminjaman->status == 'terlambat')
                                @php
                                    $hariTerlambat = \Carbon\Carbon::now()->diffInDays($peminjaman->tanggal_kembali_rencana, false);
                                @endphp
                                @if($hariTerlambat > 0)
                                <tr>
                                    <td><strong>Terlambat:</strong></td>
                                    <td><span class="text-danger">{{ $hariTerlambat }} hari</span></td>
                                </tr>
                                @endif
                            @endif
                            <tr>
                                <td><strong>Petugas:</strong></td>
                                <td>{{ $peminjaman->user->name ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                @if($peminjaman->catatan)
                <div class="mt-3">
                    <strong>Catatan:</strong>
                    <p class="mb-0">{{ $peminjaman->catatan }}</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Detail Barang --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-boxes me-2"></i>Detail Barang</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th>Jumlah</th>
                                <th>Harga/Hari</th>
                                <th>Subtotal</th>
                                <th>Kondisi Pinjam</th>
                                @if($peminjaman->status == 'dikembalikan')
                                    <th>Kondisi Kembali</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($peminjaman->detailPeminjaman as $detail)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <strong>{{ $detail->barang->nama_barang ?? '-' }}</strong>
                                    @if($detail->barang->gambar)
                                        <br><small class="text-muted">
                                            <img src="{{ asset('storage/' . $detail->barang->gambar) }}" 
                                                 style="width: 30px; height: 30px; object-fit: cover;" class="rounded">
                                        </small>
                                    @endif
                                </td>
                                <td>{{ $detail->barang->kategori->nama_kategori ?? '-' }}</td>
                                <td>{{ $detail->jumlah }}</td>
                                <td>Rp {{ number_format($detail->harga_sewa_per_hari, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($detail->subtotal_sewa, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge bg-{{ $detail->kondisi_pinjam == 'baik' ? 'success' : 'warning' }}">
                                        {{ ucfirst(str_replace('_', ' ', $detail->kondisi_pinjam)) }}
                                    </span>
                                </td>
                                @if($peminjaman->status == 'dikembalikan')
                                    <td>
                                        @if($detail->kondisi_kembali)
                                            @php
                                                $kondisiClass = [
                                                    'baik' => 'bg-success',
                                                    'rusak_ringan' => 'bg-warning',
                                                    'rusak_berat' => 'bg-danger',
                                                    'hilang' => 'bg-dark'
                                                ];
                                            @endphp
                                            <span class="badge {{ $kondisiClass[$detail->kondisi_kembali] ?? 'bg-secondary' }}">
                                                {{ ucfirst(str_replace('_', ' ', $detail->kondisi_kembali)) }}
                                            </span>
                                            @if($detail->catatan)
                                                <br><small class="text-muted">{{ $detail->catatan }}</small>
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                @endif
                            </tr>
                            @empty
                            <tr>
                                <td colspan="{{ $peminjaman->status == 'dikembalikan' ? '8' : '7' }}" class="text-center">
                                    Tidak ada detail barang
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        {{-- Info Peminjam --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-user me-2"></i>Data Peminjam</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <i class="fas fa-user-circle fa-3x text-muted"></i>
                </div>
                <table class="table table-borderless table-sm">
                    <tr>
                        <td><strong>Nama:</strong></td>
                        <td>{{ $peminjaman->peminjam->nama_peminjam ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Email:</strong></td>
                        <td>{{ $peminjaman->peminjam->email ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>No. Telepon:</strong></td>
                        <td>{{ $peminjaman->peminjam->no_telepon ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Alamat:</strong></td>
                        <td>{{ $peminjaman->peminjam->alamat ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Jenis:</strong></td>
                        <td>{{ ucfirst($peminjaman->peminjam->jenis_peminjam ?? '-') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Ringkasan Biaya --}}
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-calculator me-2"></i>Ringkasan Biaya</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td>Total Biaya Sewa:</td>
                        <td class="text-end"><strong>Rp {{ number_format($peminjaman->total_biaya_sewa, 0, ',', '.') }}</strong></td>
                    </tr>
                    @if($peminjaman->total_denda > 0)
                    <tr>
                        <td>Denda Keterlambatan:</td>
                        <td class="text-end"><strong class="text-danger">Rp {{ number_format($peminjaman->total_denda, 0, ',', '.') }}</strong></td>
                    </tr>
                    @endif
                    <tr class="border-top">
                        <td><strong>Total Bayar:</strong></td>
                        <td class="text-end"><strong class="text-primary">Rp {{ number_format($peminjaman->total_bayar, 0, ',', '.') }}</strong></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
