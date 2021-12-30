<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\SalesActivityTypeRequest;
use App\Models\SalesActivityType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SalesActivityTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(SalesActivityType::class, 'sales_activities_type');
    }

    public function index()
    {
        return view('panel.pages.sales-activities-types.index')->with([
            'sale_activities_types' => SalesActivityType::paginate(10),
        ]);
    }

    public function create()
    {

        return view('panel.pages.sales-activities-types.edit')->with([
            'action' => route('sales-activities-types.store'),
            'method' => 'POST',
            'data' => new SalesActivityType(),
            'hard_columns' => SalesActivityType::hardColumns(),
        ]);
    }

    public function store(SalesActivityTypeRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $validated['hard_columns'] = implode(",", $request->hard_columns);
        $this->translates($validated);

        $sales_activity_type = SalesActivityType::create($validated);

        return redirect()
            ->route('sales-activities-types.edit', $sales_activity_type)
            ->withNotify('success', $sales_activity_type->getAttribute('name'));
    }

    public function show(SalesActivityType $sales_activities_type)
    {
        return view('panel.pages.sales-activities-types.edit')->with([
            'action' => route('sales-activities-types.store', $sales_activities_type),
            'method' => null,
            'data' => $sales_activities_type,
            'hard_columns' => SalesActivityType::hardColumns(),
        ]);
    }

    public function edit(SalesActivityType $sales_activities_type)
    {
        return view('panel.pages.sales-activities-types.edit')->with([
            'action' => route('sales-activities-types.update', $sales_activities_type),
            'method' => 'PUT',
            'data' => $sales_activities_type,
            'hard_columns' => SalesActivityType::hardColumns(),
        ]);
    }

    public function update(SalesActivityTypeRequest $request, SalesActivityType $sales_activities_type): RedirectResponse
    {
        $validated = $request->validated();
        $validated['hard_columns'] = implode(",", $request->hard_columns);
        $this->translates($validated);

        $sales_activities_type->update($validated);

        return redirect()
            ->route('sales-activities-types.edit', $sales_activities_type)
            ->withNotify('success', $sales_activities_type->getAttribute('name'));
    }

    public function destroy(SalesActivityType $sales_activities_type)
    {
        if ($sales_activities_type->delete()) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }
}
