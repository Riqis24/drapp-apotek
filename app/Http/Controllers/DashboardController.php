<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\ArMstr;
use App\Models\BpbMstr;
use App\Models\SalesMstr;
use Illuminate\Http\Request;
use App\Models\FinancialRecords;
use App\Models\stocks;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // DashboardController.php
    public function index()
    {
        $thisMonth = now()->month;
        $thisYear = now()->year;

        // Ambil data bulan ini untuk statistik card
        $records = FinancialRecords::whereMonth('date', $thisMonth)
            ->whereYear('date', $thisYear)
            ->get();

        $totalIncome = $records->where('type', 'income')->sum('amount');
        $totalExpense = $records->where('type', 'expense')->sum('amount');
        $totalPPN = $records->where('type', 'liability')->sum('amount');
        $saldo = $totalIncome - $totalExpense - $totalPPN;

        // --- TAMBAHKAN LINE INI ---
        // Mengambil 5 transaksi terbaru untuk ditampilkan di list "Last Transactions"
        $recentRecords = FinancialRecords::orderBy('date', 'desc')
            ->where('amount', '>', 0)
            ->orderBy('id', 'desc')
            ->take(5)
            ->get()
            ->map(function ($record) {
                // Ambil nomor transaksi asli dari table asalnya
                $transactionNumber = '-';

                if ($record->source_type && $record->source_id) {
                    // Mengambil model berdasarkan string di source_type
                    $sourceModel = $record->source_type::find($record->source_id);

                    if ($sourceModel) {
                        // Sesuaikan nama kolom nomor transaksi di masing-masing tabelmu
                        // Contoh: SalesMstr menggunakan 'sales_num', Purchase menggunakan 'po_num'
                        $transactionNumber = $sourceModel->sales_mstr_nbr
                            ?? $sourceModel->sr_mstr_nbr
                            ?? $sourceModel->pr_mstr_nbr
                            ?? 'N/A';
                    }
                }

                // Tambahkan properti baru ke object record
                $record->ref_number = $transactionNumber;
                return $record;
            });
        // ---------------------------

        // Data untuk Chart (7 Hari Terakhir)
        $days = collect(range(6, 0))->map(fn($i) => now()->subDays($i)->format('Y-m-d'));
        $labels = [];
        $incomeData = [];
        $expenseData = [];

        foreach ($days as $date) {
            $dayRecords = FinancialRecords::whereDate('date', $date)->where('amount', '>', 0)->get();
            $labels[] = Carbon::parse($date)->format('D');
            $incomeData[] = $dayRecords->where('type', 'income')->sum('amount');
            $expenseData[] = $dayRecords->where('type', 'expense')->sum('amount');
        }

        // Dummy Stock Status (Pastikan ini sesuai dengan logic stokmu)
        $stockStatus = [
            'in_stock' => stocks::where('quantity', '>', 10)->count(),
            'low_stock' => stocks::whereBetween('quantity', [1, 10])->count(),
            'out_of_stock' => stocks::where('quantity', '<=', 0)->count(),
        ];

        return view('dashboard', compact(
            'totalIncome',
            'totalExpense',
            'totalPPN',
            'saldo',
            'labels',
            'incomeData',
            'expenseData',
            'recentRecords', // Kirim variabel ini ke view
            'stockStatus'
        ));
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
        //
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
