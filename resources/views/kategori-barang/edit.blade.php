@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Kategori Barang</h1>

    <form action="{{ route('kategori-barang.update', $kategori->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nama_kategori" class="form-label">Nama Kategori</label>
            <input type="text" 
                   name="nama_kategori" 
                   id="nama_kategori" 
                   class="form-control" 
                   value="{{ old('nama_kategori', $kategori->nama_kategori) }}" 
                   required>
        </div>

        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea name="deskripsi" 
                      id="deskripsi" 
                      class="form-control">{{ old('deskripsi', $kategori->deskripsi) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="jumlah_barang" class="form-label">Jumlah Barang</label>
            <input type="number" 
                   name="jumlah_barang" 
                   id="jumlah_barang" 
                   class="form-control" 
                   value="{{ old('jumlah_barang', $kategori->jumlah_barang) }}" 
                   min="0">
        </div>

        <div class="mb-3">
            <label for="harga_sewa" class="form-label">Harga Sewa</label>
            <input type="number" 
                   name="harga_sewa" 
                   id="harga_sewa" 
                   class="form-control" 
                   value="{{ old('harga_sewa', $kategori->harga_sewa ?? 0) }}" 
                   min="0">
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('kategori-barang.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
