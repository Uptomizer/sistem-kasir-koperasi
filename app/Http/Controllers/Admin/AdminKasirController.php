<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminKasirController extends Controller
{
    public function index()
    {
        $kasir = User::where('role', 'kasir')->get();
        return view('admin.kasir.index', compact('kasir'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_user' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username|max:255',
            'password' => 'required|string|min:6',
            'profile_photo' => 'nullable|image|max:2048', // 2MB Max
        ]);

        $data = [
            'nama_user' => $request->nama_user,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => 'kasir',
        ];

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $data['profile_photo'] = basename($path);
        }

        User::create($data);

        return redirect()->route('admin.kasir.index')->with('success', 'Akun Kasir berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $kasir = User::findOrFail($id);

        $request->validate([
            'nama_user' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $kasir->id_user . ',id_user',
            'password' => 'nullable|string|min:6',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        $data = [
            'nama_user' => $request->nama_user,
            'username' => $request->username,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('profile_photo')) {
             // Delete old photo if exists
            if ($kasir->profile_photo && Storage::disk('public')->exists('profile-photos/' . $kasir->profile_photo)) {
                Storage::disk('public')->delete('profile-photos/' . $kasir->profile_photo);
            }
            
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $data['profile_photo'] = basename($path);
        }

        $kasir->update($data);

        return redirect()->route('admin.kasir.index')->with('success', 'Akun Kasir berhasil diperbarui');
    }

    public function destroy($id)
    {
        $kasir = User::findOrFail($id);
        
        // Delete photo
        if ($kasir->profile_photo && Storage::disk('public')->exists('profile-photos/' . $kasir->profile_photo)) {
            Storage::disk('public')->delete('profile-photos/' . $kasir->profile_photo);
        }

        $kasir->delete();

        return redirect()->route('admin.kasir.index')->with('success', 'Akun Kasir berhasil dihapus');
    }
}
