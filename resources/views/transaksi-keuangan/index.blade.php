{{-- resources/views/transaksi-keuangan/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-money-bill-wave me-2"></i>
        Transaksi Keuangan
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('transaksi-keuangan.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>
            Tambah Transaksi
        </a>
    </div>
</div>

{{-- Summary Cards --}}
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6>Total Pemasukan</h6>
                        <h4>Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</h4>
                    </div>
                    <i class="fas fa-arrow-up fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-danger">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6>Total Pengeluaran</h6>
                        <h4>Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h4>
                    </div>
                    <i class="fas fa-arrow-down fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6>Saldo</h6>
                        <h4>Rp {{ number_format($saldo, 0, ',', '.') }}</h4>
                    </div>
                    <i class="fas fa-wallet fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6>Transaksi Bulan Ini</h6>
                        <h4>{{ $transaksibulanIni }}</h4>
                    </div>
                    <i class="fas fa-calendar fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Filter --}}
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Jenis Transaksi</label>
                <select name="jenis" class="form-select">
                    <option value="">Semua Jenis</option>
                    <option value="masuk" {{ request('jenis') == 'masuk' ? 'selected' : '' }}>Pemasukan</option>
                    <option value="keluar" {{ request('jenis') == 'keluar' ? 'selected' : '' }}>Pengeluaran</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" class="form-control" value="{{ request('tanggal_mulai') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Tanggal Selesai</label>
                <input type="date" name="tanggal_selesai" class="form-control" value="{{ request('tanggal_selesai') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-1"></i>Filter
                    </button>
                    <a href="{{ route('transaksi-keuangan.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Tabel Transaksi --}}
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Kode Transaksi</th>
                        <th>Jenis</th>
                        <th>Keterangan</th>
                        <th>Jumlah</th>
                        <th>Metode Pembayaran</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksi as $item)
                    <tr>
                        <td>{{ $loop->iteration + ($transaksi->currentPage() - 1) * $transaksi->perPage() }}</td>
                        <td>{{ $item->tanggal->format('d/m/Y H:i') }}</td>
                        <td><span class="badge bg-secondary">{{ $item->kode_transaksi }}</span></td>
                        <td>
                            @if($item->jenis == 'masuk')
                                <span class="badge bg-success"><i class="fas fa-arrow-up me-1"></i>Masuk</span>
                            @else
                                <span class="badge bg-danger"><i class="fas fa-arrow-down me-1"></i>Keluar</span>
                            @endif
                        </td>
                        <td>{{ $item->keterangan }}</td>
                        <td>
                            <strong class="text-{{ $item->jenis == 'masuk' ? 'success' : 'danger' }}">
                                {{ $item->jenis == 'masuk' ? '+' : '-' }}Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                            </strong>
                        </td>
                        <td>{{ ucfirst($item->metode_pembayaran) }}</td>
                        <td>
                            <span class="badge bg-{{ $item->status == 'berhasil' ? 'success' : ($item->status == 'pending' ? 'warning' : 'danger') }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('transaksi-keuangan.show', $item) }}" class="btn btn-sm btn-info" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($item->status != 'berhasil')
                                <a href="{{ route('transaksi-keuangan.edit', $item) }}" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('transaksi-keuangan.destroy', $item) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" 
                                            onclick="return confirm('Yakin ingin menghapus?')" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">Tidak ada data transaksi</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $transaksi->appends(request()->query())->links() }}
    </div>
</div>
@endsection

{{-- resources/views/transaksi-keuangan/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-plus me-2"></i>
        Tambah Transaksi Keuangan
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('transaksi-keuangan.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>
            Kembali
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('transaksi-keuangan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="tanggal" class="form-label">Tanggal Transaksi *</label>
                        <input type="datetime-local" class="form-control @error('tanggal') is-invalid @enderror" 
                               id="tanggal" name="tanggal" value="{{ old('tanggal', date('Y-m-d\TH:i')) }}" required>
                        @error('tanggal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="jenis" class="form-label">Jenis Transaksi *</label>
                        <select class="form-select @error('jenis') is-invalid @enderror" 
                                id="jenis" name="jenis" required>
                            <option value="">Pilih Jenis</option>
                            <option value="masuk" {{ old('jenis') == 'masuk' ? 'selected' : '' }}>Pemasukan</option>
                            <option value="keluar" {{ old('jenis') == 'keluar' ? 'selected' : '' }}>Pengeluaran</option>
                        </select>
                        @error('jenis')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan *</label>
                        <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                                  id="keterangan" name="keterangan" rows="3" required>{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="jumlah" class="form-label">Jumlah *</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control @error('jumlah') is-invalid @enderror" 
                                   id="jumlah" name="jumlah" value="{{ old('jumlah') }}" min="0" required>
                        </div>
                        @error('jumlah')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="metode_pembayaran" class="form-label">Metode Pembayaran *</label>
                        <select class="form-select @error('metode_pembayaran') is-invalid @enderror" 
                                id="metode_pembayaran" name="metode_pembayaran" required>
                            <option value="">Pilih Metode</option>
                            <option value="tunai" {{ old('metode_pembayaran') == 'tunai' ? 'selected' : '' }}>Tunai</option>
                            <option value="transfer" {{ old('metode_pembayaran') == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                            <option value="ewallet" {{ old('metode_pembayaran') == 'ewallet' ? 'selected' : '' }}>E-Wallet</option>
                            <option value="kartu_kredit" {{ old('metode_pembayaran') == 'kartu_kredit' ? 'selected' : '' }}>Kartu Kredit</option>
                        </select>
                        @error('metode_pembayaran')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="kategori_transaksi" class="form-label">Kategori Transaksi</label>
                        <select class="form-select" id="kategori_transaksi" name="kategori_transaksi">
                            <option value="">Pilih Kategori</option>
                            <option value="sewa_barang" {{ old('kategori_transaksi') == 'sewa_barang' ? 'selected' : '' }}>Sewa Barang</option>
                            <option value="denda" {{ old('kategori_transaksi') == 'denda' ? 'selected' : '' }}>Denda</option>
                            <option value="maintenance" {{ old('kategori_transaksi') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            <option value="operasional" {{ old('kategori_transaksi') == 'operasional' ? 'selected' : '' }}>Operasional</option>
                            <option value="lainnya" {{ old('kategori_transaksi') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="peminjaman_id" class="form-label">Terkait Peminjaman</label>
                        <select class="form-select" id="peminjaman_id" name="peminjaman_id">
                            <option value="">Tidak terkait peminjaman</option>
                            @foreach($peminjaman as $p)
                                <option value="{{ $p->id }}" {{ old('peminjaman_id') == $p->id ? 'selected' : '' }}>
                                    {{ $p->kode_peminjaman }} - {{ $p->peminjam->nama_peminjam }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="bukti_transaksi" class="form-label">Bukti Transaksi</label>
                        <input type="file" class="form-control @error('bukti_transaksi') is-invalid @enderror" 
                               id="bukti_transaksi" name="bukti_transaksi" accept="image/*,application/pdf">
                        <small class="form-text text-muted">Format: JPG, PNG, PDF, max 5MB</small>
                        @error('bukti_transaksi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="berhasil" {{ old('status') == 'berhasil' ? 'selected' : '' }}>Berhasil</option>
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="gagal" {{ old('status') == 'gagal' ? 'selected' : '' }}>Gagal</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="text-end">
                <button type="reset" class="btn btn-secondary">Reset</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i>
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

{{-- resources/views/transaksi-keuangan/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-eye me-2"></i>
        Detail Transaksi Keuangan
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button onclick="window.print()" class="btn btn-info">
                <i class="fas fa-print me-1"></i>
                Print
            </button>
            @if($transaksi->status != 'berhasil')
                <a href="{{ route('transaksi-keuangan.edit', $transaksi) }}" class="btn btn-warning">
                    <i class="fas fa-edit me-1"></i>
                    Edit
                </a>
            @endif
        </div>
        <a href="{{ route('transaksi-keuangan.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>
            Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Transaksi</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Kode Transaksi:</strong></td>
                                <td>{{ $transaksi->kode_transaksi }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal:</strong></td>
                                <td>{{ $transaksi->tanggal->format('d F Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Jenis:</strong></td>
                                <td>
                                    @if($transaksi->jenis == 'masuk')
                                        <span class="badge bg-success"><i class="fas fa-arrow-up me-1"></i>Pemasukan</span>
                                    @else
                                        <span class="badge bg-danger"><i class="fas fa-arrow-down me-1"></i>Pengeluaran</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Kategori:</strong></td>
                                <td>{{ ucfirst(str_replace('_', ' ', $transaksi->kategori_transaksi)) }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Jumlah:</strong></td>
                                <td>
                                    <h4 class="text-{{ $transaksi->jenis == 'masuk' ? 'success' : 'danger' }}">
                                        {{ $transaksi->jenis == 'masuk' ? '+' : '-' }}Rp {{ number_format($transaksi->jumlah, 0, ',', '.') }}
                                    </h4>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Metode Pembayaran:</strong></td>
                                <td>{{ ucfirst(str_replace('_', ' ', $transaksi->metode_pembayaran)) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>
                                    <span class="badge bg-{{ $transaksi->status == 'berhasil' ? 'success' : ($transaksi->status == 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($transaksi->status) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Petugas:</strong></td>
                                <td>{{ $transaksi->user->name ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <div class="mt-3">
                    <strong>Keterangan:</strong>
                    <p class="mb-0">{{ $transaksi->keterangan }}</p>
                </div>

                @if($transaksi->peminjaman_id)
                <div class="mt-3">
                    <strong>Terkait Peminjaman:</strong>
                    <a href="{{ route('peminjaman.show', $transaksi->peminjaman) }}" class="btn btn-sm btn-outline-primary">
                        {{ $transaksi->peminjaman->kode_peminjaman }} - {{ $transaksi->peminjaman->peminjam->nama_peminjam }}
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        @if($transaksi->bukti_transaksi)
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-file me-2"></i>Bukti Transaksi</h5>
            </div>
            <div class="card-body text-center">
                @php
                    $extension = pathinfo($transaksi->bukti_transaksi, PATHINFO_EXTENSION);
                @endphp
                @if(in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']))
                    <img src="{{ asset('storage/' . $transaksi->bukti_transaksi) }}" 
                         class="img-fluid rounded" style="max-height: 300px;">
                @else
                    <div class="text-center">
                        <i class="fas fa-file-pdf fa-3x text-danger mb-2"></i>
                        <br>
                        <a href="{{ asset('storage/' . $transaksi->bukti_transaksi) }}" 
                           target="_blank" class="btn btn-outline-primary">
                            <i class="fas fa-download me-1"></i>
                            Download Bukti
                        </a>
                    </div>
                @endif
            </div>
        </div>
        @endif

        {{-- Quick Actions --}}
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Aksi Cepat</h5>
            </div>
            <div class="card-body">
                @if($transaksi->status == 'pending')
                <form action="{{ route('transaksi-keuangan.update-status', $transaksi) }}" method="POST" class="mb-2">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="berhasil">
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fas fa-check me-1"></i>
                        Konfirmasi Berhasil
                    </button>
                </form>
                <form action="{{ route('transaksi-keuangan.update-status', $transaksi) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="gagal">
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="fas fa-times me-1"></i>
                        Tandai Gagal
                    </button>
                </form>
                @else
                <p class="text-muted text-center">Tidak ada aksi tersedia</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection