<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin() {
        return view('login');
    }


    public function login(Request $request) {
        $credentials = $request->validate([
            "username" => "required|min:5",
            "password" => "required|min:8"
        ],
        [
            "username.required" => "Vui lòng điền tên đăng nhập",
            "username.min" => "Tên đăng nhập phải có ít nhất 5 kí tự",
            "password.required" => "Vui lòng điền mật khẩu",
            "password.min" => "Mật khẩu phải có ít nhất 8 kí tự"
        ]);

        if(Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $role = Auth::user()->role->name;

            if($role == "Admin") {
                return redirect("/admin/dashboard");
            }
            if($role == "Bác sĩ") {
                return redirect("/doctor/dashboard");
            }
            if($role == "Lễ tân") {
                return redirect("/reception/dashboard");
            }
            if($role == "Nhân viên phát thuốc") {
                return redirect("/pharmacy/dashboard");
            }
        }

        return back()->withErrors([
                'login' => 'Sai tài khoản hoặc mật khẩu'
            ]);
    }


    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect("/login");
    }
}
