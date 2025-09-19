<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Export Laporan Excel</title>
</head>
<body>

    <h2>Laporan Data Lengkap</h2>

    {{-- ================= USER ================= --}}
    <h3>Data User</h3>
    <table border="1" cellspacing="0" cellpadding="5">
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
    <br>

    {{-- ================= PEMINJAMAN ================= --}}
    <h3>Data Peminjaman</h3>
    <table border="1" cellspacing="0" cellpadding="5">
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
                <td>{{ $p->total_bayar ?? 0 }}</td>
                <td>{{ $p->total_denda ?? 0 }}</td>
                <td>{{ $p->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <br>

    {{-- ================= TRANSAKSI ================= --}}
    <h3>Data Transaksi Keuangan</h3>
    <table border="1" cellspacing="0" cellpadding="5">
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
                <td>{{ $t->jumlah }}</td>
                <td>{{ $t->keterangan }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <br>

    {{-- ================= BARANG ================= --}}
    <h3>Data Barang</h3>
    <table border="1" cellspacing="0" cellpadding="5">
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
                <td>{{ $b->pendapatan ?? 0 }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <br>

    {{-- ================= KATEGORI ================= --}}
    <h3>Data Kategori Barang</h3>
    <table border="1" cellspacing="0" cellpadding="5">
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
