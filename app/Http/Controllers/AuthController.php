<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials)) {
            return back()->withErrors([
                'username' => 'Username atau password salah',
            ]);
        }

        $request->session()->regenerate();

        $user = Auth::user();

        \App\Models\ActivityLog::create([
            'id_user' => $user->id_user,
            'action' => 'login',
            'target' => 'System',
            'details' => 'User logged in via web authentication'
        ]);

        if ($user->role === 'admin') {
            return redirect()
                ->route('admin.dashboard')
                ->with('welcome', 'Selamat Datang Admin');
        }

        if ($user->role === 'supervisor') {
            return redirect()
                ->route('supervisor.dashboard')
                ->with('welcome', 'Selamat Datang Supervisor');
        }

        return redirect()
            ->route('kasir.dashboard')
            ->with('welcome', 'Selamat Datang ' . $user->nama_user);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
