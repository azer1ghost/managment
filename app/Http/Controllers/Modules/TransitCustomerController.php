<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Models\TransitCustomer;
use Illuminate\Http\Request;

class TransitCustomerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $type = $request->get('type');

        $customers = TransitCustomer::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%")
                      ->orWhere('voen', 'like', "%{$search}%")
                      ->orWhere('id', $search);
            })
            ->when($type, function ($query, $type) {
                $query->where('type', $type);
            })
            ->latest()
            ->paginate(25);

        return view('pages.transit-customers.index')->with([
            'customers' => $customers,
            'search' => $search,
            'type' => $type,
        ]);
    }

    public function create()
    {
        return view('pages.transit-customers.edit')->with([
            'action' => route('transit-customers.store'),
            'method' => 'POST',
            'data' => null,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:transit_customers,email',
            'phone' => 'required|string|unique:transit_customers,phone',
            'password' => 'required|string|min:8|confirmed',
            'type' => 'nullable|string|in:legal,people',
            'country' => 'nullable|string|max:255',
            'voen' => 'nullable|string|max:255',
            'balance' => 'nullable|numeric|min:0',
            'rekvisit' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        if ($request->hasFile('rekvisit')) {
            $validated['rekvisit'] = $request->file('rekvisit')->storeAs('rekvizits', $request->file('rekvisit')->hashName());
        }

        TransitCustomer::create($validated);

        return redirect()->route('transit-customers.index')
            ->withNotify('success', 'Transit customer created successfully');
    }

    public function show($id)
    {
        $customer = TransitCustomer::findOrFail($id);
        return view('pages.transit-customers.show')->with([
            'customer' => $customer,
            'orders' => $customer->orders()->latest()->paginate(10),
        ]);
    }

    public function edit($id)
    {
        $customer = TransitCustomer::findOrFail($id);
        return view('pages.transit-customers.edit')->with([
            'action' => route('transit-customers.update', $customer),
            'method' => 'PUT',
            'data' => $customer,
        ]);
    }

    public function update(Request $request, $id)
    {
        $customer = TransitCustomer::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:transit_customers,email,' . $customer->id,
            'phone' => 'required|string|unique:transit_customers,phone,' . $customer->id,
            'password' => 'nullable|string|min:8|confirmed',
            'type' => 'nullable|string|in:legal,people',
            'country' => 'nullable|string|max:255',
            'voen' => 'nullable|string|max:255',
            'balance' => 'nullable|numeric|min:0',
            'rekvisit' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = $validated['password'];
        } else {
            unset($validated['password']);
        }

        if ($request->hasFile('rekvisit')) {
            $validated['rekvisit'] = $request->file('rekvisit')->storeAs('rekvizits', $request->file('rekvisit')->hashName());
        }

        $customer->update($validated);

        return redirect()->route('transit-customers.index')
            ->withNotify('success', 'Transit customer updated successfully');
    }

    public function destroy($id)
    {
        $customer = TransitCustomer::findOrFail($id);
        $customer->delete();

        return redirect()->route('transit-customers.index')
            ->withNotify('success', 'Transit customer deleted successfully');
    }
}
