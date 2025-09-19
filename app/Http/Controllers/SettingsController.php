<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class SettingsController extends Controller
{
    // Halaman Profil
    public function profile()
    {
        $user = Auth::user();
        return view('settings.profile', compact('user'));
    }

    // Update Profil (nama, email, foto, password opsional)
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'nullable|min:6|confirmed',
            'photo' => 'nullable|image|mimes:jpg,png,jpeg|max:2048'
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('photo')) {
            $fileName = time().'_'.$request->photo->getClientOriginalName();
            $request->photo->move(public_path('uploads/profile'), $fileName);
            $user->photo = $fileName;
        }

        $user->save();

        return redirect()->route('settings.profile')->with('success', 'Profil berhasil diperbarui!');
    }

    // Halaman Account (ubah password)
    public function account()
    {
        $user = Auth::user();
        return view('settings.account', compact('user'));
    }

    // Update Account (password)
   public function updateAccount(Request $request)
{
    $user = Auth::user();

    $request->validate([
        'password' => 'required|min:6|confirmed'
    ]);

    $user->password = Hash::make($request->password);
    $user->save();

    Alert::success('Berhasil', 'Password berhasil diperbarui!');
    return redirect()->route('settings.account');
}
}
