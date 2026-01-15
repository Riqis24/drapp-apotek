<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\FinancialRecords;

class FinancialRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $date1 = $request->input('date1') ?: Carbon::now()->startOfMonth();
        $date2 = $request->input('date2') ?: Carbon::now();
        $type  = $request->input('type');

        $query = FinancialRecords::with('source')
            ->whereBetween('date', [$date1, $date2])
            ->orderByDesc('id');

        // Filter jika tipe income/expense dipilih
        if (in_array($type, ['income', 'expense'])) {
            $query->where('type', $type);
        }

        $records = $query->get();

        $totalIncome = $records->where('type', 'income')->sum('amount');
        $totalExpense = $records->where('type', 'expense')->sum('amount');
        $saldo = $totalIncome - $totalExpense;

        return view('report.FinancialRecord', compact('records', 'totalIncome', 'totalExpense', 'saldo'));
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
