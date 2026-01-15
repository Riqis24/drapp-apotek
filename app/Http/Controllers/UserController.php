<?php

namespace App\Http\Controllers;

use App\Models\Role;
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
        $users = User::query()->get();
        $roles = Role::query()->get();
        return view('config.UserMstrList', compact('users', 'roles'));
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
            'f_Name' => 'required|string|max:255',
            'f_Email' => 'required|email|unique:user_mstr,user_mstr_email',
            'f_Password' => 'required|string|min:8',
            'f_Role' => 'required|string',
        ]);

        // dd($request->all());

        User::create([
            'user_mstr_name' => $request->f_Name,
            'user_mstr_email' => $request->f_Email,
            'user_mstr_password' => Hash::make($request->f_Password),
            'user_mstr_active' => 1,
            'user_mstr_role' => $request->f_Role,
            'user_mstr_ct' => now(),
        ]);

        return redirect()->back()->with('success', 'User berhasil ditambahkan.');
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
    public function update(Request $request, string $id) {}

    public function updateInline(request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|string|min:8',
            'role' => 'required|string',
        ]);

        $user = User::findOrFail($id);
        $user->user_mstr_name = $request->name;
        $user->user_mstr_email = $request->email;
        $user->user_mstr_password = Hash::make($request->password);
        $user->user_mstr_role = $request->role;
        $user->save();

        // Assign ke Spatie (biar relasinya juga ke-update)
        $user->syncRoles([$request->role]);

        // return redirect()->back()->with('success', 'Data berhasil diupdate!');

        // return response()->json(['message' => 'User updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
