<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\StockTransactions;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class StockTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $trStocks = StockTransactions::query()->with(['product', 'location', 'batch'])->orderBy('id', 'desc')->get();
        $products = Product::query()->orderBy('id', 'desc')->get();
        return view('report.StockTransaction', compact('trStocks', 'products'));
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
                'effdate' => 'required|date',
                'product' => 'required|numeric',
                'type' => 'required|string',
                'note' => 'required|string',
                'qty' => 'required|numeric'
            ]);

            StockTransactions::create([
                'date' => $request->effdate,
                'product_id' => $request->product,
                'type' => $request->type,
                'note' => $request->note,
                'quantity' => $request->qty
            ]);

            recalculateStock($request->product);

            return redirect()->back()->with('success', 'Transaction berhasil ditambahkan!');
        } catch (ValidationException $e) {
            // Laravel akan otomatis redirect back, tapi kalau kamu mau manual:
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (Exception $e) {
            Log::error('Gagal menambahkan Transaction', ['error' => $e->getMessage()]);

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan Transaction.');
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
