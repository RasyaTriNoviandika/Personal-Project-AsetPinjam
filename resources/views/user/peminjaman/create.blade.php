@extends('layouts.user')

@section('title', 'Ajukan Peminjaman Baru')

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('user.peminjaman.index') }}">Riwayat Peminjaman</a>
</li>
<li class="breadcrumb-item active">Ajukan Peminjaman</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-1 text-primary">
                <i class="fas fa-plus-circle me-2"></i> Ajukan Peminjaman Baru
            </h1>
            <p class="text-muted">Lengkapi formulir di bawah untuk mengajukan peminjaman barang</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('user.peminjaman.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i> Kembali
            </a>
        </div>
    </div>

    <form action="{{ route('user.peminjaman.store') }}" method="POST" id="peminjamanForm">
        @csrf
        
        <div class="row">
            <!-- Form Information -->
            <div class="col-lg-8">
                <!-- Date Information -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-calendar me-2"></i>Informasi Tanggal
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tanggal_pinjam" class="form-label">
                                        <i class="fas fa-calendar-plus me-1"></i>Tanggal Pinjam
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" 
                                           name="tanggal_pinjam" 
                                           id="tanggal_pinjam"
                                           class="form-control @error('tanggal_pinjam') is-invalid @enderror"
                                           value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" 
                                           min="{{ date('Y-m-d') }}"
                                           required>
                                    @error('tanggal_pinjam') 
                                        <div class="invalid-feedback">{{ $message }}</div> 
                                    @enderror
                                    <div class="form-text">Tanggal mulai peminjaman barang</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tanggal_kembali_rencana" class="form-label">
                                        <i class="fas fa-calendar-check me-1"></i>Rencana Tanggal Kembali
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" 
                                           name="tanggal_kembali_rencana" 
                                           id="tanggal_kembali_rencana"
                                           class="form-control @error('tanggal_kembali_rencana') is-invalid @enderror"
                                           value="{{ old('tanggal_kembali_rencana') }}" 
                                           required>
                                    @error('tanggal_kembali_rencana') 
                                        <div class="invalid-feedback">{{ $message }}</div> 
                                    @enderror
                                    <div class="form-text">Tanggal rencana pengembalian barang</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Duration Calculator -->
                        <div class="alert alert-info" id="durationInfo" style="display: none;">
                            <i class="fas fa-info-circle me-2"></i>
                            <span id="durationText">Durasi peminjaman: -</span>
                        </div>
                    </div>
                </div>

                <!-- Item Selection -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-boxes me-2"></i>Pilih Barang
                        </h6>
                        <div class="d-flex align-items-center">
                            <input type="text" 
                                   id="searchBarang" 
                                   class="form-control form-control-sm me-2" 
                                   placeholder="Cari barang..."
                                   style="width: 200px;">
                            <select id="filterKategori" class="form-select form-select-sm" style="width: 150px;">
                                <option value="">Semua Kategori</option>
                                @foreach($kategoris ?? [] as $kategori)
                                    <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row" id="barangList">
                            @forelse($barang as $item)
                                <div class="col-lg-6 col-xl-4 mb-3 barang-item" 
                                     data-name="{{ strtolower($item->nama_barang) }}" 
                                     data-kategori="{{ $item->kategori_id }}">
                                    <div class="card h-100 border-0 shadow-sm barang-card">
                                        <div class="card-body p-3">
                                            <div class="row align-items-center">
                                                <div class="col-3">
                                                    @if($item->gambar)
                                                        <img src="{{ asset('storage/' . $item->gambar) }}" 
                                                             class="rounded" 
                                                             width="60" 
                                                             height="60" 
                                                             style="object-fit: cover;">
                                                    @else
                                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                             style="width: 60px; height: 60px;">
                                                            <i class="fas fa-box fa-2x text-muted"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-9">
                                                    <div class="form-check">
                                                        <input type="checkbox" 
                                                               name="barang[{{ $item->id }}][barang_id]" 
                                                               value="{{ $item->id }}" 
                                                               class="form-check-input barang-checkbox"
                                                               id="barang{{ $item->id }}">
                                                        <label class="form-check-label" for="barang{{ $item->id }}">
                                                            <strong>{{ $item->nama_barang }}</strong>
                                                        </label>
                                                    </div>
                                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                                        <small class="text-muted">
                                                            Stok: 
                                                            <span class="badge {{ $item->stok_tersedia > 0 ? 'bg-success' : 'bg-danger' }}">
                                                                {{ $item->stok_tersedia }}
                                                            </span>
                                                        </small>
                                                        @if($item->harga_sewa)
                                                            <small class="text-primary fw-bold">
                                                                Rp {{ number_format($item->harga_sewa, 0, ',', '.') }}/hari
                                                            </small>
                                                        @endif
                                                    </div>
                                                    <div class="mt-2" style="display: none;" id="jumlahContainer{{ $item->id }}">
                                                        <input type="number" 
                                                               name="barang[{{ $item->id }}][jumlah]" 
                                                               class="form-control form-control-sm jumlah-input" 
                                                               min="1" 
                                                               max="{{ $item->stok_tersedia }}"
                                                               placeholder="Jumlah"
                                                               id="jumlah{{ $item->id }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="text-center py-5">
                                        <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">Tidak ada barang tersedia</h5>
                                        <p class="text-muted">Silakan hubungi admin untuk informasi lebih lanjut</p>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                        
                        @error('barang')
                            <div class="alert alert-danger mt-3">
                                <i class="fas fa-exclamation-triangle me-2"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-sticky-note me-2"></i>Informasi Tambahan
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="catatan" class="form-label">
                                <i class="fas fa-comment me-1"></i>Catatan (opsional)
                            </label>
                            <textarea name="catatan" 
                                      id="catatan" 
                                      rows="4" 
                                      class="form-control @error('catatan') is-invalid @enderror"
                                      placeholder="Tuliskan catatan khusus untuk peminjaman ini...">{{ old('catatan') }}</textarea>
                            @error('catatan') 
                                <div class="invalid-feedback">{{ $message }}</div> 
                            @enderror
                            <div class="form-text">Contoh: Untuk keperluan acara, digunakan di lokasi tertentu, dll.</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Sidebar -->
            <div class="col-lg-4">
                <div class="card shadow sticky-top" style="top: 100px;">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-clipboard-check me-2"></i>Ringkasan Peminjaman
                        </h6>
                    </div>
                    <div class="card-body">
                        <div id="selectedItems">
                            <div class="text-center text-muted py-3">
                                <i class="fas fa-info-circle fa-2x mb-2"></i>
                                <p>Pilih barang untuk melihat ringkasan</p>
                            </div>
                        </div>
                        
                        <div id="summaryDetails" style="display: none;">
                            <div class="border-top pt-3 mt-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Total Item:</span>
                                    <span id="totalItems">0</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Durasi:</span>
                                    <span id="summaryDuration">-</span>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <strong>Estimasi Total:</strong>
                                    <strong class="text-success" id="totalEstimation">Rp 0</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg" id="submitBtn">
                                <i class="fas fa-paper-plane me-2"></i>Ajukan Peminjaman
                            </button>
                            <a href="{{ route('user.peminjaman.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Batal
                            </a>
                        </div>
                        
                        <div class="mt-3">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Peminjaman akan diproses setelah disetujui admin
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
.barang-card {
    transition: all 0.3s ease;
    cursor: pointer;
}

.barang-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
}

.barang-card.selected {
    border: 2px solid var(--primary-color) !important;
    box-shadow: 0 0 15px rgba(78, 115, 223, 0.3) !important;
}

.sticky-top {
    position: sticky !important;
}

.selected-item {
    background: linear-gradient(90deg, rgba(78, 115, 223, 0.1), rgba(78, 115, 223, 0.05));
    border-left: 3px solid var(--primary-color);
    margin-bottom: 10px;
    padding: 10px;
    border-radius: 5px;
}

.font-weight-bold {
    font-weight: 700;
}
</style>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tanggalPinjam = document.getElementById('tanggal_pinjam');
    const tanggalKembali = document.getElementById('tanggal_kembali_rencana');
    const durationInfo = document.getElementById('durationInfo');
    const durationText = document.getElementById('durationText');
    const searchBarang = document.getElementById('searchBarang');
    const filterKategori = document.getElementById('filterKategori');
    const selectedItems = document.getElementById('selectedItems');
    const summaryDetails = document.getElementById('summaryDetails');
    const submitBtn = document.getElementById('submitBtn');

    // Set minimum date for return date
    tanggalPinjam.addEventListener('change', function() {
        const pinjamDate = new Date(this.value);
        const nextDay = new Date(pinjamDate);
        nextDay.setDate(nextDay.getDate() + 1);
        
        tanggalKembali.min = nextDay.toISOString().split('T')[0];
        if (tanggalKembali.value && new Date(tanggalKembali.value) <= pinjamDate) {
            tanggalKembali.value = nextDay.toISOString().split('T')[0];
        }
        calculateDuration();
    });

    tanggalKembali.addEventListener('change', calculateDuration);

    function calculateDuration() {
        if (tanggalPinjam.value && tanggalKembali.value) {
            const start = new Date(tanggalPinjam.value);
            const end = new Date(tanggalKembali.value);
            const diffTime = Math.abs(end - start);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            
            durationText.textContent = `Durasi peminjaman: ${diffDays} hari`;
            durationInfo.style.display = 'block';
            
            document.getElementById('summaryDuration').textContent = `${diffDays} hari`;
            calculateTotal();
        } else {
            durationInfo.style.display = 'none';
        }
    }

    // Search and filter functionality
    searchBarang.addEventListener('input', filterBarang);
    filterKategori.addEventListener('change', filterBarang);

    function filterBarang() {
        const searchTerm = searchBarang.value.toLowerCase();
        const selectedKategori = filterKategori.value;
        const barangItems = document.querySelectorAll('.barang-item');

        barangItems.forEach(item => {
            const name = item.dataset.name;
            const kategori = item.dataset.kategori;
            
            const matchesSearch = name.includes(searchTerm);
            const matchesKategori = !selectedKategori || kategori === selectedKategori;
            
            item.style.display = (matchesSearch && matchesKategori) ? 'block' : 'none';
        });
    }

    // Handle item selection
    document.querySelectorAll('.barang-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const itemId = this.value;
            const card = this.closest('.barang-card');
            const jumlahContainer = document.getElementById(`jumlahContainer${itemId}`);
            const jumlahInput = document.getElementById(`jumlah${itemId}`);

            if (this.checked) {
                card.classList.add('selected');
                jumlahContainer.style.display = 'block';
                jumlahInput.value = 1;
                jumlahInput.required = true;
            } else {
                card.classList.remove('selected');
                jumlahContainer.style.display = 'none';
                jumlahInput.value = '';
                jumlahInput.required = false;
            }
            
            updateSummary();
        });
    });

    // Handle quantity changes
    document.querySelectorAll('.jumlah-input').forEach(input => {
        input.addEventListener('change', updateSummary);
    });

    function updateSummary() {
        const checkedItems = document.querySelectorAll('.barang-checkbox:checked');
        
        if (checkedItems.length === 0) {
            selectedItems.innerHTML = `
                <div class="text-center text-muted py-3">
                    <i class="fas fa-info-circle fa-2x mb-2"></i>
                    <p>Pilih barang untuk melihat ringkasan</p>
                </div>
            `;
            summaryDetails.style.display = 'none';
            submitBtn.disabled = true;
            return;
        }

        let html = '';
        let totalItems = 0;
        
        checkedItems.forEach(checkbox => {
            const itemId = checkbox.value;
            const card = checkbox.closest('.barang-card');
            const itemName = card.querySelector('label strong').textContent;
            const jumlahInput = document.getElementById(`jumlah${itemId}`);
            const jumlah = parseInt(jumlahInput.value) || 1;
            
            totalItems += jumlah;
            
            html += `
                <div class="selected-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>${itemName}</strong>
                            <br><small class="text-muted">${jumlah} unit</small>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeItem(${itemId})">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            `;
        });
        
        selectedItems.innerHTML = html;
        document.getElementById('totalItems').textContent = totalItems;
        summaryDetails.style.display = 'block';
        submitBtn.disabled = false;
        
        calculateTotal();
    }

    function calculateTotal() {
        // This would calculate based on item prices and duration
        // For now, showing placeholder
        const duration = calculateDurationDays();
        if (duration > 0) {
            document.getElementById('totalEstimation').textContent = 'Menunggu kalkulasi admin';
        }
    }

    function calculateDurationDays() {
        if (tanggalPinjam.value && tanggalKembali.value) {
            const start = new Date(tanggalPinjam.value);
            const end = new Date(tanggalKembali.value);
            const diffTime = Math.abs(end - start);
            return Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        }
        return 0;
    }

    // Form submission handling
    document.getElementById('peminjamanForm').addEventListener('submit', function(e) {
        const checkedItems = document.querySelectorAll('.barang-checkbox:checked');
        
        if (checkedItems.length === 0) {
            e.preventDefault();
            alert('Pilih minimal satu barang untuk dipinjam');
            return;
        }

        // Validate quantities
        let isValid = true;
        checkedItems.forEach(checkbox => {
            const itemId = checkbox.value;
            const jumlahInput = document.getElementById(`jumlah${itemId}`);
            if (!jumlahInput.value || jumlahInput.value < 1) {
                isValid = false;
            }
        });

        if (!isValid) {
            e.preventDefault();
            alert('Masukkan jumlah yang valid untuk setiap barang yang dipilih');
            return;
        }

        // Show loading state
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
        submitBtn.disabled = true;
    });

    // Initialize
    calculateDuration();
});

function removeItem(itemId) {
    const checkbox = document.querySelector(`input[value="${itemId}"]`);
    if (checkbox) {
        checkbox.checked = false;
        checkbox.dispatchEvent(new Event('change'));
    }
}
</script>
@endpush