@extends('layouts.app')

@section('title', 'Edit Profil')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-edit me-2"></i> Edit Profil</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="text-center mb-3">
                    <img src="{{ $user->profile_photo ? asset('storage/' . $user->profile_photo) : 'https://via.placeholder.com/150' }}" 
                         alt="Foto Profil" class="rounded-circle mb-2" width="120" height="120">
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" name="name" value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="mb-3">
                    <label for="profile_photo" class="form-label">Foto Profil</label>
                    <input type="file" class="form-control" name="profile_photo" accept="image/*">
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('settings.profile') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
