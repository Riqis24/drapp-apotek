<?php

namespace App\Http\Controllers;

use App\Models\SalesMstr;
use App\Models\StoreProfile;
use Illuminate\Http\Request;
use App\Models\CashierSession;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\FinancialRecords;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreCashierSessionRequest;
use App\Http\Requests\UpdateCashierSessionRequest;

class CashierSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function open(Request $request)
    {
        // 1. Validasi Input Dasar
        $request->validate([
            'open_loc_id' => 'required|exists:loc_mstr,loc_mstr_id',
            'opening_amount' => 'required|numeric|min:0',
        ], [
            'open_loc_id.required' => 'Lokasi outlet wajib dipilih.',
            'opening_amount.min' => 'Saldo awal tidak boleh kurang dari 0.',
        ]);

        $userId = Auth::user()->user_mstr_id;

        // 2. Cek apakah user ini masih punya session 'open'
        $existingSession = CashierSession::where('user_id', $userId)
            ->where('status', 'open')
            ->first();

        if ($existingSession) {
            return redirect()->back()->with('error', 'Anda masih memiliki sesi kasir yang aktif! Mohon tutup sesi sebelumnya terlebih dahulu.');
        }

        // 3. Proses Create Session
        try {
            CashierSession::create([
                'user_id' => $userId,
                'loc_id' => $request->open_loc_id,
                'opening_amount' => $request->opening_amount,
                'status' => 'open',
                'opened_at' => now(),
            ]);

            return redirect()->back()->with('success', 'Kasir Berhasil Dibuka! Semangat kerjanya ya! 😊');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat membuka kasir.');
        }
    }

    // tutup kasir
    // public function closeCashier(Request $request)
    // {
    //     $activeSession = CashierSession::where('user_id', auth()->user()->user_mstr_id)
    //         ->where('loc_id', $request->close_loc_id)
    //         ->where('status', 'open')
    //         ->latest('opened_at')
    //         ->first();

    //     if (!$activeSession) {
    //         // return response()->json(['error' => 'Tidak ada session kasir aktif'], 400);
    //         return redirect()
    //             ->back()
    //             ->with('swal', [
    //                 'icon' => 'warning',
    //                 'title' => 'Warning',
    //                 'text' => 'Tidak ada session kasir aktif'
    //             ]);
    //     }

    //     if ($activeSession->status === 'closed') {
    //         return redirect()
    //             ->back()
    //             ->with('swal', [
    //                 'icon' => 'warning',
    //                 'title' => 'Warning',
    //                 'text' => 'Session sudah ditutup'
    //             ]);
    //     }

    //     // hitung total transaksi yang dibayar
    //     $transactions_total = SalesMstr::where('cashier_session_id', $activeSession->id)
    //         ->where('sales_mstr_status', 'posted')
    //         ->sum('sales_mstr_subtotal');

    //     $discrepancy = ($request->closing_amount ?? 0) - ($activeSession->opening_amount + $transactions_total);

    //     $activeSession->update([
    //         'closing_amount' => $request->closing_amount ?? 0,
    //         'transactions_total' => $transactions_total,
    //         'discrepancy' => $discrepancy,
    //         'status' => 'closed',
    //         'closed_at' => now(),
    //     ]);

    //     // otomatis buat financial record untuk selisih
    //     if ($discrepancy != 0) {
    //         FinancialRecords::create([
    //             'amount' => abs($discrepancy),
    //             'type' => $discrepancy > 0 ? 'income' : 'expense',
    //             'data_source' => 'Kas Selisih',
    //             'source_type' => CashierSession::class,
    //             'source_id' => $activeSession->id,
    //             'date' => now()
    //         ]);
    //     }



    //     return redirect()->back()->with('success', 'Thank You, Today is Done 😁');
    // }

    public function close(Request $request)
    {
        DB::beginTransaction();
        try {
            $apotek = StoreProfile::first();
            // Coba pastikan userId ini memang yang tercatat di sales_mstr_createdby
            $userId = auth()->user()->user_mstr_id;
            $today = now()->toDateString();

            // 1. Ambil Master Sales
            $sales = SalesMstr::where('sales_mstr_createdby', $userId)
                ->whereDate('sales_mstr_date', $today)
                ->get();

            // DEBUG: Jika count tetap 0, coba hapus baris ->where('sales_mstr_createdby', $userId) 
            // untuk memastikan apakah datanya memang ada atau hanya masalah filter user.

            // 2. Query Rincian Item (Gunakan Pluck ID dari $sales agar lebih akurat)
            $salesIds = $sales->pluck('sales_mstr_id');

            $rincianItem = DB::table('sales_det')
                ->join('products', 'sales_det.sales_det_productid', '=', 'products.id')
                ->join('measurements', 'sales_det.sales_det_um', '=', 'measurements.id')
                ->whereIn('sales_det.sales_det_mstrid', $salesIds)
                ->select(
                    'products.name as product_name',
                    'measurements.name as satuan', // Satuan saat transaksi (Strip/Tablet/Botol)
                    'sales_det.sales_det_qtyconv as konversi', // Nilai konversi jika perlu audit
                    DB::raw('SUM(sales_det.sales_det_qty) as total_qty'),
                    DB::raw('SUM(sales_det.sales_det_subtotal) as total_price')
                )
                ->groupBy('products.name', 'measurements.name', 'sales_det.sales_det_qtyconv')
                ->get();

            // dd($rincianItem);

            // 3. Kalkulasi (Gunakan Variabel yang sudah dihitung)
            $bruto       = $sales->sum('sales_mstr_subtotal');
            $diskon      = $sales->sum('sales_mstr_discamt');
            $ppn         = $sales->sum('sales_mstr_ppnamt');
            $totalOmzet  = $sales->sum('sales_mstr_grandtotal');
            $totalPaid   = $sales->sum('sales_mstr_paidamt');
            $totalChange = $sales->sum('sales_mstr_changeamt');
            $netTunai    = $totalPaid - $totalChange;

            // Piutang: Semua nota yang BELUM bayar lunas
            $totalPiutang = $sales->sum(function ($item) {
                return $item->sales_mstr_grandtotal - $item->sales_mstr_paidamt;
            });

            $data = [
                'kasir'     => auth()->user()->user_mstr_name, // Gunakan nama user
                'tanggal'   => now()->format('d/m/Y H:i'),
                'rincian'   => $rincianItem,
                'omzet'     => $totalOmzet,
                'bruto'     => $bruto,
                'diskon'    => $diskon,
                'ppn'       => $ppn,
                'tunai'     => $netTunai,
                'piutang'   => $totalPiutang,
                'kembalian' => $totalChange,
                'count'     => $sales->count(),
                'apotek'    => $apotek,
            ];

            $pdf = Pdf::loadView('sales.tutupkasir', $data);
            $pdf->setPaper([0, 0, 140, 1500], 'portrait');

            $fileName = 'rekap/REKAP-' . $userId . '-' . date('YmdHis') . '.pdf';
            Storage::disk('public')->put($fileName, $pdf->output());

            DB::commit();

            return response()->json([
                'success' => true,
                'pdf_url' => asset('storage/' . $fileName)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

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
    public function store(StoreCashierSessionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(CashierSession $cashierSession)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CashierSession $cashierSession)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCashierSessionRequest $request, CashierSession $cashierSession)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CashierSession $cashierSession)
    {
        //
    }
}
