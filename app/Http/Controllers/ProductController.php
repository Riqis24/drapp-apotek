<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Product;
use App\Models\ProductCat;
use App\Models\Measurement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ProductMeasurements;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['measurement', 'cat'])->orderBy('id', 'desc')->get();
        // dd($products);
        $satuans = Measurement::orderBy('id', 'desc')->get();
        $cats = ProductCat::orderBy('product_cat_id', 'desc')->get();
        return view('master.ProductMstrList', compact('products', 'satuans', 'cats'));
    }

    public function updateMeasurement(request $request)
    {
        try {
            $request->validate([
                'product' => 'required',
                'satuan' => 'required',
                'conversi' => 'required',
            ]);

            // dd($request->all());

            ProductMeasurements::create([
                'product_id' => $request->product,
                'measurement_id' => $request->satuan,
                'conversion' => $request->conversi,
            ]);

            return redirect()->back()->with('success', 'Product berhasil ditambahkan!');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (Exception $e) {
            Log::error('Gagal menambahkan Satuan', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan satuan.');
        }
    }



    public function EditPrdMeasurement($idProduct)
    {
        $products = ProductMeasurements::query()->with(['product', 'measurement'])->where('product_id', $idProduct)->get();
        $default = Product::query()->with('measurement')->where('id', $idProduct)->first();
        $satuans = Measurement::query()->get();
        return view('master.PrdMeasurement', compact('products', 'default', 'satuans'));
    }
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
            Product::create([
                'code' => $request->code,
                'name' => $request->name,
                'type' => $request->type,
                'category' => $request->cat,
                'margin' => $request->margin,
                'measurement_id' => $request->satuan,
                'description' => $request->description,
                'is_stockable' => isset($request->is_stockable) ? 1 : 0,
                'is_visible' => isset($request->is_visible) ? 1 : 0,
            ]);

            ProductMeasurements::create([
                'product_id' => Product::latest('id')->first()->id,
                'measurement_id' => $request->satuan,
            ]);


            return redirect()->back()->with('success', 'Product berhasil ditambahkan!');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (Exception $e) {
            Log::error('Gagal menambahkan Product', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan Product.');
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
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // 1. Cek apakah ada transaksi di tabel terkait
        // Sesuaikan nama tabel 'Stock' atau 'SalesDetail' dengan milikmu
        $hasTransaction = DB::table('stocks')->where('product_id', $id)->exists() ||
            DB::table('sales_det')->where('sales_det_productid', $id)->exists();

        if ($hasTransaction) {
            return response()->json([
                'status' => 'error',
                'message' => 'Produk tidak bisa dihapus karena sudah memiliki riwayat transaksi (stok/penjualan). Silakan non-aktifkan (is_visible) saja.'
            ], 422);
        }

        // 2. Jika produk adalah Bundle, hapus dulu isinya (child-nya)
        if ($product->type === 'bundle') {
            $product->bundleItems()->delete();
        }

        // 3. Hapus Produk
        $product->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Produk berhasil dihapus.'
        ]);
    }
}
