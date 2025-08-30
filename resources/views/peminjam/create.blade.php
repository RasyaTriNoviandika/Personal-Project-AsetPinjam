@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Tambah Peminjam</h2>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('peminjam.store') }}" method="POST">
                @csrf
                            <div class="mb-3">
                    <label for="nama_peminjam" class="form-label">Nama Peminjam</label>
                    <input type="text" name="nama_peminjam" id="nama_peminjam" class="form-control" required>
            </div>

                <div class="mb-3">
                    <label>Alamat</label>
                    <textarea name="alamat" class="form-control" required></textarea>
                </div>
                <div class="mb-3">
                    <label>No HP</label>
                    <input type="text" name="no_hp" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('peminjam.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection
