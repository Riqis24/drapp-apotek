<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\SaDet;
use App\Models\SaMstr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreSaMstrRequest;
use App\Http\Requests\UpdateSaMstrRequest;
use App\Models\BatchMstr;
use App\Models\LocMstr;
use App\Models\Product;
use App\Models\stocks;
use App\Models\StockTransactions;

class SaMstrController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * =====================================================
     * INDEX
     * =====================================================
     */
    public function index()
    {
        $data = SaMstr::with(['location', 'createdby'])->orderByDesc('sa_mstr_id')->get();
        return view('adjustment.SaMstrList', compact('data'));
    }

    /**
     * =====================================================
     * CREATE FORM
     * =====================================================
     */
    public function create()
    {
        $locations = LocMstr::all();
        $products = Product::where('type', 'single')->orderBy('name')->get();
        return view('adjustment.SaMstrForm', compact('locations', 'products'));
    }

    /**
     * =====================================================
     * STORE (DRAFT)
     * =====================================================
     */
    public function store(Request $request)
    {
        // dd($request->all());
        DB::transaction(function () use ($request) {

            $sa = SaMstr::create([
                'sa_mstr_nbr'       => $this->generateSaNumber(),
                'sa_mstr_date'      => now(),
                'sa_mstr_locid'     => $request->loc_id,
                'sa_mstr_ref'       => $request->ref,
                'sa_mstr_reason'    => $request->reason,
                'sa_mstr_status'    => 'draft',
                'sa_mstr_createdby' => auth()->user()->user_mstr_id,
            ]);
            // dd($sa);

            foreach ($request->items as $item) {

                $qtySystem   = $item['qty_system'] ?? 0;
                $qtyPhysical = $item['qty_physical'];
                $qtyDiff     = $qtyPhysical - $qtySystem;

                // ======================
                // GET / CREATE BATCH
                // ======================
                if (!empty($item['batch_id'])) {
                    $batchId = $item['batch_id'];
                } else {
                    if (empty($item['batch_no']) || empty($item['expired_date'])) {
                        throw new \Exception('Batch & expired wajib diisi');
                    }

                    $batch = BatchMstr::firstOrCreate(
                        [
                            'batch_mstr_productid' => $item['product_id'],
                            'batch_mstr_no'   => $item['batch_no'],
                        ],
                        [
                            'batch_mstr_expireddate' => $item['expired_date'],
                        ]
                    );

                    $batchId = $batch->batch_mstr_id;
                }


                SaDet::create([
                    'sa_det_mstrid'      => $sa->sa_mstr_id,
                    'sa_det_productid'   => $item['product_id'],
                    'sa_det_batchid'     => $batchId,
                    'sa_det_qtysystem'   => $qtySystem,
                    'sa_det_qtyphysical' => $qtyPhysical,
                    'sa_det_qtydiff'    => $qtyDiff,
                    'sa_det_note'        => $item['note'] ?? null,
                ]);
            }
        });

        return redirect()->route('SaMstr.index')
            ->with('success', 'Stock Adjustment draft saved');
    }


    /**
     * =====================================================
     * SHOW
     * =====================================================
     */
    public function show($id)
    {
        $sa = SaMstr::with(['details.product', 'details.batch', 'location'])->findOrFail($id);
        return view('adjustment.SaMstrEdit', compact('sa'));
    }

    /**
     * =====================================================
     * POST ADJUSTMENT
     * =====================================================
     */
    public function post($id)
    {
        $sa = SaMstr::with('details')->findOrFail($id);

        DB::transaction(function () use ($sa) {

            if ($sa->sa_mstr_status === 'posted') {
                throw new Exception('Stock Adjustment sudah di-post');
            }

            foreach ($sa->details as $det) {

                $qtyDiff = $det->sa_det_qtydiff;
                if ($qtyDiff == 0) continue;

                // =========================
                // STOCK TRANSACTION
                // =========================
                StockTransactions::create([
                    'product_id'  => $det->sa_det_productid,
                    'loc_id'      => $sa->sa_mstr_locid,
                    'batch_id'    => $det->sa_det_batchid,
                    'type'        => $qtyDiff > 0 ? 'in' : 'out',
                    'quantity'    => $qtyDiff,
                    'note'        => 'Stock Adjustment',
                    'date'        => $sa->sa_mstr_date,
                    'source_type' => SaMstr::class,
                    'source_id'   => $sa->sa_mstr_id,
                ]);

                // =========================
                // UPDATE STOCK
                // =========================
                $stock = stocks::firstOrCreate(
                    [
                        'product_id' => $det->sa_det_productid,
                        'loc_id'     => $sa->sa_mstr_locid,
                        'batch_id'   => $det->sa_det_batchid,
                    ],
                    ['quantity' => 0]
                );

                if ($qtyDiff > 0) {
                    $stock->quantity += $qtyDiff;
                } else {
                    if ($stock->quantity < abs($qtyDiff)) {
                        throw new Exception('Stok tidak mencukupi untuk pengurangan');
                    }
                    $stock->quantity -= abs($qtyDiff);
                }

                $stock->save();
            }

            // =========================
            // UPDATE STATUS
            // =========================
            $sa->update([
                'sa_mstr_status' => 'posted'
            ]);
        });

        return redirect()
            ->route('SaMstr.show', $sa->sa_mstr_id)
            ->with('success', 'Stock Adjustment berhasil di-post');
    }

    /**
     * =====================================================
     * DELETE (DRAFT ONLY)
     * =====================================================
     */
    public function destroy($id)
    {
        $sa = SaMstr::with('details')->findOrFail($id);

        if ($sa->sa_mstr_status === 'posted') {
            return back()->with('error', 'Adjustment yang sudah POSTED tidak bisa dihapus');
        }

        DB::transaction(function () use ($sa) {
            $sa->details()->delete();
            $sa->delete();
        });

        return redirect()
            ->route('SaMstr.index')
            ->with('success', 'Stock Adjustment berhasil dihapus');
    }

    /**
     * =====================================================
     * GENERATE SA NUMBER
     * =====================================================
     */
    private function generateSaNumber()
    {
        $last = SaMstr::orderByDesc('sa_mstr_id')->first();
        $next = $last ? $last->sa_mstr_id + 1 : 1;

        return 'SA-' . date('Ym') . '-' . str_pad($next, 5, '0', STR_PAD_LEFT);
    }

    public function reverse($id)
    {
        $sa = SaMstr::with('details')->findOrFail($id);

        DB::transaction(function () use ($sa) {

            if ($sa->sa_mstr_status !== 'posted') {
                throw new Exception('Hanya adjustment POSTED yang bisa di-reverse');
            }

            foreach ($sa->details as $det) {

                $qtyDiff = $det->sa_det_qtydiff;
                if ($qtyDiff == 0) continue;

                // =========================
                // KEBALIKAN TRANSAKSI
                // =========================
                $reverseType = $qtyDiff > 0 ? 'OUT' : 'IN';
                $reverseQty  = abs($qtyDiff);

                // =========================
                // INSERT STOCK TRANSACTION
                // =========================
                StockTransactions::create([
                    'product_id'  => $det->sa_det_productid,
                    'loc_id'      => $sa->sa_mstr_locid,
                    'batch_id'    => $det->sa_det_batchid,
                    'type'        => $reverseType,
                    'quantity'    => $reverseQty,
                    'note'        => 'Reverse Stock Adjustment',
                    'date'        => now(),
                    'source_type' => SaMstr::class,
                    'source_id'   => $sa->id,
                ]);

                // =========================
                // UPDATE STOCK
                // =========================
                $stock = stocks::where([
                    'product_id' => $det->sa_det_productid,
                    'loc_id'     => $sa->sa_mstr_locid,
                    'batch_id'   => $det->sa_det_batchid,
                ])->lockForUpdate()->first();

                if (!$stock) {
                    throw new Exception('Stock tidak ditemukan saat reverse');
                }

                if ($reverseType === 'OUT') {
                    if ($stock->quantity < $reverseQty) {
                        throw new Exception('Stock tidak mencukupi untuk reverse');
                    }
                    $stock->quantity -= $reverseQty;
                } else {
                    $stock->quantity += $reverseQty;
                }

                $stock->save();
            }

            // =========================
            // UPDATE STATUS SA
            // =========================
            $sa->update([
                'sa_mstr_status' => 'reversed'
            ]);
        });

        return redirect()
            ->route('SaMstr.show', $sa->id)
            ->with('success', 'Stock Adjustment berhasil di-reverse');
    }

    public function getQty(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'loc_id'     => 'required',
            'batch_id'   => 'nullable'
        ]);

        /* ===============================
         * Jika batch BELUM dipilih
         * =============================== */
        if (empty($request->batch_id)) {
            return response()->json([
                'quantity' => 0
            ]);
        }

        /* ===============================
         * Ambil stock
         * =============================== */
        $stock = stocks::where([
            'product_id' => $request->product_id,
            'loc_id'     => $request->loc_id,
            'batch_id'   => $request->batch_id,
        ])->first();

        return response()->json([
            'quantity' => $stock?->quantity ?? 0
        ]);
    }

    public function byProduct(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'loc_id'     => 'required',
        ]);

        /* =====================================
         * Ambil batch yang ADA stok di lokasi
         * ===================================== */
        $batches = BatchMstr::select(
            'batch_mstr_id',
            'batch_mstr_no',
            'batch_mstr_expireddate'
        )
            ->join('stocks', 'stocks.batch_id', '=', 'batch_mstr_id')
            ->where('stocks.product_id', $request->product_id)
            ->where('stocks.loc_id', $request->loc_id)
            ->where('stocks.quantity', '>', 0)
            ->orderBy('batch_mstr_expireddate')
            ->distinct()
            ->get();
        // dd($batches);

        return response()->json($batches);
    }
}
