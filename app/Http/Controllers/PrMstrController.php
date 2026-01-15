<?php

namespace App\Http\Controllers;

use App\Models\PoMstr;
use App\Models\PrMstr;
use App\Models\BpbMstr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\PurchaseReturnService;
use App\Http\Requests\StorePrMstrRequest;
use App\Http\Requests\UpdatePrMstrRequest;

class PrMstrController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected PurchaseReturnService $service;

    public function __construct(PurchaseReturnService $service)
    {
        $this->service = $service;
    }
    /* ===============================
     * LIST RETURN
     * =============================== */
    public function index()
    {
        $returns = PrMstr::with(['bpb', 'po'])
            ->orderBy('pr_mstr_date', 'desc')
            ->get();

        return view('purchase.PurchaseReturnList', compact('returns'));
    }

    /* ===============================
     * FORM CREATE
     * =============================== */
    public function create($id)
    {
        $bpb = BpbMstr::with(['supplier', 'po', 'details.product', 'details.batch'])->findOrfail($id);
        // dd($bpb->details);
        return view('purchase.PurchaseReturnForm', compact('bpb'));
    }

    /* ===============================
     * AJAX: LOAD BPB ITEMS
     * =============================== */
    public function getBpbItems(Request $request)
    {
        $items = app(PurchaseReturnService::class)
            ->getBpbItemsWithRemaining($request->bpb_id);

        return response()->json($items);
    }

    /* ===============================
     * STORE RETURN
     * =============================== */
    public function store(Request $request, PurchaseReturnService $service)
    {
        $request->validate([
            'bpb_id' => 'required|exists:bpb_mstr,bpb_mstr_id',
            'pr_mstr_date' => 'required|date',
            'items' => 'required|array',
        ]);

        DB::beginTransaction();
        try {
            $service->process($request->all());

            DB::commit(); // Wajib Commit jika sukses

            return redirect()
                ->route('BpbMstr.index')
                ->with('success', 'Return pembelian berhasil disimpan');
        } catch (\Throwable $e) {
            DB::rollBack(); // Wajib Rollback jika gagal

            // Log error untuk debug internal
            Log::error($e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    /* ===============================
     * SHOW DETAIL
     * =============================== */
    public function show($id)
    {
        $pr = PrMstr::with([
            'bpb',
            'po.supplier',
            'details.product',
            'details.measurement',
            'details.batch',
        ])->findOrFail($id);

        return view('purchase.PurchaseReturnDet', compact('pr'));
    }

    /* ===============================
     * CANCEL RETURN
     * =============================== */
    public function destroy($id, PurchaseReturnService $service)
    {
        try {
            $service->cancel($id);

            return back()->with('success', 'Return berhasil dibatalkan');
        } catch (\Throwable $e) {

            return back()->with('error', $e->getMessage());
        }
    }
}
