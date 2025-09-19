<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Lengkap</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h2 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table, th, td { border: 1px solid #000; }
        th, td { padding: 6px; text-align: left; }
        th { background: #f2f2f2; }
    </style>
</head>
<body>
    <h2>📊 Laporan Lengkap</h2>

    {{-- ================= USERS ================= --}}
    <h3>👤 Data Users</h3>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $i => $user)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ ucfirst($user->role) }}</td>
                <td>{{ $user->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>


    {{-- Data Barang --}}
<h4>Data Barang</h4>
<table class="table">
    <thead>
        <tr>
            <th>#</th>
            <th>Nama Barang</th>
            <th>Kategori</th>
            <th>Stok</th>
            <th>Harga Sewa / Hari</th>
        </tr>
    </thead>
    <tbody>
        @foreach($barang as $b)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $b->nama_barang }}</td>
                <td>{{ $b->kategori->nama_kategori ?? '-' }}</td>
                <td>{{ $b->stok_tersedia }}</td> {{-- ✅ ambil stok_tersedia --}}
                <td>Rp {{ number_format($b->harga_sewa_per_hari, 0, ',', '.') }}</td> {{-- ✅ harga_sewa_per_hari --}}
            </tr>
        @endforeach
    </tbody>
</table>




    {{-- ================= PEMINJAMAN ================= --}}
    <h3>📑 Data Peminjaman</h3>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Peminjam</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($peminjaman as $i => $p)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $p->peminjam->nama_peminjam ?? '-' }}</td>
                <td>{{ $p->tanggal_pinjam }}</td>
                <td>{{ $p->tanggal_kembali_formatted ?? '-' }}</td>
                <td>{{ ucfirst($p->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- ================= TRANSAKSI KEUANGAN ================= --}}
    <h3>💰 Transaksi Keuangan</h3>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Tanggal</th>
                <th>Jenis</th>
                <th>Jumlah</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksi as $i => $t)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $t->tanggal_transaksi }}</td>
                <td>{{ ucfirst($t->jenis_transaksi) }}</td>
                <td>Rp {{ number_format($t->jumlah, 0, ',', '.') }}</td>
                <td>{{ $t->keterangan }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- ================= KATEGORI BARANG ================= --}}
    <h3>🏷️ Kategori Barang</h3>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Kategori</th>
                <th>Jumlah Barang</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kategori as $i => $k)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $k->nama_kategori }}</td>
                <td>{{ $k->barang->count() }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
