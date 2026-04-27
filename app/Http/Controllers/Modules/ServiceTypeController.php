<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Models\ServiceType;
use Illuminate\Http\Request;

class ServiceTypeController extends Controller
{
    public function index()
    {
        $serviceTypes = ServiceType::orderBy('name')->get();
        return view('pages.finance.service-types', compact('serviceTypes'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:500|unique:service_types,name']);

        ServiceType::create(['name' => $request->name]);

        return back()->withNotify('success', 'Xidmət əlavə edildi.');
    }

    public function destroy(ServiceType $serviceType)
    {
        $serviceType->delete();

        return back()->withNotify('success', 'Xidmət silindi.');
    }

    public function list()
    {
        return response()->json(ServiceType::orderBy('name')->pluck('name'));
    }
}
