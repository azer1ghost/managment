<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerSatisfactionRequest;
use App\Models\Company;
use App\Models\CustomerSatisfaction;
use App\Models\Department;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CustomerSatisfactionController extends Controller
{
//    public function __construct()
//    {
//        $this->middleware('auth');
//        $this->authorizeResource(CustomerSatisfaction::class, 'customerSatisfaction');
//    }

    public function index(Request $request)
    {
        return view('pages.customer-satisfactions.index')
            ->with([
            'customerSatisfactions' => CustomerSatisfaction::with( 'company')->get()
        ]);
    }

    public function create()
    {
        return view('pages.customer-satisfactions.edit')->with([
            'action' => route('customer-satisfactions.store'),
            'method' => 'POST',
            'data' => new CustomerSatisfaction(),
            'companies' => Company::get(['id','name']),
        ]);
    }

    public function store(CustomerSatisfactionRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['is_active'] = $request->has('is_active');

        $customerSatisfaction = CustomerSatisfaction::create($validated);

        return redirect()
            ->route('customer-satisfactions.edit', $customerSatisfaction)
            ->withNotify('success', $customerSatisfaction->getAttribute('name'));
    }

    public function show(CustomerSatisfaction $customerSatisfaction)
    {
        return view('pages.customer-satisfactions.edit')->with([
            'action' => null,
            'method' => null,
            'data' => $customerSatisfaction,
            'companies' => Company::get(['id','name']),
        ]);
    }

    public function edit(CustomerSatisfaction $customerSatisfaction)
    {
        return view('pages.customer-satisfactions.edit')->with([
            'action' => route('customer-satisfactions.update', $customerSatisfaction),
            'method' => 'PUT',
            'data' => $customerSatisfaction,
            'companies' => Company::get(['id','name']),
        ]);
    }

    public function update(CustomerSatisfactionRequest $request, CustomerSatisfaction $customerSatisfaction): RedirectResponse
    {
        $validated = $request->validated();
        $validated['is_active'] = $request->has('is_active');

        $customerSatisfaction->update($validated);

        // CustomerSatisfaction parameters
        \Cache::forget('customerSatisfactionParameters');
        $parameters = [];
        foreach ($validated['parameters'] ?? [] as $parameter){
            $parameters[$parameter['id']] = [
                'ordering' => $parameter['ordering'] ?? 0,

            ];
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
