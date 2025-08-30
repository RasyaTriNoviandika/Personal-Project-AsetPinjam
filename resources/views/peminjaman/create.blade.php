@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-plus me-2"></i>
        Tambah Peminjaman
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('peminjaman.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>
            Kembali
        </a>
    </div>
</div>

<form action="{{ route('peminjaman.store') }}" method="POST" id="formPeminjaman">
    @csrf
    
    {{-- Info Peminjaman --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Peminjaman</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="peminjam_id" class="form-label">Peminjam *</label>
                        <select class="form-select @error('peminjam_id') is-invalid @enderror" 
                                id="peminjam_id" name="peminjam_id" required>
                            <option value="">Pilih Peminjam</option>
                            @foreach($peminjam as $p)
                                <option value="{{ $p->id }}" {{ old('peminjam_id') == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama_peminjam }} - {{ $p->email }}
                                </option>
                            @endforeach
                        </select>
                        @error('peminjam_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="tanggal_pinjam" class="form-label">Tanggal Pinjam *</label>
                        <input type="date" class="form-control @error('tanggal_pinjam') is-invalid @enderror" 
                               id="tanggal_pinjam" name="tanggal_pinjam" 
                               value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" 
                               min="{{ date('Y-m-d') }}" required>
                        @error('tanggal_pinjam')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="tanggal_kembali_rencana" class="form-label">Tanggal Kembali Rencana *</label>
                        <input type="date" class="form-control @error('tanggal_kembali_rencana') is-invalid @enderror" 
                               id="tanggal_kembali_rencana" name="tanggal_kembali_rencana" 
                               value="{{ old('tanggal_kembali_rencana') }}" required>
                        @error('tanggal_kembali_rencana')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="catatan" class="form-label">Catatan</label>
                        <textarea class="form-control" id="catatan" name="catatan" rows="3">{{ old('catatan') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Daftar Barang --}}
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-boxes me-2"></i>Daftar Barang</h5>
            <button type="button" class="btn btn-sm btn-primary" onclick="tambahBarang()">
                <i class="fas fa-plus me-1"></i>Tambah Barang
            </button>
        </div>
        <div class="card-body">
            <div id="daftarBarang">
                <div class="row barang-item mb-3" data-index="0">
                    <div class="col-md-5">
                        <label class="form-label">Barang *</label>
                        <select class="form-select barang-select" name="barang[0][barang_id]" required>
                            <option value="">Pilih Barang</option>
                            @foreach($barang as $b)
                                <option value="{{ $b->id }}" data-harga="{{ $b->harga_sewa_per_hari }}" data-stok="{{ $b->stok_tersedia }}">
                                    {{ $b->nama_barang }} (Stok: {{ $b->stok_tersedia }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Jumlah *</label>
                        <input type="number" class="form-control jumlah-input" name="barang[0][jumlah]" min="1" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Subtotal</label>
                        <input type="text" class="form-control subtotal-display" readonly>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <div>
                            <button type="button" class="btn btn-sm btn-danger" onclick="hapusBarang(this)" style="display: none;">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-8"></div>
                <div class="col-md-4">
                    <div class="card bg-light">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <span>Durasi:</span>
                                <span id="durasi-display">0 hari</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span><strong>Total Biaya:</strong></span>
                                <span id="total-biaya"><strong>Rp 0</strong></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-end">
        <button type="reset" class="btn btn-secondary">Reset</button>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-1"></i>
            Simpan Peminjaman
        </button>
    </div>
</form>
@endsection

@section('scripts')
<script>
let barangIndex = 0;

function tambahBarang() {
    barangIndex++;
    const html = `
        <div class="row barang-item mb-3" data-index="${barangIndex}">
            <div class="col-md-5">
                <label class="form-label">Barang *</label>
                <select class="form-select barang-select" name="barang[${barangIndex}][barang_id]" required>
                    <option value="">Pilih Barang</option>
                    @foreach($barang as $b)
                        <option value="{{ $b->id }}" data-harga="{{ $b->harga_sewa_per_hari }}" data-stok="{{ $b->stok_tersedia }}">
                            {{ $b->nama_barang }} (Stok: {{ $b->stok_tersedia }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Jumlah *</label>
                <input type="number" class="form-control jumlah-input" name="barang[${barangIndex}][jumlah]" min="1" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Subtotal</label>
                <input type="text" class="form-control subtotal-display" readonly>
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <div>
                    <button type="button" class="btn btn-sm btn-danger" onclick="hapusBarang(this)">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('daftarBarang').insertAdjacentHTML('beforeend', html);
    updateHapusButton();
}

function hapusBarang(btn) {
    btn.closest('.barang-item').remove();
    hitungTotal();
    updateHapusButton();
}

function updateHapusButton() {
    const items = document.querySelectorAll('.barang-item');
    items.forEach((item, index) => {
        const deleteBtn = item.querySelector('.btn-danger');
        if (items.length > 1) {
            deleteBtn.style.display = 'inline-block';
        } else {
            deleteBtn.style.display = 'none';
        }
    });
}

function hitungDurasi() {
    const tanggalPinjam = document.getElementById('tanggal_pinjam').value;
    const tanggalKembali = document.getElementById('tanggal_kembali_rencana').value;
    
    if (tanggalPinjam && tanggalKembali) {
        const start = new Date(tanggalPinjam);
        const end = new Date(tanggalKembali);
        const durasi = Math.ceil((end - start) / (1000 * 60 * 60 * 24)) + 1;
        
        document.getElementById('durasi-display').textContent = durasi + ' hari';
        return durasi;
    }
    return 0;
}

function hitungTotal() {
    const durasi = hitungDurasi();
    let totalBiaya = 0;

    document.querySelectorAll('.barang-item').forEach(item => {
        const barangSelect = item.querySelector('.barang-select');
        const jumlahInput = item.querySelector('.jumlah-input');
        const subtotalDisplay = item.querySelector('.subtotal-display');

        if (barangSelect.value && jumlahInput.value && durasi > 0) {
            const harga = parseFloat(barangSelect.options[barangSelect.selectedIndex].dataset.harga || 0);
            const jumlah = parseInt(jumlahInput.value || 0);
            const subtotal = harga * jumlah * durasi;
            
            subtotalDisplay.value = 'Rp ' + subtotal.toLocaleString('id-ID');
            totalBiaya += subtotal;
        } else {
            subtotalDisplay.value = '';
        }
    });

    document.getElementById('total-biaya').innerHTML = '<strong>Rp ' + totalBiaya.toLocaleString('id-ID') + '</strong>';
}

function validasiStok(select, jumlahInput) {
    const stokTersedia = parseInt(select.options[select.selectedIndex].dataset.stok || 0);
    const jumlah = parseInt(jumlahInput.value || 0);
    
    if (jumlah > stokTersedia) {
        alert(`Stok tidak mencukupi! Tersedia: ${stokTersedia}`);
        jumlahInput.value = stokTersedia;
    }
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Event untuk perubahan tanggal
    document.getElementById('tanggal_pinjam').addEventListener('change', hitungTotal);
    document.getElementById('tanggal_kembali_rencana').addEventListener('change', function() {
        const tanggalPinjam = document.getElementById('tanggal_pinjam').value;
        const tanggalKembali = this.value;
        
        if (tanggalPinjam && tanggalKembali && new Date(tanggalKembali) <= new Date(tanggalPinjam)) {
            alert('Tanggal kembali harus setelah tanggal pinjam');
            this.value = '';
            return;
        }
        hitungTotal();
    });

    // Event untuk barang dan jumlah
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('barang-select') || e.target.classList.contains('jumlah-input')) {
            if (e.target.classList.contains('jumlah-input')) {
                const barangSelect = e.target.closest('.barang-item').querySelector('.barang-select');
                if (barangSelect.value) {
                    validasiStok(barangSelect, e.target);
                }
            }
            hitungTotal();
        }
    });

    updateHapusButton();
});
</script>
@endsection