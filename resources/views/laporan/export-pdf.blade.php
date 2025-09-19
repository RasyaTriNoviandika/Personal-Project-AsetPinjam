{{-- resources/views/laporan/export-pdf.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Lengkap</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h2 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 25px; }
        table th, table td {
            border: 1px solid #333;
            padding: 6px;
            text-align: left;
        }
        table th {
            background: #f2f2f2;
        }
    </style>
</head>
<body>

    <h2>Laporan Lengkap</h2>

    {{-- Tabel User --}}
    <h3>Data User</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID</th>
                <th>Nama</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $i => $u)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $u->id }}</td>
                    <td>{{ $u->name }}</td>
                    <td>{{ $u->email }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Tabel Barang --}}
    <h3>Data Barang</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Stok</th>
            </tr>
        </thead>
        <tbody>
            @foreach($barang as $i => $b)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $b->id }}</td>
                    <td>{{ $b->nama_barang }}</td>
                    <td>{{ $b->kategori->nama_kategori ?? '-' }}</td>
                    <td>{{ $b->stok }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Tabel Kategori --}}
    <h3>Data Kategori Barang</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID</th>
                <th>Nama Kategori</th>
                <th>Jumlah Barang</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kategori as $i => $k)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $k->id }}</td>
                    <td>{{ $k->nama_kategori }}</td>
                    <td>{{ $k->barang_count ?? $k->barang->count() }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Tabel Peminjaman --}}
    <h3>Data Peminjaman</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID</th>
                <th>Peminjam</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
                <th>Barang Dipinjam</th>
            </tr>
        </thead>
        <tbody>
            @foreach($peminjaman as $i => $p)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $p->id }}</td>
                    <td>{{ $p->peminjam->name ?? '-' }}</td>
                    <td>{{ $p->tanggal_pinjam ?? ($p->created_at ? $p->created_at->format('d-m-Y') : '-') }}</td>
                    <td>{{ $p->tanggal_kembali ?? '-' }}</td>
                    <td>
                        @foreach($p->detailPeminjaman as $detail)
                            - {{ $detail->barang->nama_barang ?? 'Barang tidak ditemukan' }} ({{ $detail->jumlah }}) <br>
                        @endforeach
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Tabel Transaksi Keuangan --}}
<h3>Data Transaksi Keuangan</h3>
<table border="1" cellspacing="0" cellpadding="5" width="100%">
    <thead style="background:#f2f2f2;">
        <tr>
            <th>No</th>
            <th>ID</th>
            <th>Tanggal</th>
            <th>Jenis</th>
            <th>Jumlah</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($transaksi as $i => $t)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $t->id }}</td>
                <td>
                    {{ $t->tanggal_transaksi 
                        ? \Carbon\Carbon::parse($t->tanggal_transaksi)->format('d-m-Y')
                        : '-' }}
                </td>
                <td>{{ ucfirst($t->jenis_transaksi) }}</td>
                <td>Rp {{ number_format($t->jumlah, 0, ',', '.') }}</td>
                <td>{{ $t->keterangan ?? '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

    {{-- Rekap Keuangan --}}
    <h3>Rekapitulasi Keuangan</h3>
    <table>
        <tr>
            <th>Total Pemasukan</th>
            <td>Rp{{ number_format($totalPemasukan, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Total Pengeluaran</th>
            <td>Rp{{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Saldo Akhir</th>
            <td>Rp{{ number_format($saldoAkhir, 0, ',', '.') }}</td>
        </tr>
    </table>

</body>
</html>
