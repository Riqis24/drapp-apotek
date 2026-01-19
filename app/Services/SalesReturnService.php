<?php

namespace App\Services;

use Exception;
use App\Models\SrDet;
use App\Models\ArMstr;
use App\Models\SrMstr;
use App\Models\stocks;
use App\Models\SalesDet;
use App\Models\SalesMstr;
use App\Models\FinancialRecords;
use App\Models\StockTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;


class SalesReturnService
{
  public function processReturn(
    SrMstr $return,
    array $items
  ) {
    DB::transaction(function () use ($return, $items) {
      // dd('masuk service');
      foreach ($items as $item) {
        // dd($items);

        $salesDet = SalesDet::with([
          'children',      // component bundle
          'batch',
        ])->findOrFail($item['sales_det_id']);

        // 1️⃣ VALIDASI QTY
        // Ambil setting dengan cache agar kencang
        $allowNegative = Cache::rememberForever('setting_allow_negative_stock', function () {
          return DB::table('settings')->where('key', 'allow_negative_stock')->value('value') ?? '0';
        });

        if ($allowNegative === '0' && $item['qty'] > $salesDet->qty_remaining) {
          $itemName = $salesDet->product->name;
          // dd($item['qty'], $salesDet->qty_remaining);
          // Pesan error yang lebih informatif berdasarkan tipe
          $errorMessage = "Gagal Return: Jumlah return untuk [$itemName] melebihi sisa pembelian.";
          if ($salesDet->sales_det_pmid) {
            $errorMessage = "Gagal Return: Racikan [$itemName] tidak dapat di-return melebihi jumlah sisa di nota.";
          }

          throw new \Exception($errorMessage);
        }


        if ($salesDet->sales_det_type !== 'racikan') {
          if ($item['qty'] > 0) {
            // 2️⃣ HANDLE BERDASARKAN TYPE
            if ($salesDet->sales_det_type === 'single') {
              $this->returnSingle($return, $salesDet, $item['qty'] ?? 0);
            }

            if ($salesDet->sales_det_type === 'bundle') {
              $this->returnBundle($return, $salesDet, $item['qty']);
            }

            // if ($salesDet->sales_det_type === 'racikan') {
            //   $this->returnRacikan($return, $salesDet, $item['qty']);
            // }

            // 3️⃣ UPDATE remaining_qty

            $salesDet->increment('sales_det_qtyreturn', $item['qty'] ?? 0);
          }
        } else {
          $sales = $salesDet->update([
            'sales_det_qtyreturn' => $salesDet->sales_det_qtyreturn + $item['qty'] ?? 0
          ]);
          // dd($sales);
        }
      }

      // 4️⃣ FINANCIAL REVERSAL
      $this->reverseFinancial($return);
    });
  }

  protected function returnSingle(SrMstr $return, SalesDet $salesDet, float $qty)
  {
    // 1. Hitung Base Qty (Satuan Terkecil)
    $qtyBase = $qty * $salesDet->sales_det_umconv;

    if ($qtyBase > 0) {
      // 2. 🔁 STOCK IN (Kodingan stokmu sudah oke, cuma hapus minusnya)
      StockTransactions::create([
        'product_id'  => $salesDet->sales_det_productid,
        'loc_id'      => $salesDet->sales_det_locid,
        'batch_id'    => $salesDet->sales_det_batchid,
        'type'        => 'in',
        'quantity'    => $qtyBase, // Gunakan nilai positif karena tipenya 'in'
        'source_type' => SrMstr::class,
        'source_id'   => $return->sr_mstr_id,
        'date'        => now(),
        'created_by' => auth()->user()->user_mstr_id,

      ]);

      Stocks::updateOrCreate(
        ['product_id' => $salesDet->sales_det_productid, 'loc_id' => $salesDet->sales_det_locid, 'batch_id' => $salesDet->sales_det_batchid],
        ['quantity'   => DB::raw("quantity + {$qtyBase}")]
      );

      // 3. 📄 HITUNG HARGA NETTO PER ITEM
      // Karena sales_det_subtotal sudah dipotong diskon item, kita bagi dengan qty aslinya
      $unitPriceNett = $salesDet->sales_det_subtotal / $salesDet->sales_det_qty;

      // 4. CREATE DETAIL RETURN
      SrDet::create([
        'sr_det_mstrid'    => $return->sr_mstr_id,
        'sr_det_sdid'      => $salesDet->sales_det_id,
        'sr_det_productid' => $salesDet->sales_det_productid,
        'sr_det_um'        => $salesDet->sales_det_um,
        'sr_det_umconv'    => $salesDet->sales_det_umconv,
        'sr_det_qty'       => $qty,
        'sr_det_qtyconv'   => $qtyBase,
        'sr_det_batchid'   => $salesDet->sales_det_batchid,
        'sr_det_price'     => $unitPriceNett, // Ini harga setelah diskon item
        'sr_det_subtotal'  => $qty * $unitPriceNett, // Subtotal return murni per item
      ]);
    }
  }
  protected function returnBundle(
    SrMstr $return,
    SalesDet $bundleDet,
    float $bundleQty
  ) {
    foreach ($bundleDet->children as $component) {

      // qty jual komponen PER BUNDLE sudah ada di sales_det_qty
      $componentQty = $bundleQty * $component->sales_det_qty;

      $this->returnSingle(
        $return,
        $component,
        $componentQty
      );

      // update returned qty component
      $component->increment('sales_det_qtyreturn', $componentQty);
    }

    // update returned qty bundle HEADER (tanpa SrDet financial)
    $bundleDet->increment('sales_det_qtyreturn', $bundleQty);
  }


  protected function reverseFinancial(SrMstr $return)
  {
    // dd($return->sales);
    // 1. Ambil data Sales terkait
    $sales = $return->sales;
    if (!$sales) return;

    // 2. Hitung total yang direturn (dari detail return)
    $returnTotal = $return->details()->sum('sr_det_subtotal');
    $paid = (float) $sales->sales_mstr_paidamt;

    // 3. Ambil dan Update AR (Accounts Receivable / Piutang)
    $ar = ArMstr::where('ar_mstr_salesid', $sales->sales_mstr_id)
      ->lockForUpdate() // Mengunci row agar tidak ada double update
      ->first();

    if ($ar) {
      $ar->ar_mstr_amount  -= $returnTotal;
      $ar->ar_mstr_balance -= $returnTotal;

      if ($ar->ar_mstr_balance <= 0) {
        $ar->ar_mstr_balance = 0;
        $ar->ar_mstr_status = 'paid';
      } else {
        // Jika piutang masih ada sisa
        $ar->ar_mstr_status = ($paid <= 0) ? 'unpaid' : 'partial';
      }
      $ar->save();
    }

    // 4. Update Status Sales ke VOID jika AR sudah nol (Return Total)
    if ($ar && $ar->ar_mstr_amount <= 0) {
      $sales->update(['sales_mstr_status' => 'void']);
    }

    // 5. Cek apakah ada uang yang harus dikembalikan (Refund)
    // Jika belum bayar sama sekali (paid <= 0), maka tidak ada record financial (kas keluar)
    if ($paid <= 0) return;

    // Refund maksimal sebesar yang sudah pernah dibayar (mencegah rugi akibat diskon global)
    $refund = min($paid, $returnTotal);


    if ($refund > 0) {
      // Logika DPP dan PPN (Asumsi PPN 11%)
      $dppRefund = $refund / 1.11;
      $ppnRefund = $refund - $dppRefund;
      if ($sales->sales_mstr_ppnamt > 0) {
        // A. Catat Pengembalian Pendapatan (DPP) ke Expense
        FinancialRecords::create([
          'amount'      => $dppRefund,
          'type'        => 'expense',
          'data_source' => 'Refund Return Penjualan (DPP)',
          'source_type' => SrMstr::class,
          'source_id'   => $return->sr_mstr_id,
          'date'        => now(),
          'created_by' => auth()->user()->user_mstr_id,
        ]);

        // B. Catat Pembatalan Hutang Pajak (PPN)
        if ($sales->sales_mstr_ppnamt > 0) {
          FinancialRecords::create([
            'amount'      => $ppnRefund,
            'type'        => 'liability',
            'data_source' => 'PPN Return Penjualan',
            'source_type' => SrMstr::class,
            'source_id'   => $return->sr_mstr_id,
            'date'        => now(),
            'created_by' => auth()->user()->user_mstr_id,
          ]);
        }



        // C. Kurangi nilai 'paid' di Sales Master
        $sales->decrement('sales_mstr_paidamt', $refund);
      } else {
        // A. Catat Pengembalian Pendapatan (DPP) ke Expense
        FinancialRecords::create([
          'amount'      => $refund,
          'type'        => 'expense',
          'data_source' => 'Refund Return Penjualan',
          'source_type' => SrMstr::class,
          'source_id'   => $return->sr_mstr_id,
          'date'        => now(),
          'created_by' => auth()->user()->user_mstr_id,
        ]);

        // C. Kurangi nilai 'paid' di Sales Master
        $sales->decrement('sales_mstr_paidamt', $refund);
      }
    }
  }
}
