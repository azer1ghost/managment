<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        return view('pages.orders.index')->with([
            'orders' => Order::latest()->paginate(25),
        ]);
    }

    public function store(OrderRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = auth()->id();
        $validated['code'] = Order::generateCustomCode();
        $validated['status'] = 1;
        $validated['service'] = 'Online Transit';
        $validated['amount'] = 45;

        foreach ($request->file('cmr') as $cmrArray) {
            $cmr = $cmrArray;
            $cmr->store('cmr');
            $array[] =  $cmr->store('cmr');
            $validated['cmr'] = implode(",", $array);
        }

        foreach ($request->file('invoice') as $invoiceArray) {
            $invoice = $invoiceArray;
            $invoice->store('invoice');
            $validated['invoice'] = $invoice->store('invoice');
        }
         $order = Order::create($validated);

        return redirect()->route('payment', $order);
    }

    public function show(Order $order)
    {
        return view('pages.orders.edit')->with([
            'action' => null,
            'method' => null,
            'data' => $order,
            'users' => User::get(['id', 'name']),
        ]);
    }

    public function edit(Order $order)
    {
        return view('pages.orders.edit')->with([
            'action' => route('orders.update', $order),
            'method' => 'PUT',
            'data' => $order,
            'users' => User::get(['id', 'name']),
        ]);
    }


    public function update(Order $order, Request $request)
    {
//        if ($request->file('result')) {
            dd($request->get('result'));
//            $document = $request->file('result');
//            $document->store('result');
//            $order->setAttribute('result', $document->store('result'));
//            $order->save();
//        }
//        return redirect()->back();
    }

    public function payFromBalance(Request $request, Order $order)
    {
        $order = $order->where('code', $request->get('code'))->first();
        $user = User::whereId($order->getAttribute('user_id'))->first();
        $balance = $user->getAttribute('balance');
        $amount = $order->getAttribute('amount');
        if (!($balance < $amount)) {
            $order->setAttribute('is_paid', 1);
            $user->setAttribute('balance', ($balance - $amount));
            $order->save();
            $user->save();
        }
    }

    public function destroy(Order $order)
    {
        //
    }

    public function download(Request $request)
    {
        return Storage::download($request->get('cmr'));
    }

}
