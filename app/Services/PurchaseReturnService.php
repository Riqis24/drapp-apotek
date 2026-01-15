<?php

namespace App\Services;

use Exception;
use App\Models\PoDet;
use App\Models\PrDet;
use App\Models\ApMstr;
use App\Models\BatchMstr;
use App\Models\BpbDet;
use App\Models\PrMstr;
use App\Models\Stocks;
use App\Models\BpbMstr;
use App\Models\FinancialRecords;
use App\Models\StockTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class PurchaseReturnService
{
    public function process(array $data)
    {
        $bpb = BpbMstr::findOrFail($data['bpb_id']);

        /* =========================
             * 1. CEK AP
             * ========================= */
        $ap = ApMstr::where('ap_mstr_reftype', 'bpb')
            ->where('ap_mstr_refid', $bpb->bpb_mstr_id)
            ->first();

        /* =========================
             * 2. CREATE PR MASTER
             * ========================= */
        $pr = PrMstr::create([
            'pr_mstr_nbr'       => $this->generatePrNumber(),
            'pr_mstr_bpbid'     => $bpb->bpb_mstr_id,
            'pr_mstr_poid'      => $bpb->bpb_mstr_poid,
            'pr_mstr_suppid'    => $bpb->bpb_mstr_suppid,
            'pr_mstr_date'      => $data['pr_mstr_date'],
            'pr_mstr_reason'    => $data['pr_mstr_reason'],
            'pr_mstr_createdby' => auth()->user()->user_mstr_id,
        ]);

        $totalReturn = 0;



        /* =========================
             * 3. LOOP ITEM RETURN
             * ========================= */
        foreach ($data['items'] as $item) {

            if ($item['qty'] <= 0) continue;

            $bpbDet = BpbDet::findOrFail($item['bpb_det_id']);

            /* --- VALIDASI SISA RETURN --- */
            $returned = PrDet::where('pr_det_bpbdetid', $bpbDet->bpb_det_id)
                ->sum('pr_det_qty');

            $remaining = $bpbDet->bpb_det_qty - $returned;

            if ($item['qty'] > $remaining) {
                throw new \Exception('Qty return melebihi sisa BPB');
            }

            $subtotal = $item['qty'] * $bpbDet->bpb_det_price;
            $qtyBase = $item['qty'] * $bpbDet->bpb_det_umconv;
            $totalReturn += $subtotal;


            /* =========================
                 * 3A. INSERT PR DETAIL
                 * ========================= */
            PrDet::create([
                'pr_det_mstrid'    => $pr->pr_mstr_id,
                'pr_det_bpbdetid'  => $bpbDet->bpb_det_id,
                'pr_det_productid' => $bpbDet->bpb_det_productid,
                'pr_det_um'        => $bpbDet->bpb_det_um,
                'pr_det_umconv'    => $bpbDet->bpb_det_umconv,
                'pr_det_qty'       => $item['qty'],
                'pr_det_qtyconv'   => $qtyBase,
                'pr_det_batchid'   => $bpbDet->bpb_det_batch,
                'pr_det_price'     => $bpbDet->bpb_det_price,
                'pr_det_subtotal'  => $subtotal,
            ]);

            /* =========================
                 * 3B. STOCK TRANSACTION (OUT)
                 * ========================= */
            StockTransactions::create([
                'product_id'  => $bpbDet->bpb_det_productid,
                'loc_id'      => $bpb->bpb_mstr_locid,
                'batch_id'    => $bpbDet->bpb_det_batch,
                'type'        => 'out',
                'quantity'    => $qtyBase * -1,
                'note'        => 'Purchase Return',
                'date'        => now(),
                'source_type' => PrMstr::class,
                'source_id'   => $pr->pr_mstr_id,
            ]);

            $qtyBaseMinus = $qtyBase * -1;

            /* =========================
                 * 3C. UPDATE STOCK SALDO
                 * ========================= */
            $this->updateStock(
                $bpbDet->bpb_det_productid,
                $bpb->bpb_mstr_locid,
                $bpbDet->bpb_det_batch,
                $qtyBaseMinus
            );
        }

        /* =========================
             * 4. HANDLE AP
             * ========================= */
        if ($ap) {
            $this->handleApAdjustment($ap, $totalReturn);
        }

        return $pr;
    }

    protected function generatePrNumber(): string
    {
        $last = PrMstr::orderBy('pr_mstr_id', 'desc')->first();

        $next = $last
            ? intval(substr($last->pr_mstr_nbr, -5)) + 1
            : 1;

        return 'PR-' . str_pad($next, 5, '0', STR_PAD_LEFT);
    }

    /* =========================
     * UPDATE STOCK SALDO
     * ========================= */
    protected function updateStock($productId, $locId, $batchId, $qtyChange)
    {
        // dd($productId, $locId, $batchId, $qtyChange);
        $stock = stocks::firstOrCreate(
            [
                'product_id' => $productId,
                'loc_id'     => $locId,
                'batch_id'   => $batchId,
            ],
            [
                'quantity' => 0
            ]
        );

        $newQty = $stock->quantity + $qtyChange;

        if ($newQty < 0) {
            throw new \Exception('Stok tidak mencukupi untuk return');
        }

        $stock->quantity = $newQty;
        $stock->save();
    }

    /* =========================
     * HANDLE AP
     * ========================= */
    protected function handleApAdjustment(ApMstr $ap, float $returnAmount)
    {
        // kurangi total AP
        $ap->ap_mstr_amount -= $returnAmount;

        // kurangi balance (boleh jadi minus)
        $ap->ap_mstr_balance -= $returnAmount;

        if ($ap->ap_mstr_balance > 0) {
            // masih ada hutang
            $ap->ap_mstr_status = 'partial';
        } elseif ($ap->ap_mstr_balance == 0) {
            // tepat lunas setelah return
            $ap->ap_mstr_status = 'paid';
        } else {
            // minus = credit note ke supplier
            $ap->ap_mstr_status = 'credit';
        }

        $ap->save();
        // dd('masuk step handleap');
    }


    public function getBpbItemsWithRemaining($bpbId)
    {
        return BpbDet::where('bpb_det_mstrid', $bpbId)
            ->leftJoin('pr_det', 'pr_det.pr_det_bpbdetid', '=', 'bpb_det.bpb_det_id')
            ->select(
                'bpb_det.*',
                DB::raw('COALESCE(SUM(pr_det.pr_det_qty),0) as qty_returned')
            )
            ->groupBy('bpb_det.bpb_det_id')
            ->get()
            ->map(function ($row) {
                $row->qty_remaining = $row->bpb_det_qty - $row->qty_returned;
                return $row;
            });
    }

    public function cancel($prId)
    {

        $pr = PrMstr::with(['details', 'bpb'])->findOrFail($prId);

        if ($pr->pr_mstr_status === 'cancel') {
            throw new \Exception('Return sudah dibatalkan');
        }

        foreach ($pr->details as $det) {

            $qtyBase = $det->pr_det_qtyconv;

            // rollback stock
            StockTransactions::create([
                'product_id'  => $det->pr_det_productid,
                'loc_id'      => $pr->bpb->bpb_mstr_locid,
                'batch_id'    => $det->pr_det_batchid,
                'type'        => 'in',
                'quantity'    => $qtyBase,
                'note'        => 'Cancel Purchase Return',
                'date'        => now(),
                'source_type' => PrMstr::class,
                'source_id'   => $pr->pr_mstr_id,
            ]);

            $this->updateStock(
                $det->pr_det_productid,
                $pr->bpb->bpb_mstr_locid,
                $det->pr_det_batchid,
                $qtyBase
            );
        }

        // tandai cancel (jangan delete)
        $pr->update([
            'pr_mstr_status' => 'CANCEL'
        ]);

        return true;
    }
}
