<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Hiển thị trang đổi mật khẩu.
     */
    public function showChangePassword()
    {
        return view('profile.change_password');
    }

    /**
     * Hiển thị trang hồ sơ cá nhân.
     */
    public function showProfile()
    {
        return view('profile.edit', ['user' => Auth::user()]);
    }

    /**
     * Cập nhật hồ sơ cá nhân.
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone'     => 'nullable|string|max:20',
            'avatar'    => 'nullable|image|max:2048',
        ], [
            'full_name.required' => 'Vui lòng nhập họ tên.',
        ]);

        $user = Auth::user();
        $data = $request->only(['full_name', 'phone']);

        if ($request->hasFile('avatar')) {
            // Xóa avatar cũ nếu có
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);

        return back()->with('success', 'Cập nhật hồ sơ thành công!');
    }

    /**
     * Xử lý đổi mật khẩu.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'current_password.current_password' => 'Mật khẩu hiện tại không chính xác.',
            'password.required' => 'Vui lòng nhập mật khẩu mới.',
            'password.confirmed' => 'Xác nhận mật khẩu mới không khớp.',
            'password.min' => 'Mật khẩu mới phải có ít nhất 8 ký tự.',
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Đổi mật khẩu thành công!');
    }
}
