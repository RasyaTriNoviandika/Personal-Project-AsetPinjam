@extends('layouts.app')

@section('title', 'Detail Peminjam')

@section('content')
<div class="container">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">
            <i class="fas fa-user text-primary me-2"></i>
            Detail Peminjam
        </h4>
        <a href="{{ route('peminjam.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <!-- Card -->
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-6">
                    <h6 class="text-muted">Nama Peminjam</h6>
                    <p class="fs-6 fw-semibold">{{ $peminjam->nama_peminjam }}</p>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted">Email</h6>
                    <p class="fs-6 fw-semibold">{{ $peminjam->email }}</p>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted">No. Telepon</h6>
                    <p class="fs-6 fw-semibold">{{ $peminjam->no_telepon }}</p>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted">Alamat</h6>
                    <p class="fs-6 fw-semibold">{{ $peminjam->alamat }}</p>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted">Jenis Peminjam</h6>
                    <span class="badge 
                        @if($peminjam->jenis_peminjam == 'individu') bg-primary 
                        @elseif($peminjam->jenis_peminjam == 'organisasi') bg-success 
                        @else bg-warning text-dark @endif">
                        {{ ucfirst($peminjam->jenis_peminjam) }}
                    </span>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted">No. Identitas</h6>
                    <p class="fs-6 fw-semibold">{{ $peminjam->no_identitas }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tombol Aksi -->
    <div class="d-flex gap-2 mt-4">
        <a href="{{ route('peminjam.edit', $peminjam->id) }}" class="btn btn-warning">
            <i class="fas fa-edit me-1"></i> Edit
        </a>
        <form action="{{ route('peminjam.destroy', $peminjam->id) }}" method="POST" id="deleteForm">
            @csrf
            @method('DELETE')
            <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                <i class="fas fa-trash-alt me-1"></i> Hapus
            </button>
        </form>
    </div>
</div>

<!-- SweetAlert untuk konfirmasi hapus -->
<script>
function confirmDelete() {
    Swal.fire({
        title: "Apakah Anda yakin?",
        text: "Data peminjam akan dihapus permanen!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "Ya, hapus!"
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById("deleteForm").submit();
        }
    });
}
</script>
@endsection
