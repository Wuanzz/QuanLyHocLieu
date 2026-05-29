<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'Email' => 'required|email',
            'MatKhau' => 'required'
        ]);

        $credentials = [
            'Email' => $request->Email,
            'password' => $request->MatKhau
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            $vaiTro = Auth::user()->VaiTro;
            
            if ($vaiTro === 'Admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($vaiTro === 'GiangVien') {
                return redirect()->route('giangvien.kiemduyet.index');
            }
            
            return redirect()->route('home');
        }

        return back()->withErrors([
            'Email' => 'Thông tin đăng nhập không chính xác.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login');
    }
}
