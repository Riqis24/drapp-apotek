<?php

namespace App\Http\Controllers;

use App\Models\PoDet;
use App\Models\Price;
use App\Models\ApMstr;
use App\Models\BpbDet;
use App\Models\PoMstr;
use App\Models\stocks;
use App\Models\BpbMstr;
use App\Models\LocMstr;
use App\Models\BatchMstr;
use Illuminate\Http\Request;
use App\Models\StockTransactions;
use Illuminate\Support\Facades\DB;
use App\Models\ProductMeasurements;
use App\Http\Requests\StoreBpbMstrRequest;
use App\Http\Requests\UpdateBpbMstrRequest;
use App\Models\Product;
use App\Models\SuppMstr;

class BpbMstrController extends Controller
{
    public function index()
    {
        $bpb = BpbMstr::with(['supplier', 'po'])->orderByDesc('bpb_mstr_id')->get();
        return view('bpb.BpbMstrList', compact('bpb'));
    }

    public function create()
    {
        $pos = PoMstr::with('supplier')
            ->whereHas('details', function ($query) {
                $query->where('po_det_qtyremain', '>', 0);
            })
            ->get();
        $locs = LocMstr::all();
        $allProducts = ProductMeasurements::with(['product', 'measurement'])->get();
        $suppliers = SuppMstr::all();

        return view('bpb.BpbMstrForm', compact('pos', 'locs', 'allProducts', 'suppliers'));
    }

    public function edit($id)
    {
        // Ambil data BPB beserta detail produk dan UOM-nya
        // Gunakan relasi yang sesuai dengan Model Anda
        $bpb = BpbMstr::with([
            'details.product',
            'details.measurement',
            'supplier',
            'po' // Jika BPB terkait ke PO, muat relasi PO-nya
        ])->findOrFail($id);

        // Ambil data pendukung untuk dropdown (jika ada)
        // Contoh: Lokasi gudang atau Supplier jika ingin diubah
        $locs = LocMstr::all();
        $suppliers = SuppMstr::all();
        $allProducts = ProductMeasurements::with(['product', 'measurement'])->get();
        $pos = PoMstr::with('supplier')
            ->get();
        // Arahkan ke file blade yang sama dengan create (biasanya form.blade.php)
        // Atau ke file khusus edit.blade.php
        return view('bpb.BpbMstrForm', compact('bpb', 'locs', 'suppliers', 'allProducts', 'pos'));
    }

    public function getPoItems($poId)
    {
        $po = PoMstr::with([
            'details.product',
            'details.product.ProductMeasurements', // Pastikan nama relasi sesuai di Model
            'details.um',
            'supplier'
        ])
            ->where('po_mstr_id', $poId)
            ->firstOrFail();

        $items = $po->details
            ->where('po_det_qtyremain', '>', 0)
            ->values()
            ->map(function ($detail) {
                // Ambil relasi measurements dari produk
                // Pastikan menggunakan nama relasi yang tepat (contoh: ProductMeasurements)
                $measurements = $detail->product->ProductMeasurements ?? collect();

                $matchedPm = $measurements
                    ->where('measurement_id', $detail->po_det_um)
                    ->first();

                // Pasang 'pm' sebagai relasi agar muncul di JSON
                $detail->setRelation('pm', $matchedPm);

                return $detail;
            });

        // MENGIRIM OBJEK LENGKAP
        return response()->json([
            'supplier_id'   => $po->po_mstr_suppid,
            'supplier_name' => $po->supplier->supp_mstr_name ?? 'Unknown',
            'items'         => $items
        ]);
    }

    public function getPriceHistory($productId)
    {
        return DB::table('bpb_det')
            ->join('bpb_mstr', 'bpb_det_mstrid', '=', 'bpb_mstr_id')
            ->join('measurements', 'bpb_det_um', '=', 'id')
            ->join('supp_mstr', 'bpb_mstr_suppid', '=', 'supp_mstr_id')
            ->where('bpb_det_productid', $productId)
            ->select('bpb_mstr_date as bpb_date', 'supp_mstr_name as supplier_name', 'bpb_det_qty as qty', 'bpb_det_price as price', 'name as um')
            ->orderBy('bpb_mstr_date', 'desc')
            // ->limit(20)
            ->get();
    }

    public function getMeasurementPrices($productId)
    {
        $product = Product::findOrFail($productId);

        $measurements = DB::table('product_measurements')
            ->join('measurements', 'product_measurements.measurement_id', '=', 'measurements.id')
            ->leftJoin('prices', 'product_measurements.id', '=', 'prices.product_measurement_id')
            ->where('product_measurements.product_id', $productId)
            ->select([
                'product_measurements.id as pm_id',
                'product_measurements.last_buy_price as last_bp',
                'product_measurements.conversion',
                'measurements.id as measurement_id',
                'measurements.name as unit_name',
                'prices.price as old_sell_price' // Ini adalah Hrg Jual Lama
            ])
            ->get();

        return response()->json([
            'product' => $product,
            'measurements' => $measurements
        ]);
    }

    public function updateSellPrices(Request $request)
    {
        try {
            DB::beginTransaction();

            foreach ($request->measurements as $item) {
                $productId = ProductMeasurements::where('id', $item['id'])->value('product_id');
                $margin = Product::where('id', $productId)->value('margin');
                $hb = $item['price'] / (1 + ($margin / 100));
                // $hb = $item['price'] - $marginval;
                // dd($margin, $item['price'], $hb);

                // Gunakan updateOrInsert karena tabel prices mungkin belum punya record untuk ID tersebut
                DB::table('prices')->updateOrInsert(
                    ['product_measurement_id' => $item['id']], // Kondisi pencarian
                    [
                        'price' => $item['price'],
                        'updated_at' => now(),
                        'created_at' => now() // Ditambahkan jika record baru dibuat
                    ]
                );

                ProductMeasurements::updateOrCreate(
                    ['id' => $item['id']],
                    ['last_buy_price' => $hb]
                );
            }


            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal simpan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getPoDetail($id)
    {
        $po = PoMstr::with('supplier')->findOrFail($id);

        return response()->json([
            'supplier_name' => $po->supplier->supp_mstr_name,
            'supplier_id' => $po->supplier->supp_mstr_id,
            'payment_type'  => $po->po_mstr_payment,   // cash / credit
            'due_date'      => $po->po_mstr_duedate,
            'disc_type'     => $po->po_mstr_disctype,       // percent / amount
            'disc_value'    => $po->po_mstr_discvalue,
            'ppn_type'      => $po->po_mstr_ppntype,        // none / include
            'ppn_rate'      => $po->po_mstr_ppnrate,
        ]);
    }


    // public function store(Request $request)
    // {
    //     DB::transaction(function () use ($request) {
    //         // dd($request->all());
    //         /* ======================================================
    //          * 1. Ambil PO (LOCK)
    //          * ====================================================== */
    //         $po = PoMstr::lockForUpdate()->findOrFail($request->poId);

    //         /* ======================================================
    //          * 2. Generate BPB Number
    //          * ====================================================== */
    //         $last = BpbMstr::orderByDesc('bpb_mstr_id')->first();
    //         $next = $last && preg_match('/BPB-(\d+)/', $last->bpb_mstr_nbr, $m)
    //             ? intval($m[1]) + 1
    //             : 1;

    //         $bpbNbr = 'BPB-' . str_pad($next, 4, '0', STR_PAD_LEFT);

    //         /* ======================================================
    //          * 3. Create BPB MASTER (tanpa total dulu)
    //          * ====================================================== */
    //         $bpb = BpbMstr::create([
    //             'bpb_mstr_nbr'     => $bpbNbr,
    //             'bpb_mstr_poid'    => $request->poId,
    //             'bpb_mstr_suppid'  => $request->suppid,

    //             'bpb_mstr_nofaktur'  => $request->nofaktur,
    //             'bpb_mstr_nosj'  => $request->nosj,
    //             'bpb_mstr_payment'  => $request->payment_type,
    //             'bpb_mstr_duedate'  => $request->due_date,

    //             'bpb_mstr_disctype'  => $request->disctype,
    //             'bpb_mstr_discvalue'  => $request->discvalue,

    //             'bpb_mstr_ppntype'  => $request->ppntype,
    //             'bpb_mstr_ppnrate'  => $request->ppnrate,

    //             'bpb_mstr_date'    => $request->bpb_date,
    //             'bpb_mstr_locid'   => $request->loc_id,
    //             'bpb_mstr_note'    => $request->note ?? '',
    //             'bpb_mstr_createdby'   => auth()->user()->user_mstr_id,
    //         ]);

    //         $subtotal = 0;

    //         /* ======================================================
    //          * 4. LOOP DETAIL BPB
    //          * ====================================================== */
    //         foreach ($request->items as $item) {
    //             $qtyRcvd    = (float) $item['qty'];
    //             $umConv     = (float) $item['umconv'];
    //             $qtyBase    = $qtyRcvd * $umConv;
    //             $price      = (float) $item['price'];
    //             $priceBase  = $price / $umConv;
    //             $lineTotal  = $qtyRcvd * $price; // Total kotor per baris

    //             /* ---------- LOGIKA DISKON BARU ---------- */
    //             $discValue = (float) ($item['discvalue'] ?? 0);
    //             $discType  = $item['disctype'] ?? 'amount';
    //             $discTotal = 0;

    //             if ($discType === 'percent') {
    //                 // Jika persen, hitung dari total (Price * Qty)
    //                 $discTotal = $lineTotal * ($discValue / 100);
    //             } else {
    //                 // Jika amount, langsung gunakan nilainya
    //                 $discTotal = $discValue;
    //             }

    //             $totalPerLine = $lineTotal - $discTotal;
    //             $subtotal += $totalPerLine; // Akumulasi ke Grand Total

    //             /* ---------- BATCH ---------- */
    //             $batch = BatchMstr::firstOrCreate(
    //                 [
    //                     'batch_mstr_productid'   => $item['productid'],
    //                     'batch_mstr_no'           => $item['batch_no'],
    //                     'batch_mstr_expireddate' => $item['expired_date'],
    //                 ]
    //             );

    //             /* ---------- BPB DETAIL ---------- */
    //             $bpbDet = BpbDet::create([
    //                 'bpb_det_mstrid'    => $bpb->bpb_mstr_id,
    //                 'bpb_det_podetid'   => $item['po_det_id'],
    //                 'bpb_det_productid' => $item['productid'],
    //                 'bpb_det_qty'       => $qtyRcvd,
    //                 'bpb_det_um'        => $item['umid'],
    //                 'bpb_det_umconv'    => $umConv,
    //                 'bpb_det_qtyrcvd'   => $qtyBase,
    //                 'bpb_det_price'     => $price,
    //                 'bpb_det_disctype'  => $discType,    // Simpan tipe: 'percent' atau 'amount'
    //                 'bpb_det_discvalue'  => $discValue,
    //                 'bpb_det_discamt'   => $discTotal,   // Simpan nilai nominal diskonnya
    //                 'bpb_det_total'     => $totalPerLine,
    //                 'bpb_det_priceconv' => $priceBase,
    //                 'bpb_det_batch'     => $batch->batch_mstr_id,
    //                 'bpb_det_expired'   => $item['expired_date'],
    //                 'bpb_det_updateprice' => $item['updateprice'] ?? 0,
    //             ]);

    //             /* ---------- STOCK TRANSACTION ---------- */
    //             StockTransactions::create([
    //                 'product_id'  => $item['productid'],
    //                 'loc_id'      => $request->loc_id,
    //                 'batch_id'    => $batch->batch_mstr_id,
    //                 'type'        => 'in',
    //                 'quantity'    => $qtyBase,
    //                 'date'        => $request->bpb_date,
    //                 'note'        => $bpbNbr,
    //                 'source_type' => BpbMstr::class,
    //                 'source_id'   => $bpb->bpb_mstr_id,
    //                 'created_by'  => auth()->user()->user_mstr_id,
    //             ]);

    //             /* ---------- STOCK SUMMARY ---------- */
    //             Stocks::updateOrCreate(
    //                 [
    //                     'product_id' => $item['productid'],
    //                     'loc_id'     => $request->loc_id,
    //                     'batch_id'   => $batch->batch_mstr_id,
    //                 ],
    //                 []
    //             )->increment('quantity', $qtyBase);

    //             /* ---------- UPDATE PO DETAIL ---------- */
    //             $poDet = PoDet::lockForUpdate()->findOrFail($item['po_det_id']);

    //             if ($qtyRcvd > $poDet->po_det_qtyremain) {
    //                 throw new \Exception("Qty diterima untuk produk {$item['productid']} melebihi sisa PO");
    //             }

    //             $poDet->po_det_qtyrcvd = ($poDet->po_det_qtyrcvd ?? 0) + $qtyRcvd;
    //             $poDet->po_det_qtyremain = ($poDet->po_det_qtyremain ?? 0) - $qtyRcvd;
    //             $poDet->save();
    //             // dd($poDet);
    //         }

    //         /* ======================================================
    //          * 5. HITUNG DISKON, DPP, PPN, GRAND TOTAL
    //          * ====================================================== */
    //         $discAmt = 0;

    //         if ($bpb->bpb_mstr_disctype === 'percent') {
    //             $discAmt = $subtotal * ($bpb->bpb_mstr_discvalue / 100);
    //         } elseif ($bpb->bpb_mstr_disctype === 'amount') {
    //             $discAmt = $bpb->bpb_mstr_discvalue;
    //         }

    //         $discAmt = min($discAmt, $subtotal);
    //         $dpp     = $subtotal - $discAmt;

    //         $ppnAmt = 0;
    //         $grandTotal = $dpp;

    //         if ($bpb->po_mstr_ppntype === 'include') {
    //             $ppnAmt = $dpp * ($bpb->bpb_mstr_ppnrate / 100);
    //             $grandTotal = $dpp + $ppnAmt;
    //         }

    //         /* ---------- UPDATE BPB MASTER ---------- */
    //         $bpb->update([
    //             'bpb_mstr_subtotal'   => $subtotal,
    //             'bpb_mstr_discamt'    => $discAmt,
    //             'bpb_mstr_dpp'        => $dpp,
    //             'bpb_mstr_ppnamt'     => $ppnAmt,
    //             'bpb_mstr_grandtotal' => $grandTotal,
    //         ]);

    //         /* ======================================================
    //          * 6. CREATE AP (HANYA JIKA CREDIT)
    //          * ====================================================== */
    //         if ($bpb->bpb_mstr_payment === 'credit') {
    //             ApMstr::create([
    //                 'ap_mstr_nbr'     => $this->generateApNumber(),
    //                 'ap_mstr_suppid'  => $bpb->bpb_mstr_suppid,
    //                 'ap_mstr_reftype' => 'bpb',
    //                 'ap_mstr_refid'   => $bpb->bpb_mstr_id,
    //                 'ap_mstr_date'    => $bpb->bpb_mstr_date,
    //                 'ap_mstr_duedate' => $bpb->bpb_mstr_duedate,
    //                 'ap_mstr_amount'  => $grandTotal,
    //                 'ap_mstr_paid'    => 0,
    //                 'ap_mstr_balance' => $grandTotal,
    //                 'ap_mstr_status'  => 'unpaid',
    //                 'ap_mstr_createdby' => auth()->user()->user_mstr_id,
    //             ]);
    //         }
    //     });

    //     return redirect()
    //         ->route('BpbMstr.index')
    //         ->with('success', 'BPB berhasil dibuat');
    // }

    /* ======================================================
     * Helper
     * ====================================================== */

    public function store(Request $request)
    {
        return $this->processBpb($request);
    }

    public function update(Request $request, $id)
    {
        // dd('masuk');
        return $this->processBpb($request, $id);
    }

    private function processBpb(Request $request, $id = null)
    {
        // dd($request->all());
        try {
            DB::transaction(function () use ($request, $id) {
                $isUpdate = $id !== null;

                /* ======================================================
                 * 1. REVERSE LOGIC (Jika Update)
                 * ====================================================== */
                if ($isUpdate) {
                    $this->reverseBpb($id);
                    $bpb = BpbMstr::findOrFail($id);
                } else {
                    $bpb = new BpbMstr();
                    // Generate Number hanya jika baru
                    $last = BpbMstr::orderByDesc('bpb_mstr_id')->first();
                    $next = $last && preg_match('/BPB-(\d+)/', $last->bpb_mstr_nbr, $m) ? intval($m[1]) + 1 : 1;
                    $bpb->bpb_mstr_nbr = 'BPB-' . str_pad($next, 4, '0', STR_PAD_LEFT);
                }

                /* ======================================================
                 * 2. SAVE MASTER DATA
                 * ====================================================== */
                $bpb->fill([
                    'bpb_mstr_poid'     => $request->poId, // Bisa null jika tanpa PO
                    'bpb_mstr_suppid'   => $request->suppid,
                    'bpb_mstr_nofaktur' => $request->nofaktur,
                    'bpb_mstr_nosj'     => $request->nosj,
                    'bpb_mstr_payment'  => $request->payment_type,
                    'bpb_mstr_duedate'  => $request->due_date,
                    'bpb_mstr_disctype' => $request->disctype,
                    'bpb_mstr_discvalue' => $request->discvalue,
                    'bpb_mstr_ppntype'  => $request->ppntype,
                    'bpb_mstr_ppnrate'  => $request->ppnrate,
                    'bpb_mstr_date'     => $request->bpb_date,
                    'bpb_mstr_locid'    => $request->loc_id,
                    'bpb_mstr_note'     => $request->note ?? '',
                    'bpb_mstr_createdby' => auth()->user()->user_mstr_id,
                ]);
                $bpb->save();


                // Bersihkan detail lama jika update (setelah di-reverse)
                if ($isUpdate) {
                    BpbDet::where('bpb_det_mstrid', $id)->delete();
                    ApMstr::where('ap_mstr_reftype', 'bpb')->where('ap_mstr_refid', $id)->delete();
                }

                $subtotal = 0;

                /* ======================================================
                 * 3. LOOP DETAIL BPB (Supports PO & Non-PO)
                 * ====================================================== */
                foreach ($request->items as $item) {
                    $qtyRcvd  = (float) $item['qty'];
                    if ($item['umconv'] == 0) {
                        $umConv = ProductMeasurements::where('product_id', $item['productid'])->where('measurement_id', $item['umid'])->value('conversion');
                        // dd($umConv);
                    } else {
                        $umConv  = (float) ($item['umconv']); // Default 1 jika manual
                        // dd($umConv);
                    }
                    $qtyBase  = $qtyRcvd * $umConv;
                    $price    = (float) $item['price'];
                    $priceBase = $price / $umConv;
                    $lineTotal = $qtyRcvd * $price;


                    // Hitung Diskon Line
                    $discValue = (float) ($item['discvalue'] ?? 0);
                    $discType  = $item['disctype'] ?? 'amount';
                    $discTotal = ($discType === 'percent') ? ($lineTotal * ($discValue / 100)) : $discValue;

                    $totalPerLine = $lineTotal - $discTotal;
                    $subtotal += $totalPerLine;

                    // Batch Handling
                    $batch = BatchMstr::firstOrCreate([
                        'batch_mstr_productid'   => $item['productid'],
                        'batch_mstr_no'          => $item['batch_no'],
                        'batch_mstr_expireddate' => $item['expired_date'],
                    ]);


                    // BPB Detail Create
                    BpbDet::create([
                        'bpb_det_mstrid'    => $bpb->bpb_mstr_id,
                        'bpb_det_podetid'   => $item['po_det_id'] ?? null, // Support Non-PO
                        'bpb_det_productid' => $item['productid'],
                        'bpb_det_qty'       => $qtyRcvd,
                        'bpb_det_um'        => $item['umid'],
                        'bpb_det_umconv'    => $umConv,
                        'bpb_det_qtyrcvd'   => $qtyBase,
                        'bpb_det_price'     => $price,
                        'bpb_det_disctype'  => $discType,
                        'bpb_det_discvalue' => $discValue,
                        'bpb_det_discamt'   => $discTotal,
                        'bpb_det_total'     => $totalPerLine,
                        'bpb_det_priceconv' => $priceBase,
                        'bpb_det_batch'     => $batch->batch_mstr_id,
                        'bpb_det_expired'   => $item['expired_date'],
                        'bpb_det_updateprice' => $item['updateprice'] ?? 0,
                    ]);

                    // Stock Movement
                    $this->updateStock($item['productid'], $request->loc_id, $batch->batch_mstr_id, $qtyBase, $bpb->bpb_mstr_nbr, $bpb->bpb_mstr_id, $request->bpb_date);

                    /* ---------- UPDATE PO DETAIL (Hanya jika ada PO) ---------- */
                    if (!empty($item['po_det_id'])) {
                        $poDet = PoDet::lockForUpdate()->find($item['po_det_id']);
                        if ($poDet) {
                            $poDet->po_det_qtyrcvd   += $qtyRcvd;
                            $poDet->po_det_qtyremain -= $qtyRcvd;
                            $poDet->save();
                        }
                    }
                }

                /* ======================================================
                 * 4. FINAL CALCULATION & AP
                 * ====================================================== */
                $discMstr = ($bpb->bpb_mstr_disctype === 'percent') ? ($subtotal * ($bpb->bpb_mstr_discvalue / 100)) : $bpb->bpb_mstr_discvalue;
                $dpp        = $subtotal - min($discMstr, $subtotal);
                $ppnAmt     = ($bpb->bpb_mstr_ppntype === 'include') ? ($dpp * ($bpb->bpb_mstr_ppnrate / 100)) : 0;
                $grandTotal = $dpp + $ppnAmt;

                $bpb->update([
                    'bpb_mstr_subtotal'   => $subtotal,
                    'bpb_mstr_discamt'    => $discMstr,
                    'bpb_mstr_dpp'        => $dpp,
                    'bpb_mstr_ppnamt'     => $ppnAmt,
                    'bpb_mstr_grandtotal' => $grandTotal,
                ]);

                if ($bpb->bpb_mstr_payment === 'credit') {
                    $this->createAp($bpb, $grandTotal);
                }
            });

            return redirect()->route('BpbMstr.index')->with('success', 'Data berhasil disimpan');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    /**
     * Fungsi untuk membatalkan efek BPB (Stock & PO) sebelum update
     */
    private function reverseBpb($id)
    {
        $details = BpbDet::where('bpb_det_mstrid', $id)->get();
        foreach ($details as $det) {
            // 1. Kurangi Stok
            Stocks::where([
                'product_id' => $det->bpb_det_productid,
                'loc_id'     => $det->master->bpb_mstr_locid,
                'batch_id'   => $det->bpb_det_batch,
            ])->decrement('quantity', $det->bpb_det_qtyrcvd);

            // 2. Hapus Transaksi Stok Terkait
            StockTransactions::where('source_type', BpbMstr::class)
                ->where('source_id', $id)
                ->where('product_id', $det->bpb_det_productid)
                ->delete();

            // 3. Kembalikan Qty PO (Jika ada)
            if ($det->bpb_det_podetid) {
                $poDet = PoDet::find($det->bpb_det_podetid);
                if ($poDet) {
                    $poDet->po_det_qtyrcvd   -= $det->bpb_det_qty;
                    $poDet->po_det_qtyremain += $det->bpb_det_qty;
                    $poDet->save();
                }
            }
        }
    }

    private function updateStock($prodId, $locId, $batchId, $qty, $nbr, $bpbId, $date)
    {
        StockTransactions::create([
            'product_id'  => $prodId,
            'loc_id'      => $locId,
            'batch_id'    => $batchId,
            'type'        => 'in',
            'quantity'    => $qty,
            'date'        => $date,
            'note'        => $nbr,
            'source_type' => BpbMstr::class,
            'source_id'   => $bpbId,
            'created_by'  => auth()->user()->user_mstr_id,
        ]);

        Stocks::updateOrCreate(
            ['product_id' => $prodId, 'loc_id' => $locId, 'batch_id' => $batchId],
            []
        )->increment('quantity', $qty);
    }

    private function createAp($bpb, $total)
    {
        ApMstr::create([
            'ap_mstr_nbr'       => $this->generateApNumber(), // Sesuai helper nbr anda
            'ap_mstr_suppid'    => $bpb->bpb_mstr_suppid,
            'ap_mstr_reftype'   => 'bpb',
            'ap_mstr_refid'     => $bpb->bpb_mstr_id,
            'ap_mstr_date'      => $bpb->bpb_mstr_date,
            'ap_mstr_duedate'   => $bpb->bpb_mstr_duedate,
            'ap_mstr_amount'    => $total,
            'ap_mstr_paid'      => 0,
            'ap_mstr_balance'   => $total,
            'ap_mstr_status'    => 'unpaid',
            'ap_mstr_createdby' => auth()->user()->user_mstr_id,
        ]);
    }
    private function generateApNumber()
    {
        $last = ApMstr::orderByDesc('ap_mstr_id')->first();
        $next = $last && preg_match('/AP-(\d+)/', $last->ap_mstr_nbr, $m)
            ? intval($m[1]) + 1
            : 1;

        return 'AP-' . str_pad($next, 4, '0', STR_PAD_LEFT);
    }

    public function show($id)
    {
        $bpb = BpbMstr::with(['supplier', 'po', 'batch'])->findOrFail($id);
        $details = BpbDet::with(['product', 'podet.um'])
            ->where('bpb_det_mstrid', $id)
            ->get();
        return view('bpb.BpbDetList', compact('bpb', 'details'));
    }

    public function destroy($id)
    {
        // 1. Cari data AP yang terkait dengan BPB ini
        $ap = ApMstr::where('ap_mstr_reftype', 'bpb')
            ->where('ap_mstr_refid', $id)
            ->first();

        if ($ap) {
            // Cek apakah sudah ada yang terbayar (cicilan atau lunas)
            if ($ap->ap_mstr_paid > 0) {
                return redirect()->back()->with('error', 'Gagal! Hutang sudah ada pembayaran. Hapus Pembayaran (Appay) dulu.');
            }
        }

        DB::beginTransaction();

        try {
            $bpb = BpbMstr::with(['details'])->findOrFail($id);

            /** =============================================
             * LOGIC DELETE AP (Hanya jika AP ada dan unpaid)
             * ============================================= */
            if ($ap) {
                // Karena kita sudah validasi paid == 0 di atas, aman untuk didelete
                $ap->delete();
            }

            foreach ($bpb->details as $det) {

                /** ================================
                 *  VALIDASI STOCK
                 *  ================================ */
                $stock = Stocks::where([
                    'product_id' => $det->bpb_det_productid,
                    'loc_id'     => $bpb->bpb_mstr_locid,
                    'batch_id'   => $det->bpb_det_batch,
                ])->first();

                if (!$stock || $stock->quantity < $det->qty_rcvd) {
                    throw new \Exception('Stock tidak mencukupi untuk menghapus BPB');
                }

                /** ================================
                 *  VALIDASI TIDAK ADA PENJUALAN
                 *  ================================ */
                $outQty = StockTransactions::where([
                    'product_id' => $det->bpb_det_productid,
                    'loc_id'     => $bpb->bpb_mstr_locid,
                    'batch_id'   => $det->bpb_det_batch,
                    'type'       => 'out'
                ])->sum('quantity');

                if ($outQty > 0) {
                    throw new \Exception('Batch sudah pernah dijual');
                }

                /** ================================
                 *  DELETE STOCK TRANSACTION BPB
                 *  ================================ */
                StockTransactions::where([
                    'source_type' => BpbMstr::class,
                    'source_id'   => $bpb->bpb_mstr_id,
                    'product_id'  => $det->bpb_det_productid,
                    'batch_id'    => $det->bpb_det_batch,
                ])->delete();

                /** ================================
                 *  RECALCULATE STOCK
                 *  ================================ */
                $newQty = StockTransactions::where([
                    'product_id' => $det->bpb_det_productid,
                    'loc_id'     => $bpb->bpb_mstr_locid,
                    'batch_id'   => $det->bpb_det_batch,
                ])->sum('quantity');

                if ($newQty <= 0) {
                    Stocks::where([
                        'product_id' => $det->bpb_det_productid,
                        'loc_id'     => $bpb->bpb_mstr_locid,
                        'batch_id'   => $det->bpb_det_batch,
                    ])->delete();
                } else {
                    Stocks::where([
                        'product_id' => $det->bpb_det_productid,
                        'loc_id'     => $bpb->bpb_mstr_locid,
                        'batch_id'   => $det->bpb_det_batch,
                    ])->update(['quantity' => $newQty]);
                }

                /** ================================
                 *  DELETE BATCH
                 *  ================================ */
                BatchMstr::where('batch_mstr_id', $det->bpb_det_batch)->delete();

                /** ================================
                 *  UPDATE PO DETAIL
                 *  ================================ */
                if ($det->bpb_det_podetid) { // pastikan nama kolom benar sesuai field input kamu
                    $poDet = PoDet::find($det->bpb_det_podetid);
                    if ($poDet) {
                        $poDet->decrement('po_det_qtyrcvd', $det->bpb_det_qty);
                        $poDet->increment('po_det_qtyremain', $det->bpb_det_qty);
                    }
                }
            }
            /** ================================
             *  DELETE BPB DETAIL & MASTER
             *  ================================ */
            BpbDet::where('bpb_det_mstrid', $bpb->bpb_mstr_id)->delete();
            $bpb->delete();

            DB::commit();

            return redirect()->route('BpbMstr.index')->with('success', 'BPB dan Hutang terkait berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }
}
