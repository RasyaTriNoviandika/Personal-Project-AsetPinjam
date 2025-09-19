{{-- resources/views/laporan/pdf/user.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan User</title>
    <style>
        body { 
            font-family: DejaVu Sans, sans-serif; 
            font-size: 12px; 
            margin: 20px;
        }
        table { 
            border-collapse: collapse; 
            width: 100%; 
            margin-bottom: 20px; 
        }
        th, td { 
            border: 1px solid #000; 
            padding: 8px; 
            text-align: left; 
        }
        th { 
            background-color: #f2f2f2; 
            font-weight: bold;
        }
        h4 { 
            text-align: center; 
            margin-bottom: 30px; 
            font-size: 18px;
        }
        .header-info {
            margin-bottom: 20px;
            font-size: 11px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .total-row {
            background-color: #e9ecef;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h4>LAPORAN DATA USER</h4>
    
    <div class="header-info">
        <p><strong>Tanggal Cetak:</strong> {{ now()->format('d/m/Y H:i:s') }}</p>
        <p><strong>Total User:</strong> {{ $users->count() }} orang</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="25%">Nama</th>
                <th width="25%">Email</th>
                <th width="10%">Role</th>
                <th width="10%">Status</th>
                <th width="10%">Total Peminjaman</th>
                <th width="15%">Total Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $index => $user)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td class="text-center">{{ $user->role_display }}</td>
                <td class="text-center">{{ $user->status_display }}</td>
                <td class="text-center">{{ $user->total_peminjaman ?? 0 }}</td>
                <td class="text-right">Rp {{ number_format($user->total_pendapatan ?? 0, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Tidak ada data user</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="5" class="text-center"><strong>TOTAL</strong></td>
                <td class="text-center"><strong>{{ $users->sum('total_peminjaman') }}</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($users->sum('total_pendapatan'), 0, ',', '.') }}</strong></td>
            </tr>
        </tfoot>
    </table>

    <div class="header-info" style="margin-top: 30px;">
        <p><strong>Keterangan:</strong></p>
        <ul>
            <li>Total User: {{ $users->count() }} orang</li>
            <li>User Admin: {{ $users->where('role', 'admin')->count() }} orang</li>
            <li>User Biasa: {{ $users->where('role', 'user')->count() }} orang</li>
            <li>User Aktif: {{ $users->where('status', 'aktif')->count() }} orang</li>
            <li>User Tidak Aktif: {{ $users->where('status', '!=', 'aktif')->count() }} orang</li>
        </ul>
    </div>
</body>
</html>