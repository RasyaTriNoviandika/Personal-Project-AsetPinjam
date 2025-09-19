@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit User</h1>

    <form action="{{ route('users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Nama --}}
        <div class="mb-3">
            <label for="name" class="form-label">Nama</label>
            <input type="text" 
                   name="name" 
                   id="name" 
                   class="form-control @error('name') is-invalid @enderror" 
                   value="{{ old('name', $user->name) }}" 
                   required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Email --}}
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" 
                   name="email" 
                   id="email" 
                   class="form-control @error('email') is-invalid @enderror" 
                   value="{{ old('email', $user->email) }}" 
                   required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Password (opsional) --}}
        <div class="mb-3">
            <label for="password" class="form-label">Password Baru (Opsional)</label>
            <input type="password" 
                   name="password" 
                   id="password" 
                   class="form-control @error('password') is-invalid @enderror">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Konfirmasi Password --}}
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
            <input type="password" 
                   name="password_confirmation" 
                   id="password_confirmation" 
                   class="form-control">
        </div>

        {{-- Role --}}
        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select name="role" id="role" class="form-select" required>
                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>User</option>
            </select>
        </div>

        {{-- Status --}}
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-select" required>
                <option value="active" {{ old('status', $user->status) === 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="inactive" {{ old('status', $user->status) === 'inactive' ? 'selected' : '' }}>Non Aktif</option>
            </select>
        </div>

       {{-- Tombol --}}
<div class="d-grid gap-2 d-md-flex justify-content-md-between">
    <a href="{{ route('users.index') }}" class="btn btn-secondary">Kembali</a>
    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
</div>

    </form>
</div>
@endsection
