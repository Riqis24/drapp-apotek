<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Price;
use Illuminate\Http\Request;
use App\Models\ProductMeasurements;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StorePriceRequest;
use App\Http\Requests\UpdatePriceRequest;
use Illuminate\Validation\ValidationException;

class PriceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $prices = Price::with('productMeasurement.product', 'productMeasurement.measurement')->get();
        $products = ProductMeasurements::with(['product', 'measurement'])->get();
        return view('master.PriceMstrList', compact('prices', 'products'));
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
    public function store(request $request)
    {
        try {
            $request->validate([
                'product' => 'required|unique:prices,product_measurement_id',
                'price' => 'required|numeric',
            ]);

            Price::create([
                'price' => $request->price,
                'product_measurement_id' => $request->product,
            ]);

            return redirect()->back()->with('success', 'Harga berhasil ditambahkan!');
        } catch (ValidationException $e) {
            // Laravel akan otomatis redirect back, tapi kalau kamu mau manual:
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (Exception $e) {
            Log::error('Gagal menambahkan Harga', ['error' => $e->getMessage()]);

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan harga.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Price $price)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $price = Price::findOrFail($id);

        return response()->json([
            'id' => $price->id,
            'price' => $price->price,

        ]);
    }

    public function update(Request $request)
    {
        $price = Price::findOrFail($request->price_id);

        $price->update([
            'price' => $request->price,
        ]);

        return response()->json(['message' => 'Harga berhasil diperbarui']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Price $price)
    {
        //
    }
}
