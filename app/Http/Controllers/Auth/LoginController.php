<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user()->role);
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();
            return $this->redirectBasedOnRole($user->role)->with('success', 'Selamat datang kembali, ' . $user->nama_user . '!');
        }

        return back()->withErrors([
            'username' => 'Username atau password yang dimasukkan salah.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Berhasil logout.');
    }

    private function redirectBasedOnRole($role)
    {
        switch ($role) {
            case 'Pelayan':
                return redirect()->route('pelayan.order.form');
            case 'Koki':
                return redirect()->route('koki.kds');
            case 'Kasir':
                return redirect()->route('kasir.index');
            case 'Pemilik Restoran':
                return redirect()->route('pemilik.dashboard');
            default:
                return redirect('/');
        }
    }
}
