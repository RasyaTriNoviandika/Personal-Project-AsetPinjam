@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-box me-2"></i>
        Detail Barang
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('barang.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>
            Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Barang</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td><strong>Nama Barang:</strong></td>
                        <td>{{ $barang->nama_barang }}</td>
                    </tr>
                    <tr>
                        <td><strong>Kategori:</strong></td>
                        <td>{{ $barang->kategori->nama_kategori ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Stok:</strong></td>
                        <td>{{ $barang->stok_tersedia }} / {{ $barang->stok_total }}</td>
                    </tr>
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td>
                            <span class="badge bg-{{ $barang->status == 'tersedia' ? 'success' : 'danger' }}">
                                {{ ucfirst($barang->status) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Deskripsi:</strong></td>
                        <td>{{ $barang->deskripsi ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        @if($barang->gambar)
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-image me-2"></i>Gambar Barang</h5>
            </div>
            <div class="card-body text-center">
                <img src="{{ asset('storage/' . $barang->gambar) }}" 
                     class="img-fluid rounded" style="max-height: 300px;">
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
