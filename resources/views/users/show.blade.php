@extends('layouts.app')

@section('title', 'Detail User')

@section('content')
<div class="container">
    <h1 class="mb-4">Detail User</h1>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $user->name }}</h5>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Role:</strong> 
                @if($user->role === 'admin')
                    <span class="badge bg-primary">Administrator</span>
                @else
                    <span class="badge bg-info">User</span>
                @endif
            </p>
            <p><strong>Status:</strong> 
                @if($user->status === 'active')
                    <span class="badge bg-success">Aktif</span>
                @else
                    <span class="badge bg-secondary">Non Aktif</span>
                @endif
            </p>
            <p><strong>Login Terakhir:</strong> 
                {{ $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->format('d-m-Y H:i') : '-' }}
            </p>
            <p><strong>Terdaftar Sejak:</strong> {{ $user->created_at->format('d-m-Y H:i') }}</p>
            <p><strong>Terakhir Diupdate:</strong> {{ $user->updated_at->format('d-m-Y H:i') }}</p>
        </div>
    </div>
<div class="mt-3 d-grid gap-2 d-md-flex">
    <a href="{{ route('users.index') }}" class="btn btn-secondary">Kembali</a>
    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary">Edit</a>
    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline d-md-block" onsubmit="return confirm('Yakin ingin hapus user ini?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger w-100 w-md-auto mt-2 mt-md-0">Hapus</button>
    </form>
</div>
</div>
@endsection
