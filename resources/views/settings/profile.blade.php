@extends('layouts.app')

@section('title', 'Profil')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-body text-center">

            <!-- Gambar Profil -->
            <img src="{{ $user->photo ? asset('uploads/profile/'.$user->photo) : 'https://via.placeholder.com/150' }}" 
                 class="rounded-circle mb-3" width="150" height="150" alt="Foto Profil">

            <!-- Nama -->
            <h4 class="mb-1">{{ $user->name }}</h4>

            <!-- Email -->
            <p class="mb-1">{{ $user->email }}</p>

            <!-- Role -->
            <span class="badge bg-primary">{{ ucfirst($user->role) }}</span>

            <!-- Link ke Settings -->
            <div class="mt-3">
                {{-- <a href="{{ route('settings.profile') }}" class="btn btn-secondary me-2">
                    <i class="fas fa-user-edit me-1"></i> Edit Profil
                </a> --}}
                <a href="{{ route('settings.account') }}" class="btn btn-warning">
                    <i class="fas fa-key me-1"></i> Ubah Password
                </a>
            </div>

        </div>
    </div>
</div>
@endsection
