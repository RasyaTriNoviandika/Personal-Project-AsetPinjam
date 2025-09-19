@extends('layouts.user')

@section('title', 'Cari Barang - User Panel')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-1 text-primary">
                <i class="fas fa-search me-2"></i> Cari Barang
            </h1>
            <p class="text-muted">Temukan barang yang tersedia untuk dipinjam</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('user.peminjaman.create') }}" class="btn btn-success">
                <i class="fas fa-plus-circle me-2"></i> Ajukan Peminjaman
            </a>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-filter me-2"></i>Filter & Pencarian
                    </h6>
                </div>
                <div class="col-md-6 text-end">
                    <small class="text-muted">{{ $barang->total() }} barang ditemukan</small>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('user.barang.index') }}">
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="search" class="form-label">Kata Kunci</label>
                            <input type="text" 
                                   name="search" 
                                   id="search" 
                                   class="form-control" 
                                   placeholder="Nama barang, merek, deskripsi..." 
                                   value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="kategori" class="form-label">Kategori</label>
                            <select name="kategori" id="kategori" class="form-select">
                                <option value="">Semua Kategori</option>
                                @foreach($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}" {{ request('kategori') == $kategori->id ? 'selected' : '' }}>
                                        {{ $kategori->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="sort" class="form-label">Urutkan</label>
                            <select name="sort" id="sort" class="form-select">
                                <option value="nama_barang" {{ request('sort') == 'nama_barang' ? 'selected' : '' }}>Nama</option>
                                <option value="merek" {{ request('sort') == 'merek' ? 'selected' : '' }}>Merek</option>
                                <option value="harga_sewa_per_hari" {{ request('sort') == 'harga_sewa_per_hari' ? 'selected' : '' }}>Harga</option>
                                <option value="stok_tersedia" {{ request('sort') == 'stok_tersedia' ? 'selected' : '' }}>Stok</option>
                                <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Terbaru</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-1"></i> Cari
                                </button>
                                <a href="{{ route('user.barang.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-refresh me-1"></i> Reset
                                </a>
                                <div class="form-check align-self-center ms-2">
                                    <input type="checkbox" 
                                           name="tersedia" 
                                           value="1" 
                                           class="form-check-input" 
                                           id="tersedia" 
                                           {{ request('tersedia') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="tersedia">
                                        <small>Hanya yang tersedia</small>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Items Grid -->
    <div class="row">
        @forelse($barang as $item)
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="card h-100 shadow-sm barang-card">
                <div class="position-relative">
                    @if($item->gambar)
                        <img src="{{ asset('storage/' . $item->gambar) }}" 
                             class="card-img-top" 
                             style="height: 200px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                             style="height: 200px;">
                            <i class="fas fa-box fa-3x text-muted"></i>
                        </div>
                    @endif
                    
                    <!-- Stock Badge -->
                    @if($item->stok_tersedia > 0)
                        <span class="position-absolute top-0 end-0 badge bg-success m-2">
                            {{ $item->stok_tersedia }} tersedia
                        </span>
                    @else
                        <span class="position-absolute top-0 end-0 badge bg-danger m-2">
                            Habis
                        </span>
                    @endif
                    
                    <!-- Category Badge -->
                    @if($item->kategori)
                        <span class="position-absolute top-0 start-0 badge bg-primary m-2">
                            {{ $item->kategori->nama }}
                        </span>
                    @endif
                </div>
                
                <div class="card-body d-flex flex-column">
                    <h6 class="card-title text-primary mb-2">{{ $item->nama_barang }}</h6>
                    
                    @if($item->merek)
                        <p class="card-text text-muted small mb-2">
                            <i class="fas fa-tag me-1"></i>{{ $item->merek }}
                        </p>
                    @endif
                    
                    @if($item->deskripsi)
                        <p class="card-text small text-muted mb-3" style="max-height: 60px; overflow: hidden;">
                            {{ Str::limit($item->deskripsi, 80) }}
                        </p>
                    @endif
                    
                    <!-- Price -->
                    @if($item->harga_sewa_per_hari)
                        <div class="mb-3">
                            <span class="h6 text-success mb-0">
                                Rp {{ number_format($item->harga_sewa_per_hari, 0, ',', '.') }}
                            </span>
                            <small class="text-muted">/hari</small>
                        </div>
                    @endif
                    
                    <div class="mt-auto">
                        <div class="d-grid gap-2">
                            <a href="{{ route('user.barang.show', $item->id) }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-eye me-1"></i> Detail
                            </a>
                            @if($item->stok_tersedia > 0)
                                <a href="{{ route('user.peminjaman.create') }}?barang={{ $item->id }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-plus me-1"></i> Pinjam
                                </a>
                            @else
                                <button class="btn btn-secondary btn-sm" disabled>
                                    <i class="fas fa-times me-1"></i> Tidak Tersedia
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body text-center py-5">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">Barang tidak ditemukan</h4>
                    <p class="text-muted">Coba ubah kata kunci pencarian atau filter yang digunakan</p>
                    <a href="{{ route('user.barang.index') }}" class="btn btn-primary">
                        <i class="fas fa-refresh me-2"></i>Reset Pencarian
                    </a>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($barang->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $barang->withQueryString()->links() }}
        </div>
    @endif
</div>

<style>
.barang-card {
    transition: transform 0.2s, box-shadow 0.2s;
    cursor: pointer;
}

.barang-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.15) !important;
}

.card-img-top {
    transition: all 0.3s ease;
}

.barang-card:hover .card-img-top {
    transform: scale(1.05);
}

.font-weight-bold {
    font-weight: 700;
}
</style>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Quick search functionality
    const searchInput = document.getElementById('search');
    let searchTimeout;
    
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            if (this.value.length >= 3 || this.value.length === 0) {
                document.querySelector('form').submit();
            }
        }, 500);
    });
    
    // Auto submit on filter change
    document.getElementById('kategori').addEventListener('change', function() {
        document.querySelector('form').submit();
    });
    
    document.getElementById('sort').addEventListener('change', function() {
        document.querySelector('form').submit();
    });
    
    document.getElementById('tersedia').addEventListener('change', function() {
        document.querySelector('form').submit();
    });
});
</script>
@endpush