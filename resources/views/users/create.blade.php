@extends('layouts.app')

@section('title', 'Tambah User')

@section('content')
<div class="container">
    <h1 class="mb-4">Tambah User</h1>

    <form action="{{ route('users.store') }}" method="POST">
        @include('users._form')

       <div class="d-grid gap-2 d-md-flex justify-content-md-between">
    <a href="{{ route('users.index') }}" class="btn btn-secondary">Kembali</a>
    <button type="submit" class="btn btn-success">Tambah User</button>
</div>

    </form>
</div>
@endsection
