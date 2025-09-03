<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjam;
use RealRashid\SweetAlert\Facades\Alert;

class PeminjamController extends Controller
{
    public function index()
    {
        $peminjam = Peminjam::with('peminjaman')->latest()->paginate(10);
        return view('peminjam.index', compact('peminjam'));
    }

    public function create()
    {
        return view('peminjam.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_peminjam' => 'required|string|max:255',
            'email' => 'required|email|unique:peminjam,email',
            'no_telepon' => 'required|string|max:20',
            'alamat' => 'required|string',
            'jenis_peminjam' => 'required|in:individu,organisasi,perusahaan',
            'no_identitas' => 'required|string|max:50',
        ]);

        Peminjam::create($request->all());

        Alert::success('Berhasil', 'Data peminjam berhasil ditambahkan');
        return redirect()->route('peminjam.index');
    }

    public function show(Peminjam $peminjam)
    {
        $peminjam->load('peminjaman.detailPeminjaman.barang');
        return view('peminjam.show', compact('peminjam'));
    }

    public function edit(Peminjam $peminjam)
    {
        return view('peminjam.edit', compact('peminjam'));
    }

    public function update(Request $request, Peminjam $peminjam)
    {
        $request->validate([
            'nama_peminjam' => 'required|string|max:255',
            'email' => 'required|email|unique:peminjam,email,' . $peminjam->id,
            'no_telepon' => 'required|string|max:20',
            'alamat' => 'required|string',
            'jenis_peminjam' => 'required|in:individu,organisasi,perusahaan',
            'no_identitas' => 'required|string|max:50',
        ]);

        $peminjam->update($request->all());

        Alert::success('Berhasil', 'Data peminjam berhasil diperbarui');
        return redirect()->route('peminjam.index');
    }

    public function destroy(Peminjam $peminjam)
    {
        // Cek apakah masih ada peminjaman aktif
        if ($peminjam->peminjaman()->whereIn('status', ['dipinjam', 'terlambat'])->exists()) {
            Alert::error('Gagal', 'Peminjam tidak dapat dihapus karena masih memiliki peminjaman aktif');
            return redirect()->route('peminjam.index');
        }

        $peminjam->delete();
        Alert::success('Berhasil', 'Data peminjam berhasil dihapus');
        return redirect()->route('peminjam.index');
    }
}