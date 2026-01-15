<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use Illuminate\Validation\ValidationException;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::all();
        return view('config.RoleMstrList', compact('roles'));
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
        try {
            $request->validate([
                'roles' => 'required|array',
                'permissions.*' => ['required', 'unique:roles,name'],
            ]);

            foreach ($request->roles as $role) {
                Role::create([
                    'name' => $role,
                    'guard_name' => 'web',
                ]);
            }

            return redirect()->back()->with('success', 'Role berhasil ditambahkan!');
        } catch (ValidationException $e) {
            // Laravel akan otomatis redirect back, tapi kalau kamu mau manual:
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (Exception $e) {
            Log::error('Gagal menambahkan permission', ['error' => $e->getMessage()]);

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan Role.');
        }
        // return response()->json(['message' => 'Permissions berhasil ditambahkan!']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        //
    }

    public function assignRole($idRole)
    {
        // $permissions = Permission::all();
        // Ambil semua permission dan kelompokkan berdasarkan prefix sebelum titik

        $permissions = Permission::all()->groupBy(function ($permission) {
            return Str::before($permission->name, '.');
        });
        $role = Role::with('permissions')->findOrFail($idRole);

        return view('config.AssignRolePermission', compact('permissions', 'role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $idRole)
    {
        $role = Role::findOrFail($idRole);

        $validated = $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        // Sink permission
        $role->syncPermissions($validated['permissions'] ?? []);

        return redirect()->back()->with('success', 'Permissions updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        //
    }
}
