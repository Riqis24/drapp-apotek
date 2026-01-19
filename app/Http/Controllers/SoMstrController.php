<?php

namespace App\Http\Controllers;


use App\Models\SaDet;
use App\Models\SoDet;
use App\Models\SaMstr;
use App\Models\SoMstr;
use App\Models\stocks;
use App\Models\LocMstr;
use Illuminate\Http\Request;
use App\Models\StockTransactions;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreSoMstrRequest;
use App\Http\Requests\UpdateSoMstrRequest;

class SoMstrController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $opnames = SoMstr::with(['location', 'createdBy'])
            ->latest('so_mstr_date')
            ->get();

        return view('so.SoMstrList', compact('opnames'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $locations = LocMstr::all();

        return view('so.SoMstrForm', compact('locations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date'  => 'required|date',
            'locid' => 'required|exists:loc_mstr,loc_mstr_id',
        ]);

        $so = SoMstr::create([
            'so_mstr_nbr'        => $this->generateSoNumber(),
            'so_mstr_date'       => $request->date,
            'so_mstr_locid'      => $request->locid,
            'so_mstr_status'     => 'draft',
            'so_mstr_createdby'  => auth()->user()->user_mstr_id,
        ]);

        return redirect()
            ->route('SoMstr.edit', $so->so_mstr_id)
            ->with('success', 'Stock opname dibuat');
    }

    protected function generateSoNumber()
    {
        $last = SoMstr::latest('so_mstr_id')->first();
        $next = $last ? ((int)substr($last->so_mstr_nbr, -4) + 1) : 1;

        return 'SO-' . str_pad($next, 4, '0', STR_PAD_LEFT);
    }

    protected function generateSaNumber()
    {
        $last = SaMstr::latest('sa_mstr_id')->first();
        $next = $last ? ((int)substr($last->sa_mstr_nbr, -4) + 1) : 1;

        return 'ADJ-' . str_pad($next, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Display the specified resource.
     */
    public function show(SoMstr $soMstr)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // dd($id);
        $so = SoMstr::with(['details.product.measurement', 'location'])->findOrFail($id);
        $products = stocks::with(['product', 'batch'])->where('loc_id', $so->so_mstr_locid)->where('quantity', '>', 0)
            ->get();

        if (!$so->details()->exists()) {
            $this->generateSnapshot($so);

            // 🔄 reload relasi
            $so->load(['details.product', 'details.batch']);
        }

        return view('so.SoMstrEdit', compact('so', 'products'));
    }

    public function viewApprove($id)
    {
        // dd($id);
        $so = SoMstr::with(['details.product.measurement', 'location'])->findOrFail($id);

        return view('so.SoMstrApprove', compact('so'));
    }


    protected function generateSnapshot(SoMstr $so)
    {
        $stocks = stocks::where('loc_id', $so->so_mstr_locid)->where('quantity', '>', 0)
            ->get();

        // dd($stocks);

        foreach ($stocks as $stock) {
            SoDet::create([
                'so_det_mstrid'      => $so->so_mstr_id,
                'so_det_productid'   => $stock->product_id,
                'so_det_batchid'     => $stock->batch_id,
                'so_det_qtysystem'  => $stock->quantity,
                'so_det_qtyphysical' => $stock->quantity, // default sama
            ]);
        }
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $so = SoMstr::findOrFail($id);

        if ($so->so_mstr_status !== 'draft') {
            abort(403, 'Opname sudah dikunci');
        }

        foreach ($request->details as $detId => $row) {
            SoDet::where('so_det_id', $detId)
                ->update([
                    'so_det_qtyphysical' => $row['qty_physical'],
                    'so_det_note'         => $row['note'] ?? null,
                ]);
        }

        return back()->with('success', 'Data opname disimpan');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            // Ambil data SO dengan lock agar tidak diproses user lain secara bersamaan
            $so = SoMstr::with('details')->lockForUpdate()->findOrFail($id);

            // KONDISI 1: JIKA MASIH DRAFT
            if ($so->so_mstr_status === 'draft') {
                $so->details()->delete();
                $so->delete();

                DB::commit();
                // return response()->json(['status' => 'success', 'message' => 'Draft Opname berhasil dihapus']);
                return back()->with('success', 'Draft Opname berhasil dihapus');
            }

            // KONDISI 2: JIKA SUDAH APPROVED (REVERSE STOK)
            if ($so->so_mstr_status === 'approved') {

                // 1. Cari SA terkait (Adjustment yang dibuat saat post)
                $sa = SaMstr::where('sa_mstr_ref', $so->so_mstr_id)->first();

                if ($sa) {
                    // Ambil semua detail SA untuk dibalikkan stoknya
                    $saDetails = SaDet::where('sa_det_mstrid', $sa->sa_mstr_id)->get();

                    foreach ($saDetails as $det) {
                        // Dapatkan selisih yang pernah dibuat (kita balikkan nilainya)
                        // Jika dulu +5 (In), sekarang harus -5 (Out)
                        $reverseQty = $det->sa_det_qtydiff * -1;

                        // 2. Balikkan Stok Balance
                        Stocks::updateOrCreate(
                            [
                                'product_id' => $det->sa_det_productid,
                                'batch_id'   => $det->sa_det_batchid,
                                'loc_id'     => $sa->sa_mstr_locid,
                            ],
                            [
                                'quantity' => DB::raw("quantity + {$reverseQty}")
                            ]
                        );

                        // 3. Catat transaksi stok baru sebagai "VOID/CANCEL"
                        StockTransactions::create([
                            'product_id'  => $det->sa_det_productid,
                            'loc_id'      => $sa->sa_mstr_locid,
                            'batch_id'    => $det->sa_det_batchid,
                            'type'        => $reverseQty > 0 ? 'in' : 'out',
                            'quantity'    => abs($reverseQty),
                            'source_type' => SoMstr::class, // Tandai sebagai Void
                            'source_id'   => $so->so_mstr_id,
                            'note'        => 'Void Stock Opname - Reversal of Adjustment (' . $so->so_mstr_nbr . ')',
                            'date'        => now(),
                            'created_by' => auth()->user()->user_mstr_id,

                        ]);
                    }

                    // 4. Hapus SA dan Detail SA terkait SO ini
                    SaDet::where('sa_det_mstrid', $sa->sa_mstr_id)->delete();
                    $sa->delete();
                }

                // 5. Hapus SO (Atau ganti status jadi 'cancelled' jika ingin tetap ada history)
                $so->details()->delete();
                $so->delete();

                DB::commit();
                return back()->with('success', 'Opname berhasil di-void dan stok dikembalikan');

                // return response()->json(['status' => 'success', 'message' => 'Opname berhasil di-void dan stok dikembalikan']);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function deleteDetails(Request $request, SoMstr $so)
    {

        // dd($request->all());
        if ($so->so_mstr_status !== 'draft') {
            abort(403, 'Stock opname sudah dikunci');
        }

        $request->validate([
            'detail_ids' => 'required|array|min:1'
        ]);

        DB::transaction(function () use ($request, $so) {

            SoDet::where('so_det_mstrid', $so->so_mstr_id)
                ->whereIn('so_det_id', $request->detail_ids)
                ->delete();
        });

        return back()->with('success', 'Detail berhasil dihapus');
    }


    public function approve(Request $request, $id)
    {
        // dd($id);
        DB::transaction(function () use ($id) {

            $so = SoMstr::with('details')->lockForUpdate()->findOrFail($id);

            if ($so->so_mstr_status !== 'draft') {
                throw new \Exception('Opname sudah diproses');
            }

            // create adjustment
            $sa = SaMstr::create([
                'sa_mstr_nbr'        => $this->generateSaNumber(),
                'sa_mstr_date'      => now(),
                'sa_mstr_locid'     => $so->so_mstr_locid,
                'sa_mstr_ref'       => $so->so_mstr_id,
                'sa_mstr_reason'    => 'Stock Opname',
                'sa_mstr_status'    => 'posted',
                'sa_mstr_createdby' => auth()->user()->user_mstr_id,
            ]);


            foreach ($so->details as $det) {

                $diff = $det->so_det_qtyphysical - $det->so_det_qtysystem;
                // dd($diff);
                if ($diff == 0) continue;

                SaDet::create([
                    'sa_det_mstrid'       => $sa->sa_mstr_id,
                    'sa_det_productid'    => $det->so_det_productid,
                    'sa_det_batchid'      => $det->so_det_batchid,
                    'sa_det_qtysystem'   => $det->so_det_qtysystem,
                    'sa_det_qtyphysical' => $det->so_det_qtyphysical,
                    'sa_det_qtydiff'     => $diff,
                ]);

                // stock transaction
                StockTransactions::create([
                    'product_id'  => $det->so_det_productid,
                    'loc_id'      => $so->so_mstr_locid,
                    'batch_id'    => $det->so_det_batchid,
                    'type'        => $diff > 0 ? 'in' : 'out',
                    'quantity'    => $diff,
                    'note'        => 'Stock Opname Adjustment (' . $so->so_mstr_nbr . ')',
                    'source_type' => SaMstr::class,
                    'source_id'   => $sa->sa_mstr_id,
                    'date'        => now(),
                    'created_by' => auth()->user()->user_mstr_id,

                ]);

                Stocks::updateOrCreate(
                    [
                        'product_id' => $det->so_det_productid,
                        'batch_id'   => $det->so_det_batchid,
                        'loc_id'     => $so->so_mstr_locid,
                    ],
                    [
                        'quantity' => DB::raw("quantity + {$diff}")
                    ]
                );
            }

            $so->update([
                'so_mstr_status'     => 'approved',
                'so_mstr_approvedby' => auth()->user()->user_mstr_id,
                'so_mstr_approvedate' => now(),
            ]);
        });

        return redirect()
            ->route('SoMstr.index')
            ->with('success', 'Stock opname berhasil diposting');
    }
}
