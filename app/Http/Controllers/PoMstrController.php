<?php

namespace App\Http\Controllers;

use App\Models\PoDet;
use App\Models\BpbDet;
use App\Models\PoMstr;
use App\Models\Product;
use App\Models\SuppMstr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ProductMeasurements;
use App\Http\Requests\StorePoMstrRequest;
use App\Http\Requests\UpdatePoMstrRequest;

class PoMstrController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = PoMstr::with(['supplier', 'user'])->orderBy('po_mstr_id', 'desc')->get();
        return view('purchase.PurchaseOrderMstr', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = SuppMstr::get();

        $locId = auth()->user()->hasRole(['Super Admin', 'Owner']) ? null : 1; // Sesuaikan ambil Loc ID dari session/request

        $products = Product::query()
            ->with(['stocks' => function ($q) use ($locId) {
                $q->orderBy('created_at', 'asc')
                    ->when($locId, function ($query) use ($locId) {
                        return $query->where('loc_id', $locId);
                    });
            }])
            ->whereHas('stocks', function ($q) use ($locId) {
                $q->when($locId, function ($query) use ($locId) {
                    return $query->where('loc_id', $locId);
                });
            })
            ->get();

        // dd($products);

        // dd($products);
        $ums = ProductMeasurements::with('measurement')->get();
        return view('purchase.PurchaseOrderForm', compact('suppliers', 'products', 'ums'));
    }

    public function getProductUms($productId)
    {
        $ums = ProductMeasurements::where('product_id', $productId)
            ->with('measurement')
            ->get()
            ->map(function ($pm) {
                return [
                    'id' => $pm->measurement->id, // product_measurements.id
                    'name' => $pm->measurement->name,
                ];
            });

        return response()->json($ums);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        $last = PoMstr::orderBy('po_mstr_id', 'desc')->first();

        if ($last && preg_match('/PO-(\d+)/', $last->po_mstr_nbr, $m)) {
            $nextNumber = intval($m[1]) + 1;
        } else {
            $nextNumber = 1;
        }

        $ponbr = 'PO-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        try {
            // 1. Simpan PO Master
            $poMstrId = DB::table('po_mstr')->insertGetId([
                'po_mstr_nbr'        => $ponbr,
                'po_mstr_suppid'      => $request->suppid,
                'po_mstr_date'        => $request->po_date,
                'po_mstr_eta'         => $request->po_eta,
                'po_mstr_status'      => 'draft',

                'po_mstr_payment'    => $request->payment_type,
                'po_mstr_duedate'     => $request->payment_type === 'credit' ? $request->due_date : $request->po_date,

                'po_mstr_disctype'    => $request->disctype,
                'po_mstr_discvalue'  => $request->discvalue ?? 0,
                'po_mstr_ppntype'     => $request->ppntype,
                'po_mstr_ppnrate'     => $request->ppnrate ?? 0,

                'po_mstr_createdby'   => auth()->id(),
                'po_mstr_createdat'   => now(),
            ]);

            $subtotal = 0;

            // 2. Simpan Detail
            foreach ($request->items as $item) {
                $qty   = (float) $item['qty'];
                $price = (float) $item['price'];

                $lineTotal = $qty * $price;

                // diskon item
                $discAmt = 0;
                if (!empty($item['disctype'])) {
                    if ($item['disctype'] === 'percent') {
                        $discAmt = $lineTotal * ($item['discvalue'] / 100);
                    } else {
                        $discAmt = $item['discvalue'];
                    }
                }

                $lineTotal -= $discAmt;
                $subtotal  += $lineTotal;

                $umconv = ProductMeasurements::where('product_id', $item['productid'])->where('measurement_id', $item['umid'])->first();

                // dd($umconv);

                DB::table('po_det')->insert([
                    'po_det_mstrid'   => $poMstrId,
                    'po_det_productid' => $item['productid'],
                    'po_det_um' => $item['umid'],
                    'po_det_umconv' => $umconv->conversion,
                    'po_det_qty'      => $qty,
                    'po_det_qtyremain' => $qty,
                    'po_det_price'    => $price,
                    'po_det_disctype' => $item['disctype'] ?? null,
                    'po_det_discvalue' => $item['discvalue'] ?? 0,
                    'po_det_discamt'  => $discAmt,
                    'po_det_total'    => $lineTotal,
                ]);
            }

            // 3. Diskon Global PO
            $discAmt = 0;
            if ($request->disctype === 'percent') {
                $discAmt = $subtotal * ($request->discvalue / 100);
            } elseif ($request->disctype === 'amount') {
                $discAmt = $request->discvalue;
            }

            $afterDisc = $subtotal - $discAmt;

            // 4. Hitung PPN
            $ppnAmt = 0;
            if ($request->ppntype === 'include') {
                $ppnAmt = $afterDisc * ($request->ppnrate / 100);
                $grand  = $afterDisc + $ppnAmt;
            } else {
                $grand = $afterDisc;
            }

            // 5. Update Master dengan hasil hitung
            DB::table('po_mstr')
                ->where('po_mstr_id', $poMstrId)
                ->update([
                    'po_mstr_subtotal'   => $subtotal,
                    'po_mstr_discamt'    => $discAmt,
                    'po_mstr_ppnamt'     => $ppnAmt,
                    'po_mstr_grandtotal' => $grand,
                    'po_mstr_updatedat'  => now(),
                ]);

            DB::commit();

            return redirect()->route('PurchaseOrder.index')->with('success', 'PO berhasil dibuat!');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->route('PurchaseOrder.index')->with('error', 'Error ' . $e);
        }
    }

    /**
     * Display the specified resource.
     */

    public function priceHistory(Request $request)
    {
        // dd($request->all());
        $productId = $request->product_id;
        $umId      = $request->um_id;
        $price     = $request->price;

        // cari histori harga TERMURAH
        $history = BpbDet::join('bpb_mstr', 'bpb_mstr.bpb_mstr_id', '=', 'bpb_det.bpb_det_mstrid')
            ->join('supp_mstr', 'supp_mstr.supp_mstr_id', '=', 'bpb_mstr.bpb_mstr_suppid')
            ->where('bpb_det.bpb_det_productid', $productId)
            ->where('bpb_det.bpb_det_um', $umId)
            ->orderBy('bpb_det.bpb_det_price', 'asc')
            ->select([
                'bpb_det.bpb_det_price',
                'supp_mstr.supp_mstr_name',
                'bpb_mstr.bpb_mstr_date'
            ])
            ->first();

        // dd($history);

        if (!$history) {
            return response()->json(['found' => false]);
        }

        return response()->json([
            'found'        => true,
            'cheaper'      => $price > $history->bpb_det_price,
            'best_price'   => $history->bpb_det_price,
            'supplier'     => $history->supp_mstr_name,
            'date'         => $history->bpb_mstr_date,
        ]);
    }
    public function show($poId)
    {
        $po = PoMstr::findOrFail($poId);
        $details = PoDet::with('product.measurement')->where('po_det_mstrid', $poId)->get();
        return view('purchase.PurchaseOrderDet', compact('po', 'details'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PoMstr $poMstr)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePoMstrRequest $request, PoMstr $poMstr)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $po = PoMstr::with(['bpbs', 'details'])
            ->where('po_mstr_id', $id)
            ->firstOrFail();

        // Cek jika sudah ada BPB
        if ($po->bpbs->count() > 0) {
            return back()->withErrors([
                'error' => 'PO tidak dapat dihapus karena sudah memiliki penerimaan (BPB).'
            ]);
        }

        // Cek jika ada qty diterima (antisipasi data lama)
        $hasReceived = $po->details->where('po_det_qtyrcvd', '>', 0)->count();

        if ($hasReceived > 0) {
            return back()->withErrors([
                'error' => 'PO tidak dapat dihapus karena sudah ada barang diterima.'
            ]);
        }

        DB::transaction(function () use ($po) {
            // Hapus detail dulu
            $po->details()->delete();

            // Hapus master
            $po->delete();
        });

        return redirect()
            ->route('PurchaseOrder.index')
            ->with('success', 'Purchase Order berhasil dihapus.');
    }
}
