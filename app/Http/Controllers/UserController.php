<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "username" => 'required|unique:users,username',
            'password' => 'required|min:6',
            'full_name' => 'required',
            'gender' => 'required',
            'birth_year' => 'nullable|integer',
            'phone' => 'nullable',
            'status' => 'required',
            'role_id' => 'required|exists:roles,id',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/avatars'), $filename);
            $avatarPath = 'uploads/avatars/' . $filename;
        }

        $user = User::create([
            'username'   => $request->username,
            'password'   => Hash::make($request->password),
            'full_name'  => $request->full_name,
            'gender'     => $request->gender,
            'birth_year' => $request->birth_year,
            'phone'      => $request->phone,
            'status'     => $request->status,
            'role_id'    => $request->role_id,
            'avatar'     => $avatarPath,
            ]);

        if ($request->role_id == 2) {
            $user->doctor()->create([
                'specialty' => $request->specialty,
                'is_free'   => $request->is_free
            ]);
        }

        return redirect()->back()->with('success', 'Thêm user thành công');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json(
            User::with('role','doctor')->findOrFail($id)
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'username'   => 'required|min:3|unique:users,username,' . $user->id,
            'full_name'  => 'required',
            'gender'     => 'required',
            'status'     => 'required',
            'role_id'    => 'required|exists:roles,id',
            'avatar'     => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'username'   => $request->username,
            'full_name'  => $request->full_name,
            'gender'     => $request->gender,
            'birth_year' => $request->birth_year,
            'phone'      => $request->phone,
            'status'     => $request->status,
            'role_id'    => $request->role_id,
        ];

        if ($request->hasFile('avatar')) {
            // Xoá avatar cũ nếu có
            if ($user->avatar && File::exists(public_path($user->avatar))) {
                File::delete(public_path($user->avatar));
            }

            $file = $request->file('avatar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/avatars'), $filename);
            $data['avatar'] = 'uploads/avatars/' . $filename;
        }

        // nếu nhập password mới thì đổi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        if ($request->role_id == 2) {

        // nếu chưa có doctor → tạo
        if (!$user->doctor) {
            $user->doctor()->create([
                'specialty' => $request->specialty,
                'is_free'   => $request->is_free
            ]);
        }
        // nếu đã có → update
        else {
            $user->doctor->update([
                'specialty' => $request->specialty,
                'is_free'   => $request->is_free
            ]);
        }
    }
    // nếu KHÔNG còn là bác sĩ → xoá doctor
    else {
        if ($user->doctor) {
            $user->doctor->delete();
        }
    }


        

        return redirect()->back()->with('success', 'Cập nhật người dùng thành công');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            // Nếu là bác sĩ thì xóa record trong bảng doctors trước
            if ($user->doctor) {
                $user->doctor->delete();
            }

            // Xoá avatar nếu có
            if ($user->avatar && File::exists(public_path($user->avatar))) {
                File::delete(public_path($user->avatar));
            }
            
            $user->delete();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Xoá người dùng thành công'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không thể xóa người dùng này vì có dữ liệu ràng buộc (VD: Lịch khám, Hồ sơ khám...)'
            ], 400);
        }
    }
}
