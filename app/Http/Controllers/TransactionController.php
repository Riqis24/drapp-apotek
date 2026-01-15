<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Measurement;
use Illuminate\Http\Request;
use App\Models\CustTransactions;
use App\Models\FinancialRecords;
use App\Models\StockTransactions;
use App\Models\ReceivablePayments;
use Illuminate\Support\Facades\DB;
use App\Models\ProductMeasurements;
use App\Models\ProductTransactions;
use App\Models\stocks;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = Customer::query()->get();
        $satuans = ProductMeasurements::query()->with('measurement')->get();
        // $products = Product::query()->with('stock')->get();
        $products = stocks::query()->with('product')->where('quantity', '>', 0)->get();
        // dd($products);
        return view('transaction.transaction', compact('customers', 'satuans', 'products'));
    }

    function toDecimal($number)
    {
        return floatval(str_replace(',', '.', str_replace('.', '', $number)));
    }

    public function getProduct()
    {
        $stocks = stocks::with('product')
            ->where('quantity', '>', 0)
            ->get();

        // Ambil hanya produk unik dari hasil stok yang tersedia
        $products = $stocks->pluck('product')->unique('id')->values()->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->code . ' - ' . $product->name,
            ];
        });

        return response()->json($products);
    }

    public function getSatuans(Product $product)
    {
        $satuans = $product->measurements()->get(['measurements.id', 'measurements.name']);
        return response()->json($satuans);
    }

    public function getHarga($productId, $satuanId)
    {
        $price = ProductMeasurements::query()
            ->where('product_id', $productId)
            ->where('measurement_id', $satuanId)
            ->join('prices', 'product_measurements.id', '=', 'prices.product_measurement_id')
            ->value('prices.price');

        return response()->json(['harga' => $price ?? 0]);
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

    // private function toDecimal($number, $decimals = 2)
    // {
    //     return number_format((float) $number, $decimals, '.', '');
    // }

    private function cleanCurrency($value)
    {
        if (!$value)
            return 0;
        $value = str_replace('.', '', $value); // Hapus titik
        $value = preg_replace('/[^\d,]/', '', $value); // Sisakan angka & koma
        return str_replace(',', '.', $value); // Ubah koma jadi titik
    }

    public function recalculateOutstanding($customerId)
    {
        $totalCredit = CustTransactions::where('customer_id', $customerId)
            ->where('status', 'credit')
            ->sum('total');

        $totalPaid = ReceivablePayments::where('customer_id', $customerId)
            ->sum('amount_paid');

        $customer = Customer::find($customerId);
        $customer->total_outstanding = $totalCredit - $totalPaid;
        $customer->save();
    }

    function convertToDefault($productId, $inputQty, $inputMeasurementId)
    {
        $pm = ProductMeasurements::where('product_id', $productId)
            ->where('measurement_id', $inputMeasurementId)
            ->first();

        if (!$pm || $pm->conversion == 0) {
            throw new Exception("Konversi tidak valid.");
        }

        return $inputQty / $pm->conversion;
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->merge([
            'total' => $this->cleanCurrency($request->total),
            'bayar' => $this->cleanCurrency($request->bayar),
            'kembalian' => $this->cleanCurrency($request->kembalian),

            // Bersihkan array harga, diskon, dan subtotal
            'harga' => collect($request->harga)->map(fn($v) => $this->cleanCurrency($v))->toArray(),
            'diskon' => collect($request->diskon)->map(fn($v) => $this->cleanCurrency($v))->toArray(),
            'subtotal' => collect($request->subtotal)->map(fn($v) => $this->cleanCurrency($v))->toArray(),
        ]);

        // 1. Validasi input
        $validated = $request->validate([
            'effdate' => 'required|date',
            'customer' => 'nullable|exists:customers,id',
            'method_payment' => 'required|in:cash,credit',
            'item' => 'required|array|min:1',
            'item.*' => 'required|exists:products,id',
            'measurement.*' => 'required|exists:measurements,id',
            'qty.*' => 'required|numeric|min:0.01',
            'harga.*' => 'required|numeric|min:0',
            'diskon.*' => 'nullable|numeric|min:0',
            'subtotal.*' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'bayar' => 'required|numeric|min:0',
            'kembalian' => 'required|numeric|min:0',
        ]);

        // dd($validated);

        DB::beginTransaction();

        $effDate = Carbon::parse($request->effdate);
        $invoiceNumber = CustTransactions::generateInvoiceNumber($effDate);
        $hutang = $request->total - $request->bayar;
        if ($hutang > 0) {
            $debt = $hutang;
        } else if ($hutang < 0) {
            $debt = 0;
        } else {
            $debt = 0;
        }

        if ($request->method_payment == 'cash') {
            $mp = 1;
        } else if ($request->method_payment == 'credit') {
            $mp = 0;
        } else {
            $mp = NULL;
        }

        if ($request->method_payment == 'cash') {
            if ($debt > 0) {
                // dd($debt);
                return back()->with('error', 'Wrong Method Payment');
            }
        }

        try {
            // 2. Simpan transaksi utama
            $custTransaction = CustTransactions::create([
                'invoice_number' => $invoiceNumber,
                'customer_id' => $request->customer,
                'date' => $request->effdate,
                'method_payment' => $request->method_payment,
                'total' => ($request->total),
                'paid' => ($request->bayar),
                'change' => ($request->kembalian),
                'debt' => $debt,
                'status' => $mp,
            ]);

            // dd($custTransaction);

            // 3. Simpan detail item
            foreach ($request->item as $index => $productId) {
                ProductTransactions::create([
                    'transaction_id' => $custTransaction->id,
                    'product_id' => $productId,
                    'measurement_id' => $request->measurement[$index],
                    'quantity' => ($request->qty[$index]),
                    'unit_price' => ($request->harga[$index]),
                    // 'discount' => ($request->diskon[$index]),
                    'subtotal' => ($request->subtotal[$index]),
                ]);

                $cekIsStockable = Product::find($productId)->is_stockable;
                $cekSatuan = Product::find($productId)->measurement_id;
                if ($cekIsStockable == 1) {
                    if ($request->measurement[$index] <> $cekSatuan) {
                        $qtyStore = $this->convertToDefault($productId, $request->qty[$index], $request->measurement[$index]);
                    } else {
                        $qtyStore = $request->qty[$index];
                    }
                    // dd($qtyStore);
                    StockTransactions::create([
                        'product_id' => $productId,
                        'type' => 'out',
                        'quantity' => $qtyStore * -1,
                        'note' => 'Penjualan',
                        'date' => $request->effdate,
                        'source_type' => CustTransactions::class,
                        'source_id' => $custTransaction->id,
                    ]);

                    recalculateStock($productId);
                }
            }

            // 4. Simpan catatan keuangan
            FinancialRecords::create([
                'date' => $request->effdate,
                'type' => 'income',
                'data_source' => 'Penjualan',
                'amount' => ($request->bayar),
                'source_id' => $custTransaction->id,
                'source_type' => CustTransactions::class,
            ]);

            // 5. Tambah piutang jika metode kredit
            if ($request->method_payment === 'credit') {
                ReceivablePayments::create([
                    'transaction_id' => $custTransaction->id,
                    'customer_id' => $custTransaction->customer_id,
                    'amount_paid' => ($request->bayar),
                    'date' => $request->effdate,
                ]);

                $this->recalculateOutstanding($custTransaction->customer_id);
            }

            DB::commit();

            return redirect()->route('Transaction.index')->with('success', 'Transaksi berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan transaksi: ' . $e->getMessage());
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
