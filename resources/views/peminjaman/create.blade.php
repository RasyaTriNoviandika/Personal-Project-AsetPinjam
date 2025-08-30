@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-hand-holding fa-fw me-2"></i>Tambah Peminjaman</h1>
    <a href="{{ route('peminjaman.index') }}" class="btn btn-secondary">Kembali</a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('peminjaman.store') }}" method="POST">
            @csrf

            {{-- Peminjam --}}
            <div class="mb-3">
                <label for="peminjam_id" class="form-label">Peminjam</label>
                <select name="peminjam_id" id="peminjam_id" class="form-control select2" required>
                    <option value="">-- Pilih Peminjam --</option>
                    @foreach($peminjam as $p)
                        <option value="{{ $p->id }}">{{ $p->nama }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Barang --}}
            <div class="mb-3">
                <label for="barang_id" class="form-label">Barang</label>
                <select name="barang_id" id="barang_id" class="form-control select2" required>
                    <option value="">-- Pilih Barang --</option>
                    @foreach($barang as $b)
                        <option value="{{ $b->id }}">{{ $b->nama_barang }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Jumlah --}}
            <div class="mb-3">
                <label for="jumlah" class="form-label">Jumlah</label>
                <input type="number" name="jumlah" id="jumlah" class="form-control" min="1" required>
            </div>

            {{-- Tanggal Pinjam --}}
            <div class="mb-3">
                <label for="tanggal_pinjam" class="form-label">Tanggal Pinjam</label>
                <input type="date" name="tanggal_pinjam" id="tanggal_pinjam" class="form-control" required>
            </div>

            {{-- Tanggal Kembali --}}
            <div class="mb-3">
                <label for="tanggal_kembali" class="form-label">Tanggal Kembali</label>
                <input type="date" name="tanggal_kembali" id="tanggal_kembali" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('peminjaman.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection

@push('scripts')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- CSS Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- JS Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function () {
            // Aktifkan select2 dengan fitur search + ketik
            $('.select2').select2({
                placeholder: "Silakan pilih atau ketik...",
                allowClear: true,
                width: '100%'
            });
        });
    </script>
@endpush
