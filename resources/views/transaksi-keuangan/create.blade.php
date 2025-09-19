@extends('layouts.app')

@section('title', 'Tambah Transaksi Keuangan')

@section('content')
<div class="container">
    <h4 class="mb-3">Tambah Transaksi Keuangan</h4>

    {{-- Error validation --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('transaksi-keuangan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Nama --}}
        <div class="mb-3">
            <label for="nama" class="form-label">Nama</label>
            <input type="text" name="nama" id="nama" class="form-control" 
                   value="{{ old('nama') }}" required>
        </div>

        {{-- Jenis Transaksi --}}
        <div class="mb-3">
            <label for="jenis_transaksi" class="form-label">Jenis Transaksi</label>
            <select name="jenis_transaksi" id="jenis_transaksi" class="form-control" required>
                <option value="">-- Pilih Jenis --</option>
                <option value="masuk" {{ old('jenis_transaksi') == 'masuk' ? 'selected' : '' }}>Masuk</option>
                <option value="keluar" {{ old('jenis_transaksi') == 'keluar' ? 'selected' : '' }}>Keluar</option>
            </select>
        </div>

        {{-- Kategori (pilih atau ketik) --}}
        <div class="mb-3">
            <label for="kategori_transaksi" class="form-label">Kategori</label>
            <input list="kategori-list" 
                   name="kategori_transaksi" 
                   id="kategori_transaksi" 
                   class="form-control" 
                   value="{{ old('kategori_transaksi') }}" 
                   placeholder="Pilih atau ketik kategori">

            <datalist id="kategori-list">
                <option value="Mesin">
                <option value="Alat">
                <option value="Kendaraan">
                <option value="Elektronik">
                <option value="Operasional">
                <option value="Lainnya">
            </datalist>
        </div>

        {{-- Jumlah --}}
        <div class="mb-3">
            <label for="jumlah" class="form-label">Nominal</label>
            <input type="number" name="jumlah" id="jumlah" class="form-control" 
                   value="{{ old('jumlah') }}" required>
        </div>

        {{-- Tanggal --}}
        <div class="mb-3">
            <label for="tanggal_transaksi" class="form-label">Tanggal Transaksi</label>
            <input type="date" name="tanggal_transaksi" id="tanggal_transaksi" class="form-control" 
                   value="{{ old('tanggal_transaksi') }}" required>
        </div>

        {{-- Status --}}
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-control" required>
                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="berhasil" {{ old('status') == 'berhasil' ? 'selected' : '' }}>Berhasil</option>
                <option value="gagal" {{ old('status') == 'gagal' ? 'selected' : '' }}>Gagal</option>
            </select>
        </div>

        {{-- Metode Pembayaran --}}
<div class="mb-3">
    <label for="metode_pembayaran" class="form-label">Metode Pembayaran</label>
    <input 
        list="metode-list" 
        name="metode_pembayaran" 
        id="metode_pembayaran" 
        class="form-control @error('metode_pembayaran') is-invalid @enderror" 
        value="{{ old('metode_pembayaran') }}" 
        placeholder="Pilih atau ketik metode pembayaran" 
        required
    >
    <datalist id="metode-list">
        <option value="Cash">
        <option value="Transfer Bank">
        <option value="QRIS">
        <option value="Lainnya">
    </datalist>
    @error('metode_pembayaran')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>


        {{-- Bukti Transaksi --}}
        <div class="mb-3">
            <label for="bukti_transaksi" class="form-label">Bukti Transaksi (Opsional)</label>
            <input type="file" name="bukti_transaksi" id="bukti_transaksi" class="form-control">
        </div>

        {{-- Keterangan --}}
        <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan</label>
            <textarea name="keterangan" id="keterangan" rows="3" class="form-control">{{ old('keterangan') }}</textarea>
        </div>

        {{-- Tombol --}}
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success">Simpan</button>
            <a href="{{ route('transaksi-keuangan.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
