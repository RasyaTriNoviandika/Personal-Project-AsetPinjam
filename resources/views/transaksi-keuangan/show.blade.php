{{-- resources/views/transaksi-keuangan/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Detail Transaksi')

@section('content')
<div class="container">
    <h4 class="mb-3">Detail Transaksi</h4>

    <div class="card">
        <div class="card-body">
            <h4>Detail Transaksi</h4>
            <p><strong>Kode:</strong> {{ $transaksi->kode_transaksi }}</p>
            <p><strong>Nama:</strong> {{ $transaksi->nama }}</p>
            <p><strong>Tanggal:</strong> {{ $transaksi->tanggal_transaksi }}</p>
            <p><strong>Jenis:</strong> {{ ucfirst($transaksi->jenis_transaksi) }}</p>
            <p><strong>Jumlah:</strong> Rp {{ number_format($transaksi->jumlah, 0, ',', '.') }}</p>

            <p><strong>Metode Pembayaran:</strong> {{ $transaksi->metode_pembayaran ?? '-' }}</p>

            <p><strong>Status:</strong> 
                <span class="badge 
                    @if($transaksi->status == 'berhasil') bg-success 
                    @elseif($transaksi->status == 'pending') bg-warning 
                    @else bg-danger @endif">
                    {{ ucfirst($transaksi->status ?? '-') }}
                </span>
            </p>

            <p><strong>Bukti Transaksi:</strong><br>
                @if($transaksi->bukti_transaksi)
                    <a href="{{ asset('storage/' . $transaksi->bukti_transaksi) }}" target="_blank" class="btn btn-sm btn-info">Lihat Bukti</a>
                @else
                    <span class="text-muted">Tidak ada bukti</span>
                @endif
            </p>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('transaksi-keuangan.index') }}" class="btn btn-secondary">Kembali</a>
        <a href="{{ route('transaksi-keuangan.edit', $transaksi->id) }}" class="btn btn-warning">Edit</a>
        <form action="{{ route('transaksi-keuangan.destroy', $transaksi->id) }}" method="POST" class="d-inline"
              onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger">Hapus</button>
        </form>
    </div>
</div>
@endsection
