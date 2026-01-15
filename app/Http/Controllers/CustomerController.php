<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = Customer::all();
        return view('master.CustMstrList', compact('customers'));
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
            Customer::create([
                'name' => $request->name,
                'address' => $request->address,
                'phone' => $request->phone,
                'type' => $request->type,
                'isvisible' => $request->isvisible,
                'total_outstanding' => $request->outstanding ?? 0,
            ]);
            return redirect()->back()->with('success', 'Customer berhasil ditambahkan!');
        } catch (ValidationException $e) {
            // Laravel akan otomatis redirect back, tapi kalau kamu mau manual:
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (Exception $e) {
            Log::error('Gagal menambahkan Customer', ['error' => $e->getMessage()]);

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan customer.');
        }
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
