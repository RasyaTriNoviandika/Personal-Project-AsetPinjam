<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjam;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Barang;

class PeminjamController extends Controller
{
    public function index()
    {
        $peminjams = Peminjam::latest()->paginate(10);
        return view('peminjam.index', compact('peminjams'));
    }

    public function create()
{
    // Ambil semua data peminjam
    $peminjam = \App\Models\Peminjam::all();

    // Ambil barang yang stoknya masih tersedia
    $barang = \App\Models\Barang::where('stok_tersedia', '>', 0)->get();

    // Lempar ke view
    return view('peminjam.create', compact('peminjam', 'barang'));
}

    
public function store(Request $request)
{
    $request->validate([
        // Pilih peminjam lama atau tambah baru
        'new_peminjam_name' => 'nullable|string|max:255',
        'new_peminjam_email' => 'nullable|email|unique:peminjams,email',
        'peminjam_id' => 'nullable|exists:peminjams,id',
        'tanggal_pinjam' => 'required|date',
        'tanggal_kembali_rencana' => 'required|date|after_or_equal:tanggal_pinjam',
        'barang' => 'required|array|min:1',
        'barang.*.barang_id' => 'required|exists:barang,id',
        'barang.*.jumlah' => 'required|integer|min:1',
    ]);

    try {
        // Tentukan peminjam
        if ($request->filled('new_peminjam_name') && $request->filled('new_peminjam_email')) {
            $peminjam = Peminjam::create([
                'nama_peminjam' => $request->new_peminjam_name,
                'email' => $request->new_peminjam_email,
                'no_telepon' => $request->new_peminjam_no_telepon ?? null,
                'alamat' => $request->new_peminjam_alamat ?? null,
                'jenis_peminjam' => $request->new_peminjam_jenis ?? 'individu',
                'no_identitas' => $request->new_peminjam_no_identitas ?? '-',
            ]);
            $peminjam_id = $peminjam->id;
        } else {
            $peminjam_id = $request->peminjam_id;
        }

        // Buat peminjaman
        $peminjaman = \App\Models\Peminjaman::create([
            'peminjam_id' => $peminjam_id,
            'user_id' => auth()->id(),
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_kembali_rencana' => $request->tanggal_kembali_rencana,
            'status' => 'dipinjam',
            'catatan' => $request->catatan
        ]);

        // Simpan detail barang
        foreach ($request->barang as $item) {
            $peminjaman->detailPeminjaman()->create([
                'barang_id' => $item['barang_id'],
                'jumlah' => $item['jumlah']
            ]);

            // Kurangi stok barang
            $barang = \App\Models\Barang::find($item['barang_id']);
            $barang->stok_tersedia -= $item['jumlah'];
            $barang->save();
        }

        \RealRashid\SweetAlert\Facades\Alert::success('Berhasil', 'Peminjaman berhasil ditambahkan');
        return redirect()->route('peminjaman.index');

    } catch (\Exception $e) {
        \RealRashid\SweetAlert\Facades\Alert::error('Gagal', 'Terjadi kesalahan: ' . $e->getMessage());
        return redirect()->back()->withInput();
    }
}


    public function show(Peminjam $peminjam)
    {
        return view('peminjam.show', compact('peminjam'));
    }

    public function edit(Peminjam $peminjam)
    {
        return view('peminjam.edit', compact('peminjam'));
    }

    public function update(Request $request, Peminjam $peminjam)
    {
        $request->validate([
            'nama_peminjam'  => 'required|string|max:255',
            'email'         => 'required|email|unique:peminjams,email,' . $peminjam->id,
            'no_telepon'    => 'required|string|max:20',
            'alamat'        => 'required|string',
            'jenis_peminjam'=> 'required|in:individu,organisasi,perusahaan',
            'no_identitas'  => 'required|string|max:50',
        ]);

        try {
            $peminjam->update($request->all());

            Alert::success('Berhasil', 'Data peminjam berhasil diperbarui');
            return redirect()->route('peminjam.index');

        } catch (\Exception $e) {
            Alert::error('Gagal', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function destroy(Peminjam $peminjam)
    {
        try {
            if ($peminjam->peminjaman()->whereIn('status', ['dipinjam', 'terlambat'])->exists()) {
                Alert::error('Gagal', 'Peminjam tidak dapat dihapus karena masih memiliki peminjaman aktif');
                return redirect()->route('peminjam.index');
            }

            $peminjam->delete();

            Alert::success('Berhasil', 'Data peminjam berhasil dihapus');
            return redirect()->route('peminjam.index');

        } catch (\Exception $e) {
            Alert::error('Gagal', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->route('peminjam.index');
        }
    }
}
