@extends('layouts.app')

@section('title', 'Pengaturan Akun')

@section('content')
<div class="container">
    <h4 class="mb-3">Ubah Password</h4>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('settings.updateAccount') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="password" class="form-label">Password Baru</label>
                    <input type="password" name="password" id="password" 
                           class="form-control @error('password') is-invalid @enderror" required>
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" 
                           class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Simpan
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
