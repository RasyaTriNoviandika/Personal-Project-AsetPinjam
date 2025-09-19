<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Semua</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }
        h2 {
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        th, td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }
        th {
            background: #eee;
        }
    </style>
</head>
<body>

    <h2>Laporan Data Lengkap</h2>

    {{-- ================= USER ================= --}}
    <h3>Data User</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $u)
            <tr>
                <td>{{ $u->id }}</td>
                <td>{{ $u->name }}</td>
                <td>{{ $u->email }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- ================= PEMINJAMAN ================= --}}
    <h3>Data Peminjaman</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Peminjam</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
                <th>Total Bayar</th>
                <th>Total Denda</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($peminjaman as $p)
            <tr>
                <td>{{ $p->id }}</td>
                <td>{{ $p->peminjam->nama ?? '-' }}</td>
                <td>{{ $p->tanggal_pinjam }}</td>
                <td>{{ $p->tanggal_kembali ?? '-' }}</td>
                <td>Rp {{ number_format($p->total_bayar ?? 0, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($p->total_denda ?? 0, 0, ',', '.') }}</td>
                <td>{{ $p->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- ================= TRANSAKSI ================= --}}
    <h3>Data Transaksi Keuangan</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tanggal</th>
                <th>Jenis</th>
                <th>Jumlah</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksi as $t)
            <tr>
                <td>{{ $t->id }}</td>
                <td>{{ $t->tanggal_transaksi }}</td>
                <td>{{ $t->jenis_transaksi }}</td>
                <td>Rp {{ number_format($t->jumlah, 0, ',', '.') }}</td>
                <td>{{ $t->keterangan }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- ================= BARANG ================= --}}
    <h3>Data Barang</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Stok</th>
                <th>Total Dipinjam</th>
                <th>Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($barang as $b)
            <tr>
                <td>{{ $b->id }}</td>
                <td>{{ $b->nama_barang }}</td>
                <td>{{ $b->kategori->nama_kategori ?? '-' }}</td>
                <td>{{ $b->stok_tersedia }}</td>
                <td>{{ $b->total_dipinjam ?? 0 }}</td>
                <td>Rp {{ number_format($b->pendapatan ?? 0, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- ================= KATEGORI BARANG ================= --}}
    <h3>Data Kategori Barang</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Kategori</th>
                <th>Jumlah Barang</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kategori as $k)
            <tr>
                <td>{{ $k->id }}</td>
                <td>{{ $k->nama_kategori }}</td>
                <td>{{ $k->barang->count() }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
