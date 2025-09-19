@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header">
            <h4><i class="fas fa-plus me-2"></i>Tambah Peminjaman</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('peminjaman.store') }}" method="POST">
                @csrf
                <div class="row">
                    {{-- Kolom Kiri: Peminjam - -}}
                    <div class="col-lg-6 mb-3">
                        <div class="card h-100">
                            <div class="card-header">Peminjam</div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="peminjam_id" class="form-label">Pilih Peminjam</label>
                                    <select name="peminjam_id" id="peminjam_id" class="form-select">
                                        <option value="">-- Pilih dari list --</option>
                                        @foreach($peminjam as $p)
                                            <option value="{{ $p->id }}" {{ old('peminjam_id') == $p->id ? 'selected' : '' }}>
                                                {{ $p->name }} - {{ $p->email }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <p class="text-center">-- atau tambah peminjam baru --</p>

                                <div class="mb-3">
                                    <label for="new_peminjam_name" class="form-label">Nama Peminjam Baru</label>
                                    <input type="text" class="form-control" name="new_peminjam_name" value="{{ old('new_peminjam_name') }}">
                                </div>

                                <div class="mb-3">
                                    <label for="new_peminjam_email" class="form-label">Email Peminjam Baru</label>
                                    <input type="email" class="form-control" name="new_peminjam_email" value="{{ old('new_peminjam_email') }}">
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="tanggal_pinjam" class="form-label">Tanggal Pinjam</label>
                                        <input type="date" name="tanggal_pinjam" class="form-control" value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="tanggal_kembali_rencana" class="form-label">Tanggal Kembali Rencana</label>
                                        <input type="date" name="tanggal_kembali_rencana" class="form-control" value="{{ old('tanggal_kembali_rencana') }}" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="catatan" class="form-label">Catatan</label>
                                    <textarea name="catatan" class="form-control">{{ old('catatan') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Kolom Kanan: Daftar Barang --}}
                    <div class="col-lg-6 mb-3">
                        <div class="card h-100">
                            <div class="card-header">Daftar Barang</div>
                            <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                                @foreach($barang as $b)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" value="{{ $b->id }}" name="barang[{{ $b->id }}][barang_id]" id="barang_{{ $b->id }}">
                                        <label class="form-check-label" for="barang_{{ $b->id }}">
                                            {{ $b->nama_barang }} (Stok: {{ $b->stok_tersedia }})
                                        </label>
                                        <input type="number" name="barang[{{ $b->id }}][jumlah]" class="form-control d-inline w-auto ms-2" min="1" value="1" placeholder="Jumlah">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-end mt-3">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan Peminjaman</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
