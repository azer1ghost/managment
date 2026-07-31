<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::orderBy('name')->get();
        return view('pages.finance.units', compact('units'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:500|unique:units,name']);

        Unit::create(['name' => $request->name]);

        return back()->withNotify('success', 'Ölçü vahidi əlavə edildi.');
    }

    public function destroy(Unit $unit)
    {
        $unit->delete();

        return back()->withNotify('success', 'Ölçü vahidi silindi.');
    }

    public function list()
    {
        return response()->json(Unit::orderBy('name')->pluck('name'));
    }
}
