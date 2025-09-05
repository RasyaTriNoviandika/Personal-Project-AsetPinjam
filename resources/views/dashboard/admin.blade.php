@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container">
    <h1>Dashboard Admin</h1>
    <p>Total Barang: {{ $totalBarang }}</p>
    <p>Total Peminjam: {{ $totalPeminjam }}</p>
    <p>Total Peminjaman Aktif: {{ $totalPeminjamanAktif }}</p>
    <p>Total User: {{ $totalUsers }}</p>
</div>
@endsection
