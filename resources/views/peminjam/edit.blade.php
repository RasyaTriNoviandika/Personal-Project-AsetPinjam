@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Peminjam</h2>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('peminjam.update', $peminjam) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" class="form-control" value="{{ $peminjam->nama_lengkap }}" required>
                </div>
                <div class="mb-3">
                    <label>Alamat</label>
                    <textarea name="alamat" class="form-control" required>{{ $peminjam->alamat }}</textarea>
                </div>
                <div class="mb-3">
                    <label>No HP</label>
                    <input type="text" name="no_hp" class="form-control" value="{{ $peminjam->no_hp }}" required>
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('peminjam.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection
