<?php

namespace App\Http\Controllers;

use App\Models\LocMstr;
use Exception;
use App\Models\stocks;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\StockTransactions;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

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

    public function StockCard(Request $request)
    {
        $products = Product::orderBy('name')->where('type', 'single')->get();
        return view('report.StockCard', compact('products'));
    }

    public function SummaryStockCard(Request $request)
    {
        // 1. Set default filter tanggal jika tidak diisi (default bulan berjalan)
        $fromDate = $request->from_date ?: now()->startOfMonth()->format('Y-m-d');
        $toDate = $request->to_date ?: now()->endOfMonth()->format('Y-m-d');
        $locId = $request->loc_id;
        $search = $request->search;

        if (auth()->user()->hasrole('Super Admin')) {
            $locations = LocMstr::orderBy('loc_mstr_name')->get();
        } else {
            $locations = LocMstr::where('loc_mstr_id', 1)->get();
        }
        // 2. Query dasar produk
        $query = Product::with('measurement')->where('type', 'single');

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('code', 'like', "%{$search}%");
        }

        $products = $query->orderBy('name')->get();

        // 3. Tambahkan data kalkulasi untuk setiap produk
        $reportData = $products->map(function ($product) use ($fromDate, $toDate, $locId) {

            // A. Hitung Saldo Awal (Mutasi sebelum $fromDate)
            $saldoAwalIn = StockTransactions::where('product_id', $product->id)
                ->where('created_at', '<', $fromDate)
                ->when($locId, fn($q) => $q->where('loc_id', $locId))
                ->where('type', 'in')
                ->sum('quantity');

            $saldoAwalOut = StockTransactions::where('product_id', $product->id)
                ->where('created_at', '<', $fromDate)
                ->when($locId, fn($q) => $q->where('loc_id', $locId))
                ->where('type', 'out')
                ->sum('quantity');

            $saldoAwal = $saldoAwalIn + $saldoAwalOut;

            // B. Hitung Mutasi MASUK dalam periode
            $masuk = StockTransactions::where('product_id', $product->id)
                ->whereBetween('created_at', [$fromDate, $toDate])
                ->when($locId, fn($q) => $q->where('loc_id', $locId))
                ->where('type', 'in')
                ->sum('quantity');

            // C. Hitung Mutasi KELUAR dalam periode
            $keluar = StockTransactions::where('product_id', $product->id)
                ->whereBetween('created_at', [$fromDate, $toDate])
                ->when($locId, fn($q) => $q->where('loc_id', $locId))
                ->where('type', 'out')
                ->sum('quantity');

            // D. Saldo Akhir
            $saldoAkhir = $saldoAwal + $masuk - $keluar;

            return (object) [
                'id'            => $product->id,
                'product_name'  => $product->name,
                'description'   => $product->description,
                'unit'          => $product->measurement->name,
                'saldo_awal'    => $saldoAwal,
                'masuk'         => $masuk,
                'keluar'        => $keluar,
                'saldo_akhir'   => $saldoAkhir
            ];
        });

        // dd($reportData);


        return view('report.SummaryStockCard', [
            'products' => $reportData,
            'fromDate' => $fromDate,
            'toDate'   => $toDate,
            'locId'    => $locId,
            'search'   => $search,
            'locations' => $locations
        ]);
    }

    public function DetStockCard($id)
    {
        $locId = auth()->user()->hasRole('Super Admin') ? null : 1;
        $product = Product::findOrFail($id);
        if (!empty($locId)) {
            $stock = stocks::with('batch')->where('product_id', $id)->where('loc_id', $locId);
        } else {
            $stock = stocks::with('batch')->where('product_id', $id);
        }
        $stockAct = (clone $stock)->whereHas('batch', function ($q) {
            $q->where('batch_mstr_expireddate', '>=', date('Y-m-d'))
                ->orWhereNull('batch_mstr_expireddate');
        })->get();
        $stockNonAct = (clone $stock)->whereHas('batch', function ($q) {
            $q->where('batch_mstr_expireddate', '<', date('Y-m-d'));
        })->get();
        // dd($stockAct);

        $transactions = StockTransactions::with(['product', 'user', 'location', 'batch', 'source'])
            ->where('product_id', $id)
            ->orderby('id', 'desc')
            ->paginate(50);


        $detailsMap = [];

        foreach ($transactions as $st) {
            // Inisialisasi default agar tidak null jika tidak ditemukan detailnya
            $detailsMap[$st->id] = [
                'price' => 0,
                'total' => 0
            ];

            if ($st->source && method_exists($st->source, 'details')) {
                foreach ($st->source->details as $det) {
                    // Mapping ID produk dari berbagai kemungkinan nama kolom detail
                    $detPid = $det->sales_det_productid ??
                        $det->bpb_det_productid ??
                        $det->sr_det_productid ??
                        $det->pr_det_productid ??
                        $det->sa_det_productid ??
                        $det->ts_det_productid ?? 0;

                    // Cek kecocokan produk
                    if ($detPid == $st->product_id) {
                        $price = 0;
                        $total = 0;

                        // Logika Penentuan Harga & Total berdasarkan Tipe Model
                        if ($st->source_type == \App\Models\SalesMstr::class) {
                            $qtyConv = max($det->sales_det_umconv ?? 1, 1);
                            $price = ($det->sales_det_price ?? 0) / $qtyConv;
                            $total = $det->sales_det_subtotal ?? 0;
                        } elseif ($st->source_type == \App\Models\BpbMstr::class) {
                            $price = $det->bpb_det_priceconv ?? 0;
                            $total = $det->bpb_det_total ?? 0;
                        } elseif ($st->source_type == \App\Models\PrMstr::class) {
                            $qtyConv = max($det->pr_det_umconv ?? 1, 1);
                            $price = ($det->pr_det_price ?? 0) / $qtyConv;
                            $total = $det->pr_det_subtotal ?? 0;
                        } elseif ($st->source_type == \App\Models\SrMstr::class) {
                            $qtyConv = max($det->sr_det_umconv ?? 1, 1);
                            $price = ($det->sr_det_price ?? 0) / $qtyConv;
                            $total = $det->sr_det_subtotal ?? 0;
                        } elseif ($st->source_type == \App\Models\SaMstr::class) {
                            $price = $det->sa_det_price ?? 0;
                            $total = $det->sa_det_subtotal ?? 0;
                        }

                        // Simpan menggunakan ID Transaksi Stok sebagai Key Unik
                        $detailsMap[$st->id] = [
                            'price' => $price,
                            'total' => $total
                        ];

                        // Sangat Penting: Stop loop detail jika baris yang cocok sudah ketemu
                        break;
                    }
                }
            }
        }
        return view('report.DetStockCard', compact('transactions', 'detailsMap', 'product', 'stockAct', 'stockNonAct'));
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
