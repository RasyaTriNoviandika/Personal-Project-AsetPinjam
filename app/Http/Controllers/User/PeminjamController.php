<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Peminjam;
use RealRashid\SweetAlert\Facades\Alert;

class PeminjamController extends Controller
{
    public function index()
    {
        $peminjams = Peminjam::where('user_id', auth()->id())
            ->latest()
            ->paginate(10);
            
        return view('user.peminjam.index', compact('peminjams'));
    }
    
    public function create()
    {
        return view('user.peminjam.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_identitas' => 'required|string|max:20|unique:peminjams,no_identitas',
            'jenis_identitas' => 'required|in:ktp,sim,ktm,paspor',
            'no_telepon' => 'required|string|max:15',
            'email' => 'nullable|email|max:255',
            'alamat' => 'required|string',
            'pekerjaan' => 'nullable|string|max:255',
            'instansi' => 'nullable|string|max:255',
        ]);
        
        try {
            Peminjam::create([
                'user_id' => auth()->id(),
                'nama' => $request->nama,
                'no_identitas' => $request->no_identitas,
                'jenis_identitas' => $request->jenis_identitas,
                'no_telepon' => $request->no_telepon,
                'email' => $request->email,
                'alamat' => $request->alamat,
                'pekerjaan' => $request->pekerjaan,
                'instansi' => $request->instansi,
            ]);
            
            Alert::success('Berhasil', 'Data peminjam berhasil ditambahkan');
            return redirect()->route('user.peminjam.index');
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }
    
    public function show(Peminjam $peminjam)
    {
        $this->authorizeUser($peminjam);
        
        // Get borrowing history for this peminjam
        $peminjaman = $peminjam->peminjaman()
            ->with(['detailPeminjaman.barang'])
            ->latest()
            ->paginate(10);
            
        return view('user.peminjam.show', compact('peminjam', 'peminjaman'));
    }
    
    public function edit(Peminjam $peminjam)
    {
        $this->authorizeUser($peminjam);
        return view('user.peminjam.edit', compact('peminjam'));
    }
    
    public function update(Request $request, Peminjam $peminjam)
    {
        $this->authorizeUser($peminjam);
        
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_identitas' => 'required|string|max:20|unique:peminjams,no_identitas,' . $peminjam->id,
            'jenis_identitas' => 'required|in:ktp,sim,ktm,paspor',
            'no_telepon' => 'required|string|max:15',
            'email' => 'nullable|email|max:255',
            'alamat' => 'required|string',
            'pekerjaan' => 'nullable|string|max:255',
            'instansi' => 'nullable|string|max:255',
        ]);
        
        try {
            $peminjam->update([
                'nama' => $request->nama,
                'no_identitas' => $request->no_identitas,
                'jenis_identitas' => $request->jenis_identitas,
                'no_telepon' => $request->no_telepon,
                'email' => $request->email,
                'alamat' => $request->alamat,
                'pekerjaan' => $request->pekerjaan,
                'instansi' => $request->instansi,
            ]);
            
            Alert::success('Berhasil', 'Data peminjam berhasil diperbarui');
            return redirect()->route('user.peminjam.index');
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }
    
    public function destroy(Peminjam $peminjam)
    {
        $this->authorizeUser($peminjam);
        
        // Check if peminjam has active borrowings
        if ($peminjam->peminjaman()->whereIn('status', ['pending', 'dipinjam'])->exists()) {
            Alert::error('Gagal', 'Tidak dapat menghapus peminjam yang masih memiliki peminjaman aktif');
            return redirect()->back();
        }
        
        try {
            $peminjam->delete();
            Alert::success('Berhasil', 'Data peminjam berhasil dihapus');
            return redirect()->route('user.peminjam.index');
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back();
        }
    }
    
    private function authorizeUser(Peminjam $peminjam)
    {
        if ($peminjam->user_id !== auth()->id()) {
            abort(403, 'Akses ditolak');
        }
    }
}