<?php

namespace App\Http\Controllers;

use App\Models\LocMstr;
use Illuminate\Http\Request;
use App\Http\Requests\StoreLocMstrRequest;
use App\Http\Requests\UpdateLocMstrRequest;

class LocMstrController extends Controller
{
    public function index()
    {
        $locations = LocMstr::orderBy('loc_mstr_name')->get();
        return view('master.LocMstrList', compact('locations'));
    }

    public function create()
    {
        return view('master.LocMstrList');
    }

    public function store(Request $request)
    {
        $request->validate([
            'loc_mstr_code' => 'required|unique:loc_mstr,loc_mstr_code',
            'loc_mstr_name' => 'required',
        ]);

        LocMstr::create([
            'loc_mstr_code' => $request->loc_mstr_code,
            'loc_mstr_name' => $request->loc_mstr_name,
            'loc_mstr_active' => $request->has('loc_mstr_active') ? 1 : 0,
            'loc_mstr_isvisible' => $request->has('loc_mstr_isvisible') ? 1 : 0
        ]);

        return redirect()->route('LocMstr.index')->with('success', 'Gudang berhasil ditambahkan');
    }

    public function edit($id)
    {
        $location = LocMstr::findOrFail($id);
        return view('loc_mstr.edit', compact('location'));
    }

    public function update(Request $request, $id)
    {
        $location = LocMstr::findOrFail($id);

        $request->validate([
            'loc_mstr_code' => 'required|unique:loc_mstr,loc_mstr_code,' . $id . ',loc_mstr_id',
            'loc_mstr_name' => 'required',
        ]);

        $location->update([
            'loc_mstr_code' => $request->loc_mstr_code,
            'loc_mstr_name' => $request->loc_mstr_name,
            'loc_mstr_active' => $request->has('loc_mstr_active') ? 1 : 0
        ]);

        return redirect()->route('loc_mstr.index')->with('success', 'Gudang berhasil diupdate');
    }

    public function destroy($id)
    {
        $location = LocMstr::findOrFail($id);
        $location->delete();

        return redirect()->route('loc_mstr.index')->with('success', 'Gudang berhasil dihapus');
    }
}
