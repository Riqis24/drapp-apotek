<?php

namespace App\Http\Controllers;

use App\Models\ArMstr;
use App\Models\SrMstr;
use App\Models\stocks;
use App\Models\SalesDet;
use App\Models\SalesMstr;
use Illuminate\Http\Request;
use App\Models\FinancialRecords;
use App\Models\StockTransactions;
use Illuminate\Support\Facades\DB;
use App\Services\SalesReturnService;
use App\Http\Requests\StoreSrMstrRequest;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Requests\UpdateSrMstrRequest;

class SrMstrController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $srs = SrMstr::with('sales')->get();
        return view('sales.SalesReturnList', compact('srs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        // Muat sales dengan detail dan relasi racikan/bundle
        $sm = SalesMstr::with([
            'details.product',
            'details.batch',
            'details.prescription.details.product'
        ])->findOrFail($id);

        return view('sales.SalesReturnForm', compact('id', 'sm'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, SalesReturnService $service)
    {
        // dd($request->all());
        $validated = $request->validate([
            'sales_id' => 'required',
            'items'    => 'required|array',
            // 'items.*.sales_det_id' => 'required',
            // 'items.*.qty' => 'required|numeric|min:0.01',
        ]);

        // dd($validated);
        $sales = SalesMstr::findOrFail($request->sales_id);

        DB::transaction(function () use ($request, $service, $sales) {
            // dd($request->all());
            $sr = SrMstr::create([
                'sr_mstr_nbr'    => $this->generateSrNumber(),
                'sr_mstr_smid'      => $request->sales_id,
                'sr_mstr_custid'      => $sales->sales_mstr_custid,
                'sr_mstr_date'    => $request->sr_mstr_date,
                'sr_mstr_reason'    => $request->sr_mstr_reason,
                'sr_mstr_createdby'    => auth()->user()->user_mstr_id,
            ]);

            $service->processReturn($sr, $request->items);
        });

        return redirect()
            ->route('SalesMstr.index')
            ->with('success', 'Return penjualan berhasil');
    }

    protected function generateSrNumber(): string
    {
        $last = SrMstr::orderBy('sr_mstr_id', 'desc')->first();

        $next = $last
            ? intval(substr($last->sr_mstr_nbr, -5)) + 1
            : 1;

        return 'SR-' . str_pad($next, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $sr = SrMstr::with([
            'sales.customer',         // Mengambil info invoice asal dan data pelanggan
            'details.product',        // Mengambil nama produk
            'details.measurement',    // Mengambil satuan (Unit of Measurement)
            'details.batch',          // Mengambil info Batch dan Expired
        ])->findOrFail($id);

        // Pastikan path view sesuai dengan lokasi file Sales Return kamu
        return view('sales.SalesReturnDet', compact('sr'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SrMstr $srMstr)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSrMstrRequest $request, SrMstr $srMstr)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            // Ambil data retur beserta detailnya
            $sr = SrMstr::with('details')->findOrFail($id);
            $sales = SalesMstr::find($sr->sr_mstr_smid);
            // 1. KEMBALIKAN STOK & HAPUS TRANSACTION LOG
            foreach ($sr->details as $det) {
                // Update Master Stok (Kurangi kembali karena retur batal masuk)
                stocks::where('product_id', $det->sr_det_productid)
                    ->where('loc_id', $sales->sales_mstr_locid)
                    ->where('batch_id', $det->sr_det_batchid)
                    ->decrement('quantity', $det->sr_det_qtyconv);
            }

            // Hapus log transaksi stok agar tidak ada record "yatim" (karena source-nya akan dihapus)
            StockTransactions::where('source_type', SrMstr::class)
                ->where('source_id', $sr->sr_mstr_id)
                ->delete();

            // 2. KEMBALIKAN KEUANGAN (Hanya panggil 1 kali di luar loop)
            $this->undoFinancial($sr);

            // 3. HAPUS DATA RETUR
            $sr->details()->delete();
            $sr->delete();

            DB::commit();

            return redirect()
                ->route('SrMstr.index')
                ->with('success', 'Data retur berhasil dihapus. Stok, Piutang, dan Transaksi telah dibersihkan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }

    protected function undoFinancial(SrMstr $return)
    {
        $sales = $return->sales;
        if (!$sales) return;

        $returnTotal = (float) $return->details()->sum('sr_det_subtotal');

        // A. KEMBALIKAN PIUTANG (AR) - Tambah lagi karena retur batal
        $ar = ArMstr::where('ar_mstr_salesid', $sales->sales_mstr_id)->first();
        if ($ar) {
            $ar->ar_mstr_amount  += $returnTotal;
            $ar->ar_mstr_balance += $returnTotal;

            // Update status kembali ke unpaid atau partial
            $ar->ar_mstr_status = ($sales->sales_mstr_paidamt <= 0) ? 'unpaid' : 'partial';
            $ar->save();
        }

        // B. BATALKAN VOID - Jika sales sempat void, aktifkan kembali
        if ($sales->sales_mstr_status === 'void') {
            $sales->update(['sales_mstr_status' => 'posted']);
        }

        // C. BATALKAN REFUND (Jika ada uang keluar saat store)
        // Kita cari record financial yang dibuat oleh retur ini
        $refunds = FinancialRecords::where('source_type', SrMstr::class)
            ->where('source_id', $return->sr_mstr_id)
            ->get();

        $totalRefund = 0;
        foreach ($refunds as $rec) {
            $totalRefund += $rec->amount;
            $rec->delete(); // Hapus record financial-nya
        }

        // D. KEMBALIKAN PAIDAMT SALES
        // Jika ada uang refund yang dihapus, maka paidamt di sales harus naik lagi
        if ($totalRefund > 0) {
            $sales->increment('sales_mstr_paidamt', $totalRefund);
        }
    }
}
