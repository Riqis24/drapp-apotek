<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\CustTransactions;
use App\Models\FinancialRecords;
use App\Models\ReceivablePayments;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ReceivablePaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = Customer::query()->where('total_outstanding', '>', 0)->get();
        $transactions = ReceivablePayments::query()->with(['customer', 'custtr'])->orderBy('id', 'desc')->get();
        return view('transaction.ReceivablePayment', compact('customers', 'transactions'));
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
        try {
            $request->validate([
                'date' => 'required|date',
                'customer' => 'required|numeric',
                'amount' => 'required|string',
                'note' => 'required|string',
            ]);

            // dd($request->all());
            $cust = Customer::findOrFail($request->customer);
            // $custTr = CustTransactions::where('customer_id', $request->customer)->first();

            $rcvPayment = ReceivablePayments::create([
                'customer_id' => $request->customer,
                'date' => $request->date,
                'transaction_id' => $request->transaction_id ?? NULL,
                'amount_paid' => $request->amount,
            ]);

            // dd($cust);
            FinancialRecords::create([
                'date' => $request->date,
                'type' => 'Income',
                'data_source' => 'Receivable Payment',
                'amount' => $request->amount,
                'description' => $request->note,
                'source_id' => $rcvPayment->id,
                'source_type' => ReceivablePayments::class,
            ]);

            Customer::where('id', $request->customer)->update([
                'total_outstanding' => $cust->total_outstanding - $request->amount,
            ]);

            return redirect()->back()->with('success', 'Transaksi berhasil ditambahkan!');
        } catch (ValidationException $e) {
            // Laravel akan otomatis redirect back, tapi kalau kamu mau manual:
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (Exception $e) {
            Log::error('Gagal menambahkan Transaksi', ['error' => $e->getMessage()]);

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan Transaksi.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = ReceivablePayments::where('id', $id)->first();
        dd($data);
        return view('transaction.DebtPayment');
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
