<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\SalesActivityRequest;
use App\Models\Certificate;
use App\Models\Client;
use App\Models\Inquiry;
use App\Models\Organization;
use App\Models\SalesActivitiesSupply;
use App\Models\SalesActivity;
use App\Models\SalesActivityType;

class SalesActivityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(SalesActivity::class, 'sales_activity');
    }

    public function index()
    {
        return view('panel.pages.sales-activities.index')->with([
            'sale_activities' => SalesActivity::latest()->paginate(10),
            'salesActivitiesTypes' => SalesActivityType::pluck('name', 'id')->prepend(trans('translates.filters.select'), null)->toArray(),
        ]);
    }

    public function create()
    {
        return view('panel.pages.sales-activities.edit')->with([
            'action' => route('sales-activities.store'),
            'method' => 'POST',
            'data' => new SalesActivity(),
            'organizations' => Organization::pluck('name', 'id')->prepend(trans('translates.filters.select'), null)->toArray(),
            'certificates' => Certificate::pluck('name', 'id')->prepend(trans('translates.filters.select'), null)->toArray(),
        ]);
    }

    public function store(SalesActivityRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = auth()->id();

        $sales_activity = SalesActivity::create($validated);

        // Add supplies
        if($request->has('supplies')){
            $sales_activity->salesSupplies()->createMany($validated['supplies']);
        }

        return redirect()
            ->route('sales-activities.edit', $sales_activity)
            ->withNotify('success', $sales_activity->getAttribute('name'));
    }

    public function show(SalesActivity $sales_activity)
    {
        return view('panel.pages.sales-activities.edit')->with([
            'action' => route('sales-activities.store', $sales_activity),
            'method' => null,
            'data' => $sales_activity,
            'organizations' => Organization::pluck('name', 'id')->prepend(trans('translates.filters.select'), null)->toArray(),
            'certificates' => Certificate::pluck('name', 'id')->prepend(trans('translates.filters.select'), null)->toArray(),
        ]);
    }

    public function edit(SalesActivity $sales_activity)
    {
        return view('panel.pages.sales-activities.edit')->with([
            'action' => route('sales-activities.update', $sales_activity),
            'method' => 'PUT',
            'data' => $sales_activity,
            'organizations' => Organization::pluck('name', 'id')->prepend(trans('translates.filters.select'), null)->toArray(),
            'certificates' => Certificate::pluck('name', 'id')->prepend(trans('translates.filters.select'), null)->toArray(),
        ]);
    }

    public function update(SalesActivityRequest $request, SalesActivity $sales_activity)
    {
        $validated = $request->validated();

        $sales_activity->update($validated);

        // Add, update or delete supplies
        $supplies = collect($request->get('supplies') ?? []);

        // destroy should appear before create or update
        SalesActivitiesSupply::destroy($sales_activity->salesSupplies()->pluck('id')->diff($supplies->pluck('id')));

        $supplies->each(fn($supply) => $sales_activity->salesSupplies()->updateOrCreate(['id' => $supply['id']], $supply));

        return redirect()
            ->route('sales-activities.edit', $sales_activity)
            ->withNotify('success', $sales_activity->getAttribute('name'));
    }

    public function destroy(SalesActivity $salesActivity)
    {
        if ($salesActivity->delete()) {
            return response('OK');
        }

        return response()->setStatusCode('204');
    }
}
