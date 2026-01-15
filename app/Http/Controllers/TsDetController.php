<?php

namespace App\Http\Controllers;

use App\Models\TsDet;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTsDetRequest;
use App\Http\Requests\UpdateTsDetRequest;

class TsDetController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function updateDetail(Request $request, $id)
    {
        $det = TsDet::with('master')->where('ts_det_id', $id)->first();

        abort_if($det->master->ts_mstr_status !== 'draft', 403);

        $request->validate([
            'qty' => 'required|numeric|min:0.01'
        ]);

        $det->update([
            'ts_det_qty' => $request->qty
        ]);

        return response()->json(['success' => true]);
    }

    public function destroyDetail($id)
    {
        $det = TsDet::with('master')->where('ts_det_id', $id)->first();
        abort_if($det->master->ts_mstr_status !== 'draft', 403);
        $det->delete();
        return response()->json(['success' => true]);
    }
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
    public function store(StoreTsDetRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(TsDet $tsDet)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TsDet $tsDet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTsDetRequest $request, TsDet $tsDet)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TsDet $tsDet)
    {
        //
    }
}
