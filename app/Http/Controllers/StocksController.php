<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\stocks;
use App\Models\StockTransactions;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\StorestocksRequest;
use App\Http\Requests\UpdatestocksRequest;
use Illuminate\Validation\ValidationException;

class StocksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roleAdmin = auth()->user()->hasRole('Admin');
        $roleKasir = auth()->user()->hasRole('Kasir');
        if ($roleAdmin || $roleKasir) {
            $stocks = stocks::where('loc_id', 1)->orderBy('id', 'desc')->get();
        } else {
            $stocks = stocks::orderBy('id', 'desc')->get();
        }
        return view('report.Stock', compact('stocks'));
    }


    public function stockHistory($id)
    {
        $stock = stocks::findOrFail($id);
        $transactions = StockTransactions::with(['product', 'location', 'batch', 'source'])
            ->where('product_id', $stock->product_id)
            ->where('loc_id', $stock->loc_id)
            ->where('batch_id', $stock->batch_id)
            ->latest()
            ->paginate(50);


        $detailsMap = [];

        foreach ($transactions as $st) {
            if ($st->source && method_exists($st->source, 'details')) {
                foreach ($st->source->details as $det) {
                    // Kita buat Key unik per transaksi agar tidak tertukar

                    $key = "{$st->source_type}_{$st->source_id}";

                    // Ambil harga berdasarkan jenis transaksi
                    $price = 0;
                    if ($st->source_type == \App\Models\SalesMstr::class) {
                        $qtyConv = max($det->sales_det_umconv ?? 1, 1);
                        $price = ($det->sales_det_price ?? 0) / $qtyConv;
                    } elseif ($st->source_type == \App\Models\BpbMstr::class) {
                        $price = $det->bpb_det_priceconv ?? 0;
                    } elseif ($st->source_type == \App\Models\PrMstr::class) {
                        $qtyConv = max($det->pr_det_umconv ?? 1, 1);
                        $price = ($det->pr_det_price ?? 0) / $qtyConv;
                    } elseif ($st->source_type == \App\Models\SrMstr::class) {
                        $qtyConv = max($det->sr_det_umconv ?? 1, 1);
                        $price = ($det->sr_det_price ?? 0) / $qtyConv;
                    } else {
                        $price = $det->sr_det_price ?? 0;
                    }

                    // Simpan ke map hanya jika product_id-nya cocok dengan transaksi ini
                    // Sesuaikan nama kolom ID produk masing-masing tabel
                    $detPid = $det->sales_det_productid ?? $det->bpb_det_productid ?? $det->sr_det_productid ?? $det->pr_det_productid ?? $det->sa_det_productid ?? $det->ts_det_productid ?? 0;

                    if ($detPid == $st->product_id) {
                        $detailsMap[$key] = [
                            'price' => $price,
                            'total' => $det->sales_det_subtotal ?? $det->bpb_det_total ?? $det->sr_det_subtotal ?? $det->pr_det_subtotal ?? 0
                        ];
                    }
                }
            }
        }

        // dd($detailsMap);

        return view('report.StockHistory', compact('transactions', 'detailsMap'));
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
    public function store(Request $request)
    {
        // 
    }

    /**
     * Display the specified resource.
     */
    public function show(stocks $stocks)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(stocks $stocks)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatestocksRequest $request, stocks $stocks)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(stocks $stocks)
    {
        //
    }
}
