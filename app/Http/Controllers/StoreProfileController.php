<?php

namespace App\Http\Controllers;

use App\Models\storeProfile;
use Illuminate\Http\Request;
use App\Http\Requests\StorestoreProfileRequest;
use App\Http\Requests\UpdatestoreProfileRequest;
use App\Models\CustTransactions;
use App\Models\ProductTransactions;
use App\Models\StoreProfile as ModelsStoreProfile;

class StoreProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $profile = StoreProfile::first();
        return view('store.StoreProfile', compact('profile'));
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
        // Validasi input
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'owner'       => 'nullable|string|max:255',
            'npwp'        => 'nullable|string|max:50',
            'address'     => 'nullable|string',
            'phone'       => 'nullable|string|max:50',
            'email'       => 'nullable|email|max:255',
            'logo'        => 'nullable|string|max:255',
            'footer_note' => 'nullable|string',
        ]);

        // Jika data hanya 1 baris (profile perusahaan), biasanya pakai first()
        $profile = StoreProfile::first();

        if ($profile) {
            // Update
            $profile->update($validated);
        } else {
            // Insert baru
            StoreProfile::create($validated);
        }

        return back()->with('success', 'Data profil berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(storeProfile $storeProfile)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(storeProfile $storeProfile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatestoreProfileRequest $request, storeProfile $storeProfile)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(storeProfile $storeProfile)
    {
        //
    }

    public function printInvoice($id)
    {
        $items = ProductTransactions::with(['product', 'measurement'])->where('transaction_id', $id)->get();
        $custTr = CustTransactions::with('customer')->findOrFail($id);
        $store = StoreProfile::first();

        return view('print.invoice', compact('store', 'items', 'custTr'));
    }

    public function printNota($id)
    {
        // dd($id);
        $items = ProductTransactions::with(['product', 'measurement'])->where('transaction_id', $id)->get();
        $custTr = CustTransactions::with('customer')->where('id', $id)->first();
        $store = StoreProfile::first();
        // dd($store);
        return view('print.nota', compact('store', 'items', 'custTr'));
    }
}
