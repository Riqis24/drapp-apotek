<?php

namespace App\Services;

use Exception;
use App\Models\SrDet;
use App\Models\ArMstr;
use App\Models\SrMstr;
use App\Models\stocks;
use App\Models\TsMstr;
use App\Models\SalesDet;
use App\Models\FinancialRecords;
use App\Models\StockTransactions;
use Illuminate\Support\Facades\DB;

class TransferService
{
    public function post(TsMstr $ts)
    {
        DB::transaction(function () use ($ts) {

            foreach ($ts->details as $det) {

                $stock = Stocks::lockForUpdate()
                    ->where('loc_id', $ts->ts_mstr_from)
                    ->where('product_id', $det->ts_det_productid)
                    ->where('batch_id', $det->ts_det_batchid)
                    ->first();

                if (!$stock) {
                    throw new Exception('Stok tidak ditemukan');
                }

                if ($det->ts_det_qty > $stock->quantity) {
                    throw new Exception(
                        "Qty melebihi stok batch {$stock->batch_id}"
                    );
                }

                // 🔽 STOCK OUT
                $stock->decrement('quantity', $det->ts_det_qty);

                // 🔼 STOCK IN (tujuan)
                Stocks::updateOrCreate(
                    [
                        'product_id' => $det->ts_det_productid,
                        'batch_id'   => $det->ts_det_batchid,
                        'loc_id'     => $ts->ts_mstr_to,
                    ],
                    [
                        'quantity' => DB::raw("quantity + {$det->ts_det_qty}")
                    ]
                );
                // OUT
                StockTransactions::create([
                    'product_id' => $det->ts_det_productid,
                    'loc_id'     => $ts->ts_mstr_from,
                    'batch_id'   => $det->ts_det_batchid,
                    'quantity'   => $det->ts_det_qty * -1,
                    'type'       => 'out',
                    'source_type' => TsMstr::class,
                    'source_id'  => $ts->ts_mstr_id,
                    'date'       => now()
                ]);

                // IN
                StockTransactions::create([
                    'product_id' => $det->ts_det_productid,
                    'loc_id'     => $ts->ts_mstr_to,
                    'batch_id'   => $det->ts_det_batchid,
                    'quantity'   => $det->ts_det_qty,
                    'type'       => 'in',
                    'source_type' => TsMstr::class,
                    'source_id'  => $ts->ts_mstr_id,
                    'date'       => now()
                ]);
            }

            $ts->update(['ts_mstr_status' => 'posted']);
        });
    }

    public function cancelpost(TsMstr $ts)
    {
        DB::transaction(function () use ($ts) {


            foreach ($ts->details as $det) {
                $stock = Stocks::lockForUpdate()
                    ->where('loc_id', $ts->ts_mstr_to)
                    ->where('product_id', $det->ts_det_productid)
                    ->where('batch_id', $det->ts_det_batchid)
                    ->first();

                if (!$stock) {
                    throw new Exception('Stok tidak ditemukan');
                }

                if ($stock->quantity < $det->ts_det_qty) {
                    throw new Exception(
                        'Stok tujuan sudah berkurang, tidak bisa cancel TS'
                    );
                }

                // 🔽 STOCK OUT
                $stock->decrement('quantity', $det->ts_det_qty);

                // 🔼 STOCK IN (from)
                Stocks::updateOrCreate(
                    [
                        'product_id' => $det->ts_det_productid,
                        'batch_id'   => $det->ts_det_batchid,
                        'loc_id'     => $ts->ts_mstr_from,
                    ],
                    [
                        'quantity' => DB::raw("quantity + {$det->ts_det_qty}")
                    ]
                );

                $trhist = StockTransactions::where('source_type', TsMstr::class)->where('source_id', $ts->ts_mstr_id)->delete();
            }

            $ts->update(['ts_mstr_status' => 'cancelled']);
        });
    }
}
