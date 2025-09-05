@extends('layouts.app')

@section('title', 'Dashboard Operator')

@section('content')
<div class="container">
    <h1>Dashboard Operator</h1>
    <p>Total Barang: {{ $totalBarang }}</p>
    <p>Total Peminjam: {{ $totalPeminjam }}</p>
    <p>Total Peminjaman Aktif: {{ $totalPeminjamanAktif }}</p>
    <p>Peminjaman Hari Ini: {{ $peminjamanHariIni }}</p>
    <p>Pengembalian Hari Ini: {{ $pengembalianHariIni }}</p>
</div>
@endsection
