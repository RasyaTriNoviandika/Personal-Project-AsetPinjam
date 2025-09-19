{{-- resources/views/transaksi-keuangan/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Transaksi')

@section('content')
<div class="container">
    <h4 class="mb-3">Edit Transaksi Keuangan</h4>

    {{-- Validasi error --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Periksa kembali inputan Anda:</strong>
            <ul class="mb-0">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('transaksi-keuangan.update', $transaksi->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Nama</label>
            <input type="text" name="nama" class="form-control" value="{{ old('nama', $transaksi->nama) }}" required>
        </div>

        <div class="mb-3">
            <label>Tanggal Transaksi</label>
            <input type="date" name="tanggal_transaksi" class="form-control" 
                value="{{ old('tanggal_transaksi', $transaksi->tanggal_transaksi ? $transaksi->tanggal_transaksi->format('Y-m-d') : '') }}" required>
        </div>

        <div class="mb-3">
            <label>Jenis Transaksi</label>
            <select name="jenis_transaksi" class="form-control" required>
                <option value="masuk" {{ old('jenis_transaksi', $transaksi->jenis_transaksi) == 'masuk' ? 'selected' : '' }}>Masuk</option>
                <option value="keluar" {{ old('jenis_transaksi', $transaksi->jenis_transaksi) == 'keluar' ? 'selected' : '' }}>Keluar</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Kategori</label>
            <input type="text" name="kategori_transaksi" class="form-control" value="{{ old('kategori_transaksi', $transaksi->kategori_transaksi) }}">
        </div>

        <div class="mb-3">
            <label>Jumlah</label>
            <input type="number" name="jumlah" class="form-control" value="{{ old('jumlah', $transaksi->jumlah) }}" required>
        </div>

        <div class="mb-3">
            <label>Metode Pembayaran</label>
            <input type="text" name="metode_pembayaran" class="form-control" value="{{ old('metode_pembayaran', $transaksi->metode_pembayaran) }}" required>
        </div>

        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control" required>
                <option value="berhasil" {{ old('status', $transaksi->status) == 'berhasil' ? 'selected' : '' }}>Berhasil</option>
                <option value="pending" {{ old('status', $transaksi->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="gagal" {{ old('status', $transaksi->status) == 'gagal' ? 'selected' : '' }}>Gagal</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Bukti Transaksi</label>
            <input type="file" name="bukti_transaksi" class="form-control">
            @if($transaksi->bukti_transaksi)
                <small class="d-block mt-1">
                    File saat ini: <a href="{{ asset('storage/' . $transaksi->bukti_transaksi) }}" target="_blank">Lihat</a>
                </small>
            @endif
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="{{ route('transaksi-keuangan.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
