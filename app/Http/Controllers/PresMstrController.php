<?php

namespace App\Http\Controllers;

use App\Models\PresMstr;
use Illuminate\Http\Request;
use App\Services\PrescriptionService;
use App\Http\Requests\StorePresMstrRequest;
use App\Http\Requests\UpdatePresMstrRequest;

class PresMstrController extends Controller
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
    public function store(Request $request, PrescriptionService $service)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'qty'  => 'required|numeric|min:1',
            'items' => 'required|array|min:1',
        ]);

        $status = $request->from_cashier ? 'ready' : 'draft';

        $pres = $service->create(
            [
                'code'   => $this->generatePresCode(),
                'name'   => $request->name,
                'doctor' => $request->doctor,
                'qty'    => $request->qty,
                'fee'    => $request->fee,
                'mark'   => $request->mark,
            ],
            $request->items,
            $status
        );

        return response()->json([
            'success' => true,
            'data' => $pres
        ]);
    }

    protected function generatePresCode()
    {
        $last = PresMstr::latest('pres_mstr_code')->first();
        $next = $last ? ((int)substr($last->pres_mstr_code, -4) + 1) : 1;

        return 'PRES-' . str_pad($next, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Display the specified resource.
     */
    public function show(PresMstr $presMstr)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PresMstr $presMstr)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePresMstrRequest $request, PresMstr $presMstr)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PresMstr $presMstr)
    {
        //
    }
}
