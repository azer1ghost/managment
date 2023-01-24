<?php

namespace App\Http\Controllers\Modules;

use App\Http\{Requests\CustomerSatisfactionRequest, Controllers\Controller};
use App\Models\{CustomerSatisfaction, Satisfaction};
use Illuminate\Http\{RedirectResponse, Request};

class CustomerSatisfactionController extends Controller
{

    public function index()
    {
        $satisfactions = Satisfaction::query()->get();
        return view('pages.customer-satisfactions.index')
            ->with([
            'customerSatisfactions' => CustomerSatisfaction::get(),
            'satisfactions' => $satisfactions
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
