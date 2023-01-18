<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerSatisfactionRequest;
use App\Models\CustomerSatisfaction;
use App\Models\Satisfaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CustomerSatisfactionController extends Controller
{

    public function index(Request $request)
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

    public function store(CustomerSatisfactionRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $customerSatisfaction = CustomerSatisfaction::create($validated);

        $parameters = [];
        foreach ($validated['parameters'] ?? [] as $key => $parameter) {
            $parameters[$key] = ['value' => $parameter];
        }

        $customerSatisfaction->parameters()->sync($parameters);

        return redirect()
            ->route('home', $customerSatisfaction)
            ->withNotify('success', $customerSatisfaction->getAttribute('id'));
    }

    public function show(CustomerSatisfaction $customerSatisfaction)
    {
        return view('pages.customer-satisfactions.edit')->with([
            'action' => null,
            'method' => null,
            'data' => $customerSatisfaction,
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
}
