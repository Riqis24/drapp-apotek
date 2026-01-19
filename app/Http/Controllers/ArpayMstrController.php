<?php

namespace App\Http\Controllers;

use App\Models\ArMstr;
use App\Models\ArpayDet;
use App\Models\ArpayMstr;
use Illuminate\Http\Request;
use App\Models\FinancialRecords;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreArpayMstrRequest;
use App\Http\Requests\UpdateArpayMstrRequest;
use App\Models\Customer;
use App\Models\SalesMstr;

class ArpayMstrController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        return view('ar.ArpayMstrList', compact('customers'));
    }

    public function getArByCustomer($custid)
    {
        return ArMstr::where('ar_mstr_customerid', $custid)
            ->where('ar_mstr_balance', '>', 0)
            ->orderBy('ar_mstr_duedate')
            ->get();
    }

    /**
     * Store a newly created resource in storage.
     */

    private function generateArPayNumber()
    {
        $last = ArpayMstr::orderByDesc('arpay_mstr_id')->first();
        $next = $last && preg_match('/ARPAY-(\d+)/', $last->ar_mstr_nbr, $m)
            ? intval($m[1]) + 1
            : 1;

        return 'ARPAY-' . str_pad($next, 4, '0', STR_PAD_LEFT);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pay_date'     => 'required|date',
            'customer_id'  => 'required',
            'totalPay'   => 'required|numeric|min:1',
            'items'        => 'required|array|min:1',
        ]);

        DB::transaction(function () use ($request) {
            $totalPay = (float)str_replace('.', '', $request->totalPay);
            // dd($totalPay);

            // 1. header payment
            $pay = ArPayMstr::create([
                'arpay_mstr_nbr'       => $this->generateArPayNumber(),
                'arpay_mstr_date'       => $request->pay_date,
                'arpay_mstr_customerid' => $request->customer_id,
                'arpay_mstr_amount'     => $totalPay,
                'arpay_mstr_method'     => $request->payment_method,
                'arpay_mstr_ref'        => $request->ref_no,
                'arpay_mstr_createdby'  => auth()->user()->user_mstr_id,
            ]);

            $totalAllocated = 0;

            // 2. loop AR yang dibayar
            foreach ($request->items as $item) {

                if ($item['pay_amount'] <= 0) continue;

                $ar = ArMstr::lockForUpdate()->findOrFail($item['ar_id']);

                if ($item['pay_amount'] > $ar->ar_mstr_balance) {
                    throw new \Exception('Pembayaran melebihi sisa piutang');
                }

                ArpayDet::create([
                    'arpay_det_mstrid' => $pay->arpay_mstr_id,
                    'arpay_det_arid'   => $ar->ar_mstr_id,
                    'arpay_det_amount' => $item['pay_amount'],
                ]);

                // update AR
                $ar->ar_mstr_paid    += $item['pay_amount'];
                $ar->ar_mstr_balance -= $item['pay_amount'];

                $ar->ar_mstr_status = $ar->ar_mstr_balance <= 0
                    ? 'paid'
                    : 'partial';

                $ar->save();

                $totalAllocated += $item['pay_amount'];

                // update sales_mstr_paidamt
                $sales = SalesMstr::lockForUpdate()->findOrFail($ar->ar_mstr_salesid);
                // dd($sales);
                $sales->sales_mstr_paidamt += $item['pay_amount'];
                $sales->save();
            }

            if ($totalAllocated != $totalPay) {
                throw new \Exception('Total alokasi tidak sama dengan total pembayaran');
            }

            FinancialRecords::create([
                'amount'      => $totalPay,
                'type'        => 'income',
                'method'      => $request->payment_method,
                'data_source' => 'Ar Payment',
                'source_type' => ArpayMstr::class,
                'source_id'   => $pay->arpay_mstr_id,
                'date'        => now(),
                'created_by' => auth()->user()->user_mstr_id,

            ]);

            // jurnal nanti:
            // Kas / Bank (D)
            // Piutang Usaha (C)
        });

        return redirect()->route('ArMstr.index')
            ->with('success', 'Pembayaran piutang berhasil');
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // 1. Ambil data Master AR (Piutang)
        $transaction = ArMstr::with(['customer', 'sales']) // Pastikan relasi ini ada di Model
            ->where('ar_mstr_id', $id) // Sesuaikan PK Anda
            ->firstOrFail();

        // 2. Ambil history pembayaran (AR Payment Detail) yang berkaitan dengan AR ini
        // Kita join ke Master Payment untuk mendapatkan Nomor, Tanggal, dan Method
        $aps = ArPayDet::with(['master.customer']) // Eager load master payment
            ->where('arpay_det_arid', $id)
            ->get();

        return view('ar.ArMstrPay', compact('transaction', 'aps'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ArpayMstr $arpayMstr)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateArpayMstrRequest $request, ArpayMstr $arpayMstr)
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
            // 1. Cari Master Payment yang akan dihapus
            $payMstr = ArPayMstr::findOrFail($id);

            // 2. Ambil semua detail pembayaran terkait (karena satu payment bisa melunasi beberapa invoice AR)
            $details = ArPayDet::where('arpay_det_mstrid', $id)->get();

            foreach ($details as $det) {
                // 3. Cari Master AR (Invoice) yang bersangkutan
                $ar = ArMstr::where('ar_mstr_id', $det->arpay_det_arid)->first();

                if ($ar) {
                    // 4. Update Saldo AR: Kurangi Paid, Tambah Balance
                    $newPaid = $ar->ar_mstr_paid - $det->arpay_det_amount;
                    $newBalance = $ar->ar_mstr_balance + $det->arpay_det_amount;

                    // 5. Tentukan ulang status (Unpaid / Partial)
                    $status = 'unpaid';
                    if ($newPaid > 0 && $newBalance > 0) {
                        $status = 'partial';
                    }

                    $ar->update([
                        'ar_mstr_paid'    => $newPaid,
                        'ar_mstr_balance' => $newBalance,
                        'ar_mstr_status'  => $status
                    ]);
                }

                // 6. Hapus Detail Payment
                $det->delete();
            }

            // 7. Hapus Master Payment
            $payMstr->delete();

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Pembayaran berhasil dibatalkan']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal menghapus: ' . $e->getMessage()], 500);
        }
    }
}
