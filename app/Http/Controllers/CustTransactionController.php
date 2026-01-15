<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\CustTransactions;
use App\Models\FinancialRecords;
use App\Models\ReceivablePayments;
use Illuminate\Support\Facades\DB;
use App\Models\ProductTransactions;

class CustTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = Customer::all();
        $transactions = CustTransactions::query()->with('customer')->orderBy('id', 'desc')->get();
        return view('report.CustTransaction', compact('customers', 'transactions'));
    }

    public function detailTransaction($id)
    {
        $details = ProductTransactions::query()->with(['custTransaction', 'product', 'measurement'])->where('transaction_id', $id)->get();
        $transaction = CustTransactions::query()->with('customer')->where('id', $id)->first();
        // dd($transaction);
        return view('report.ProductTransaction', compact('details', 'transaction', 'id'));
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
    public function show($id)
    {
        $transaction = CustTransactions::with('customer')->where('id', $id)->first();
        return view('transaction.DebtPayment', compact('transaction'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());
        $payment = str_replace(".", "", $request->payment);
        $rest = $request->rest ? str_replace(".", "", $request->rest) : 0;
        // dd($payment, $rest);
        try {
            DB::beginTransaction();

            // dd($payment);
            // 1. Simpan catatan keuangan
            FinancialRecords::create([
                'date' => $request->effdate,
                'type' => 'income',
                'data_source' => 'Pembayaran Hutang',
                'amount' => $payment,
                'source_id' => $id,
                'source_type' => CustTransactions::class,
            ]);

            $custTransaction = CustTransactions::where('id', $id)->first();
            // dd($custTransaction);
            // 2. update sisa piutang
            ReceivablePayments::create([
                'transaction_id' => $id,
                'customer_id' => $custTransaction->customer_id,
                'amount_paid' => $payment,
                'date' => $request->effdate,
            ]);

            $totalpayment = $custTransaction->paid + $payment;
            // dd($payment);

            // 3. update customer transaction (mengurangi sisa hutang)
            CustTransactions::where('id', $id)->update([
                'paid' => $totalpayment,
                'debt' => $rest,
            ]);

            // 4. update piutang customer
            $totalCredit = CustTransactions::where('customer_id', $custTransaction->customer_id)
                ->where('status', 'credit')
                ->where('debt', '>', '0')
                // ->pluck('debt');
                ->sum('debt');

            // $totalPaid = ReceivablePayments::where('customer_id', $custTransaction->customer_id)
            //     ->where('transaction_id', $custTransaction->id)
            //     ->sum('amount_paid');
            // dd($totalPaid, $totalCredit);

            $customer = Customer::find($custTransaction->customer_id);
            $customer->total_outstanding = $totalCredit;
            // dd($customer->total_outstanding);
            $customer->save();

            if ($rest == 0) {
                CustTransactions::where('id', $id)->update([
                    'status' => 1,
                ]);
            }

            DB::commit();


            return redirect()->route('CustTransaction.show', $id)->with('success', 'Transaksi berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
