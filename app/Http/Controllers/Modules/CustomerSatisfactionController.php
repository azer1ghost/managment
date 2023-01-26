<?php

namespace App\Http\Controllers\Modules;

use Carbon\Carbon;
use App\Http\{Requests\CustomerSatisfactionRequest, Controllers\Controller};
use App\Models\{Company, CustomerSatisfaction, Parameter, Satisfaction};
use Illuminate\Http\{RedirectResponse, Request};

class CustomerSatisfactionController extends Controller
{

    public function index(Request $request)
    {
        $filters = [
            'note'            => $request->get('note'),
            'rate'            => $request->get('rate'),
            'price_rate'      => $request->get('price_rate'),
        ];

        $company_id =  $request->get('company_id');

        $parameterFilters = [
            'name' => [],
            'phone' => [],
            'mail' => [],
            'search_client' => null,
        ];
        $limit  = $request->get('limit', 25);

        foreach ($parameterFilters as $key => $filter) {
            if($key == 'search_client'){
                $parameterFilters[$key] = $request->get($key);
            }else {
                if($request->get($key) != null) {
                    $parameterFilters[$key] = explode(',', $request->get($key));
                }
            }
        }
        if($request->has('created_at')){
            $created_at = $request->get('created_at');
        }else{
            $created_at = now()->firstOfMonth()->format('Y/m/d') . ' - ' . now()->format('Y/m/d');
        }
        [$from, $to] = explode(' - ', $created_at);
//        $subjects  =  Parameter::where('name', 'subject')->first()->options->unique();

        $companies = Company::get();

        $customerSatisfactions = CustomerSatisfaction::with('satisfaction')->whereBetween('created_at', [Carbon::parse($from)->startOfDay(), Carbon::parse($to)->endOfDay()])
            ->when($company_id, fn ($query) => $query
                ->whereHas('satisfaction',fn($q) => $q->where('company_id', 'like', "%$company_id")))
            ->where(function ($query) use ($filters) {
                foreach ($filters as $column => $value) {
                    $query->when($value, function ($query, $value) use ($column) {
                        if(is_numeric($value)) {
                            $query->where($column, $value);
                        } else if (is_array($value)) {
                            $query->whereIn($column, $value);
                        } else {
                            $query->where($column, 'LIKE',  "%$value%");
                        }
                    });
                }
            })
            ->where(function ($query) use ($parameterFilters) {
                foreach ($parameterFilters as $column => $value) {
                    $query->when($value, function ($query) use ($column, $value){
                        $query->whereHas('parameters', function ($query) use ($column, $value) {
                            if (is_array($value)) {
                                $parameter_id = Parameter::where('name', $column)->first()->getAttribute('id');
                                $query->where('parameter_id', $parameter_id)->whereIn('value', $value);
                            } else {
                                $query->where('value',   'LIKE', "%" . phone_cleaner($value) . "%")
                                    ->orWhere('value', 'LIKE', "%" . trim($value) . "%");
                            }
                        });
                    });
                }
            })
            ->latest('id');

            $customerSatisfactions = $customerSatisfactions->paginate($limit);

        return view('pages.customer-satisfactions.index')
            ->with([
            'customerSatisfactions' => $customerSatisfactions,
//            'satisfactions' => $satisfactions
            'created_at' => $created_at,
            'companies' => $companies,
        ]);
    }

    public function create()
    {
        return view('pages.customer-satisfactions.edit')->with([
            'action' => route('customer-satisfactions.store'),
            'method' => 'POST',
            'data' => new CustomerSatisfaction()
        ]);
    }

    public function store(CustomerSatisfactionRequest $request)
    {
        $validated = $request->validated();

        $customerSatisfaction = CustomerSatisfaction::create($validated);

        $parameters = [];
        foreach ($validated['parameters'] ?? [] as $key => $parameter) {
            $parameters[$key] = ['value' => $parameter];
        }

        $customerSatisfaction->parameters()->sync($parameters);

        return view('pages.customer-satisfactions.components.customer-satisfaction-thanks');
    }

    public function show(CustomerSatisfaction $customerSatisfaction)
    {
        return view('pages.customer-satisfactions.edit')->with([
            'action' => null,
            'method' => null,
            'data' => $customerSatisfaction,
            'satisfactions' => Satisfaction::get(),
        ]);
    }

    public function edit(CustomerSatisfaction $customerSatisfaction)
    {
        return view('pages.customer-satisfactions.edit')->with([
            'action' => route('customer-satisfactions.update', $customerSatisfaction),
            'method' => 'PUT',
            'data' => $customerSatisfaction,
        ]);
    }

    public function update(CustomerSatisfactionRequest $request, CustomerSatisfaction $customerSatisfaction): RedirectResponse
    {
        $validated = $request->validated();

        $customerSatisfaction->update($validated);


        $parameters = [];
        foreach ($validated['parameters'] ?? [] as $key => $parameter) {
            $parameters[$key] = ['value' => $parameter];
        }

        $customerSatisfaction->parameters()->sync($parameters);


        return redirect()
            ->route('customer-satisfactions.edit', $customerSatisfaction)
            ->withNotify('success', $customerSatisfaction->getAttribute('id'));
    }

    public function destroy(CustomerSatisfaction $customerSatisfaction)
    {
        if ($customerSatisfaction->delete()) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }

    public function createSatisfaction()
    {
        return view('pages.customer-satisfactions.edit')->with([
            'action' => route('customer-satisfactions.store'),
            'method' => 'POST',
            'data' => new CustomerSatisfaction()
        ]);
    }
}
