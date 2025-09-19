@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Tambah Kategori Barang</h1>

    <form action="{{ route('kategori-barang.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="nama_kategori" class="form-label">Nama Kategori</label>
            <input type="text" name="nama_kategori" id="nama_kategori" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea name="deskripsi" id="deskripsi" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label for="jumlah_barang" class="form-label">Jumlah Barang</label>
            <input type="number" name="jumlah_barang" class="form-control" value="0">
        </div>

        <div class="mb-3">
            <label for="harga_sewa" class="form-label">Harga Sewa</label>
            <input type="number" name="harga_sewa" class="form-control" value="0">
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('kategori-barang.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Kalau validasi error
    @if ($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Gagal Menyimpan',
            html: `
                <ul style="text-align: left;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            `
        });
    @endif
</script>
@endpush
