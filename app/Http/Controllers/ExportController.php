<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Peminjam;
use App\Models\Peminjaman;
use App\Models\TransaksiKeuangan;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BarangExport;
use App\Exports\PeminjamanExport;
use App\Exports\TransaksiExport;

class ExportController extends Controller
{
    public function barang()
    {
        return Excel::download(new BarangExport, 'data-barang.xlsx');
    }

    public function peminjaman(Request $request)
    {
        return Excel::download(new PeminjamanExport($request->all()), 'data-peminjaman.xlsx');
    }

    public function transaksi(Request $request)
    {
        return Excel::download(new TransaksiExport($request->all()), 'data-transaksi.xlsx');
    }
}
