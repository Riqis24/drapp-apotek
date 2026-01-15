<?php

namespace App\Services;

use App\Models\stocks;
use App\Models\PresDet;
use App\Models\PresMstr;
use App\Models\StockTransactions;
use Illuminate\Support\Facades\DB;

class PrescriptionService
{
  public function create(array $data, array $items, string $status = 'draft'): PresMstr
  {
    return DB::transaction(function () use ($data, $items, $status) {

      $pres = PresMstr::create([
        'pres_mstr_code'   => $data['code'],
        'pres_mstr_name'   => $data['name'],
        'pres_mstr_doctor' => $data['doctor'] ?? null,
        'pres_mstr_type'   => $data['type'] ?? 'prescription',
        'pres_mstr_qty'    => $data['qty'],
        'pres_mstr_status' => $status,
        'pres_mstr_fee'    => $data['fee'] ?? 0,
        'pres_mstr_mark'   => $data['mark'] ?? 0,
        'pres_mstr_createdby'       => auth()->user()->user_mstr_id,
      ]);

      $totalMat = 0;

      foreach ($items as $item) {
        $det = PresDet::create([
          'pres_mstr_id'  => $pres->pres_mstr_id,
          'pres_det_productid'    => $item['product_id'],
          'pres_det_um' => $item['measurement_id'],
          'pres_det_batchid'      => $item['batch_id'],
          'pres_det_qty'  => $item['qty'],
          'pres_det_price' => $item['price'],
        ]);

        $totalMat += $det->pres_det_price;
      }

      $pres->update([
        'pres_mstr_mat'   => $totalMat,
        'pres_mstr_total' => $totalMat + $pres->pres_mstr_fee + $pres->pres_mstr_mark,
      ]);

      return $pres;
    });
  }

  public function markPaid(PresMstr $pres, int $salesId): void
  {
    if ($pres->pres_mstr_status !== 'ready') {
      throw new \Exception('Prescription not ready to be paid');
    }

    $pres->update([
      'pres_mstr_status' => 'paid',
      'sales_mstr_id'    => $salesId,
    ]);
  }

  public function consumePrescription(PresMstr $pres, int $locId): void
  {
    DB::transaction(function () use ($pres, $locId) {

      foreach ($pres->details as $det) {

        // 1️⃣ ambil stok batch per lokasi
        $stock = stocks::where('product_id', $det->pres_det_productid)
          ->where('batch_id', $det->pres_det_batchid)
          ->where('loc_id', $locId)
          ->lockForUpdate()
          ->first();

        if (!$stock) {
          throw new \Exception('Stock not found for batch');
        }

        // 2️⃣ validasi stok cukup
        if ($stock->quantity < $det->pres_det_qty) {
          throw new \Exception('Insufficient stock for product');
        }

        // 3️⃣ kurangi stok
        $stock->update([
          'quantity' => $stock->quantity - $det->pres_det_qty
        ]);

        // 4️⃣ catat transaksi stok
        StockTransactions::create([
          'date'          => now(),
          'product_id'    => $det->pres_det_productid,
          'batch_id'      => $det->pres_det_batchid,
          'quantity'      => $det->pres_det_qty * -1,
          'type'          => 'out',
          'source_id'     => $pres->pres_mstr_id,
          'source_type'   => PresMstr::class,
        ]);
      }
    });
  }
}
