<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Price;
use App\Models\ApMstr;
use App\Models\ArMstr;
use App\Models\SrMstr;
use App\Models\stocks;
use App\Models\LocMstr;
use App\Models\Product;
use App\Models\ArpayDet;
use App\Models\Customer;
use App\Models\PresMstr;
use App\Models\SalesDet;
use App\Models\ArpayMstr;
use App\Models\SalesMstr;
use App\Helpers\AppSetting;
use App\Models\StoreProfile;
use Illuminate\Http\Request;
use App\Models\ProductBundle;
use App\Models\CashierSession;
use App\Models\FinancialRecords;
use App\Models\ProductPlacement;
use App\Models\StockTransactions;
use Illuminate\Support\Facades\DB;
use App\Models\ProductMeasurements;
use App\Http\Requests\StoreSalesMstrRequest;
use App\Http\Requests\UpdateSalesMstrRequest;

class SalesMstrController extends Controller
{
    public function cashier()
    {

        $dueAps = ApMstr::with('supplier')
            ->where('ap_mstr_balance', '>', 0)
            ->whereDate('ap_mstr_duedate', '<=', now()->addDays(3))
            ->orderBy('ap_mstr_duedate')
            ->get();
        $holds = SalesMstr::where('sales_mstr_status', 'draft')->get();

        $productMeasurements = ProductMeasurements::with([
            'product.stocks.loc',
            'product.stocks.batch',
            'measurement',
            'price',
            'placement',
        ])->get();

        $items = collect();

        foreach ($productMeasurements as $p) {
            $product = $p->product;
            if (!$product) continue;

            // =====================
            // SINGLE PRODUCT
            // =====================
            if ($product->type === 'single') {
                foreach ($product->stocks as $stock) {
                    // Pastikan lokasi ada
                    if (!$stock->loc) continue;

                    // $groupKey = "prod-{$product->id}-loc-{$stock->loc_id}"; //penentu groupby
                    $groupKey = "prod-{$product->id}-pm-{$p->id}-loc-{$stock->loc_id}"; //penentu groupby


                    // Gunakan put agar jika key sama, tidak akan menambah baris baru (grouping)
                    if (!$items->has($groupKey)) {
                        $items->put($groupKey, [
                            'id'                     => $p->id,
                            'type'                   => 'single',
                            'text'                   => "{$product->name} (" . ($p->measurement->name ?? '-') . ") - " . ($p->placement->name ?? '-'),
                            'price'                  => $p->price->price ?? 0,
                            'loc_id'                 => $stock->loc->loc_mstr_id,
                            'product_id'             => $product->id,
                            'product'                => $product->name,
                            'rak'                    => $p->placement->name ?? '-',
                            'product_measurement_id' => $p->id,
                            'measurement_id'         => $p->measurement_id,
                            'measurement'            => $p->measurement->name ?? '-',
                            // Batch ini adalah batch pertama yang ditemukan di lokasi tersebut
                            'batch_number'           => $stock->batch->batch_mstr_no ?? '-',
                            'batch_id'              => $stock->batch->batch_mstr_id ?? '-',
                            'batch_exp'              => $stock->batch->batch_mstr_expireddate ?? '-',
                        ]);
                    }
                }
            }

            // =====================
            // BUNDLE PRODUCT
            // =====================
            if ($product->type === 'bundle') {
                $items->push([
                    'id'                     => $p->id,
                    'type'                   => 'bundle',
                    'text'                   => "{$product->name} (" . ($p->measurement->name ?? '-') . ")",
                    'price'                  => $p->price->price ?? 0,
                    'loc_id'                 => 1, // Global / Default
                    'product_id'             => $product->id,
                    'product'                => $product->name,
                    'product_measurement_id' => $p->id,
                    'measurement_id'         => $p->measurement_id,
                    'measurement'            => $p->measurement->name ?? '-',
                ]);
            }
        }

        $items = $items->values();



        $items = collect($items)->values();




        // dd($prices);
        $session = CashierSession::where('user_id', auth()->user()->user_mstr_id)->whereDate('opened_at', now()->day)->value('status');
        // dd($session);
        $locations = LocMstr::get();
        $customers = Customer::get();
        return view('sales.cashier', compact('locations', 'items', 'holds', 'dueAps', 'customers', 'session'));
    }

    public function cashierv2()
    {
        $roleKasir = auth()->user()->hasRole('Kasir');
        // dd($roleKasir);
        $roleAdmin = auth()->user()->hasRole('admin');
        $dueAps = ApMstr::with('supplier')
            ->where('ap_mstr_balance', '>', 0)
            ->whereDate('ap_mstr_duedate', '<=', now()->addDays(3))
            ->orderBy('ap_mstr_duedate')
            ->get();
        $holds = SalesMstr::where('sales_mstr_status', 'draft')->get();

        $productMeasurements = ProductMeasurements::with([
            'product.stocks.loc',
            'product.stocks.batch',
            'measurement',
            'price',
            'placement',
        ])
            ->whereHas('product', function ($query) use ($roleKasir, $roleAdmin) {
                if ($roleKasir && !$roleAdmin) {
                    $query->where('is_visible', true);
                }
            })
            ->get();

        $items = collect();

        foreach ($productMeasurements as $p) {
            $product = $p->product;
            if (!$product) continue;

            // =====================
            // SINGLE PRODUCT
            // =====================
            if ($product->type === 'single') {
                foreach ($product->stocks as $stock) {
                    // Pastikan lokasi ada
                    if (!$stock->loc) continue;
                    $sumstock = stocks::where('product_id', $stock->product_id)->where('loc_id', $stock->loc_id)->sum('quantity');

                    // $groupKey = "prod-{$product->id}-loc-{$stock->loc_id}"; //penentu groupby
                    $groupKey = "prod-{$product->id}-pm-{$p->id}-loc-{$stock->loc_id}"; //penentu groupby

                    if ($stock->quantity > 0) {
                        // Gunakan put agar jika key sama, tidak akan menambah baris baru (grouping)
                        if (!$items->has($groupKey)) {
                            $items->put($groupKey, [
                                'id'                     => $p->id,
                                'type'                   => 'single',
                                'text'                   => "{$product->name} (" . ($p->measurement->name ?? '-') . ") - " . ($p->placement->name ?? '-'),
                                'price'                  => $p->price->price ?? 0,
                                'loc_id'                 => $stock->loc->loc_mstr_id,
                                'product_id'             => $product->id,
                                'product'                => $product->name,
                                'rak'                    => $p->placement->name ?? '-',
                                'product_measurement_id' => $p->id,
                                'measurement_id'         => $p->measurement_id,
                                'measurement'            => $p->measurement->name ?? '-',
                                // Batch ini adalah batch pertama yang ditemukan di lokasi tersebut
                                'batch_number'           => $stock->batch->batch_mstr_no ?? '-',
                                'batch_exp'              => $stock->batch->batch_mstr_expireddate ?? '-',
                                'stock'                  => $sumstock
                            ]);
                        }
                    }
                }
            }

            // =====================
            // BUNDLE PRODUCT
            // =====================
            if ($product->type === 'bundle') {
                $items->push([
                    'id'                     => $p->id,
                    'type'                   => 'bundle',
                    'text'                   => "{$product->name} (" . ($p->measurement->name ?? '-') . ")",
                    'price'                  => $p->price->price ?? 0,
                    'loc_id'                 => 1, // Global / Default
                    'product_id'             => $product->id,
                    'product'                => $product->name,
                    'product_measurement_id' => $p->id,
                    'measurement_id'         => $p->measurement_id,
                    'measurement'            => $p->measurement->name ?? '-',
                ]);
            }
        }

        $items = $items->values();



        $items = collect($items)->values();



        $now = now();
        // dd($now);
        $session = CashierSession::where('user_id', auth()->user()->user_mstr_id)->whereDate('opened_at', $now)->value('status');
        // dd($session);

        if ($roleAdmin || $roleKasir) {
            $locations = LocMstr::where('loc_mstr_isvisible', 1)->get();
            $customers = Customer::where('isvisible', '1')->get();
        } else {
            $locations = LocMstr::get();
            $customers = Customer::get();
        }

        // dd($locations);
        return view('sales.cashier2', compact('locations', 'items', 'holds', 'dueAps', 'customers', 'session'));
    }

    public function getHistoryRacik(Request $request)
    {
        $search = $request->q;

        $history = SalesDet::query()
            // Kita tarik relasinya
            ->with(['prescription.details.product'])
            ->where('sales_det_type', 'racikan')
            // Pencarian diarahkan ke tabel prescription_masters
            ->whereHas('prescription', function ($query) use ($search) {
                if ($search) {
                    // Asumsi nama kolom di pres_mstr adalah 'pres_mstr_name' 
                    // atau sesuaikan dengan nama kolom Anda (misal: 'name')
                    $query->where('pres_mstr_name', 'LIKE', "%$search%");
                }
            })
            ->latest()
            ->limit(20)
            ->get();

        $response = $history->map(function ($item) {
            $presMstr = $item->prescription;

            return [
                'id' => $item->sales_det_id,
                // Nama diambil dari master resep, bukan dari sale_details
                'nama_racikan' => $presMstr->pres_mstr_name ?? 'Racikan Tanpa Nama',
                'qty_hasil'    => $item->sales_det_qty,
                'created_at'   => $item->sales_det_createdat->format('d M Y'),
                'details'      => $presMstr ? $presMstr->details->map(function ($detail) {
                    return [
                        'product_id'   => $detail->pres_det_productid,
                        'product_name' => $detail->product->name ?? 'Produk Tidak Ditemukan',
                        'unit_name'    => $detail->product->measurement_id ?? '-',
                        'current_stock' => 0,
                        'price_at_time' => 0,
                        'qty_needed'   => $detail->pres_det_qty,
                    ];
                }) : []
            ];
        });

        return response()->json($response);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactions = SalesMstr::with('loc')->orderBy('sales_mstr_id', 'desc')->get();
        return view('sales.SalesMstrList', compact('transactions'));
    }

    public function listHold()
    {
        $holds = SalesMstr::where('sales_mstr_status', 'draft')->get();
        return response()->json($holds);
    }

    public function resumeHold($id)
    {
        $hold = SalesMstr::with(['details.product', 'details.measurement'])->findOrFail($id);
        // dd($hold);

        $items = $hold->details->map(function ($det) use ($hold) {
            if (!empty($det->sales_det_pmid)) {
                $pres = PresMstr::with('details')->find($det->sales_det_pmid);
                return [
                    // 'price_id'      => $det->sales_det_priceid,
                    'product_id'    => 0,
                    'measurement_id' => 0,
                    'qty'           => $det->sales_det_qty,
                    'price'         => $det->sales_det_price,
                    'disc'          => $det->sales_det_discamt,
                    'product'       => $pres->pres_mstr_name,
                    'measurement'   => 'Bungkus',
                    'rak'           => 'resep',
                    'type'          => 'racikan',
                    'batch_number'  => 'Resep' ?? '-',
                    'batch_exp'     =>  '-',
                ];
            } else {
                $pm = ProductMeasurements::where('product_id', $det->sales_det_productid)
                    ->where('measurement_id', $det->sales_det_um)
                    ->firstOrFail();
                $placement = ProductPlacement::where('id', $pm->placement_id)->first();
                $stock = stocks::with('batch')
                    ->where('product_id', $det->sales_det_productid)
                    ->where('loc_id', $hold->sales_mstr_locid)
                    ->where('batch_id', $det->sales_det_batchid)
                    ->first();
                // dd($stock);

                return [
                    'id'             => $det->sales_det_id, // Tambahkan ID det agar JS bisa mengenali baris
                    'product_id'     => $det->sales_det_productid,
                    'product'        => $det->product->name, // Sesuaikan dengan yang dipanggil formatObat
                    'measurement'    => $det->measurement->name,
                    'measurement_id' => $det->sales_det_um,
                    'qty'            => $det->sales_det_qty,
                    'price'          => (float)$det->sales_det_price,
                    'disc'           => $det->sales_det_discamt,
                    'batch_number'   => $stock->batch->batch_mstr_no ?? '-',
                    'batch_exp'      => $stock->batch->batch_mstr_expireddate ?? '-',
                    'rak'            => $placement ? $placement->name : '-',
                    'type'           => 'regular',
                ];
            }
        });
        if ($hold->sales_mstr_ppnamt > 0) {
            $ppn = 'include';
        } else {
            $ppn = 'none';
        }

        // dd($items);
        return response()->json([
            'sales_mstr_id' => $hold->sales_mstr_id,
            'sales_mstr_discamt' => $hold->sales_mstr_discamt,
            'ppn_type' => $ppn,
            'items' => $items
        ]);
    }

    public function cancelHold($id)
    {
        $hold = SalesMstr::findOrFail($id);
        $hold->details()->delete();
        $hold->delete();
        return response()->json(['message' => 'Hold dibatalkan']);
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
    public function store(StoreSalesMstrRequest $request)
    {
        // dd($request->all());
        $activeSession = CashierSession::where('user_id', auth()->user()->user_mstr_id)
            ->where('loc_id', $request->loc_id)
            ->where('status', 'open')
            ->latest('opened_at')
            ->first();

        if (!$activeSession) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak ada session kasir aktif'
            ], 400);
        }
        DB::beginTransaction();
        try {
            $last = SalesMstr::orderBy('sales_mstr_id', 'desc')->first();

            if ($last && preg_match('/SO-(\d+)/', $last->sales_mstr_nbr, $m)) {
                $nextNumber = intval($m[1]) + 1;
            } else {
                $nextNumber = 1;
            }

            $sonbr = 'SO-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
            $changee = (float)(str_replace('.', '', $request->change));
            $change = number_format($changee, 2, '.', '');
            // dd($request->paymentInput, $changee);

            if ($request->holdid) {
                // Resume hold → update SO existing
                $sales = SalesMstr::findOrFail($request->holdid);
                $sales->update([
                    'sales_mstr_status'   => $request->type === 'paid' ? 'posted' : 'draft',
                    'sales_mstr_subtotal' => $request->subtotal,
                    'sales_mstr_custid'  => $request->customer_id,
                    'sales_mstr_paymenttype'  => $request->payment_type,
                    'sales_mstr_discamt'  => $request->disc_global,
                    'sales_mstr_paidamt'  => $request->paymentInput,
                    'sales_mstr_changeamt'  => $change,
                    'sales_mstr_ppnamt'   => $request->ppn,
                    'sales_mstr_grandtotal' => $request->grandtotal,
                    'sales_mstr_createdby' => auth()->user()->user_mstr_id,
                ]);

                // Hapus detil lama sebelum insert ulang
                $sales->details()->delete();
            } else {
                // Transaksi baru
                $last = SalesMstr::orderBy('sales_mstr_id', 'desc')->first();
                $nextNumber = $last && preg_match('/INV-(\d+)/', $last->sales_mstr_nbr, $m) ? intval($m[1]) + 1 : 1;
                $sonbr = 'INV-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);


                $sales = SalesMstr::create([
                    'sales_mstr_nbr'      => $sonbr,
                    'sales_mstr_date'     => now(),
                    'sales_mstr_locid'    => $request->loc_id,
                    'sales_mstr_status'   => $request->type === 'paid' ? 'posted' : 'draft',
                    'sales_mstr_subtotal' => $request->subtotal,
                    'sales_mstr_custid'  => $request->customer_id,
                    'sales_mstr_paymenttype'  => $request->payment_type,
                    'sales_mstr_paidamt'  => $request->paymentInput ?? 0,
                    'sales_mstr_changeamt'  => $change ?? 0,
                    'sales_mstr_discamt'  => $request->disc_global,
                    'sales_mstr_ppnamt'   => $request->ppn,
                    'sales_mstr_grandtotal' => $request->grandtotal,
                    'cashier_session_id' =>  $activeSession->id, // ✅ relasi session
                    'sales_mstr_createdby' => auth()->user()->user_mstr_id,
                ]);
            }

            $subtotal = 0;

            // dd($request->details);
            if ($request->details) {
                foreach ($request->details as $keyRacik => $racik) {
                    foreach ($racik['details'] as $index => $bahan) {
                        $pm = ProductMeasurements::where('product_id', $bahan['product_id'])
                            ->where('measurement_id', $bahan['measurement_id'])
                            ->firstOrFail();

                        $conversion = (float) $pm->conversion;
                        $qtyBase    = (float) $bahan['qty'] * $conversion;

                        // FIFO
                        $this->processStockOutFIFO(
                            $bahan['product_id'],
                            $request->loc_id,
                            $qtyBase,
                            $sales->sales_mstr_id
                        );
                    }
                }
            } else {
                foreach ($request->items as $item) {
                    if ($item['type'] === 'bundle') {
                        $this->processBundleItem($sales, $item, $request);
                    } elseif ($item['type'] === 'racikan') { // <--- Tambahkan ini
                        $this->processRacikanItem($sales, $item, $request);
                    } else {
                        $this->processSingleItem($sales, $item, $request);
                    }
                }
            }





            // 💰 FINANCIAL (HANYA PAID)
            if ($request->type === 'paid' && $request->payment_type === 'cash') {
                // income
                FinancialRecords::create([
                    'amount'      => $request->subtotal - $request->disc_global,
                    'type'        => 'income',
                    'method'      => $request->payment_method,
                    'data_source' => 'Penjualan',
                    'source_type' => SalesMstr::class,
                    'source_id'   => $sales->sales_mstr_id,
                    'date'        => now()
                ]);

                // ppn
                FinancialRecords::create([
                    'amount'      => $request->ppn,
                    'type'        => 'liability',
                    'method'      => $request->payment_method,
                    'data_source' => 'PPN Penjualan',
                    'source_type' => SalesMstr::class,
                    'source_id'   => $sales->sales_mstr_id,
                    'date'        => now()
                ]);
            }

            if ($request->payment_type === 'credit') {
                ArMstr::create([
                    'ar_mstr_nbr'    => $this->generateArNumber(),
                    'ar_mstr_salesid'   => $sales->sales_mstr_id,
                    'ar_mstr_customerid' => $request->customer_id,
                    'ar_mstr_amount'    => $sales->sales_mstr_grandtotal,
                    'ar_mstr_balance'   => $sales->sales_mstr_grandtotal,
                    'ar_mstr_date'      => now(),
                    'ar_mstr_duedate' => now()->addDays(14),
                    'ar_mstr_status'    => 'unpaid',
                ]);
            }

            DB::commit();

            // 4. RETURN SUKSES (DI LUAR TRANSAKSI)
            if ($request->type === 'paid') {
                return response()->json([
                    'status'  => 'success',
                    'message' => 'Transaksi berhasil disimpan!',
                    'print_id'      => $sales->sales_mstr_id
                ], 200);
            } else {
                return response()->json([
                    'status'  => 'success',
                    'message' => 'Transaksi berhasil dihold!'
                ], 200);
            }
        } catch (Exception $e) {
            // 5. ROLLBACK JIKA GAGAL
            // Semua data yang sempat masuk akan ditarik kembali
            DB::rollBack();

            // 6. RETURN ERROR
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan transaksi: ' . $e->getMessage()
            ], 500);
        };


        // return redirect()->route('SalesMstr.index')->with('success', 'Transaction Created Successfully!');
    }

    public function print($id)
    {
        $apotek = StoreProfile::first();
        // dd($apotek);
        // Menggunakan relasi yang sudah kita buat agar data bundle & racikan muncul
        $transaction = SalesMstr::with(['customer'])->findOrFail($id);

        $details = SalesDet::with([
            'product',
            'measurement',
            'prescription.details.product' // Penting untuk detail racikan di struk
        ])
            ->where('sales_det_mstrid', $id)
            ->get();

        return view('sales.print', compact('transaction', 'details', 'apotek'));
    }

    private function generateArNumber()
    {
        $last = ArMstr::orderByDesc('ar_mstr_id')->first();
        $next = $last && preg_match('/AR-(\d+)/', $last->ar_mstr_nbr, $m)
            ? intval($m[1]) + 1
            : 1;

        return 'AR-' . str_pad($next, 4, '0', STR_PAD_LEFT);
    }



    private function processSingleItem($sales, $item, $request)
    {
        // dd($item);
        $pm = ProductMeasurements::where('product_id', $item['product_id'])
            ->where('measurement_id', $item['measurement_id'])
            ->firstOrFail();

        $conversion = (float) $pm->conversion;

        $qty        = (float) $item['qty'];
        $price      = (float) $item['price'];
        $disc       = (float) ($item['disc'] ?? 0);

        $qtyBase    = $qty * $conversion;
        $priceBase = $price / $conversion;

        // dd($qtyBase, $priceBase);

        $batches = stocks::where('product_id', $item['product_id'])
            ->where('loc_id', $request->loc_id)
            ->where('quantity', '>', 0)
            ->join('batch_mstr', 'batch_mstr.batch_mstr_id', '=', 'stocks.batch_id')
            ->orderBy('batch_mstr_expireddate', 'asc')
            ->select('stocks.*')
            ->get();

        $needQty = $qtyBase;
        // dd($batches);

        foreach ($batches as $batch) {

            if ($needQty <= 0) break;

            $takeQty = min($batch->quantity, $needQty);
            $net     = ($takeQty * $priceBase) - $disc;

            SalesDet::create([
                'sales_det_mstrid'  => $sales->sales_mstr_id,
                'sales_det_productid' => $item['product_id'],
                'sales_det_locid'   => $request->loc_id,
                'sales_det_batchid' => $batch->batch_id,
                'sales_det_type' => 'single',
                'sales_det_parentid' => $item['sales_det_id'] ?? null,
                'sales_det_um'      => $item['measurement_id'],
                'sales_det_qty'     => $takeQty / $conversion,
                'sales_det_qtyconv' => $takeQty,
                'sales_det_umconv'  => $conversion,
                'sales_det_price'   => $price,
                'sales_det_priceconv' => $priceBase,
                'sales_det_disctype' => 'amount',
                'sales_det_discvalue' => $disc,
                'sales_det_discamt'  => $disc,
                'sales_det_subtotal' => $net,
            ]);

            // $batch->decrement('quantity', $takeQty);
            $needQty -= $takeQty;
        }

        // dd($needQty);

        if ($request->type === 'paid') {
            $this->processStockOutFIFO(
                $item['product_id'],
                $request->loc_id,
                $qtyBase,
                $sales->sales_mstr_id
            );
        }
    }

    private function processBundleItem($sales, $item, $request)
    {
        // 1️⃣ simpan bundle sebagai header
        $bundleRow = SalesDet::create([
            'sales_det_mstrid'  => $sales->sales_mstr_id,
            'sales_det_productid' => $item['product_id'],
            'sales_det_locid'   => $request->loc_id,
            'sales_det_batchid'   => null,
            'sales_det_type'   => $item['type'],
            'sales_det_parentid'   => null,
            'sales_det_um'      => $item['measurement_id'],
            'sales_det_umconv'    => '1',
            'sales_det_qty'     => $item['qty'],
            'sales_det_qtyconv'     => $item['qty'],
            'sales_det_price'   => $item['price'],
            'sales_det_discamt'   => 0,
            'sales_det_subtotal' => $item['qty'] * $item['price'],
            // 'is_bundle'         => 1, // ⭐
        ]);

        // 2️⃣ ambil komponen bundle
        $components = ProductBundle::where('bundle_product_id', $item['product_id'])
            ->with('productMeasurement.price')
            ->get();

        // dd($components);

        foreach ($components as $comp) {

            $pm = $comp->productMeasurement;
            // dd($pm);

            $componentQty = $comp->quantity * $item['qty'];
            // dd($componentQty);

            $this->processSingleItem(
                $sales,
                [
                    'sales_det_id'   => $bundleRow->sales_det_id,
                    'product_id'     => $pm->product_id,
                    'measurement_id' => $pm->measurement_id,
                    'qty'            => $componentQty,
                    'price'          => $pm->price->price,
                    'disc'           => 0,
                ],
                $request
            );
        }
    }

    protected function processRacikanItem($sales, $item, $request)
    {
        $idUnik = $item['price_id'];
        $dataRacik = $request->details[$idUnik] ?? null;

        if (!$dataRacik) return;

        // 1. SIMPAN HEADER RESEP
        $prescription = PresMstr::create([
            'pres_mstr_code'      => 'RCK-' . time(),
            'pres_mstr_name'      => $dataRacik['nama'],
            'pres_mstr_doctor'    => $request->doctor_name ?? 'Umum',
            'pres_mstr_type'      => 'prescription',
            'pres_mstr_qty'       => $dataRacik['jumlah_bungkus'],
            'pres_mstr_status'    => 'ready',
            'pres_mstr_mat'       => $dataRacik['total'] - $dataRacik['jasa'] - $dataRacik['markup'],
            'pres_mstr_fee'       => $dataRacik['jasa'] ?? 0,
            'pres_mstr_mark'      => $dataRacik['markup'] ?? 0,
            'pres_mstr_total'     => $dataRacik['total'],
            'pres_mstr_smid'      => $sales->sales_mstr_id,
            'pres_mstr_createdby' => auth()->user()->user_mstr_id,
        ]);

        // 2. LOOP BAHAN BAKU
        foreach ($dataRacik['details'] as $bahan) {
            $pm = ProductMeasurements::where('product_id', $bahan['product_id'])
                ->where('measurement_id', $bahan['measurement_id'])
                ->firstOrFail();

            $conversion = (float) $pm->conversion;
            $qtyBase    = (float) $bahan['qty'] * $conversion;

            // FIFO
            $appliedBatches = $this->processStockOutFIFO(
                $bahan['product_id'],
                $request->loc_id,
                $qtyBase,
                $sales->sales_mstr_id
            );

            // Simpan Detail Resep (Bisa banyak baris jika pecah batch)
            foreach ($appliedBatches as $ab) {
                $prescription->details()->create([
                    'pres_det_productid' => $bahan['product_id'],
                    'pres_det_um'        => $bahan['measurement_id'],
                    'pres_det_batchid'   => $ab['batch_id'],
                    'pres_det_qty'       => $ab['qty'] / $conversion, // Kembalikan ke satuan yang dipilih
                    'pres_det_price'     => $bahan['price'],
                ]);
            }
        }

        // 3. SIMPAN KE DETAIL PENJUALAN (Cukup 1 Baris per Racikan)
        // diletakkan di luar loop detail bahan
        $sales->details()->create([
            'sales_det_productid' => 0, // 0 menandakan ini item non-inventory/racikan
            'sales_det_qty'       => $item['qty'], // Ini adalah jumlah_bungkus (misal: 10)
            'sales_det_qtyconv'   => $item['qty'],
            'sales_det_parentid'  => null,
            'sales_det_um'        => $item['measurement_id'],
            'sales_det_umconv'    => 1,
            'sales_det_price'     => $item['price'],
            'sales_det_priceconv'     => $item['price'],
            'sales_det_subtotal'  => $item['qty'] * $item['price'],
            'sales_det_type'      => 'racikan',
            'sales_det_comp'      => true,
            'sales_det_pmid'      => $prescription->pres_mstr_id, // Link ke header racikan
            'sales_det_locid'     => $request->loc_id,
        ]);
    }

    private function processStockOutFIFO($productId, $locId, $qty, $sourceId)
    {
        $usedBatches = [];
        $remain = $qty;
        $allowNegative = AppSetting::get('allow_negative_stock', '0');

        // 1. Ambil semua batch yang tersedia
        $batches = stocks::where('product_id', $productId)
            ->where('loc_id', $locId)
            ->where('quantity', '>', 0)
            ->join('batch_mstr', 'batch_mstr.batch_mstr_id', '=', 'stocks.batch_id')
            ->orderBy('batch_mstr_expireddate', 'asc')
            ->select('stocks.*')
            ->lockForUpdate()
            ->get();
        $productname = Product::find($productId)->name;

        // 2. CEK TOTAL STOK DULU (Penting agar alert akurat)
        $totalTersedia = $batches->sum('quantity');
        // dd($totalTersedia);

        if ($allowNegative === '0' && $totalTersedia < $qty) {
            $kurangnya = $qty - $totalTersedia;
            throw new \Exception("Stok tidak mencukupi. Obat: {$productname}, Total tersedia: {$totalTersedia}, Kurang: {$kurangnya}");
        }

        // 3. JIKA LOLOS CEK, BARU LAKUKAN PENGURANGAN (Looping)
        foreach ($batches as $stock) {
            if ($remain <= 0) break;

            $take = min($stock->quantity, $remain);

            // Catat transaksi stok
            StockTransactions::create([
                'product_id' => $productId,
                'loc_id'     => $locId,
                'batch_id'   => $stock->batch_id,
                'type'       => 'out',
                'quantity'   => $take * -1,
                'source_type' => SalesMstr::class,
                'source_id'  => $sourceId,
                'date'       => now()
            ]);

            $usedBatches[] = [
                'batch_id' => $stock->batch_id,
                'qty'      => $take
            ];

            // Kurangi stok di tabel stocks
            $stock->decrement('quantity', $take);
            $remain -= $take;
        }

        // 4. Logika jika boleh minus (allowNegative == 1) tapi semua batch sudah habis
        if ($remain > 0 && $allowNegative === '1') {
            throw new \Exception("Stok negatif telah diizinkan, namun stok fisik untuk produk ID {$productId} di lokasi ID {$locId} sudah habis.");
        }

        return $usedBatches; // Benar di sini
    }



    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $details = SalesDet::with([
            'product',
            'measurement',
            'prescription.details.product', // Untuk bahan racikan
        ])
            ->where('sales_det_mstrid', $id)
            ->get();

        $transaction = SalesMstr::findOrFail($id);
        return view('sales.SalesDetList', compact('details', 'transaction'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SalesMstr $salesMstr)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSalesMstrRequest $request, SalesMstr $salesMstr)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $sales = SalesMstr::findOrFail($id);

            // 1. AMBIL SEMUA DETAIL
            $details = SalesDet::where('sales_det_mstrid', $id)->get();

            // --- VALIDASI TAMBAHAN: CEK RETURN ---
            // Asumsi nama model return Anda adalah SalesReturnMstr
            $hasReturn = SrMstr::where('sr_mstr_smid', $id)->exists();
            if ($hasReturn) {
                return redirect()->back()->with('error', 'Gagal! Transaksi ini sudah memiliki data Return. Hapus data return terlebih dahulu.');
            }

            foreach ($details as $item) {
                /** * LOGIKA PENGEMBALIAN STOK BERDASARKAN KOREKSI:
                 * - Jika Racikan (pmid tidak NULL), stok TIDAK kembali.
                 * - Selain itu (Single & Bundle), stok kembali ke tabel stocks.
                 */
                if (is_null($item->sales_det_pmid)) {

                    if ($item->sales_det_batchid) {
                        $stock = stocks::where('product_id', $item->sales_det_productid)
                            ->where('loc_id', $item->sales_det_locid)
                            ->where('batch_id', $item->sales_det_batchid)
                            ->lockForUpdate()
                            ->first();

                        if ($stock) {
                            $stock->increment('quantity', $item->sales_det_qtyconv);

                            // 2. CATAT PEMBATALAN DI STOCK TRANSACTIONS (Hanya jika stok kembali)
                            StockTransactions::create([
                                'product_id' => $item->sales_det_productid,
                                'loc_id'     => $item->sales_det_locid,
                                'batch_id'   => $item->sales_det_batchid,
                                'type'       => 'in',
                                'quantity'   => $item->sales_det_qtyconv,
                                'source_type' => SalesMstr::class,
                                'source_id'  => $id,
                                'date'       => now(),
                                'note'       => 'Void Sales: ' . $sales->sales_mstr_nbr
                            ]);
                        }
                    }
                }
            }

            // 3. HAPUS FINANCIAL RECORDS
            FinancialRecords::where('source_id', $id)
                ->where('source_type', SalesMstr::class)
                ->delete();

            // 4. LOGIKA AR / PIUTANG
            // Kita gunakan pengecekan paid > 0 agar lebih aman
            $ar = ArMstr::where('ar_mstr_salesid', $id)->first();
            if ($ar) {
                if ($ar->ar_mstr_paid > 0) {
                    // Opsional: Jika ingin ketat, lempar exception jika sudah ada cicilan
                    throw new \Exception("Transaksi tidak bisa dihapus karena sudah ada pembayaran pada Piutang.");
                }

                // Hapus Payment Detail & Master jika ada (meskipun paid 0, mungkin ada record draft)
                $payments = ArpayDet::where('arpay_det_arid', $ar->ar_mstr_id)->get();
                foreach ($payments as $p) {
                    // Ambil master payment-nya untuk dihapus
                    ArpayMstr::where('arpay_mstr_id', $p->arpay_det_mstrid)->delete();
                    $p->delete();
                }
                $ar->delete();
            }

            // 5. HAPUS SALES DETAIL & MASTER
            SalesDet::where('sales_det_mstrid', $id)->delete();
            $sales->delete();

            DB::commit();
            return redirect()->back()->with('success', 'Transaksi berhasil dihapus. Stok (non-racikan) telah dikembalikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus transaksi: ' . $e->getMessage());
        }
    }
}
