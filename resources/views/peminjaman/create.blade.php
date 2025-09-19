@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-plus me-2"></i>Tambah Peminjaman</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('peminjaman.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Kembali
        </a>
    </div>
</div>

<form action="{{ route('peminjaman.store') }}" method="POST" id="formPeminjaman">
    @csrf

    {{-- Informasi Peminjam --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Peminjam</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="peminjam_id" class="form-label">Peminjam *</label>
                    <select name="peminjam_id" class="form-select @error('peminjam_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Peminjam --</option>
                        @foreach($peminjam as $p)
                            <option value="{{ $p->id }}" {{ old('peminjam_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->nama_peminjam }} - {{ $p->email }}
                            </option>
                        @endforeach
                    </select>
                    @error('peminjam_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label for="tanggal_pinjam" class="form-label">Tanggal Pinjam *</label>
                    <input type="date" class="form-control @error('tanggal_pinjam') is-invalid @enderror" 
                           id="tanggal_pinjam" name="tanggal_pinjam" 
                           value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" 
                           min="{{ date('Y-m-d') }}" required>
                    @error('tanggal_pinjam')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label for="tanggal_kembali_rencana" class="form-label">Tanggal Kembali Rencana *</label>
                    <input type="date" class="form-control @error('tanggal_kembali_rencana') is-invalid @enderror" 
                           id="tanggal_kembali_rencana" name="tanggal_kembali_rencana" 
                           value="{{ old('tanggal_kembali_rencana') }}" required>
                    @error('tanggal_kembali_rencana')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label for="catatan" class="form-label">Catatan</label>
                    <textarea class="form-control" id="catatan" name="catatan" rows="2">{{ old('catatan') }}</textarea>
                </div>
            </div>
        </div>
    </div>

    {{-- Daftar Barang --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-boxes me-2"></i>Daftar Barang</h5>
        </div>
        <div class="card-body">
            <div id="daftarBarang">
                <div class="row g-3 barang-item" data-index="0">
                    <div class="col-md-7">
                        <label class="form-label">Barang *</label>
                        <select class="form-select barang-select" name="barang[0][barang_id]" required>
                            <option value="">Pilih Barang</option>
                            @foreach($barang as $b)
                                <option value="{{ $b->id }}" data-stok="{{ $b->stok_tersedia }}">
                                    {{ $b->nama_barang }} (Stok: {{ $b->stok_tersedia }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Jumlah *</label>
                        <input type="number" class="form-control jumlah-input" name="barang[0][jumlah]" min="1" required>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Submit --}}
    <div class="text-end">
        {{-- <button type="reset" class="btn btn-secondary">Reset</button> --}}
       <button type="button" id="btnSubmit" class="btn btn-primary">
    <i class="fas fa-save me-1"></i>Simpan Peminjaman
</button>

    </div>
</form>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validasi stok saat user ubah jumlah
    document.addEventListener('change', function(e){
        if(e.target.classList.contains('jumlah-input')){
            const select = e.target.closest('.barang-item').querySelector('.barang-select');
            const stok = parseInt(select.options[select.selectedIndex].dataset.stok || 0);
            if(parseInt(e.target.value) > stok){
                alert('Stok tidak mencukupi! Tersedia: '+stok);
                e.target.value = stok;
            }
        }
    });
});
</script>
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tombol simpan
    document.getElementById('btnSubmit').addEventListener('click', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Konfirmasi',
            text: "Apakah data peminjaman sudah benar?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Simpan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('formPeminjaman').submit();
            }
        });
    });

    // Validasi stok saat user ubah jumlah
    document.addEventListener('change', function(e){
        if(e.target.classList.contains('jumlah-input')){
            const select = e.target.closest('.barang-item').querySelector('.barang-select');
            const stok = parseInt(select.options[select.selectedIndex].dataset.stok || 0);
            if(parseInt(e.target.value) > stok){
                Swal.fire('Stok tidak mencukupi!', 'Tersedia hanya '+stok+' item.', 'warning');
                e.target.value = stok;
            }
        }
    });
});
</script>
@endsection

@endsection
