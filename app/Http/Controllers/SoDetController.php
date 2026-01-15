<?php

namespace App\Http\Controllers;

use App\Models\SoDet;
use App\Models\SoMstr;
use Illuminate\Http\Request;
use App\Http\Requests\StoreSoDetRequest;
use App\Http\Requests\UpdateSoDetRequest;
use App\Models\stocks;

class SoDetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(SoMstr $so)
    {
        abort_if($so->so_mstr_status !== 'draft', 403);

        return view('so.items', [
            'so'       => $so->load('details.product'),
            'products' => stocks::with(['product', 'batch'])->orderBy('name')->get()
        ]);
    }

    // ADD ITEM
    public function store(Request $request, SoMstr $so)
    {
        abort_if($so->so_mstr_status !== 'draft', 403);

        $request->validate([
            'stock_id'   => 'required|exists:stocks,id',
            'qty_physical' => 'required|numeric|min:0',
        ]);

        $stock = stocks::where('id', $request->stock_id)->first();
        // dd($stock);

        $sodet = SoDet::updateOrCreate(
            [
                'so_det_mstrid'           => $so->so_mstr_id,
                'so_det_productid'        => $stock->product_id,
                'so_det_batchid'          => $stock->batch_id,
            ],
            [
                'so_det_qtyphysical'   => $request->qty_physical,
                'so_det_note'          => null,
            ]
        );

        // dd($sodet);
        return back()->with('success', 'Item sudah diupdate/ditambahkan');
    }

    // UPDATE QTY (BULK)
    public function update(Request $request, SoMstr $so)
    {
        abort_if($so->so_mstr_status !== 'draft', 403);

        foreach ($request->details ?? [] as $detId => $qty) {
            SoDet::where('so_det_id', $detId)
                ->where('so_mstr_id', $so->so_mstr_id)
                ->update([
                    'so_det_qtyphysical' => $qty
                ]);
        }

        return back()->with('success', 'Perubahan disimpan');
    }

    // DELETE ITEM
    public function destroy(SoDet $detail)
    {
        if ($detail->so->so_mstr_status !== 'draft') {
            return response()->json([
                'message' => 'SO sudah di-approve'
            ], 403);
        }

        $detail->delete();

        return response()->json([
            'message' => 'Item berhasil dihapus'
        ]);
    }
}
