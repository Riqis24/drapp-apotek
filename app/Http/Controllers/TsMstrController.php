<?php

namespace App\Http\Controllers;

use App\Models\TsDet;
use App\Models\stocks;
use App\Models\TsMstr;
use App\Models\LocMstr;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\TransferService;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreTsMstrRequest;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Requests\UpdateTsMstrRequest;

class TsMstrController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = TsMstr::with(['fromLocation', 'toLocation'])
            ->latest()
            ->get();



        return view('transfer.TsMstrList', compact('data'));
    }

    public function create()
    {
        $isvisible = auth()->user()->hasRole(['Super Admin', 'Owner']) ? [0, 1] : [1];


        $locations = LocMstr::get();
        $products = Product::whereIn('is_visible', $isvisible)->get();
        // dd($products);
        return view('transfer.TsMstrForm', compact('locations', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'from_loc' => 'required|different:to_loc',
            'to_loc' => 'required',
            'items' => 'required|array|min:1',
        ]);

        DB::transaction(function () use ($request) {

            $ts = TsMstr::create([
                'ts_mstr_nbr'        => $this->generateNumber(),
                'ts_mstr_date'       => $request->date,
                'ts_mstr_from' => $request->from_loc,
                'ts_mstr_to'   => $request->to_loc,
                'ts_mstr_note'       => $request->note,
                'ts_mstr_createdby'  => auth()->user()->user_mstr_id,
            ]);

            foreach ($request->items as $item) {
                $um = Product::where('id', $item['product_id'])->value('measurement_id');
                TsDet::create([
                    'ts_det_mstrid'  => $ts->ts_mstr_id,
                    'ts_det_productid' => $item['product_id'],
                    'ts_det_batchid'   => $item['batch_id'],
                    'ts_det_um'        => $um,
                    'ts_det_qty'       => $item['qty'],
                    // 'ts_det_qtyconv'   => $item['qty'],
                ]);
            }
        });

        return redirect()->route('TsMstr.index')
            ->with('success', 'Transfer Sheet berhasil dibuat');
    }

    public function show($id)
    {
        $ts = TsMstr::with(['fromLocation', 'toLocation', 'details.product', 'details.batch'])->where('ts_mstr_id', $id)->first();
        // dd($ts);
        return view('transfer.TsDetList', compact('ts'));
    }

    public function post($id, TransferService $service)
    {
        $ts = TsMstr::findOrFail($id);
        // dd($ts);
        if ($ts->ts_mstr_status !== 'draft') {
            abort(403);
        }

        $service->post($ts);

        return redirect()->route('TsMstr.show', $ts)
            ->with('success', 'Transfer berhasil diposting');
    }

    public function cancelpost($id, TransferService $service)
    {
        $ts = TsMstr::findOrFail($id);
        // dd($ts);
        if ($ts->ts_mstr_status !== 'posted') {
            abort(403);
        }

        $service->cancelpost($ts);

        return redirect()->route('TsMstr.show', $ts)
            ->with('success', 'Transfer berhasil dicancel');
    }

    protected function generateNumber()
    {
        $last = TsMstr::latest('ts_mstr_id')->first();
        $next = $last ? ((int)substr($last->ts_mstr_nbr, -4) + 1) : 1;

        return 'TS-' . str_pad($next, 4, '0', STR_PAD_LEFT);
    }

    public function items(Request $request)
    {
        $isvisible = auth()->user()->hasRole(['Super Admin', 'Owner']) ? [0, 1] : [1];

        $locId = $request->loc_id;
        $q = $request->q;
        $stocks = Stocks::with([
            'product',
            'batch',
            'product.measurement',
        ])
            ->where('loc_id', $locId)
            ->where('quantity', '>', 0)
            ->whereHas('product', function (Builder $query) use ($q, $isvisible) {
                $query->where('name', 'like', '%' . $q . '%')->whereIn('is_visible', $isvisible);
            })
            ->get();

        return response()->json(
            $stocks->map(function ($s) {
                return [
                    'product_id' => $s->product_id,
                    'text' => $s->product->name,
                ];
            })->unique('product_id')->values()
        );
    }

    // 🔹 batch per product
    public function batches(Request $request)
    {
        $stocks = stocks::with('batch')
            ->where('loc_id', $request->loc_id)
            ->where('product_id', $request->product_id)
            ->where('quantity', '>', 0)
            ->get();

        return response()->json(
            $stocks->map(function ($s) {
                return [
                    'batch_id' => $s->batch_id,
                    'text' => $s->batch->batch_mstr_no . ' | exp ' . $s->batch->batch_mstr_expireddate,
                    'qty_base' => numfmt($s->quantity),
                ];
            })
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TsMstr $tsMstr)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTsMstrRequest $request, TsMstr $tsMstr)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $ts = TsMstr::findOrFail($id);
        if ($ts->ts_mstr_status == 'draft' || $ts->ts_mstr_status == 'cancelled') {
            TsDet::where('ts_det_mstrid', $ts->ts_mstr_id)->delete();
            $ts->delete();
            return redirect()->back()->with('success', 'Deleted Successfully');
        } else {
            return redirect()->back()->with('error', 'Status TS Posted, Cannot Delete');
        }
    }
}
