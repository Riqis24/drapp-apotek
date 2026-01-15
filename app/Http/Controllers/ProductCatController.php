<?php

namespace App\Http\Controllers;

use App\Models\ProductCat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreProductCatRequest;
use App\Http\Requests\UpdateProductCatRequest;

class ProductCatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = ProductCat::all();
        return view('master.ProductCatList', compact('categories'));
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
        $validator = Validator::make($request->all(), [
            'product_cat_name' => 'required|string|max:255|unique:product_cat,product_cat_name',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        $cat = ProductCat::create($request->all());
        return back()->with('success', 'Category added');
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductCat $productCat)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $category = ProductCat::findOrFail($id);
        return response()->json($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $cat = ProductCat::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'product_cat_name' => 'required|string|max:255|unique:product_cat,product_cat_name,' . $id . ',product_cat_id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'error.');
        }

        $cat->update($request->all());
        return redirect()->back()->with('success', 'Kategori berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $cat = ProductCat::findOrFail($id);
        // CEK: Jika kategori masih digunakan oleh produk, jangan hapus
        if ($cat->products()->count() > 0) {
            return response()->json([
                'message' => 'Gagal! Kategori ini masih digunakan oleh beberapa produk.'
            ], 400);
        }
        // dd($cat);

        $cat->delete();
        return response()->json(['message' => 'Kategori berhasil dihapus']);
    }
}
