<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\FinanceClientRequest;
use App\Models\FinanceClient;
use Illuminate\Http\Request;
use App\Models\Invoice;

class FinanceClientController extends Controller
{
    public function index()
    {
        return view('pages.finance.index');
    }

    public function createFinanceClient(Request $request)
    {
        $data = $request->only([
            'name',
            'voen',
            'hn',
            'mh',
            'bank',
            'bvoen',
            'code',
            'swift',
            'orderer',
        ]);

        FinanceClient::create($data);

        return response()->json(['message' => 'Client Created'], 200);
    }

    public function createFinanceInvoice(Request $request)
    {
        $data = $request->only([
            'company',
            'client',
            'invoiceNo',
            'invoiceDate',
            'paymentType',
            'protocolDate',
            'contractNo',
            'contractDate',
            'invoiceNumbers',
        ]);

        $data['services'] = json_encode($request->get('services'));

        Invoice::create($data);

        return response()->json(['message' => $data['company']], 200);
    }

    public function getClients()
    {
        $clients = FinanceClient::get();
        return response()->json($clients);
    }

    public function invoices()
    {
        return view('pages.finance.invoices')->with(['invoices' => Invoice::get()]);
    }
    public function clients()
    {
        return view('pages.finance.clients')->with(['clients' => FinanceClient::get(['id', 'name', 'voen'])]);
    }

    public function financeInvoice(Invoice $invoice)
    {
        return view('pages.finance.invoice')->with(['data' => $invoice]);
    }

    public function editFinanceClient(FinanceClient $client)
    {
        return view('pages.finance.client')->with(['data' => $client]);
    }

    public function updateFinanceClient(FinanceClient $client, FinanceClientRequest $request)
    {
        $client->update($request->validated());

        return redirect()
            ->route('editFinanceClient', $client)
            ->withNotify('success', $client->getAttribute('name'));
    }

    public function deleteInvoice(Invoice $invoice)
    {
        if ($invoice->delete()) {
            return back();
        }
        return response()->setStatusCode('204');
    }
    public function deleteFinanceClient(FinanceClient $client)
    {
        if ($client->delete()) {
            return back();
        }
        return response()->setStatusCode('204');
    }

    public function signInvoice(Request $request)
    {
        Invoice::whereId($request->get('id'))->update(['is_signed' => $request->get('sign')]);

        return response()->setStatusCode('200');
    }
}
