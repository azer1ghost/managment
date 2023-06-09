<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\SupplierRequest;
use App\Models\Client;
use App\Models\Evaluation;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Supplier::class, 'supplier');
    }

    public function search(Request $request): object
    {
        $suppliers = Supplier::where('name', 'LIKE', "%{$request->get('search')}%")
            ->orWhere('voen', 'LIKE', "%{$request->get('search')}%")
            ->limit(10)
            ->get(['id', 'name', 'voen']);

        $suppliersArray = [];

        foreach ($suppliers as $supplier) {
            $suppliersArray[] = [
                "id" => $supplier->id,
                "text" => "{$supplier->name}",
            ];
        }

        return (object)[
            'results' => $suppliersArray,
            'pagination' => [
                "more" => false
            ]
        ];
    }

    public function index(Request $request)
    {
        $limit = $request->get('limit', 25);
        $search = $request->get('search');

        return view('pages.suppliers.index' )->with([
            'suppliers' => Supplier::query()
                ->when($search, fn ($query) => $query->where('name', 'like', "%$search%"))
                ->paginate($limit)
        ]);
    }

    public function create()
    {
        return view('pages.suppliers.edit')->with([
            'action' => route('suppliers.store'),
            'method' => 'POST',
            'data' => new Supplier(),
        ]);
    }

    public function store(SupplierRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['is_service'] = $request->has('is_service');
        $supplier = Supplier::create($validated);

        return redirect()
            ->route('suppliers.edit', $supplier)
            ->withNotify('success', $supplier->getAttribute('name'));
    }

    public function show(Supplier $supplier)
    {
        return view('pages.suppliers.edit')->with([
            'action' => route('suppliers.store', $supplier),
            'method' => null,
            'data' => $supplier,
        ]);
    }

    public function edit(Supplier $supplier)
    {
        return view('pages.suppliers.edit')->with([
            'action' => route('suppliers.update', $supplier),
            'method' => 'PUT',
            'data' => $supplier,
        ]);
    }

    public function update(SupplierRequest $request, Supplier $supplier): RedirectResponse
    {
        $validated = $request->validated();
        $validated['is_service'] = $request->has('is_service');
        $supplier->update($validated);

        return redirect()
            ->route('suppliers.edit', $supplier)
            ->withNotify('success', $supplier->getAttribute('name'));
    }

    public function destroy(Supplier $supplier)
    {
        if ($supplier->delete()) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }



}
