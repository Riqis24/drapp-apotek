<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ProductMeasurements;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Events\Validated;
use Illuminate\Validation\ValidationException;

class ProductMeasurementController extends Controller
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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());

        try {
            DB::beginTransaction();
            ProductMeasurements::create([
                'product_id'     => $request->product_id,
                'measurement_id' => $request->measurement_id,
                'conversion'     => $request->conversion, // Base unit selalu 1
                'last_buy_price' => 0,
                'placement_id'   => $request->placement_id,
            ]);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Master Produk Baru Berhasil Ditambahkan'
            ]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan produk: ' . $e->getMessage()
            ], 500); // Mengirim status 500 (Internal Server Error)
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
    public function updateProduct(Request $request, $id)
    {
        $request->validate([
            'name'           => 'required|max:255',
            'category'       => 'required',
            'type'           => 'required|in:single,bundle',
            'measurement_id' => 'required',
            'margin'         => 'nullable|numeric',
        ]);

        try {
            DB::beginTransaction();

            $product = Product::findOrFail($id);

            // Mapping data sesuai model
            $product->update([
                'name'           => $request->name,
                'description'    => $request->description,
                'category'       => $request->category,
                'type'           => $request->type,
                'measurement_id' => $request->measurement_id,
                'margin'         => $request->margin ?? 0,
                'is_stockable'   => $request->has('is_stockable') ? 1 : 0,
                'is_visible'     => $request->has('is_visible') ? 1 : 0,
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Master produk berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal memperbarui: ' . $e->getMessage());
        }
    }

    public function updateMeasurement(Request $request, $id)
    {
        $request->validate([
            'measurement_id' => 'required',
            'conversion'     => 'required|numeric|min:1',
            'placement_id'   => 'nullable',
        ]);

        try {
            $pm = ProductMeasurements::findOrFail($id);

            $pm->update([
                'measurement_id' => $request->measurement_id,
                'conversion'     => $request->conversion,
                'placement_id'   => $request->placement_id,

            ]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $pm = ProductMeasurements::findOrFail($id);
            $pm->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
