@extends('layouts.app')

@section('title', 'Dashboard User')

@section('content')
<div class="container">
    <h1>Dashboard User</h1>
    <p>Total Peminjaman: {{ $totalPeminjaman }}</p>
    <p>Peminjaman Aktif: {{ $peminjamanAktif }}</p>
    <p>Peminjaman Selesai: {{ $peminjamanSelesai }}</p>
    <p>Peminjaman Terlambat: {{ $peminjamanTerlambat }}</p>

    <h3>Riwayat Peminjaman</h3>
    <ul>
        @foreach($riwayatPeminjaman as $p)
            <li>{{ $p->id }} - {{ $p->status }}</li>
        @endforeach
    </ul>
</div>
@endsection
