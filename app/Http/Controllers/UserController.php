<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
            ]);

        $user = User::create([
            'username'   => $request->username,
            'password'   => Hash::make($request->password),
            'full_name'  => $request->full_name,
            'gender'     => $request->gender,
            'birth_year' => $request->birth_year,
            'phone'      => $request->phone,
            'status'     => $request->status,
            'role_id'    => $request->role_id,
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
            'role_id'    => 'required|exists:roles,id'
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
            
            $user->delete();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Xoá người dùng thành công'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không thể xóa người dùng này vì có dữ liệu ràng buộc (VD: Lịch khám, Bệnh án...)'
            ], 500);
        }
    }
}
