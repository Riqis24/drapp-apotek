<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductBundle;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreProductBundleRequest;
use App\Http\Requests\UpdateProductBundleRequest;
use App\Models\Measurement;
use App\Models\ProductCat;
use App\Models\ProductMeasurements;

class ProductBundleController extends Controller
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
    // Menampilkan form Tambah
    public function create()
    {
        $cats = ProductCat::all();
        $satuan = Measurement::where('name', 'bundle')->first();
        return view('master.ProductBundleList', compact('cats', 'satuan'));
    }

    // Menampilkan form Edit
    public function edit($id)
    {
        $bundle = Product::with(['bundleItems.productMeasurement.product', 'bundleItems.productMeasurement.measurement'])->findOrFail($id);
        // dd($bundle);
        $cats = ProductCat::all();
        $satuan = Measurement::where('name', 'bundle')->first();
        return view('master.ProductBundleList', compact('bundle', 'cats', 'satuan'));
    }

    public function products()
    {
        return Product::where('type', 'single')
            ->orderBy('name')
            ->get([
                'id',
                'name as text'
            ]);
    }

    public function measurements($productId)
    {
        return ProductMeasurements::with(['measurement', 'price'])
            ->where('product_id', $productId)
            ->get()
            ->map(function ($pm) {
                return [
                    'id'    => $pm->id,
                    'text'  => $pm->measurement->name,
                    'price' => $pm->price?->price ?? 0
                ];
            });
    }

    public function store(Request $request)
    {
        DB::transaction(function () use ($request) {
            $bundle = Product::create([
                'code' => $request->code,
                'name' => $request->product,
                'type' => 'bundle',
                'category' => $request->cat,
                'measurement_id' => $request->satuan,
                'description' => $request->description,
                'is_stockable' => isset($request->is_stockable) ? 1 : 0,
            ]);

            ProductMeasurements::create([
                'product_id' => $bundle->id,
                'measurement_id' => $request->satuan,
            ]);

            foreach ($request->items as $item) {
                ProductBundle::create([
                    'bundle_product_id' => $bundle->id,
                    'product_measurement_id' => $item['product_measurement_id'],
                    'quantity' => $item['qty']
                ]);
            }
        });

        return redirect()->route('ProductMstr.index')->with('success', 'Bundle created');
    }


    /**
     * Display the specified resource.
     */
    public function show(ProductBundle $productBundle)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $bundle = Product::findOrFail($id);
        DB::transaction(function () use ($request, $bundle) {
            // 1. Update Header
            $bundle->update([
                'code' => $request->code,
                'name' => $request->product,
                'description' => $request->description,
                'category' => $request->cat,
            ]);

            // 2. Hapus detail lama
            $bundle->bundleItems()->delete();


            // 3. Simpan detail baru
            if ($request->has('items')) {
                // dd($request->items);
                foreach ($request->items as $item) {
                    $bundle->bundleItems()->create([
                        'bundle_product_id' => $item['product_id'],
                        'product_measurement_id' => $item['product_measurement_id'],
                        'quantity' => $item['qty']
                    ]);
                }
            }
        });

        return redirect()->route('ProductMstr.index')->with('success', 'Bundle updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductBundle $productBundle)
    {
        //
    }
}
