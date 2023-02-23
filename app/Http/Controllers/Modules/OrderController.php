<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    public function store(OrderRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = auth()->id();
        $validated['code'] = Order::generateCustomCode();
        $validated['status'] = 1;
        $validated['service'] = 'Online Transit';
        $validated['amount'] = 45;
        if ($request->file('cmr')) {
            $cmr = $request->file('cmr');
            $cmr->store('cmr');
            $validated['cmr'] = $cmr->store('cmr');
        }
        if ($request->file('invoice')) {
            $invoice = $request->file('invoice');
            $invoice->store('invoice');
            $validated['invoice'] = $invoice->store('invoice');
        }
        Order::create($validated);
    }

    public function update(Request $request, Order $order)
    {
        //
    }

    public function destroy(Order $order)
    {
        //
    }
}
