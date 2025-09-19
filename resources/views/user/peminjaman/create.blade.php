@extends('layouts.user')

@section('title', 'Ajukan Peminjaman')

@section('content')
<div class="container">
    <h4 class="mb-4">📝 Ajukan Peminjaman</h4>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('user.peminjaman.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="tanggal_pinjam" class="form-label">Tanggal Pinjam</label>
                    <input type="date" name="tanggal_pinjam" id="tanggal_pinjam"
                        class="form-control @error('tanggal_pinjam') is-invalid @enderror"
                        value="{{ old('tanggal_pinjam') }}" required>
                    @error('tanggal_pinjam') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="tanggal_kembali_rencana" class="form-label">Tanggal Kembali</label>
                    <input type="date" name="tanggal_kembali_rencana" id="tanggal_kembali_rencana"
                        class="form-control @error('tanggal_kembali_rencana') is-invalid @enderror"
                        value="{{ old('tanggal_kembali_rencana') }}" required>
                    @error('tanggal_kembali_rencana') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Pilih Barang</label>
                    <div class="row">
                        @foreach($barang as $item)
                            <div class="col-md-4">
                                <div class="form-check border rounded p-2 mb-2">
                                    <input type="checkbox" name="barang[{{ $item->id }}][barang_id]" value="{{ $item->id }}" class="form-check-input">
                                    <label class="form-check-label">
                                        {{ $item->nama_barang }} <br>
                                        <small class="text-muted">Stok: {{ $item->stok_tersedia }}</small>
                                    </label>
                                    <input type="number" name="barang[{{ $item->id }}][jumlah]" class="form-control mt-2" min="1" placeholder="Jumlah">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mb-3">
                    <label for="catatan" class="form-label">Catatan (opsional)</label>
                    <textarea name="catatan" id="catatan" rows="3" class="form-control">{{ old('catatan') }}</textarea>
                </div>

                <button type="submit" class="btn btn-success">Ajukan Peminjaman</button>
                <a href="{{ route('user.peminjaman.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection
