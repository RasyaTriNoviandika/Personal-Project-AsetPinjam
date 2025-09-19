@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Daftar User</h1>

    <a href="{{ route('users.create') }}" class="btn btn-success mb-3">Tambah User</a>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Login Terakhir</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $key => $user)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ ucfirst($user->role) }}</td>
                    <td>
                        @if($user->status === 'active')
                        <span class="badge bg-success">Aktif</span>
                        @else
                        <span class="badge bg-secondary">Non Aktif</span>
                        @endif
                    </td>
                    <td>{{ $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->format('d-m-Y H:i') : '-' }}</td>
                    <td>
                        <a href="{{ route('users.show', $user->id) }}" class="btn btn-info btn-sm mb-1">Detail</a>
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary btn-sm mb-1">Edit</a>
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin hapus user ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm mb-1">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">Belum ada user</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection