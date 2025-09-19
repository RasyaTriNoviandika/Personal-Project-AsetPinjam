<table>
    <thead>
        <tr><th colspan="5">📊 Laporan Lengkap</th></tr>
    </thead>
</table>

{{-- USERS --}}
<table>
    <thead>
        <tr><th colspan="5">👤 Data Users</th></tr>
        <tr>
            <th>#</th><th>Nama</th><th>Email</th><th>Role</th><th>Status</th>
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

{{-- BARANG --}}
<table>
    <thead>
        <tr><th colspan="6">📦 Data Barang</th></tr>
        <tr>
            <th>#</th>
            <th>Nama Barang</th>
            <th>Kategori</th>
            <th>Stok Total</th>
            <th>Stok Tersedia</th>
            <th>Harga Sewa / Hari</th>
        </tr>
    </thead>
    <tbody>
        @foreach($barang as $i => $b)
        <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $b->nama_barang }}</td>
            <td>{{ $b->kategori->nama_kategori ?? '-' }}</td>
            <td>{{ $b->stok_total }}</td>
            <td>{{ $b->stok_tersedia }}</td>
            <td>{{ $b->harga_sewa_per_hari }}</td>
        </tr>
        @endforeach
    </tbody>
</table>


{{-- PEMINJAMAN --}}
<table>
    <thead>
        <tr><th colspan="5">📑 Data Peminjaman</th></tr>
        <tr>
            <th>#</th><th>Peminjam</th><th>Tanggal Pinjam</th><th>Tanggal Kembali</th><th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($peminjaman as $i => $p)
        <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $p->peminjam->nama_peminjam ?? '-' }}</td>
            <td>{{ $p->tanggal_pinjam }}</td>
            <td>{{ $p->tanggal_kembali ?? '-' }}</td>
            <td>{{ ucfirst($p->status) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

{{-- TRANSAKSI --}}
<table>
    <thead>
        <tr><th colspan="5">💰 Transaksi Keuangan</th></tr>
        <tr>
            <th>#</th><th>Tanggal</th><th>Jenis</th><th>Jumlah</th><th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($transaksi as $i => $t)
        <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $t->tanggal_transaksi }}</td>
            <td>{{ ucfirst($t->jenis_transaksi) }}</td>
            <td>{{ $t->jumlah }}</td>
            <td>{{ $t->keterangan }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

{{-- KATEGORI --}}
<table>
    <thead>
        <tr><th colspan="3">🏷️ Kategori Barang</th></tr>
        <tr>
            <th>#</th><th>Nama Kategori</th><th>Jumlah Barang</th>
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
