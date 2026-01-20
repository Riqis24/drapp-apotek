<?php

namespace App\Http\Controllers;

use App\Models\SuppMstr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\StoreSuppMstrRequest;
use App\Http\Requests\UpdateSuppMstrRequest;

class SuppMstrController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $suppliers = SuppMstr::orderBy('supp_mstr_name')->get();
        return view('master.SuppMstrList', compact('suppliers'));
    }

    public function create()
    {
        return view('master.SuppMstrForm');
    }

    public function store(Request $request)
    {
        $request->validate([
            'supp_mstr_name' => 'required',
        ]);

        $last = SuppMstr::orderBy('supp_mstr_id', 'desc')->first();

        if ($last && preg_match('/VD-(\d+)/', $last->supp_mstr_code, $m)) {
            $nextNumber = intval($m[1]) + 1;
        } else {
            $nextNumber = 1;
        }

        $suppCode = 'VD-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        SuppMstr::create([
            'supp_mstr_code'   => $suppCode,
            'supp_mstr_name'   => $request->supp_mstr_name,
            'supp_mstr_addr'   => $request->supp_mstr_addr,
            'supp_mstr_phone'  => $request->supp_mstr_phone,
            'supp_mstr_npwp'   => $request->supp_mstr_npwp,
            'supp_mstr_ppn'    => $request->supp_mstr_ppn ?? 0,
            'supp_mstr_active' => 1,
        ]);

        return redirect()->route('SupplierMstr.index')->with('success', 'Supplier created');
    }

    public function edit($id)
    {
        $supplier = SuppMstr::findOrFail($id);
        return view('master.SuppMstrForm', compact('supplier'));
    }

    public function update(Request $request, $id)
    {
        $supplier = SuppMstr::findOrFail($id);

        $supplier->update($request->all());

        return redirect()->route('SupplierMstr.index')->with('success', 'Supplier updated');
    }

    public function destroy($id)
    {
        SuppMstr::where('supp_mstr_id', $id)->delete();
        return back()->with('success', 'Supplier deleted');
    }
}
