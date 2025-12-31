<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Models\Client;
use App\Models\Order;
use App\Models\TransitCustomer;
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
        
        // Support transit customers, regular users, and client users
        if (auth('transit')->check()) {
            $validated['transit_customer_id'] = auth('transit')->id();
            $validated['user_id'] = null;
        } elseif (auth()->check() && auth()->user()->isTransitCustomer()) {
            // Legacy support for old transit users in users table
            $validated['user_id'] = auth()->id();
            $validated['transit_customer_id'] = null;
        } elseif (auth('clients')->check()) {
            // For client users, we might need to add client_id column to orders table
            // For now, we'll use user_id as null
            $validated['user_id'] = null;
            $validated['transit_customer_id'] = null;
        } else {
            $validated['user_id'] = auth()->id();
            $validated['transit_customer_id'] = null;
        }
        
        $validated['code'] = Order::generateCustomCode();
        $validated['status'] = 1;
        $validated['service'] = 'Online Transit';
        $validated['amount'] = 45;

        $note = $request->input('note');
        $validated['note'] = $note;

        // Handle CMR files (multiple files)
        if ($request->hasFile('cmr')) {
            $cmrFiles = [];
            foreach ($request->file('cmr') as $cmrFile) {
                $cmrFiles[] = $cmrFile->store('cmr');
            }
            $validated['cmr'] = implode(",", $cmrFiles);
        }

        // Handle Invoice files (multiple files)
        if ($request->hasFile('invoice')) {
            $invoiceFiles = [];
            foreach ($request->file('invoice') as $invoiceFile) {
                $invoiceFiles[] = $invoiceFile->store('invoice');
            }
            $validated['invoice'] = implode(",", $invoiceFiles);
        }
        
        // Handle Packing files (optional, multiple files)
        if ($request->hasFile('packing')) {
            $packingFiles = [];
            foreach ($request->file('packing') as $packingFile) {
                $packingFiles[] = $packingFile->store('packing');
            }
            $validated['packing'] = implode(",", $packingFiles);
        }
        
        // Handle Other files (optional, multiple files)
        if ($request->hasFile('other')) {
            $otherFiles = [];
            foreach ($request->file('other') as $otherFile) {
                $otherFiles[] = $otherFile->store('other');
            }
            $validated['other'] = implode(",", $otherFiles);
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
            'clients' => Client::get(['id', 'name']),
            'statuses' => Order::statuses(),
        ]);
    }

    public function edit(Order $order)
    {
        return view('pages.orders.edit')->with([
            'action' => route('orders.update', $order),
            'method' => 'PUT',
            'data' => $order,
            'clients' => Client::get(['id', 'name']),
            'statuses' => Order::statuses(),
        ]);
    }


    public function update(Order $order, OrderRequest $request)
    {
        $validated = $request->validated();
        $validated['is_paid'] = $request->has('is_paid');
        if ($request->file('result')) {
            $document = $request->file('result');
            $document->store('result');
            $validated['result'] = $document->store('result');
        }

        $order->update($validated);
        return redirect()
            ->route('orders.edit', $order)
            ->withNotify('success', $order->getAttribute('code'));
    }

    public function payFromBalance(Request $request)
    {
        $order = Order::where('code', $request->get('code'))->firstOrFail();
        
        // Support transit customers
        if ($order->transit_customer_id) {
            $customer = \App\Models\TransitCustomer::findOrFail($order->transit_customer_id);
            $balance = $customer->balance;
            $amount = $order->amount;
            
            if ($balance >= $amount) {
                $order->is_paid = 1;
                $customer->balance = $balance - $amount;
                $order->save();
                $customer->save();
                
                return redirect()->route('profile.index')
                    ->with('success', 'Ödəniş uğurla tamamlandı!');
            } else {
                return back()->withErrors(['balance' => 'Kifayət qədər balans yoxdur.']);
            }
        } 
        // Legacy support for users table
        elseif ($order->user_id) {
            $user = User::findOrFail($order->user_id);
            $balance = $user->balance;
            $amount = $order->amount;
            
            if ($balance >= $amount) {
                $order->is_paid = 1;
                $user->balance = $balance - $amount;
                $order->save();
                $user->save();
                
                return redirect()->route('profile.index')
                    ->with('success', 'Ödəniş uğurla tamamlandı!');
            } else {
                return back()->withErrors(['balance' => 'Kifayət qədər balans yoxdur.']);
            }
        }
        
        return back()->withErrors(['error' => 'Sifariş tapılmadı.']);
    }

    public function destroy(Order $order)
    {
        //
    }

    public function download(Request $request)
    {
        return Storage::download($request->get('document'));
    }

    public function uploadDeclaration(Request $request, Order $order)
    {
        $request->validate([
            'declaration' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        if ($request->hasFile('declaration')) {
            $declarationFile = $request->file('declaration');
            $declarationPath = $declarationFile->store('declarations');
            $order->declaration = $declarationPath;
            $order->save();

            return redirect()->back()
                ->with('success', 'Bəyannamə uğurla yükləndi!');
        }

        return redirect()->back()
            ->withErrors(['declaration' => 'Fayl yüklənmədi.']);
    }

    public function resultDownload(Order $order)
    {
        return Storage::download($order->getAttribute('result'));
    }

}
