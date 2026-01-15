<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use Illuminate\Validation\ValidationException;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perms = Permission::all();
        return view('config.PermissionMstrList', compact('perms'));
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
                'permissions' => 'required|array',
                'permissions.*' => ['required', 'regex:/^[a-z_]+\.[a-z_]+$/', 'unique:permissions,name'],
            ]);

            foreach ($request->permissions as $perm) {
                Permission::create([
                    'name' => $perm,
                    'guard_name' => 'web',
                ]);
            }

            return redirect()->back()->with('success', 'Permission berhasil ditambahkan!');
        } catch (ValidationException $e) {
            // Laravel akan otomatis redirect back, tapi kalau kamu mau manual:
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (Exception $e) {
            Log::error('Gagal menambahkan permission', ['error' => $e->getMessage()]);

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan permission.');
        }
        // return response()->json(['message' => 'Permissions berhasil ditambahkan!']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePermissionRequest $request, Permission $permission)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        //
    }
}
