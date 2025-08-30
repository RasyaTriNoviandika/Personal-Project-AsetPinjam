@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-hand-holding fa-fw me-2"></i>Edit Peminjaman</h1>
    <a href="{{ route('peminjaman.index') }}" class="btn btn-secondary">Kembali</a>
</div>
<div class="card">
    <div class="card-body">
        <form action="{{ route('peminjaman.store') }}" method="POST">
            @csrf
            {{-- Untuk edit tambahkan: @method('PUT') --}}
            <div class="mb-3">
                <label for="peminjam_id" class="form-label">Peminjam</label>
                <select name="peminjam_id" id="peminjam_id" class="form-control" required>
                    <option value="">-- Pilih Peminjam --</option>
                    @foreach($peminjam as $p)
                        <option value="{{ $p->id }}" {{ isset($peminjaman) && $peminjaman->peminjam_id == $p->id ? 'selected' : '' }}>
                            {{ $p->nama_lengkap }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="barang_id" class="form-label">Barang</label>
                <select name="barang_id" id="barang_id" class="form-control" required>
                    <option value="">-- Pilih Barang --</option>
                    @foreach($barang as $b)
                        <option value="{{ $b->id }}" {{ isset($peminjaman) && $peminjaman->barang_id == $b->id ? 'selected' : '' }}>
                            {{ $b->nama_barang }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="jumlah" class="form-label">Jumlah</label>
                <input type="number" name="jumlah" id="jumlah" class="form-control" value="{{ $peminjaman->jumlah ?? '' }}" min="1" required>
            </div>
            <div class="mb-3">
                <label for="tanggal_pinjam" class="form-label">Tanggal Pinjam</label>
                <input type="date" name="tanggal_pinjam" id="tanggal_pinjam" class="form-control" value="{{ $peminjaman->tanggal_pinjam ?? '' }}" required>
            </div>
            <div class="mb-3">
                <label for="tanggal_kembali" class="form-label">Tanggal Kembali</label>
                <input type="date" name="tanggal_kembali" id="tanggal_kembali" class="form-control" value="{{ $peminjaman->tanggal_kembali ?? '' }}" required>
            </div>
            <button type="submit" class="btn btn-primary">{{ isset($peminjaman) ? 'Update' : 'Simpan' }}</button>
            <a href="{{ route('peminjaman.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
