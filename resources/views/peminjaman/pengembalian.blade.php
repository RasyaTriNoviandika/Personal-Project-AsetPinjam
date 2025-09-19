@extends('layouts.app')

@section('title', 'Pengembalian Peminjaman')

@section('content')
<div class="container">
    <h4 class="mb-3">Proses Pengembalian</h4>

    {{-- Alert error --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="formPengembalian" action="{{ route('peminjaman.kembali', $peminjaman->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Tanggal kembali aktual --}}
        <div class="mb-3">
            <label for="tanggal_kembali_aktual" class="form-label fw-semibold">Tanggal Kembali Aktual</label>
            <input type="date" name="tanggal_kembali_aktual" id="tanggal_kembali_aktual" 
                   class="form-control" value="{{ old('tanggal_kembali_aktual', now()->toDateString()) }}" required>
        </div>

        <h5 class="mt-4">Detail Barang</h5>
        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>Nama Barang</th>
                    <th>Jumlah</th>
                    <th>Kondisi Kembali</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($peminjaman->detailPeminjaman as $detail)
                    <tr>
                        <td>{{ $detail->barang->nama_barang }}</td>
                        <td>{{ $detail->jumlah }}</td>
                        <td>
                            <select name="detail[{{ $detail->id }}][kondisi_kembali]" class="form-select" required>
                                <option value="baik">Baik</option>
                                <option value="rusak_ringan">Rusak Ringan</option>
                                <option value="rusak_berat">Rusak Berat</option>
                                <option value="hilang">Hilang</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="detail[{{ $detail->id }}][catatan]" 
                                   class="form-control" placeholder="Catatan (opsional)">
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-3">
            <a href="{{ route('peminjaman.index') }}" class="btn btn-secondary">Batal</a>
            <button type="button" id="btnSubmit" class="btn btn-success">Proses Pengembalian</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.getElementById("btnSubmit").addEventListener("click", function(e) {
    Swal.fire({
        title: 'Konfirmasi Pengembalian',
        text: "Apakah data sudah benar? Barang akan diproses sebagai dikembalikan.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#198754',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Proses!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById("formPengembalian").submit();
        }
    });
});
</script>
@endpush
