
@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-undo me-2"></i>
        Proses Pengembalian
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('peminjaman.show', $peminjaman) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>
            Kembali
        </a>
    </div>
</div>

<form action="{{ route('peminjaman.proses-kembali', $peminjaman) }}" method="POST" id="formPengembalian">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-lg-8">
            {{-- Info Peminjaman --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Peminjaman</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Kode:</strong> {{ $peminjaman->kode_peminjaman }}</p>
                            <p><strong>Peminjam:</strong> {{ $peminjaman->peminjam->nama_peminjam }}</p>
                            <p><strong>Tanggal Pinjam:</strong> {{ $peminjaman->tanggal_pinjam->format('d F Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Tanggal Kembali Rencana:</strong> {{ $peminjaman->tanggal_kembali_rencana->format('d F Y') }}</p>
                            <p><strong>Status:</strong> 
                                <span class="badge bg-{{ $peminjaman->status == 'terlambat' ? 'danger' : 'primary' }}">
                                    {{ ucfirst($peminjaman->status) }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form Pengembalian --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-calendar me-2"></i>Data Pengembalian</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="tanggal_kembali_aktual" class="form-label">Tanggal Kembali Aktual *</label>
                        <input type="date" class="form-control @error('tanggal_kembali_aktual') is-invalid @enderror" 
                               id="tanggal_kembali_aktual" name="tanggal_kembali_aktual" 
                               value="{{ old('tanggal_kembali_aktual', date('Y-m-d')) }}" 
                               min="{{ $peminjaman->tanggal_pinjam->format('Y-m-d') }}" required>
                        @error('tanggal_kembali_aktual')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div id="info-keterlambatan" class="alert alert-info" style="display: none;">
                        <i class="fas fa-info-circle me-2"></i>
                        <span id="text-keterlambatan"></span>
                    </div>
                </div>
            </div>

            {{-- Detail Barang --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-boxes me-2"></i>Kondisi Barang Kembali</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Barang</th>
                                    <th>Jumlah</th>
                                    <th>Kondisi Pinjam</th>
                                    <th>Kondisi Kembali *</th>
                                    <th>Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($peminjaman->detailPeminjaman as $detail)
                                <tr>
                                    <td>
                                        <strong>{{ $detail->barang->nama_barang }}</strong><br>
                                        <small class="text-muted">{{ $detail->barang->kategori->nama_kategori ?? '' }}</small>
                                    </td>
                                    <td>{{ $detail->jumlah }}</td>
                                    <td>
                                        <span class="badge bg-{{ $detail->kondisi_pinjam == 'baik' ? 'success' : 'warning' }}">
                                            {{ ucfirst(str_replace('_', ' ', $detail->kondisi_pinjam)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <select class="form-select @error('detail.' . $detail->id . '.kondisi_kembali') is-invalid @enderror" 
                                                name="detail[{{ $detail->id }}][kondisi_kembali]" required>
                                            <option value="">Pilih Kondisi</option>
                                            <option value="baik" {{ old('detail.' . $detail->id . '.kondisi_kembali') == 'baik' ? 'selected' : '' }}>Baik</option>
                                            <option value="rusak_ringan" {{ old('detail.' . $detail->id . '.kondisi_kembali') == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                            <option value="rusak_berat" {{ old('detail.' . $detail->id . '.kondisi_kembali') == 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                                            <option value="hilang" {{ old('detail.' . $detail->id . '.kondisi_kembali') == 'hilang' ? 'selected' : '' }}>Hilang</option>
                                        </select>
                                        @error('detail.' . $detail->id . '.kondisi_kembali')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td>
                                        <textarea class="form-control" name="detail[{{ $detail->id }}][catatan]" 
                                                  rows="2" placeholder="Catatan tambahan">{{ old('detail.' . $detail->id . '.catatan') }}</textarea>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            {{-- Kalkulasi Denda --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-calculator me-2"></i>Kalkulasi Biaya</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td>Biaya Sewa:</td>
                            <td class="text-end">Rp {{ number_format($peminjaman->total_biaya_sewa, 0, ',', '.') }}</td>
                        </tr>
                        <tr id="row-denda" style="display: none;">
                            <td>Denda Keterlambatan:</td>
                            <td class="text-end text-danger" id="denda-amount">Rp 0</td>
                        </tr>
                        <tr class="border-top">
                            <td><strong>Total Bayar:</strong></td>
                            <td class="text-end"><strong id="total-bayar">Rp {{ number_format($peminjaman->total_biaya_sewa, 0, ',', '.') }}</strong></td>
                        </tr>
                    </table>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-check me-1"></i>
                            Proses Pengembalian
                        </button>
                    </div>
                </div>
            </div>

            {{-- Info Denda Per Barang --}}
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Denda per Barang/Hari</h6>
                </div>
                <div class="card-body">
                    @foreach($peminjaman->detailPeminjaman as $detail)
                    <div class="d-flex justify-content-between mb-2">
                        <span>{{ $detail->barang->nama_barang }}</span>
                        <span>Rp {{ number_format($detail->barang->denda_per_hari, 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tanggalKembaliInput = document.getElementById('tanggal_kembali_aktual');
    const infoKeterlambatan = document.getElementById('info-keterlambatan');
    const textKeterlambatan = document.getElementById('text-keterlambatan');
    const rowDenda = document.getElementById('row-denda');
    const dendaAmount = document.getElementById('denda-amount');
    const totalBayar = document.getElementById('total-bayar');

    const tanggalRencana = new Date('{{ $peminjaman->tanggal_kembali_rencana->format("Y-m-d") }}');
    const biayaSewa = {{ $peminjaman->total_biaya_sewa }};
    
    // Denda per barang per hari
    const dendaPerBarang = [
        @foreach($peminjaman->detailPeminjaman as $detail)
        {
            nama: '{{ $detail->barang->nama_barang }}',
            jumlah: {{ $detail->jumlah }},
            denda: {{ $detail->barang->denda_per_hari }}
        },
        @endforeach
    ];

    function hitungDenda() {
        const tanggalKembali = new Date(tanggalKembaliInput.value);
        
        if (tanggalKembali > tanggalRencana) {
            const hariTerlambat = Math.ceil((tanggalKembali - tanggalRencana) / (1000 * 60 * 60 * 24));
            
            let totalDenda = 0;
            dendaPerBarang.forEach(item => {
                totalDenda += item.denda * item.jumlah * hariTerlambat;
            });

            // Tampilkan info keterlambatan
            textKeterlambatan.textContent = `Terlambat ${hariTerlambat} hari. Total denda: Rp ${totalDenda.toLocaleString('id-ID')}`;
            infoKeterlambatan.style.display = 'block';
            infoKeterlambatan.className = 'alert alert-warning';
            
            // Tampilkan row denda
            rowDenda.style.display = 'table-row';
            dendaAmount.textContent = 'Rp ' + totalDenda.toLocaleString('id-ID');
            
            // Update total bayar
            const total = biayaSewa + totalDenda;
            totalBayar.innerHTML = '<strong>Rp ' + total.toLocaleString('id-ID') + '</strong>';
            
        } else {
            // Tidak terlambat
            textKeterlambatan.textContent = 'Tidak ada keterlambatan.';
            infoKeterlambatan.style.display = 'block';
            infoKeterlambatan.className = 'alert alert-success';
            
            // Sembunyikan row denda
            rowDenda.style.display = 'none';
            
            // Reset total bayar
            totalBayar.innerHTML = '<strong>Rp ' + biayaSewa.toLocaleString('id-ID') + '</strong>';
        }
    }

    // Event listener untuk tanggal kembali
    tanggalKembaliInput.addEventListener('change', hitungDenda);
    
    // Hitung denda saat pertama kali load
    if (tanggalKembaliInput.value) {
        hitungDenda();
    }
});
</script>
@endsection