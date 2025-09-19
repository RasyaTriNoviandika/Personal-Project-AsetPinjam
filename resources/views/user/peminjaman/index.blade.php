@extends('layouts.user')

@section('title', 'Peminjaman Saya')

@section('content')
<div class="container">
    <h1 class="h4 mb-4 text-primary">
        <i class="fas fa-box me-2"></i> Peminjaman Saya
    </h1>

    {{-- Tombol untuk ajukan peminjaman baru --}}
    <div class="mb-3">
        <a href="{{ route('user.peminjaman.create') }}" class="btn btn-success">
            <i class="fas fa-plus-circle me-1"></i> Ajukan Peminjaman
        </a>
    </div>

    {{-- Daftar peminjaman user --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-striped table-hover align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Kembali</th>
                        <th>Status</th>
                        <th>Total Biaya</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peminjamans as $peminjaman)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $peminjaman->tanggal_pinjam->format('d M Y') }}</td>
                            <td>
                                @if($peminjaman->tanggal_kembali)
                                    {{ $peminjaman->tanggal_kembali->format('d M Y') }}
                                @else
                                    <span class="text-muted">Belum kembali</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $peminjaman->status == 'dipinjam' ? 'warning' : 'success' }}">
                                    {{ ucfirst($peminjaman->status) }}
                                </span>
                            </td>
                            <td>Rp {{ number_format($peminjaman->total_bayar, 0, ',', '.') }}</td>
                            <td>
                                <a href="{{ route('user.peminjaman.show', $peminjaman->id) }}" 
                                   class="btn btn-sm btn-info">
                                   <i class="fas fa-eye"></i>
                                </a>

                                @if($peminjaman->status == 'dipinjam')
                                    <form action="{{ route('user.peminjaman.kembalikan', $peminjaman->id) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">
                                            <i class="fas fa-undo"></i> Kembalikan
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                Belum ada peminjaman.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
