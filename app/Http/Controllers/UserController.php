<?php

namespace App\Http\Controllers;

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

        User::create([
            'username'   => $request->username,
            'password'   => Hash::make($request->password),
            'full_name'  => $request->full_name,
            'gender'     => $request->gender,
            'birth_year' => $request->birth_year,
            'phone'      => $request->phone,
            'status'     => $request->status,
            'role_id'    => $request->role_id,
            ]);

        return redirect()->back()->with('success', 'Thêm user thành công');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
