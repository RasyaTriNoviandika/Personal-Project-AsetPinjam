<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Barang</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: center; }
        th { background: #f2f2f2; }
    </style>
</head>
<body>
    <h2 style="text-align: center;">Laporan Barang</h2>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Stok Tersedia</th>
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
                    <td>{{ $b->total_dipinjam }}</td>
                    <td>Rp {{ number_format($b->pendapatan, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
