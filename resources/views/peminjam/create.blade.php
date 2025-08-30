@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-user-plus me-2"></i>
        Tambah Peminjam
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('peminjam.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>
            Kembali
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('peminjam.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="nama_peminjam" class="form-label">Nama Lengkap *</label>
                        <input type="text" class="form-control @error('nama_peminjam') is-invalid @enderror" 
                               id="nama_peminjam" name="nama_peminjam" value="{{ old('nama_peminjam') }}" required>
                        @error('nama_peminjam')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="no_telepon" class="form-label">No. Telepon *</label>
                        <input type="text" class="form-control @error('no_telepon') is-invalid @enderror" 
                               id="no_telepon" name="no_telepon" value="{{ old('no_telepon') }}" required>
                        <small class="form-text text-muted">Format: 08xxxxxxxxxx</small>
                        @error('no_telepon')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="jenis_peminjam" class="form-label">Jenis Peminjam *</label>
                        <select class="form-select @error('jenis_peminjam') is-invalid @enderror" 
                                id="jenis_peminjam" name="jenis_peminjam" required>
                            <option value="">Pilih Jenis Peminjam</option>
                            <option value="individu" {{ old('jenis_peminjam') == 'individu' ? 'selected' : '' }}>Individu</option>
                            <option value="organisasi" {{ old('jenis_peminjam') == 'organisasi' ? 'selected' : '' }}>Organisasi</option>
                            <option value="perusahaan" {{ old('jenis_peminjam') == 'perusahaan' ? 'selected' : '' }}>Perusahaan</option>
                        </select>
                        @error('jenis_peminjam')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat *</label>
                        <textarea class="form-control @error('alamat') is-invalid @enderror" 
                                  id="alamat" name="alamat" rows="4" required>{{ old('alamat') }}</textarea>
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="no_identitas" class="form-label">No. Identitas *</label>
                        <input type="text" class="form-control @error('no_identitas') is-invalid @enderror" 
                               id="no_identitas" name="no_identitas" value="{{ old('no_identitas') }}" required>
                        <small class="form-text text-muted">KTP/SIM/Passport</small>
                        @error('no_identitas')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Non Aktif</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="text-end">
                <button type="reset" class="btn btn-secondary">Reset</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i>
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection