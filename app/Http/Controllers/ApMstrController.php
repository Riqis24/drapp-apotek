<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\ApDet;
use App\Models\ApMstr;
use App\Models\AppayDet;
use App\Models\SuppMstr;
use App\Models\AppayMstr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreApMstrRequest;
use App\Http\Requests\UpdateApMstrRequest;

class ApMstrController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $aps = ApMstr::with('supplier')->get();
        return view('ap.ApMstrList', compact('aps'));
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
    public function store(StoreApMstrRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // dd('test');
        $aps = AppayDet::with(['master.supplier', 'ap'])->where('appay_det_apid', $id)->get();
        $transaction = ApMstr::findOrFail($id);
        return view('ap.ApMstrPay', compact('aps', 'transaction'));
    }

    public function suppstatement(Request $request)
    {
        $suppliers = SuppMstr::orderBy('supp_mstr_name')->get();

        $rows = [];
        $openingBalance = 0;
        $runningBalance = 0;

        if ($request->suppid && $request->from && $request->to) {

            $suppid = $request->suppid;
            $from   = $request->from;
            $to     = $request->to;

            /* ===============================
             * OPENING BALANCE
             * =============================== */
            $openingDebit = ApMstr::where('ap_mstr_suppid', $suppid)
                ->whereDate('ap_mstr_date', '<', $from)
                ->sum('ap_mstr_amount');

            $openingCredit = AppayDet::join('appay_mstr', 'appay_mstr_id', '=', 'appay_det_mstrid')
                ->join('ap_mstr', 'ap_mstr_id', '=', 'appay_det_apid')
                ->where('ap_mstr_suppid', $suppid)
                ->whereDate('appay_mstr_date', '<', $from)
                ->sum('appay_det_payamount');

            $openingBalance = $openingDebit - $openingCredit;
            $runningBalance = $openingBalance;

            /* ===============================
             * AP (HUTANG)
             * =============================== */
            $apRows = ApMstr::where('ap_mstr_suppid', $suppid)
                ->whereBetween('ap_mstr_date', [$from, $to])
                ->select(
                    'ap_mstr_date as trx_date',
                    'ap_mstr_nbr as doc_no',
                    DB::raw("'AP' as trx_type"),
                    'ap_mstr_amount as debit',
                    DB::raw('0 as credit')
                );

            /* ===============================
             * PAYMENT
             * =============================== */
            $payRows = AppayDet::join('appay_mstr', 'appay_mstr_id', '=', 'appay_det_mstrid')
                ->join('ap_mstr', 'ap_mstr_id', '=', 'appay_det_apid')
                ->where('ap_mstr_suppid', $suppid)
                ->whereBetween('appay_mstr_date', [$from, $to])
                ->select(
                    'appay_mstr_date as trx_date',
                    'appay_mstr_nbr as doc_no',
                    DB::raw("'PAY' as trx_type"),
                    DB::raw('0 as debit'),
                    'appay_det_payamount as credit'
                );

            /* ===============================
             * UNION + ORDER
             * =============================== */
            $rows = $apRows
                ->unionAll($payRows)
                ->orderBy('trx_date')
                ->get();

            /* ===============================
             * RUNNING BALANCE
             * =============================== */
            foreach ($rows as $row) {
                $runningBalance += $row->debit;
                $runningBalance -= $row->credit;
                $row->balance = $runningBalance;
            }
        }

        return view('report.SuppStatement', compact(
            'suppliers',
            'rows',
            'openingBalance'
        ));
    }

    public function ApAgingHutang(Request $request)
    {
        $suppliers = SuppMstr::orderBy('supp_mstr_name')->get();

        $query = ApMstr::where('ap_mstr_balance', '>', 0);

        if ($request->suppid) {
            $query->where('ap_mstr_suppid', $request->suppid);
        }

        $aps = $query
            ->with('supplier')
            ->orderBy('ap_mstr_duedate')
            ->get();

        $rows = [];

        foreach ($aps as $ap) {

            $days = Carbon::today()->diffInDays(
                Carbon::parse($ap->ap_mstr_duedate),
                false
            );

            $bucket = [
                'current' => 0,
                '0_30'    => 0,
                '31_60'   => 0,
                'gt_60'   => 0,
            ];

            if ($days >= 0) {
                $bucket['current'] = $ap->ap_mstr_balance;
            } elseif ($days >= -30) {
                $bucket['0_30'] = $ap->ap_mstr_balance;
            } elseif ($days >= -60) {
                $bucket['31_60'] = $ap->ap_mstr_balance;
            } else {
                $bucket['gt_60'] = $ap->ap_mstr_balance;
            }

            $rows[] = [
                'supplier' => $ap->supplier->supp_mstr_name,
                'ap_no'    => $ap->ap_mstr_nbr,
                'duedate'  => $ap->ap_mstr_duedate,
                'balance'  => $ap->ap_mstr_balance,
                'bucket'   => $bucket,
            ];
        }

        return view('report.AgingHutang', compact('rows', 'suppliers'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ApMstr $apMstr)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $ap = ApMstr::findOrFail($id);

        $request->validate([
            'pay_date'   => 'required|date',
            'pay_amount' => 'required|numeric|min:1|max:' . $ap->ap_mstr_balance,
        ]);

        DB::transaction(function () use ($request, $ap) {

            ApDet::create([
                'ap_det_mstrid'     => $ap->ap_mstr_id,
                'ap_det_paydate'       => $request->pay_date,
                'ap_det_payamount'     => $request->pay_amount,
                'ap_det_paymethod'      => $request->payment_method,
                'ap_det_createdby'     => auth()->user()->user_mstr_id,
            ]);

            $ap->ap_mstr_paid    += $request->pay_amount;
            $ap->ap_mstr_balance  = $ap->ap_mstr_amount - $ap->ap_mstr_paid;

            $ap->ap_mstr_status = $ap->ap_mstr_balance <= 0
                ? 'paid'
                : 'partial';

            $ap->save();

            // jurnal (opsional nanti)
            // Hutang (D) - Kas/Bank (C)
        });

        return redirect()->route('ApMstr.index')->with('success', 'Pembayaran berhasil');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ApMstr $apMstr)
    {
        //
    }
}
