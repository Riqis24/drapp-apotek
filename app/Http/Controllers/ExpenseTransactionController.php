<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\FinancialRecords;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ExpenseTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $records = FinancialRecords::query()->with('source.customer')->where('type', 'expense')->get();
        // $customers = Customer::query()->where('total_outstanding', '>', 0)->get();
        return view('transaction.ExpenseTransaction', compact('records'));
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
                'type' => 'required|string',
                'category' => 'required|string',
                'amount' => 'required|string',
                'note' => 'required|string',
            ]);

            // dd($request->all());

            FinancialRecords::create([
                'date' => $request->date,
                'type' => $request->type,
                'data_source' => $request->category,
                'amount' => $request->amount,
                'description' => $request->note,
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
        //
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
