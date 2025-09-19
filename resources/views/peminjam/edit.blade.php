@extends('layouts.app')

@section('title', 'Edit Peminjam')

@section('content')
<div class="container">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">
            <i class="fas fa-user-edit text-warning me-2"></i>
            Edit Peminjam
        </h4>
        <a href="{{ route('peminjam.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <!-- Card -->
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-body">
            <form action="{{ route('peminjam.update', $peminjam->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <!-- Nama -->
                    <div class="col-md-6">
                        <label for="nama_peminjam" class="form-label fw-semibold">Nama Peminjam</label>
                        <input type="text" name="nama_peminjam" id="nama_peminjam"
                               class="form-control @error('nama_peminjam') is-invalid @enderror"
                               value="{{ old('nama_peminjam', $peminjam->nama_peminjam) }}" required>
                        @error('nama_peminjam')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="col-md-6">
                        <label for="email" class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" id="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', $peminjam->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- No Telepon -->
                    <div class="col-md-6">
                        <label for="no_telepon" class="form-label fw-semibold">No. Telepon</label>
                        <input type="text" name="no_telepon" id="no_telepon"
                               class="form-control @error('no_telepon') is-invalid @enderror"
                               value="{{ old('no_telepon', $peminjam->no_telepon) }}" required>
                        @error('no_telepon')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Alamat -->
                    <div class="col-md-6">
                        <label for="alamat" class="form-label fw-semibold">Alamat</label>
                        <textarea name="alamat" id="alamat"
                                  class="form-control @error('alamat') is-invalid @enderror"
                                  rows="2" required>{{ old('alamat', $peminjam->alamat) }}</textarea>
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Jenis Peminjam -->
                    <div class="col-md-6">
                        <label for="jenis_peminjam" class="form-label fw-semibold">Jenis Peminjam</label>
                        <select name="jenis_peminjam" id="jenis_peminjam"
                                class="form-select @error('jenis_peminjam') is-invalid @enderror" required>
                            <option value="">-- Pilih Jenis --</option>
                            <option value="individu" {{ old('jenis_peminjam', $peminjam->jenis_peminjam) == 'individu' ? 'selected' : '' }}>Individu</option>
                            <option value="organisasi" {{ old('jenis_peminjam', $peminjam->jenis_peminjam) == 'organisasi' ? 'selected' : '' }}>Organisasi</option>
                            <option value="perusahaan" {{ old('jenis_peminjam', $peminjam->jenis_peminjam) == 'perusahaan' ? 'selected' : '' }}>Perusahaan</option>
                        </select>
                        @error('jenis_peminjam')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- No Identitas -->
                    <div class="col-md-6">
                        <label for="no_identitas" class="form-label fw-semibold">No. Identitas</label>
                        <input type="text" name="no_identitas" id="no_identitas"
                               class="form-control @error('no_identitas') is-invalid @enderror"
                               value="{{ old('no_identitas', $peminjam->no_identitas) }}" required>
                        @error('no_identitas')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Tombol -->
                <div class="d-flex justify-content-end gap-2 mt-4">
                    {{-- <button type="reset" class="btn btn-secondary">
                        <i class="fas fa-undo me-1"></i> Reset
                    </button> --}}
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
